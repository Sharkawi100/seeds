<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiManagementController extends Controller
{
    protected $claudeService;

    public function __construct(ClaudeService $claudeService)
    {
        $this->claudeService = $claudeService;
    }

    /**
     * Display AI management dashboard
     */
    public function index()
    {
        try {
            $stats = [
                'total_ai_generated' => Cache::get('ai_requests_' . today()->format('Y-m-d'), 0),
                'total_ai_quizzes' => Quiz::count(), // Total quizzes since creation_method column doesn't exist
                'monthly_usage' => $this->getMonthlyUsage(),
                'api_status' => $this->checkApiStatus(),
                'recent_generations' => $this->getRecentGenerations()
            ];

            return view('admin.ai.index', compact('stats'));
        } catch (\Exception $e) {
            Log::error('Admin AI dashboard error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback data to prevent crashes
            $stats = [
                'total_ai_generated' => 0,
                'total_ai_quizzes' => Quiz::count(),
                'monthly_usage' => 0,
                'api_status' => false,
                'recent_generations' => collect([]) // Empty collection
            ];

            return view('admin.ai.index', compact('stats'))
                ->with('error', 'حدث خطأ في تحميل بيانات الذكاء الاصطناعي');
        }
    }

    /**
     * Generate quiz content using AI
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:quiz,passage,questions',
            'title' => 'required|string|max:255',
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'topic' => 'required|string|max:255',
            'settings' => 'nullable|array',
            'roots' => 'nullable|array',
            'include_passage' => 'nullable|boolean',
            'passage_topic' => 'nullable|string|max:255',
            'passage_method' => 'nullable|in:ai,manual',
            'manual_passage' => 'nullable|string',
            'manual_passage_title' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Create quiz first
            $quiz = Quiz::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'grade_level' => $validated['grade_level'],
                'settings' => json_encode($validated['settings'] ?? []),
                'is_active' => true,
                'pin' => $this->generateQuizPin(),
            ]);

            // Handle passage if requested
            if ($validated['include_passage'] ?? false) {
                if (($validated['passage_method'] ?? 'ai') === 'manual') {
                    // Manual passage
                    $quiz->update([
                        'passage_data' => json_encode([
                            'passage' => $validated['manual_passage'] ?? '',
                            'passage_title' => $validated['manual_passage_title'] ?? $validated['topic'],
                        ])
                    ]);
                } else {
                    // AI generated passage
                    $passage = $this->claudeService->generateEducationalText(
                        $validated['subject'],
                        $validated['grade_level'],
                        $validated['passage_topic'] ?? $validated['topic'],
                        'article',
                        'medium'
                    );

                    $quiz->update([
                        'passage_data' => json_encode([
                            'passage' => $passage,
                            'passage_title' => $validated['passage_topic'] ?? $validated['topic'],
                        ])
                    ]);
                }
            }

            // Generate questions if roots are provided
            if (!empty($validated['roots'])) {
                $transformedRoots = $this->transformRootsSettings($validated['roots']);

                $questions = $this->claudeService->generateQuestionsFromText(
                    $quiz->passage_data ? json_decode($quiz->passage_data, true)['passage'] ?? '' : '',
                    $validated['subject'],
                    $validated['grade_level'],
                    $transformedRoots
                );

                // Save questions
                foreach ($questions as $questionData) {
                    Question::create([
                        'quiz_id' => $quiz->id,
                        'question' => $questionData['question'],
                        'options' => $questionData['options'],
                        'correct_answer' => $questionData['correct_answer'],
                        'root_type' => $questionData['root_type'],
                        'depth_level' => $questionData['depth_level'],
                    ]);
                }
            }

            DB::commit();

            // Track usage
            $this->trackUsage('quiz_generation', 1);

            return response()->json([
                'success' => true,
                'message' => 'تم توليد الاختبار بنجاح',
                'redirect' => route('quizzes.show', $quiz),
                'quiz_id' => $quiz->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AI quiz generation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل توليد الاختبار: ' . $e->getMessage(),
                'debug' => config('app.debug') ? $e->getTraceAsString() : null
            ], 422);
        }
    }

    /**
     * Generate AI report for quiz result
     */
    public function generateReport(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'result_id' => 'required|exists:results,id'
        ]);

        try {
            $result = Result::findOrFail($validated['result_id']);

            // Generate AI analysis
            $report = $this->generateResultAnalysis($result);

            return response()->json([
                'success' => true,
                'report' => $report
            ]);

        } catch (\Exception $e) {
            Log::error('AI report generation failed', [
                'error' => $e->getMessage(),
                'quiz_id' => $quiz->id,
                'result_id' => $validated['result_id']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل توليد التقرير: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get monthly AI usage statistics
     */
    private function getMonthlyUsage(): int
    {
        try {
            // Count AI usage for current month
            $startOfMonth = now()->startOfMonth();
            $aiUsage = 0;

            // Try to get from cache or calculate
            for ($day = 1; $day <= now()->day; $day++) {
                $date = $startOfMonth->copy()->addDays($day - 1);
                $aiUsage += Cache::get('ai_requests_' . $date->format('Y-m-d'), 0);
            }

            return $aiUsage;
        } catch (\Exception $e) {
            Log::warning('Failed to get monthly usage', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Check Claude API status
     */
    private function checkApiStatus(): bool
    {
        try {
            // Test connection with simple request
            $response = $this->claudeService->generateCompletion('مرحبا', [
                'max_tokens' => 10,
                'temperature' => 0.1
            ]);

            return !empty($response);
        } catch (\Exception $e) {
            Log::warning('Claude API status check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get recent AI-generated content
     */
    private function getRecentGenerations()
    {
        try {
            return Quiz::with(['questions', 'user'])
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($quiz) {
                    // Ensure subject has a fallback value to prevent empty key errors
                    $quiz->subject = $quiz->subject ?: 'arabic';
                    return $quiz;
                });
        } catch (\Exception $e) {
            Log::warning('Failed to get recent generations', ['error' => $e->getMessage()]);
            return collect([]); // Return empty collection on error
        }
    }

    /**
     * Generate unique quiz PIN
     */
    private function generateQuizPin(): string
    {
        do {
            $pin = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (Quiz::where('pin', $pin)->exists());

        return $pin;
    }

    /**
     * Transform roots settings for Claude service
     */
    private function transformRootsSettings(array $roots): array
    {
        $transformed = [];

        foreach ($roots as $rootKey => $rootData) {
            $transformed[$rootKey] = [];

            if (isset($rootData['levels']) && is_array($rootData['levels'])) {
                foreach ($rootData['levels'] as $level) {
                    $depth = (int) ($level['depth'] ?? 1);
                    $count = (int) ($level['count'] ?? 1);

                    if ($count > 0) {
                        $transformed[$rootKey][$depth] = $count;
                    }
                }
            }
        }

        return $transformed;
    }

    /**
     * Generate AI analysis for quiz result
     */
    private function generateResultAnalysis(Result $result): string
    {
        try {
            $quiz = $result->quiz;
            $scores = $result->scores;

            $prompt = "قم بتحليل نتيجة هذا الطالب في اختبار جُذور:

العنوان: {$quiz->title}
المادة: {$quiz->subject}
الصف: {$quiz->grade_level}

النتائج حسب الجذور:
- جَوهر: {$scores['jawhar']}%
- ذِهن: {$scores['zihn']}%  
- وَصلات: {$scores['waslat']}%
- رُؤية: {$scores['roaya']}%

المجموع الكلي: {$result->total_score}%

اكتب تحليلاً مختصراً (100-150 كلمة) يتضمن:
1. تقييم الأداء العام
2. نقاط القوة والضعف
3. توصيات للتحسين

اكتب بأسلوب تربوي مشجع:";

            return $this->claudeService->generateCompletion($prompt, [
                'max_tokens' => 500,
                'temperature' => 0.7
            ]);

        } catch (\Exception $e) {
            Log::error('Result analysis generation failed', [
                'error' => $e->getMessage(),
                'result_id' => $result->id
            ]);

            return 'لا يمكن توليد التحليل في الوقت الحالي. يرجى المحاولة مرة أخرى.';
        }
    }

    /**
     * Track AI usage for analytics
     */
    private function trackUsage(string $operation, int $count): void
    {
        try {
            $today = today()->format('Y-m-d');
            $currentUsage = Cache::get('ai_requests_' . $today, 0);
            Cache::put('ai_requests_' . $today, $currentUsage + $count, now()->addDays(30));

            Log::info('AI Usage tracked', [
                'user_id' => Auth::id(),
                'operation' => $operation,
                'count' => $count,
                'date' => $today
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to track AI usage', ['error' => $e->getMessage()]);
        }
    }
}