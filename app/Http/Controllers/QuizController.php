<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\Answer;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    protected $claudeService;

    public function __construct(ClaudeService $claudeService)
    {
        $this->claudeService = $claudeService;
    }

    public function index()
    {
        $quizzes = Quiz::where('user_id', Auth::id())
            ->with('questions')
            ->latest()
            ->get();

        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'creation_method' => 'required|in:ai,manual,hybrid',
            'roots' => 'required_if:creation_method,ai,hybrid|array',
            'topic' => 'required_if:creation_method,ai,hybrid|string|max:255',
            'include_passage' => 'boolean',
            'passage_topic' => 'nullable|string|max:255',
            'educational_text' => 'nullable|string',
            'text_source' => 'nullable|string',
            'text_type' => 'nullable|string',
            'text_length' => 'nullable|string'
        ]);

        Log::info('Quiz store request', [
            'method' => $request->method(),
            'creation_method' => $validated['creation_method'] ?? 'not set',
            'all_data' => $request->all(),
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson()
        ]);

        DB::beginTransaction();

        try {
            // Transform roots structure for database storage AND for ClaudeService
            $settingsForDb = [];
            $settingsForClaude = [];

            if (isset($validated['roots'])) {
                foreach ($validated['roots'] as $rootKey => $root) {
                    $settingsForClaude[$rootKey] = [];
                    $levelsForDb = [];

                    foreach ($root['levels'] ?? [] as $level => $levelData) {
                        if (isset($levelData['count']) && $levelData['count'] > 0) {
                            // For database storage
                            $levelsForDb[] = [
                                'depth' => $levelData['depth'] ?? $level,
                                'count' => (int) $levelData['count']
                            ];

                            // For Claude service (simple key => value)
                            $depthKey = $levelData['depth'] ?? $level;
                            $settingsForClaude[$rootKey][$depthKey] = (int) $levelData['count'];
                        }
                    }

                    if (!empty($levelsForDb)) {
                        $settingsForDb[$rootKey] = $levelsForDb;
                    }
                }
            }

            $quiz = Quiz::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'grade_level' => $validated['grade_level'],
                'pin' => strtoupper(Str::random(6)), // ? Add this line
                'settings' => [
                    'creation_method' => $validated['creation_method'],
                    'question_count' => $validated['question_count'] ?? 0,
                    'time_limit' => $validated['time_limit'] ?? null,
                    'allow_review' => $validated['allow_review'] ?? true,
                ],
            ]);
            // Generate PIN immediately
            $quiz->generatePin();

            // Handle different creation methods
            switch ($validated['creation_method']) {
                case 'manual':
                    DB::commit();

                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => true,
                            'redirect' => route('quizzes.questions.create', $quiz)
                        ]);
                    }

                    return redirect()->route('quizzes.questions.create', $quiz)
                        ->with('success', 'تم إنشاء الاختبار. الآن أضف الأسئلة.');

                case 'ai':
                case 'hybrid':
                    try {
                        // Check if we have educational text
                        $hasEducationalText = isset($validated['educational_text']) &&
                            !empty(trim($validated['educational_text']));

                        Log::info('Educational text check', [
                            'has_educational_text' => $hasEducationalText,
                            'text_length' => strlen($validated['educational_text'] ?? ''),
                            'text_preview' => substr($validated['educational_text'] ?? '', 0, 200)
                        ]);

                        if ($hasEducationalText) {
                            Log::info('Using provided educational text for questions generation');

                            // Generate questions from the provided text
                            $questions = $this->claudeService->generateQuestionsFromText(
                                $validated['educational_text'],
                                $quiz->subject,
                                $quiz->grade_level,
                                $settingsForClaude  // Use the Claude-formatted settings
                            );

                            // Structure the response with the educational text as passage
                            $aiResponse = [
                                'questions' => $questions,
                                'passage' => $validated['educational_text'],
                                'passage_title' => $validated['topic'] ?? 'نص القراءة'
                            ];

                            Log::info('Questions generated from educational text', [
                                'questions_count' => count($questions),
                                'will_save_passage' => true
                            ]);
                        } else {
                            Log::info('No educational text provided, generating complete quiz with passage');

                            // Generate complete quiz with passage
                            $aiResponse = $this->claudeService->generateJuzoorQuiz(
                                $quiz->subject,
                                $quiz->grade_level,
                                $validated['topic'] ?? '',
                                $settingsForDb,  // Use the DB-formatted settings (generateJuzoorQuiz transforms it)
                                true, // Always include passage
                                $validated['passage_topic'] ?? null
                            );
                        }

                        // Save questions with passage
                        $this->parseAndSaveQuestions($quiz, $aiResponse);

                        // Verify the passage was saved
                        $savedPassage = $quiz->questions()->whereNotNull('passage')->first();
                        Log::info('Passage save verification', [
                            'passage_saved' => !is_null($savedPassage),
                            'passage_length' => $savedPassage ? strlen($savedPassage->passage) : 0
                        ]);

                        DB::commit();

                        $redirectUrl = $validated['creation_method'] === 'hybrid'
                            ? route('quizzes.questions.index', $quiz)
                            : route('quizzes.show', $quiz);

                        if ($request->expectsJson() || $request->ajax()) {
                            return response()->json([
                                'success' => true,
                                'redirect' => $redirectUrl
                            ]);
                        }

                        return redirect()->to($redirectUrl)
                            ->with('success', $validated['creation_method'] === 'hybrid'
                                ? 'تم توليد الأسئلة بنجاح. يمكنك الآن تعديلها.'
                                : 'تم إنشاء الاختبار وتوليد الأسئلة بنجاح.')
                            ->with('quiz_created', true)
                            ->with('quiz_pin', $quiz->pin_code)
                            ->with('quiz_id', $quiz->id);

                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Quiz AI generation failed', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'quiz_id' => $quiz->id
                        ]);

                        // Delete the quiz if AI generation fails
                        $quiz->delete();

                        if ($request->expectsJson() || $request->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'فشل توليد الأسئلة بالذكاء الاصطناعي. الرجاء المحاولة مرة أخرى.'
                            ], 422);
                        }

                        return redirect()->route('quizzes.create')
                            ->with('error', 'فشل توليد الأسئلة بالذكاء الاصطناعي. الرجاء المحاولة مرة أخرى.')
                            ->withInput();
                    }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء الاختبار.'
                ], 422);
            }

            return redirect()->route('quizzes.create')
                ->with('error', 'حدث خطأ أثناء إنشاء الاختبار.')
                ->withInput();
        }
    }
    public function generateMissingPins()
    {
        $quizzes = Quiz::whereNull('pin_code')->orWhere('pin_code', '')->get();

        foreach ($quizzes as $quiz) {
            $quiz->generatePin();
        }

        return "Generated PINs for {$quizzes->count()} quizzes";
    }
    public function show(Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        $quiz->load('questions');
        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'show_results' => 'boolean'
        ]);

        $quiz->update($validated);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'تم تحديث الاختبار بنجاح.');
    }

    public function destroy(Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', 'تم حذف الاختبار بنجاح.');
    }

    public function take(Quiz $quiz)
    {
        $quiz->load('questions');

        if ($quiz->questions->isEmpty()) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يحتوي هذا الاختبار على أسئلة بعد.');
        }

        return view('quizzes.take', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            // Create result
            $result = Result::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id(),
                'guest_token' => !Auth::check() ? Str::random(32) : null,
                'scores' => ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0],
                'total_score' => 0,
                'expires_at' => !Auth::check() ? now()->addDays(7) : null
            ]);

            if (!Auth::check()) {
                session(['guest_token' => $result->guest_token]);
            }

            // Calculate scores
            $rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
            $rootCounts = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
            $totalScore = 0;

            foreach ($validated['answers'] as $questionId => $selectedAnswer) {
                $question = $quiz->questions->find($questionId);
                if (!$question) {
                    continue;
                }

                $isCorrect = $question->correct_answer === $selectedAnswer;

                Answer::create([
                    'question_id' => $questionId,
                    'result_id' => $result->id,
                    'selected_answer' => $selectedAnswer,
                    'is_correct' => $isCorrect
                ]);

                if (isset($rootCounts[$question->root_type])) {
                    $rootCounts[$question->root_type]++;
                    if ($isCorrect) {
                        $rootScores[$question->root_type]++;
                        $totalScore++;
                    }
                }
            }

            // Calculate percentages
            foreach ($rootScores as $root => $score) {
                if ($rootCounts[$root] > 0) {
                    $rootScores[$root] = round(($score / $rootCounts[$root]) * 100);
                }
            }

            // Update result with calculated scores
            $result->update([
                'scores' => $rootScores,
                'total_score' => $quiz->questions->count() > 0
                    ? round(($totalScore / $quiz->questions->count()) * 100)
                    : 0
            ]);

            DB::commit();

            return redirect()->route('results.show', $result);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz submission failed', [
                'error' => $e->getMessage(),
                'quiz_id' => $quiz->id
            ]);

            return redirect()->route('quiz.take', $quiz)
                ->with('error', 'حدث خطأ أثناء حفظ النتائج. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * Generate educational text using AI
     * This handles the AJAX request from step 2 of quiz creation
     */
    public function generateText(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
            'topic' => 'required|string|max:255',
            'text_type' => 'required|in:story,article,dialogue,description',
            'length' => 'required|in:short,medium,long'
        ]);

        try {
            $text = $this->claudeService->generateEducationalText(
                $validated['subject'],
                $validated['grade_level'],
                $validated['topic'],
                $validated['text_type'],
                $validated['length']
            );

            return response()->json([
                'success' => true,
                'text' => $text
            ]);

        } catch (\Exception $e) {
            Log::error('Text generation failed', [
                'error' => $e->getMessage(),
                'params' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل توليد النص: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Parse and save questions from AI response
     */
    private function parseAndSaveQuestions(Quiz $quiz, array $aiResponse)
    {
        if (!isset($aiResponse['questions']) || !is_array($aiResponse['questions'])) {
            throw new \Exception('Invalid AI response format');
        }

        $passageTitle = $aiResponse['passage_title'] ?? null;
        $passage = $aiResponse['passage'] ?? null;

        foreach ($aiResponse['questions'] as $index => $questionData) {
            // Validate question data
            if (
                !isset(
                $questionData['question'],
                $questionData['root_type'],
                $questionData['depth_level'],
                $questionData['options'],
                $questionData['correct_answer']
            )
            ) {
                Log::warning('Skipping invalid question data', ['index' => $index]);
                continue;
            }

            // Ensure options is an array
            $options = is_array($questionData['options'])
                ? array_values($questionData['options'])
                : [];

            if (count($options) < 2) {
                Log::warning('Skipping question with insufficient options', ['index' => $index]);
                continue;
            }

            Question::create([
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
}