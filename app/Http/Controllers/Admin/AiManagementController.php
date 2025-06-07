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
        $stats = [
            'total_ai_generated' => Cache::get('ai_requests_' . today()->format('Y-m-d'), 0),
            'monthly_usage' => $this->getMonthlyUsage(),
            'api_status' => $this->checkApiStatus(),
            'recent_generations' => $this->getRecentGenerations()
        ];

        return view('admin.ai.index', compact('stats'));
    }

    /**
     * Generate quiz content using AI
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:quiz,passage,questions',
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'topic' => 'required|string|max:255',
            'settings' => 'required_if:type,quiz,questions|array',
            'passage' => 'required_if:type,questions|string',
        ]);

        try {
            switch ($validated['type']) {
                case 'quiz':
                    $result = $this->generateCompleteQuiz($validated);
                    break;
                case 'passage':
                    $result = $this->generatePassageOnly($validated);
                    break;
                case 'questions':
                    $result = $this->generateQuestionsOnly($validated);
                    break;
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('AI generation failed', [
                'type' => $validated['type'],
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل التوليد: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Generate report for quiz results
     */
    public function generateReport(Request $request, Quiz $quiz)
    {
        try {
            $type = $request->input('type', 'individual');

            if ($type === 'class_analysis') {
                // Get all results for this quiz
                $results = $quiz->results()->with('answers.question')->get();

                if ($results->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'لا توجد نتائج لتحليلها'
                    ]);
                }

                // Prepare class-wide statistics
                $prompt = $this->buildClassAnalysisPrompt($quiz, $results);

                $report = $this->claudeService->generateCompletion($prompt);

                return response()->json([
                    'success' => true,
                    'report' => $report
                ]);
            }

            // Original individual result code...
            $resultId = $request->input('result_id');
            // ... rest of existing code
        } catch (\Exception $e) {
            Log::error('Report generation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'فشل توليد التقرير'
            ], 500);
        }
    }

    private function buildClassAnalysisPrompt($quiz, $results)
    {
        // Calculate aggregate statistics
        $totalStudents = $results->count();
        $avgScore = round($results->avg('total_score'), 1);
        $passRate = round($results->where('total_score', '>=', 60)->count() / $totalStudents * 100, 1);

        // Root analysis
        $rootStats = [];
        foreach (['jawhar', 'zihn', 'waslat', 'roaya'] as $root) {
            $scores = $results->pluck('scores')->pluck($root)->filter();
            $rootStats[$root] = [
                'avg' => round($scores->avg(), 1),
                'min' => $scores->min(),
                'max' => $scores->max()
            ];
        }

        // Common wrong answers
        $wrongAnswers = [];
        foreach ($quiz->questions as $question) {
            $incorrect = $results->flatMap->answers
                ->where('question_id', $question->id)
                ->where('is_correct', false);

            if ($incorrect->count() > $totalStudents * 0.5) {
                $wrongAnswers[] = [
                    'question' => $question->question,
                    'root' => $question->root_type,
                    'error_rate' => round($incorrect->count() / $totalStudents * 100)
                ];
            }
        }

        $prompt = "تحليل أداء الصف للاختبار: {$quiz->title}\n\n";
        $prompt .= "إحصائيات عامة:\n";
        $prompt .= "- عدد الطلاب: {$totalStudents}\n";
        $prompt .= "- متوسط الدرجات: {$avgScore}%\n";
        $prompt .= "- نسبة النجاح: {$passRate}%\n\n";

        $prompt .= "أداء الجذور:\n";
        foreach ($rootStats as $root => $stats) {
            $rootNames = [
                'jawhar' => 'جَوهر',
                'zihn' => 'ذِهن',
                'waslat' => 'وَصلات',
                'roaya' => 'رُؤية'
            ];
            $prompt .= "- {$rootNames[$root]}: متوسط {$stats['avg']}% (أدنى: {$stats['min']}%, أعلى: {$stats['max']}%)\n";
        }

        if (!empty($wrongAnswers)) {
            $prompt .= "\nالأسئلة الأكثر خطأ:\n";
            foreach ($wrongAnswers as $wa) {
                $prompt .= "- السؤال: {$wa['question']} (جذر {$wa['root']}, نسبة الخطأ: {$wa['error_rate']}%)\n";
            }
        }

        $prompt .= "\nالرجاء تقديم:\n";
        $prompt .= "1. تحليل لنقاط القوة والضعف في أداء الصف\n";
        $prompt .= "2. تحديد المفاهيم الخاطئة الشائعة\n";
        $prompt .= "3. توصيات تدريسية محددة لتحسين الأداء\n";
        $prompt .= "4. استراتيجيات لتقوية كل جذر ضعيف\n";

        return $prompt;
    }

    /**
     * Generate complete quiz with passage and questions
     */
    private function generateCompleteQuiz(array $data): array
    {
        // Transform settings to match ClaudeService format
        $settings = $this->transformSettings($data['settings']);

        $result = $this->claudeService->generateJuzoorQuiz(
            $data['subject'],
            $data['grade_level'],
            $data['topic'],
            $settings,
            true, // include passage
            $data['passage_topic'] ?? null
        );

        // Track usage
        $this->trackUsage('complete_quiz', count($result['questions']));

        return $result;
    }

    /**
     * Generate passage only
     */
    private function generatePassageOnly(array $data): array
    {
        // Determine passage type and length from request or use defaults
        $textType = $data['text_type'] ?? 'article';
        $length = $data['text_length'] ?? 'medium';

        // Use generateEducationalText method
        $passage = $this->claudeService->generateEducationalText(
            $data['subject'],
            $data['grade_level'],
            $data['topic'],
            $textType,
            $length
        );

        $this->trackUsage('passage_only', 1);

        return [
            'passage' => $passage,
            'passage_title' => $data['topic']
        ];
    }

    /**
     * Generate questions from existing passage
     */
    private function generateQuestionsOnly(array $data): array
    {
        $settings = $this->transformSettings($data['settings']);

        // Use generateQuestionsFromText method
        $questions = $this->claudeService->generateQuestionsFromText(
            $data['passage'],
            $data['subject'],
            $data['grade_level'],
            $settings
        );

        $this->trackUsage('questions_only', count($questions));

        return ['questions' => $questions];
    }

    /**
     * Generate detailed report for quiz result
     */
    private function generateResultReport(Result $result): string
    {
        $quiz = $result->quiz;
        $scores = $result->scores;

        // Build analysis prompt
        $prompt = $this->buildReportPrompt($result, $quiz, $scores);

        // Generate report using general completion
        $report = $this->generateAICompletion($prompt, [
            'temperature' => 0.7,
            'max_tokens' => 1000
        ]);

        return $report;
    }

    /**
     * Generate general AI completion
     */
    private function generateAICompletion(string $prompt, array $options = []): string
    {
        try {
            // Use the public generateCompletion method
            return $this->claudeService->generateCompletion($prompt, $options);
        } catch (\Exception $e) {
            Log::error('AI completion failed', ['error' => $e->getMessage()]);
            throw new \Exception('فشل توليد النص: ' . $e->getMessage());
        }
    }

    /**
     * Build report generation prompt
     */
    private function buildReportPrompt(Result $result, Quiz $quiz, array $scores): string
    {
        $rootNames = [
            'jawhar' => 'جَوهر (الأساس)',
            'zihn' => 'ذِهن (التحليل)',
            'waslat' => 'وَصلات (الربط)',
            'roaya' => 'رُؤية (التطبيق)'
        ];

        $prompt = "قم بكتابة تقرير تحليلي مفصل لنتائج الطالب في اختبار '{$quiz->title}' للصف {$quiz->grade_level}.\n\n";
        $prompt .= "النتائج:\n";
        $prompt .= "- الدرجة الإجمالية: {$result->total_score}%\n";

        foreach ($scores as $root => $score) {
            if (isset($rootNames[$root])) {
                $prompt .= "- {$rootNames[$root]}: {$score}%\n";
            }
        }

        $prompt .= "\nالمطلوب:\n";
        $prompt .= "1. تحليل الأداء في كل جذر\n";
        $prompt .= "2. تحديد نقاط القوة والضعف\n";
        $prompt .= "3. توصيات محددة للتحسين\n";
        $prompt .= "4. خطة عمل مقترحة\n\n";
        $prompt .= "اكتب التقرير باللغة العربية بأسلوب تربوي إيجابي ومحفز.";

        return $prompt;
    }

    /**
     * Transform settings from frontend format to ClaudeService format
     */
    private function transformSettings(array $settings): array
    {
        $transformed = [];

        foreach ($settings as $root => $levels) {
            if (is_array($levels)) {
                foreach ($levels as $level => $count) {
                    if ($count > 0) {
                        $transformed[$root][$level] = $count;
                    }
                }
            }
        }

        return $transformed;
    }

    /**
     * Track AI usage
     */
    private function trackUsage(string $type, int $count): void
    {
        $key = 'ai_usage_' . $type . '_' . today()->format('Y-m-d');
        Cache::increment($key, $count);

        // Store in database for long-term tracking
        try {
            DB::table('ai_usage_logs')->insert([
                'type' => $type,
                'count' => $count,
                'user_id' => Auth::id(),
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to track AI usage in database', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get monthly usage statistics
     */
    private function getMonthlyUsage(): array
    {
        $usage = [];
        $startDate = now()->startOfMonth();
        $endDate = now();

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $key = 'ai_requests_' . $date->format('Y-m-d');
            $usage[$date->format('Y-m-d')] = Cache::get($key, 0);
        }

        return $usage;
    }

    /**
     * Check API status
     */
    private function checkApiStatus(): array
    {
        try {
            $testResult = $this->claudeService->testConnection();
            return [
                'status' => $testResult['connected'] ? 'متصل' : 'غير متصل',
                'last_check' => now()->toDateTimeString(),
                'details' => $testResult
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'خطأ',
                'last_check' => now()->toDateTimeString(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get recent AI generations
     */
    private function getRecentGenerations(): array
    {
        try {
            return DB::table('ai_usage_logs')
                ->select('type', 'count', 'created_at')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::warning('Failed to get recent generations', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * API endpoints for AJAX requests
     */

    /**
     * Generate passage via API
     */
    public function apiGeneratePassage(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'topic' => 'required|string|max:255',
            'text_type' => 'required|in:story,article,dialogue,description',
            'length' => 'required|in:short,medium,long'
        ]);

        try {
            // Use generateEducationalText
            $passage = $this->claudeService->generateEducationalText(
                $validated['subject'],
                $validated['grade_level'],
                $validated['topic'],
                $validated['text_type'],
                $validated['length']
            );

            return response()->json([
                'success' => true,
                'passage' => $passage
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Generate questions via API
     */
    public function apiGenerateQuestions(Request $request)
    {
        $validated = $request->validate([
            'passage' => 'required|string',
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'roots' => 'required|array'
        ]);

        try {
            // Use generateQuestionsFromText
            $questions = $this->claudeService->generateQuestionsFromText(
                $validated['passage'],
                $validated['subject'],
                $validated['grade_level'],
                $validated['roots']
            );

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Generate complete quiz via API
     */
    public function apiGenerateQuiz(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'topic' => 'required|string|max:255',
            'settings' => 'required|array',
            'include_passage' => 'boolean',
            'passage_topic' => 'nullable|string'
        ]);

        try {
            $result = $this->claudeService->generateJuzoorQuiz(
                $validated['subject'],
                $validated['grade_level'],
                $validated['topic'],
                $validated['settings'],
                $validated['include_passage'] ?? false,
                $validated['passage_topic'] ?? null
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Generate report via API
     */
    public function apiGenerateReport(Request $request)
    {
        $validated = $request->validate([
            'result_id' => 'required|exists:results,id'
        ]);

        try {
            // FIXED: Find the result by ID and generate report
            $result = Result::findOrFail($validated['result_id']);
            $report = $this->generateResultReport($result);

            return response()->json([
                'success' => true,
                'report' => $report
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Regenerate a specific question
     */
    public function apiRegenerateQuestion(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'passage' => 'nullable|string'
        ]);

        try {
            $question = Question::findOrFail($validated['question_id']);
            $quiz = $question->quiz;

            // Generate a single question with the same parameters
            $newQuestions = $this->claudeService->generateQuestionsFromText(
                $validated['passage'] ?? $question->passage ?? '',
                $quiz->subject,
                $quiz->grade_level,
                [$question->root_type => [$question->depth_level => 1]]
            );

            if (!empty($newQuestions)) {
                $newQuestion = $newQuestions[0];

                // Update the existing question
                $question->update([
                    'question' => $newQuestion['question'],
                    'options' => $newQuestion['options'],
                    'correct_answer' => $newQuestion['correct_answer']
                ]);
            }

            return response()->json([
                'success' => true,
                'question' => $question->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}