<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        return view('quizzes.questions.index', compact('quiz'));
    }

    public function create(Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        return view('quizzes.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        $validated = $request->validate([
            'passage_title' => 'nullable|string|max:255',
            'passage' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.root_type' => 'required|in:jawhar,zihn,waslat,roaya',
            'questions.*.depth_level' => 'required|in:1,2,3',
            'questions.*.options' => 'required|array|min:2|max:6',
            'questions.*.correct_answer' => 'required|numeric'
        ]);

        foreach ($validated['questions'] as $index => $q) {
            $correctAnswerIndex = $q['correct_answer'];
            $correctAnswer = $q['options'][$correctAnswerIndex] ?? $q['options'][0];

            Question::create([
                'quiz_id' => $quiz->id,
                'passage' => $index === 0 ? ($validated['passage'] ?? null) : null,
                'passage_title' => $index === 0 ? ($validated['passage_title'] ?? null) : null,
                'question' => $q['question'],
                'root_type' => $q['root_type'],
                'depth_level' => $q['depth_level'],
                'options' => array_values(array_filter($q['options'])),
                'correct_answer' => $correctAnswer
            ]);
        }

        return redirect()->route('quizzes.show', $quiz);
    }

    public function edit(Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        return view('quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        $validated = $request->validate([
            'question' => 'required|string',
            'root_type' => 'required|in:jawhar,zihn,waslat,roaya',
            'depth_level' => 'required|in:1,2,3',
            'options' => 'required|array|min:2|max:6',
            'correct_answer_index' => 'required|numeric'
        ]);

        $correctAnswer = $validated['options'][$validated['correct_answer_index']] ?? $validated['options'][0];

        $question->update([
            'question' => $validated['question'],
            'root_type' => $validated['root_type'],
            'depth_level' => $validated['depth_level'],
            'options' => array_values($validated['options']),
            'correct_answer' => $correctAnswer
        ]);

        return redirect()->route('quizzes.questions.index', $quiz);
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        $question->delete();

        return redirect()->route('quizzes.questions.index', $quiz);
    }

    public function updateText(Request $request, Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.'
            ], 403);
        }

        $validated = $request->validate([
            'question' => 'required|string'
        ]);

        $question->update([
            'question' => $validated['question']
        ]);

        return response()->json(['success' => true]);
    }
}