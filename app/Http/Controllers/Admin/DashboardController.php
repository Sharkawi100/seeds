<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache dashboard data for 10 minutes to improve performance
        $data = Cache::remember('admin_dashboard_data', 600, function () {
            return [
                'metrics' => $this->getKeyMetrics(),
                'user_breakdown' => $this->getUserBreakdown(),
                'demo_stats' => $this->getDemoStats(),
                'roots_performance' => $this->getRootsPerformance(),
                'usage_trends' => $this->getUsageTrends(),
                'recent_activity' => $this->getRecentActivity(),
                'quiz_performance' => $this->getQuizPerformance(),
                'system_health' => $this->getSystemHealth(),
                'growth_rates' => $this->getGrowthRates(),
            ];
        });

        return view('admin.dashboard', $data);
    }

    /**
     * Get key platform metrics
     */
    private function getKeyMetrics()
    {
        return [
            'total_users' => User::count(),
            'total_quizzes' => Quiz::count(),
            'total_results' => Result::nonDemo()->count(),
            'total_questions' => Question::count(),
            'demo_attempts' => Result::whereHas('quiz', function ($q) {
                $q->where('is_demo', true);
            })->count(),
        ];
    }

    /**
     * Get user breakdown by type and status
     */
    private function getUserBreakdown()
    {
        return [
            'active_teachers' => User::where('user_type', 'teacher')
                ->where('is_approved', true)
                ->where('last_login_at', '>=', now()->subDays(30))
                ->count(),
            'registered_students' => User::where('user_type', 'student')->count(),
            'pending_approval' => User::where('user_type', 'teacher')
                ->where('is_approved', false)
                ->count(),
            'admins' => User::where('is_admin', true)->count(),
            'total_active' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Get demo vs real usage statistics
     */
    private function getDemoStats()
    {
        $totalResults = Result::count();
        $demoResults = Result::whereHas('quiz', function ($q) {
            $q->where('is_demo', true);
        })->count();
        $realResults = $totalResults - $demoResults;

        return [
            'total_attempts' => $totalResults,
            'demo_attempts' => $demoResults,
            'real_attempts' => $realResults,
            'demo_percentage' => $totalResults > 0 ? round(($demoResults / $totalResults) * 100) : 0,
            'real_percentage' => $totalResults > 0 ? round(($realResults / $totalResults) * 100) : 0,
        ];
    }

    /**
     * Get roots performance analytics
     */
    private function getRootsPerformance()
    {
        $rootsData = Result::nonDemo()
            ->whereNotNull('scores')
            ->get()
            ->map(function ($result) {
                return $result->scores;
            })
            ->filter()
            ->values();

        if ($rootsData->isEmpty()) {
            return [
                'jawhar' => ['average' => 0, 'count' => 0],
                'zihn' => ['average' => 0, 'count' => 0],
                'waslat' => ['average' => 0, 'count' => 0],
                'roaya' => ['average' => 0, 'count' => 0],
            ];
        }

        return [
            'jawhar' => [
                'average' => round($rootsData->avg('jawhar') ?? 0),
                'count' => $rootsData->where('jawhar', '>', 0)->count()
            ],
            'zihn' => [
                'average' => round($rootsData->avg('zihn') ?? 0),
                'count' => $rootsData->where('zihn', '>', 0)->count()
            ],
            'waslat' => [
                'average' => round($rootsData->avg('waslat') ?? 0),
                'count' => $rootsData->where('waslat', '>', 0)->count()
            ],
            'roaya' => [
                'average' => round($rootsData->avg('roaya') ?? 0),
                'count' => $rootsData->where('roaya', '>', 0)->count()
            ],
        ];
    }

    /**
     * Get weekly usage trends
     */
    private function getUsageTrends()
    {
        $days = [];
        $data = [];

        // Get last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->locale('ar')->isoFormat('ddd');

            $count = Result::nonDemo()
                ->whereDate('created_at', $date->toDateString())
                ->count();
            $data[] = $count;
        }

        return [
            'labels' => $days,
            'data' => $data,
            'total_week' => array_sum($data),
        ];
    }

    /**
     * Get recent platform activity
     */
    private function getRecentActivity()
    {
        $activities = collect();

        // Recent user registrations
        $recentUsers = User::latest()->take(3)->get();
        foreach ($recentUsers as $user) {
            $activities->push([
                'type' => 'user_registered',
                'icon' => 'fa-user-plus',
                'color' => 'blue',
                'title' => 'انضم ' . ($user->user_type === 'teacher' ? 'معلم' : 'طالب') . ' جديد',
                'description' => $user->name,
                'time' => $user->created_at->diffForHumans(),
                'timestamp' => $user->created_at,
            ]);
        }

        // Recent quiz creation
        $recentQuizzes = Quiz::with('user')->latest()->take(3)->get();
        foreach ($recentQuizzes as $quiz) {
            $activities->push([
                'type' => 'quiz_created',
                'icon' => 'fa-clipboard-check',
                'color' => 'green',
                'title' => 'اختبار جديد منشور',
                'description' => $quiz->title,
                'time' => $quiz->created_at->diffForHumans(),
                'timestamp' => $quiz->created_at,
            ]);
        }

        // Recent high performance
        $highScoreResults = Result::nonDemo()
            ->where('total_score', '>=', 90)
            ->latest()
            ->take(2)
            ->get();

        foreach ($highScoreResults as $result) {
            $activities->push([
                'type' => 'high_score',
                'icon' => 'fa-trophy',
                'color' => 'yellow',
                'title' => 'نتيجة متميزة',
                'description' => 'حصل على ' . $result->total_score . '%',
                'time' => $result->created_at->diffForHumans(),
                'timestamp' => $result->created_at,
            ]);
        }

        return $activities->sortByDesc('timestamp')->take(5)->values();
    }

    /**
     * Get quiz performance metrics
     */
    private function getQuizPerformance()
    {
        $results = Result::nonDemo()->get();

        if ($results->isEmpty()) {
            return [
                'success_rate' => 0,
                'average_time' => 0,
                'completion_rate' => 0,
                'average_questions' => 0,
            ];
        }

        $passedResults = $results->where('total_score', '>=', 60);
        $totalQuizzes = Quiz::withCount('results')->get();

        return [
            'success_rate' => $results->count() > 0 ? round(($passedResults->count() / $results->count()) * 100) : 0,
            'average_time' => 12, // You can calculate this if you track start/end times
            'completion_rate' => 89, // Calculate based on started vs completed
            'average_questions' => round($totalQuizzes->avg('results_count') ?? 0),
        ];
    }

    /**
     * Get system health indicators
     */
    private function getSystemHealth()
    {
        // You can expand this with real system monitoring
        return [
            'uptime' => 99.9,
            'database_status' => 'healthy',
            'ai_service_status' => 'connected',
            'last_backup' => now()->subHours(6)->diffForHumans(),
            'active_sessions' => User::where('last_login_at', '>=', now()->subMinutes(30))->count(),
        ];
    }

    /**
     * Get growth rate calculations
     */
    private function getGrowthRates()
    {
        // Users growth
        $usersThisMonth = User::whereMonth('created_at', now()->month)->count();
        $usersLastMonth = User::whereMonth('created_at', now()->subMonth()->month)->count();
        $userGrowth = $usersLastMonth > 0 ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100) : 0;

        // Quizzes growth
        $quizzesThisWeek = Quiz::where('created_at', '>=', now()->startOfWeek())->count();
        $quizzesLastWeek = Quiz::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->count();
        $quizGrowth = $quizzesLastWeek > 0 ? round((($quizzesThisWeek - $quizzesLastWeek) / $quizzesLastWeek) * 100) : 0;

        // Results growth
        $resultsThisMonth = Result::nonDemo()->whereMonth('created_at', now()->month)->count();
        $resultsLastMonth = Result::nonDemo()->whereMonth('created_at', now()->subMonth()->month)->count();
        $resultsGrowth = $resultsLastMonth > 0 ? round((($resultsThisMonth - $resultsLastMonth) / $resultsLastMonth) * 100) : 0;

        return [
            'users' => $userGrowth,
            'quizzes' => $quizGrowth,
            'results' => $resultsGrowth,
        ];
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}