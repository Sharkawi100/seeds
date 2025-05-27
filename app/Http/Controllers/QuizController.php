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

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('user_id', Auth::id())->latest()->get();
        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function store(Request $request, ClaudeService $claude)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'grade_level' => 'required|integer|min:1|max:9',
            'creation_method' => 'required|in:ai,manual,hybrid',
            'roots' => 'required_if:creation_method,ai,hybrid|array'
        ]);

        // Transform roots structure
        if (isset($validated['roots'])) {
            foreach ($validated['roots'] as $rootKey => $root) {
                $levels = [];
                foreach ($root['levels'] ?? [] as $depth => $count) {
                    if ($count > 0) {
                        $levels[] = ['depth' => $depth, 'count' => $count];
                    }
                }
                $validated['roots'][$rootKey] = $levels;
            }
        }

        $quiz = Quiz::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'subject' => $validated['subject'],
            'grade_level' => $validated['grade_level'],
            'settings' => $validated['roots'] ?? []
        ]);

        // Handle different creation methods
        switch ($validated['creation_method']) {
            case 'manual':
                return redirect()->route('quizzes.questions.create', $quiz);

            case 'ai':
                try {
                    $aiResponse = $claude->generateQuiz($quiz->subject, $quiz->grade_level, $quiz->settings);
                    if (isset($aiResponse['content'][0]['text'])) {
                        $this->parseAndSaveQuestions($quiz, $aiResponse['content'][0]['text']);
                    }
                } catch (\Exception $e) {
                    Log::error('Quiz generation failed: ' . $e->getMessage());
                }
                return redirect()->route('quizzes.show', $quiz);

            case 'hybrid':
                try {
                    $aiResponse = $claude->generateQuiz($quiz->subject, $quiz->grade_level, $quiz->settings);
                    if (isset($aiResponse['content'][0]['text'])) {
                        $this->parseAndSaveQuestions($quiz, $aiResponse['content'][0]['text']);
                    }
                } catch (\Exception $e) {
                    Log::error('Quiz generation failed: ' . $e->getMessage());
                }
                return redirect()->route('quizzes.questions.index', $quiz);
        }
    }

    public function show(Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403);
        }

        $quiz->load('questions');
        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403);
        }

        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'grade_level' => 'required|string',
        ]);

        $quiz->update($validated);
        return redirect()->route('quizzes.show', $quiz);
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403);
        }

        $quiz->delete();
        return redirect()->route('quizzes.index');
    }

    public function take(Quiz $quiz)
    {
        $quiz->load('questions');
        return view('quizzes.take', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $answers = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ])['answers'];

        $result = Result::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'guest_token' => !Auth::check() ? Str::random(32) : null,
            'scores' => [],
            'total_score' => 0,
            'expires_at' => !Auth::check() ? now()->addDay() : null
        ]);

        if (!Auth::check()) {
            session(['guest_token' => $result->guest_token]);
        }

        $rootScores = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
        $rootCounts = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
        $totalScore = 0;

        foreach ($answers as $questionId => $selectedAnswer) {
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

            $rootCounts[$question->root_type]++;
            if ($isCorrect) {
                $rootScores[$question->root_type]++;
                $totalScore++;
            }
        }

        foreach ($rootScores as $root => $score) {
            if ($rootCounts[$root] > 0) {
                $rootScores[$root] = round(($score / $rootCounts[$root]) * 100);
            }
        }

        $result->update([
            'scores' => $rootScores,
            'total_score' => $quiz->questions->count() > 0
                ? round(($totalScore / $quiz->questions->count()) * 100)
                : 0
        ]);

        return redirect()->route('results.show', $result);
    }

    private function parseAndSaveQuestions(Quiz $quiz, string $aiResponse)
    {
        $sampleQuestions = [
            ['root' => 'jawhar', 'depth' => 1, 'q' => 'What is the definition?', 'options' => ['Option A', 'Option B', 'Option C', 'Option D'], 'answer' => 'Option A'],
            ['root' => 'zihn', 'depth' => 2, 'q' => 'How does it work?', 'options' => ['Option A', 'Option B', 'Option C', 'Option D'], 'answer' => 'Option B'],
            ['root' => 'waslat', 'depth' => 2, 'q' => 'How does it connect?', 'options' => ['Option A', 'Option B', 'Option C', 'Option D'], 'answer' => 'Option C'],
            ['root' => 'roaya', 'depth' => 3, 'q' => 'How can we use it?', 'options' => ['Option A', 'Option B', 'Option C', 'Option D'], 'answer' => 'Option D'],
        ];

        foreach ($sampleQuestions as $q) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question' => $q['q'],
                'root_type' => $q['root'],
                'depth_level' => $q['depth'],
                'options' => $q['options'],
                'correct_answer' => $q['answer']
            ]);
        }
    }
}