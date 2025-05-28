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
            'passage_topic' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            // Transform roots structure for database storage
            $settings = [];
            if (isset($validated['roots'])) {
                foreach ($validated['roots'] as $rootKey => $root) {
                    $levels = [];
                    foreach ($root['levels'] ?? [] as $level) {
                        if (isset($level['count']) && $level['count'] > 0) {
                            $levels[] = [
                                'depth' => $level['depth'] ?? 1,
                                'count' => (int) $level['count']
                            ];
                        }
                    }
                    if (!empty($levels)) {
                        $settings[$rootKey] = $levels;
                    }
                }
            }

            $quiz = Quiz::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'grade_level' => $validated['grade_level'],
                'settings' => $settings
            ]);

            // Handle different creation methods
            switch ($validated['creation_method']) {
                case 'manual':
                    DB::commit();
                    return redirect()->route('quizzes.questions.create', $quiz)
                        ->with('success', 'تم إنشاء الاختبار. الآن أضف الأسئلة.');

                case 'ai':
                case 'hybrid':
                    try {
                        $aiResponse = $this->claudeService->generateJuzoorQuiz(
                            $quiz->subject,
                            $quiz->grade_level,
                            $validated['topic'] ?? '',
                            $settings,
                            $request->boolean('include_passage'),
                            $validated['passage_topic'] ?? null
                        );

                        $this->parseAndSaveQuestions($quiz, $aiResponse);

                        DB::commit();

                        if ($validated['creation_method'] === 'hybrid') {
                            return redirect()->route('quizzes.questions.index', $quiz)
                                ->with('success', 'تم توليد الأسئلة بنجاح. يمكنك الآن تعديلها.');
                        }

                        return redirect()->route('quizzes.show', $quiz)
                            ->with('success', 'تم إنشاء الاختبار وتوليد الأسئلة بنجاح.');

                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Quiz AI generation failed', [
                            'error' => $e->getMessage(),
                            'quiz_id' => $quiz->id
                        ]);

                        // Delete the quiz if AI generation fails
                        $quiz->delete();

                        return redirect()->route('quizzes.create')
                            ->with('error', 'فشل توليد الأسئلة بالذكاء الاصطناعي. الرجاء المحاولة مرة أخرى.')
                            ->withInput();
                    }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz creation failed', ['error' => $e->getMessage()]);

            return redirect()->route('quizzes.create')
                ->with('error', 'حدث خطأ أثناء إنشاء الاختبار.')
                ->withInput();
        }
    }

    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);

        $quiz->load('questions');
        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|in:arabic,english,hebrew',
            'grade_level' => 'required|integer|min:1|max:9',
        ]);

        $quiz->update($validated);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'تم تحديث الاختبار بنجاح.');
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);

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
                if (!$question)
                    continue;

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

    protected function authorize($ability, $quiz)
    {
        if (!Auth::check() || Auth::id() !== $quiz->user_id) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }
    }

}

