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
            // Show the same detailed view for everyone (teachers, students, admins)
            return view('results.show', compact('result'));
        }

        abort(403, 'غير مصرح لك بعرض هذه النتيجة');
    }

    /**
     * Check if current user/guest can view the result
     */
    private function canViewResult(Result $result): bool
    {
        // Case 1: Admin can view all results
        if (Auth::check() && Auth::user()->is_admin) {
            return true;
        }

        // Case 2: Authenticated user viewing their own result
        if (Auth::check() && $result->user_id !== null) {
            return (int) $result->user_id === Auth::id();
        }

        // Case 3: Guest viewing their result with matching token
        if (!Auth::check() && $result->guest_token !== null) {
            return $result->guest_token === session('guest_token');
        }

        // Case 4: Authenticated user (teacher) viewing results for their quiz
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

        // Check if user is a teacher
        if (Auth::user()->is_admin || Auth::user()->user_type === 'teacher') {
            // Show teacher's quizzes with result counts (excluding demo results)
            $quizzes = \App\Models\Quiz::where('user_id', Auth::id())
                ->withCount([
                    'results' => function ($query) {
                        $query->whereHas('quiz', function ($q) {
                            $q->where('is_demo', false);
                        });
                    }
                ])
                ->with([
                    'results' => function ($query) {
                        $query->whereHas('quiz', function ($q) {
                            $q->where('is_demo', false);
                        })->latest()->limit(5);
                    }
                ])
                ->latest()
                ->paginate(10);

            // Calculate statistics for all quizzes (excluding demo results)
            $stats = \App\Models\Quiz::where('user_id', Auth::id())
                ->withCount([
                    'results' => function ($query) {
                        $query->whereHas('quiz', function ($q) {
                            $q->where('is_demo', false);
                        });
                    }
                ])
                ->with([
                    'results' => function ($query) {
                        $query->whereHas('quiz', function ($q) {
                            $q->where('is_demo', false);
                        });
                    }
                ])
                ->get()
                ->reduce(function ($carry, $quiz) {
                    $carry['total_results'] += $quiz->results_count;
                    $carry['total_quizzes']++;
                    foreach ($quiz->results as $result) {
                        if ($result->total_score >= 60) {
                            $carry['passing_results']++;
                        }
                    }
                    return $carry;
                }, ['total_results' => 0, 'total_quizzes' => 0, 'passing_results' => 0]);

            $stats['success_rate'] = $stats['total_results'] > 0
                ? round(($stats['passing_results'] / $stats['total_results']) * 100)
                : 0;

            return view('results.teacher-index', compact('quizzes', 'stats'));
        }

        // For students, show their own results (excluding demo results)
        $results = Result::nonDemo()->where('user_id', Auth::id())
            ->with(['quiz'])
            ->latest()
            ->paginate(10);

        return view('results.index', compact('results'));
    }

    /**
     * Show results for a specific quiz (for quiz owners)
     * Note: This shows ALL results for the specific quiz, including demo if the quiz is demo
     * because teachers should see all results for their own quizzes
     */
    public function quizResults($quizId)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $quiz = \App\Models\Quiz::findOrFail($quizId);

        // Check if user owns the quiz
        if ((int) $quiz->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بعرض نتائج هذا الاختبار');
        }

        // Get ALL results for this specific quiz (including demo results if this quiz is demo)
        // Teachers should see all results for their own quizzes
        $results = Result::where('quiz_id', $quizId)
            ->with(['user'])
            ->latest()
            ->paginate(20);

        return view('results.quiz-results', compact('quiz', 'results'));
    }
}