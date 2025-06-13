<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    protected $claudeService;

    public function __construct(ClaudeService $claudeService)
    {
        $this->claudeService = $claudeService;
    }

    public function index(Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        $questions = $quiz->questions()->get();

        return view('quizzes.questions.index', compact('quiz', 'questions'));
    }

    public function create(Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.questions.index', $quiz)
                ->with('error', 'لا يمكن إضافة أسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        return view('quizzes.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.questions.index', $quiz)
                ->with('error', 'لا يمكن إضافة أسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string|max:500',
            'correct_answer' => 'required|string|max:500',
            'root_type' => 'required|in:jawhar,zihn,waslat,roaya',
            'depth_level' => 'required|integer|min:1|max:3',
            'explanation' => 'nullable|string|max:1000'
        ]);

        // Ensure correct answer exists in options
        if (!in_array($validated['correct_answer'], $validated['options'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'الإجابة الصحيحة يجب أن تكون من ضمن الخيارات المتاحة.');
        }

        Question::create([
            'quiz_id' => $quiz->id,
            'question' => $validated['question'],
            'options' => $validated['options'],
            'correct_answer' => $validated['correct_answer'],
            'root_type' => $validated['root_type'],
            'depth_level' => $validated['depth_level'],
            'explanation' => $validated['explanation']
        ]);

        return redirect()->route('quizzes.questions.index', $quiz)
            ->with('success', 'تم إضافة السؤال بنجاح.');
    }

    public function edit(Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.questions.index', $quiz)
                ->with('error', 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        return view('quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.questions.index', $quiz)
                ->with('error', 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string|max:500',
            'correct_answer' => 'required|string|max:500',
            'root_type' => 'required|in:jawhar,zihn,waslat,roaya',
            'depth_level' => 'required|integer|min:1|max:3',
            'explanation' => 'nullable|string|max:1000'
        ]);

        // Ensure correct answer exists in options
        if (!in_array($validated['correct_answer'], $validated['options'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'الإجابة الصحيحة يجب أن تكون من ضمن الخيارات المتاحة.');
        }

        $question->update($validated);

        return redirect()->route('quizzes.questions.index', $quiz)
            ->with('success', 'تم تحديث السؤال بنجاح.');
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.questions.index', $quiz)
                ->with('error', 'لا يمكن حذف الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        $question->delete();

        return redirect()->route('quizzes.questions.index', $quiz)
            ->with('success', 'تم حذف السؤال بنجاح.');
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
            'field' => 'required|in:question,explanation',
            'value' => 'required|string|max:1000'
        ]);

        $question->update([
            $validated['field'] => $validated['value']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم التحديث بنجاح.'
        ]);
    }

    public function clone(Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.questions.index', $quiz)
                ->with('error', 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        $newQuestion = $question->replicate();
        $newQuestion->save();

        return redirect()->route('quizzes.questions.index', $quiz)
            ->with('success', 'تم نسخ السؤال بنجاح.');
    }

    public function bulkDelete(Request $request, Quiz $quiz)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        if ($quiz->has_submissions) {
            return redirect()->route('quizzes.questions.index', $quiz)
                ->with('error', 'لا يمكن تعديل الأسئلة بعد أن بدأ الطلاب في الاختبار.');
        }

        $validated = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id'
        ]);

        Question::whereIn('id', $validated['question_ids'])
            ->where('quiz_id', $quiz->id)
            ->delete();

        return redirect()->route('quizzes.questions.index', $quiz)
            ->with('success', 'تم حذف الأسئلة المحددة بنجاح.');
    }

    /**
     * Generate AI suggestions for question improvement
     */
    public function generateSuggestions(Request $request, Quiz $quiz, Question $question)
    {
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        try {
            $suggestions = $this->claudeService->generateQuestionSuggestions(
                $question->question,
                $question->options,
                $question->correct_answer,
                $question->root_type,
                $question->depth_level,
                $quiz->passage ?? ''
            );

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            Log::error('Question suggestions failed', [
                'error' => $e->getMessage(),
                'question_id' => $question->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل توليد الاقتراحات: ' . $e->getMessage()
            ], 500);
        }
    }
}