<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page with statistics
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cache data for 30 minutes to improve performance
        $data = Cache::remember('welcome_page_data', 1800, function () {
            return [
                'activeSubjects' => $this->getActiveSubjects(),
                'stats' => $this->getGeneralStats(),
                'growthStats' => $this->getGrowthStats(),
            ];
        });

        return view('welcome', $data);
    }

    /**
     * Handle PIN entry from landing page
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enterPin(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:6'
        ]);

        // Find quiz by PIN
        $quiz = Quiz::where('pin', $validated['pin'])
            ->where('is_active', true)
            ->first();

        if (!$quiz) {
            return redirect()->back()
                ->with('error', 'رمز الاختبار غير صحيح أو منتهي الصلاحية')
                ->withInput();
        }

        // Redirect to quiz taking page
        // Check if user is guest
        if (!Auth::check()) {
            return redirect()->route('quiz.take', $quiz)
                ->with('show_guest_form', true);
        }
        return redirect()->route('quiz.take', $quiz);
    }

    /**
     * Show demo quiz
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function demo()
    {
        $demoQuiz = Quiz::where('is_demo', true)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (!$demoQuiz) {
            abort(404, 'الاختبار التجريبي غير متاح حالياً');
        }

        return redirect()->route('quiz.take', $demoQuiz);
    }

    /**
     * Get active subjects for today
     *
     * @return array
     */
    private function getActiveSubjects()
    {
        // Use the proper relationship with subjects table
        $subjects = Quiz::join('subjects', 'quizzes.subject_id', '=', 'subjects.id')
            ->where('quizzes.created_at', '>=', today())
            ->where('subjects.is_active', true)
            ->groupBy('subjects.id', 'subjects.name', 'subjects.slug')
            ->select(
                'subjects.id',
                'subjects.name',
                'subjects.slug',
                DB::raw('count(quizzes.id) as count')
            )
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $subjects->map(function ($item) {
            return [
                'name' => $item->name,
                'slug' => $item->slug,
                'count' => $item->count
            ];
        })->toArray();
    }

    /**
     * Get general statistics for the welcome page
     *
     * @return array
     */
    private function getGeneralStats()
    {
        return [
            'total_quizzes' => Quiz::where('is_active', true)->count(),
            'total_users' => User::where('is_active', true)->count(),
            'total_results' => Result::count(),
            'demo_quizzes' => Quiz::where('is_demo', true)
                ->where('is_active', true)
                ->count(),
        ];
    }

    /**
     * Get growth statistics (compared to last period)
     *
     * @return array
     */
    private function getGrowthStats()
    {
        $lastWeek = now()->subWeek();
        $twoWeeksAgo = now()->subWeeks(2);

        $currentWeek = [
            'quizzes' => Quiz::where('created_at', '>=', $lastWeek)->count(),
            'users' => User::where('created_at', '>=', $lastWeek)->count(),
            'results' => Result::where('created_at', '>=', $lastWeek)->count(),
        ];

        $previousWeek = [
            'quizzes' => Quiz::whereBetween('created_at', [$twoWeeksAgo, $lastWeek])->count(),
            'users' => User::whereBetween('created_at', [$twoWeeksAgo, $lastWeek])->count(),
            'results' => Result::whereBetween('created_at', [$twoWeeksAgo, $lastWeek])->count(),
        ];

        return [
            'quizzes_growth' => $this->calculateGrowthPercentage(
                $currentWeek['quizzes'],
                $previousWeek['quizzes']
            ),
            'users_growth' => $this->calculateGrowthPercentage(
                $currentWeek['users'],
                $previousWeek['users']
            ),
            'results_growth' => $this->calculateGrowthPercentage(
                $currentWeek['results'],
                $previousWeek['results']
            ),
        ];
    }

    /**
     * Calculate growth percentage between two numbers
     *
     * @param int $current
     * @param int $previous
     * @return float
     */
    private function calculateGrowthPercentage(int $current, int $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}