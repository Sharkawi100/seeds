<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function show(Result $result)
    {
        // Load relationships
        $result->load(['quiz.questions', 'answers.question']);

        // Check authorization
        if ($this->canViewResult($result)) {
            return view('results.show', compact('result'));
        }

        abort(403, 'غير مصرح لك بعرض هذه النتيجة');
    }

    /**
     * Check if current user/guest can view the result
     */
    private function canViewResult(Result $result): bool
    {
        // Case 1: Authenticated user viewing their own result
        if (Auth::check() && $result->user_id !== null) {
            return (int) $result->user_id === Auth::id();
        }

        // Case 2: Guest viewing their result with matching token
        if (!Auth::check() && $result->guest_token !== null) {
            return $result->guest_token === session('guest_token');
        }

        // Case 3: Authenticated user (teacher) viewing results for their quiz
        if (Auth::check() && $result->quiz) {
            return (int) $result->quiz->user_id === Auth::id();
        }

        return false;
    }

    /**
     * Show all results for authenticated users
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $results = Result::where('user_id', Auth::id())
            ->with(['quiz'])
            ->latest()
            ->paginate(10);

        return view('results.index', compact('results'));
    }

    /**
     * Show results for a specific quiz (for quiz owners)
     */
    public function quizResults($quizId)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $quiz = \App\Models\Quiz::findOrFail($quizId);

        // Check if user owns the quiz
        if ((int) $quiz->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض نتائج هذا الاختبار');
        }

        $results = Result::where('quiz_id', $quizId)
            ->with(['user'])
            ->latest()
            ->paginate(20);

        return view('results.quiz-results', compact('quiz', 'results'));
    }
}