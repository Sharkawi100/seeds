<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Result;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AiManagementController extends Controller
{
    protected $claudeService;

    public function __construct(ClaudeService $claudeService)
    {
        $this->claudeService = $claudeService;
    }

    public function index()
    {
        $stats = [
            'total_ai_quizzes' => Quiz::whereHas('questions')->count(),
            'recent_generations' => Quiz::with(['user', 'questions'])
                ->whereHas('questions')
                ->latest()
                ->take(10)
                ->get()
        ];

        return view('admin.ai.index', compact('stats'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'topic' => 'required|string|max:255',
            'include_passage' => 'nullable|string',
            'passage_topic' => 'nullable|string|max:255',
            'manual_passage' => 'nullable|string',
            'manual_passage_title' => 'nullable|string|max:255',
            'roots' => 'required|array'
        ]);

        DB::beginTransaction();

        try {
            // Transform roots structure
            $settings = [];
            foreach ($validated['roots'] as $rootKey => $root) {
                if (!isset($root['levels']))
                    continue;

                $levels = [];
                foreach ($root['levels'] as $level) {
                    if (isset($level['count']) && $level['count'] > 0) {
                        $levels[] = [
                            'depth' => (int) ($level['depth'] ?? 1),
                            'count' => (int) $level['count']
                        ];
                    }
                }
                if (!empty($levels)) {
                    $settings[$rootKey] = $levels;
                }
            }

            // Create quiz
            $quiz = Quiz::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'grade_level' => $validated['grade_level'],
                'settings' => $settings
            ]);

            // Check if we should include a passage
            $includePassage = $request->input('include_passage') === 'on' || $request->input('include_passage') === '1';
            $manualPassage = $request->input('manual_passage');
            $manualPassageTitle = $request->input('manual_passage_title');

            // Generate questions with AI
            $aiResponse = $this->claudeService->generateJuzoorQuiz(
                $quiz->subject,
                $quiz->grade_level,
                $validated['topic'],
                $settings,
                $includePassage && !$manualPassage, // Only generate passage if no manual passage
                $validated['passage_topic'] ?? null
            );

            // If manual passage is provided, add it to the response
            if ($manualPassage) {
                $aiResponse['passage'] = $manualPassage;
                $aiResponse['passage_title'] = $manualPassageTitle;
            }

            $this->parseAndSaveQuestions($quiz, $aiResponse);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('admin.quizzes.show', $quiz)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete the quiz if it was created
            if (isset($quiz)) {
                $quiz->delete();
            }

            Log::error('AI quiz generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $validated
            ]);

            // More specific error messages
            $message = 'فشل توليد الاختبار. ';

            if (strpos($e->getMessage(), 'API') !== false || strpos($e->getMessage(), 'key') !== false) {
                $message .= 'تحقق من مفتاح Claude API.';
            } elseif (strpos($e->getMessage(), 'network') !== false || strpos($e->getMessage(), 'connection') !== false) {
                $message .= 'خطأ في الاتصال بالخادم.';
            } else {
                $message .= 'الرجاء المحاولة مرة أخرى.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function generateReport(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'result_id' => 'required|exists:results,id'
        ]);

        try {
            $result = Result::with(['answers.question', 'user'])->findOrFail($validated['result_id']);

            // Generate a detailed report based on the result
            $report = $this->generateResultReport($result, $quiz);

            return response()->json([
                'success' => true,
                'report' => $report
            ]);

        } catch (\Exception $e) {
            Log::error('Report generation failed', [
                'error' => $e->getMessage(),
                'quiz_id' => $quiz->id,
                'result_id' => $validated['result_id']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل توليد التقرير.'
            ], 500);
        }
    }

    private function generateResultReport(Result $result, Quiz $quiz)
    {
        // Prepare the data for the AI
        $performance = [
            'total_score' => $result->total_score,
            'root_scores' => $result->scores,
            'quiz_info' => [
                'title' => $quiz->title,
                'subject' => $quiz->subject,
                'grade_level' => $quiz->grade_level,
                'total_questions' => $quiz->questions->count()
            ],
            'detailed_answers' => $result->answers->map(function ($answer) {
                return [
                    'question' => $answer->question->question,
                    'root_type' => $answer->question->root_type,
                    'depth_level' => $answer->question->depth_level,
                    'is_correct' => $answer->is_correct,
                    'selected_answer' => $answer->selected_answer,
                    'correct_answer' => $answer->question->correct_answer
                ];
            })
        ];

        // Use Claude to generate an educational report
        $prompt = $this->buildReportPrompt($performance);

        // Call Claude API to generate the report
        $response = $this->claudeService->generateCompletion($prompt, [
            'max_tokens' => 2000,
            'temperature' => 0.7
        ]);

        return $response['content'] ?? 'تعذر إنشاء التقرير';
    }

    private function buildReportPrompt($performance)
    {
        $prompt = "أنت خبير تربوي متخصص في نموذج جُذور التعليمي. قم بتحليل أداء الطالب وإنشاء تقرير تفصيلي باللغة العربية.\n\n";

        $prompt .= "معلومات الاختبار:\n";
        $prompt .= "- العنوان: {$performance['quiz_info']['title']}\n";
        $prompt .= "- المادة: {$performance['quiz_info']['subject']}\n";
        $prompt .= "- الصف: {$performance['quiz_info']['grade_level']}\n";
        $prompt .= "- عدد الأسئلة: {$performance['quiz_info']['total_questions']}\n\n";

        $prompt .= "النتيجة الإجمالية: {$performance['total_score']}%\n\n";

        $prompt .= "أداء الجذور:\n";
        foreach ($performance['root_scores'] as $root => $score) {
            $rootName = ['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'][$root] ?? $root;
            $prompt .= "- $rootName: $score%\n";
        }

        $prompt .= "\nيرجى كتابة تقرير شامل يتضمن:\n";
        $prompt .= "1. تحليل عام للأداء\n";
        $prompt .= "2. نقاط القوة في كل جذر\n";
        $prompt .= "3. المجالات التي تحتاج إلى تحسين\n";
        $prompt .= "4. توصيات محددة للتطوير\n";
        $prompt .= "5. أنشطة مقترحة لتعزيز التعلم\n\n";

        $prompt .= "اجعل التقرير إيجابياً ومحفزاً، مع التركيز على النمو والتطور بدلاً من النقص.";

        return $prompt;
    }

    private function parseAndSaveQuestions(Quiz $quiz, array $aiResponse)
    {
        if (!isset($aiResponse['questions']) || !is_array($aiResponse['questions'])) {
            throw new \Exception('Invalid AI response format');
        }

        $passageTitle = $aiResponse['passage_title'] ?? null;
        $passage = $aiResponse['passage'] ?? null;

        foreach ($aiResponse['questions'] as $index => $questionData) {
            if (
                !isset(
                $questionData['question'],
                $questionData['root_type'],
                $questionData['depth_level'],
                $questionData['options'],
                $questionData['correct_answer']
            )
            ) {
                continue;
            }

            $options = is_array($questionData['options'])
                ? array_values($questionData['options'])
                : [];

            if (count($options) < 2) {
                continue;
            }

            \App\Models\Question::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'root_type' => $questionData['root_type'],
                'depth_level' => (int) $questionData['depth_level'],
                'options' => $options,
                'correct_answer' => $questionData['correct_answer'],
                'passage' => $index === 0 ? $passage : null,
                'passage_title' => $index === 0 ? $passageTitle : null,
            ]);
        }
    }

    // API methods for additional functionality
    public function apiGeneratePassage(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'topic' => 'required|string|max:255',
            'length' => 'required|in:short,medium,long'
        ]);

        try {
            $passage = $this->claudeService->generatePassage(
                $validated['subject'],
                $validated['grade_level'],
                $validated['topic'],
                $validated['length']
            );

            return response()->json([
                'success' => true,
                'passage' => $passage['content'],
                'title' => $passage['title'] ?? $validated['topic']
            ]);
        } catch (\Exception $e) {
            Log::error('Passage generation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'فشل توليد النص'
            ], 500);
        }
    }

    public function apiGenerateQuestions(Request $request)
    {
        $validated = $request->validate([
            'passage' => 'required|string',
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'root_distribution' => 'required|array'
        ]);

        try {
            $questions = $this->claudeService->generateQuestionsFromPassage(
                $validated['passage'],
                $validated['subject'],
                $validated['grade_level'],
                $validated['root_distribution']
            );

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);
        } catch (\Exception $e) {
            Log::error('Question generation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'فشل توليد الأسئلة'
            ], 500);
        }
    }
}