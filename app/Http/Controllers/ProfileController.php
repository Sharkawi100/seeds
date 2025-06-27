<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the main dashboard based on user role and perspective.
     */
    public function dashboard(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        // Check if admin is viewing as teacher
        if ($user && $user->is_admin && session('viewing_as') === 'teacher') {
            $teacherData = $this->getTeacherDashboardData($user);
            return view('dashboard', array_merge($teacherData, [
                'user' => $user,
                'viewing_as' => 'teacher'
            ]));
        }

        // Redirect admins to admin dashboard (default behavior)
        if ($user && $user->is_admin && !session('viewing_as')) {
            return redirect()->route('admin.dashboard');
        }

        // Enhanced dashboard for teachers
        if ($user && $user->user_type === 'teacher') {
            $teacherData = $this->getTeacherDashboardData($user);
            return view('dashboard', array_merge($teacherData, [
                'user' => $user,
            ]));
        }

        // Student dashboard with their specific data
        if ($user && $user->user_type === 'student') {
            $studentData = $this->getStudentDashboardData($user);
            return view('dashboard', array_merge($studentData, [
                'user' => $user,
            ]));
        }

        // Fallback for other roles
        return view('dashboard', [
            'user' => $user,
        ]);
    }

    /**
     * Display the user's enhanced profile dashboard.
     */
    public function profileDashboard(): View
    {
        $user = Auth::user();

        // Cache profile dashboard data for 5 minutes
        $cacheKey = "profile_dashboard_{$user->id}_{$user->updated_at->timestamp}";

        $dashboardData = Cache::remember($cacheKey, 300, function () use ($user) {
            return [
                'profile_stats' => $this->getProfileStats($user),
                'subscription_data' => $this->getSubscriptionData($user),
                'recent_activity' => $this->getRecentActivityForProfile($user),
                'achievements' => $this->getUserAchievements($user),
                'quick_actions' => $this->getProfileQuickActions($user),
            ];
        });

        return view('profile.index', array_merge($dashboardData, ['user' => $user]));
    }

    /**
     * Get comprehensive teacher dashboard data with optimized queries.
     */
    private function getTeacherDashboardData($user): array
    {
        // Cache teacher dashboard data for 10 minutes
        return Cache::remember("teacher_dashboard_{$user->id}", 600, function () use ($user) {
            // Single optimized query to get all needed data
            $quizzes = Quiz::where('user_id', $user->id)
                ->with([
                    'results' => function ($query) {
                        $query->with('user:id,name');
                    },
                    'questions:id,quiz_id'
                ])
                ->get();

            return [
                'teacher_stats' => $this->getTeacherStats($user, $quizzes),
                'quick_actions' => $this->getQuickActions($user, $quizzes),
                'roots_analytics' => $this->getRootsAnalytics($quizzes),
                'student_insights' => $this->getStudentInsights($quizzes),
                'recent_activity' => $this->getRecentActivity($user),
                'recommendations' => $this->getSmartRecommendations($user, $quizzes),
                'performance_trends' => $this->getPerformanceTrends($quizzes),
            ];
        });
    }

    /**
     * Get student dashboard data with optimized queries.
     */
    private function getStudentDashboardData($user): array
    {
        // Cache student data for 5 minutes
        return Cache::remember("student_dashboard_{$user->id}", 300, function () use ($user) {
            $results = Result::where('user_id', $user->id)
                ->whereHas('quiz') // Only get results with existing quizzes
                ->with(['quiz:id,title,user_id'])
                ->latest()
                ->take(10)
                ->get();

            return [
                'student_stats' => $this->getStudentStats($user, $results),
                'recent_results' => $results,
                'progress_overview' => $this->getStudentProgress($results),
            ];
        });
    }

    /**
     * Get profile-specific statistics.
     */
    private function getProfileStats($user): array
    {
        $stats = [];

        if ($user->user_type !== 'student') {
            // Teacher/Admin stats
            $stats['total_quizzes'] = $user->quizzes()->count();
            $stats['total_student_attempts'] = Result::whereHas('quiz', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
            $stats['average_quiz_score'] = Result::whereHas('quiz', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->avg('total_score') ?? 0;
            $stats['this_week_quizzes'] = $user->quizzes()
                ->where('created_at', '>=', now()->startOfWeek())
                ->count();
        } else {
            // Student stats
            $stats['total_attempts'] = $user->results()->count();
            $stats['average_score'] = $user->results()->avg('total_score') ?? 0;
            $stats['best_score'] = $user->results()->max('total_score') ?? 0;
            $stats['recent_attempts'] = $user->results()
                ->where('created_at', '>=', now()->subDays(7))
                ->count();
        }

        return $stats;
    }

    /**
     * Get subscription data for profile dashboard.
     */
    private function getSubscriptionData($user): array
    {
        if ($user->is_admin) {
            return [
                'is_admin' => true,
                'subscription_active' => true,
                'plan_name' => 'مدير النظام',
                'unlimited_access' => true,
            ];
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get monthly usage
        $monthlyQuizUsage = $user->quizzes()
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return [
            'is_admin' => false,
            'subscription_active' => $user->subscription_active ?? false,
            'subscription_plan' => $user->subscription_plan,
            'subscription_expires_at' => $user->subscription_expires_at,
            'monthly_quiz_usage' => $monthlyQuizUsage,
            'monthly_quiz_limit' => $user->subscription_active ? 40 : 5,
            'can_use_ai' => $user->subscription_active ?? false,
        ];
    }

    /**
     * Get recent activity for profile dashboard.
     */
    private function getRecentActivityForProfile($user): array
    {
        if ($user->user_type !== 'student') {
            // For teachers: show recent quizzes they created
            return $user->quizzes()
                ->with('questions:id,quiz_id')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($quiz) {
                    return [
                        'type' => 'quiz_created',
                        'title' => $quiz->title,
                        'questions_count' => $quiz->questions->count(),
                        'created_at' => $quiz->created_at,
                        'url' => route('quizzes.show', $quiz),
                    ];
                })
                ->toArray();
        } else {
            // For students: show recent quiz attempts
            return $user->results()
                ->whereHas('quiz') // Only results with existing quizzes
                ->with(['quiz:id,title'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($result) {
                    return [
                        'type' => 'quiz_attempt',
                        'quiz_title' => $result->quiz->title,
                        'score' => $result->total_score,
                        'created_at' => $result->created_at,
                        'url' => route('results.show', $result),
                    ];
                })
                ->toArray();
        }
    }

    /**
     * Get user achievements.
     */
    private function getUserAchievements($user): array
    {
        $achievements = [];
        $stats = $this->getProfileStats($user);

        if ($user->user_type !== 'student') {
            // Teacher achievements
            if ($stats['total_quizzes'] >= 1) {
                $achievements[] = ['name' => 'أول اختبار', 'icon' => 'fas fa-star', 'color' => 'blue'];
            }
            if ($stats['total_quizzes'] >= 5) {
                $achievements[] = ['name' => 'منشئ نشيط', 'icon' => 'fas fa-medal', 'color' => 'green'];
            }
            if ($stats['total_quizzes'] >= 10) {
                $achievements[] = ['name' => 'خبير الاختبارات', 'icon' => 'fas fa-crown', 'color' => 'purple'];
            }
        } else {
            // Student achievements
            if ($stats['total_attempts'] >= 1) {
                $achievements[] = ['name' => 'أول اختبار', 'icon' => 'fas fa-star', 'color' => 'green'];
            }
            if ($stats['average_score'] >= 80) {
                $achievements[] = ['name' => 'متفوق', 'icon' => 'fas fa-medal', 'color' => 'yellow'];
            }
        }

        // Common achievements
        if ($user->subscription_active) {
            $achievements[] = ['name' => 'عضو مميز', 'icon' => 'fas fa-gem', 'color' => 'indigo'];
        }

        $completion = $this->calculateProfileCompletion($user);
        if ($completion >= 100) {
            $achievements[] = ['name' => 'ملف مكتمل', 'icon' => 'fas fa-certificate', 'color' => 'pink'];
        }

        return $achievements;
    }

    /**
     * Get quick actions for profile dashboard.
     */
    private function getProfileQuickActions($user): array
    {
        $actions = [];

        if ($user->user_type !== 'student') {
            $actions[] = [
                'name' => 'إنشاء اختبار',
                'icon' => 'fas fa-plus',
                'url' => route('quizzes.create'),
                'color' => 'blue',
            ];
            $actions[] = [
                'name' => 'اختباراتي',
                'icon' => 'fas fa-list',
                'url' => route('quizzes.index'),
                'color' => 'purple',
            ];
        }

        $actions[] = [
            'name' => 'عرض النتائج',
            'icon' => 'fas fa-chart-line',
            'url' => route('results.index'),
            'color' => 'green',
        ];

        $actions[] = [
            'name' => 'تعديل الملف',
            'icon' => 'fas fa-user-edit',
            'url' => route('profile.edit'),
            'color' => 'gray',
        ];

        return $actions;
    }

    /**
     * Get teacher statistics with error handling.
     */
    private function getTeacherStats($user, $quizzes): array
    {
        try {
            $totalQuizzes = $quizzes->count();
            $activeQuizzes = $quizzes->where('is_active', true)->count();
            $totalResults = $quizzes->sum(function ($quiz) {
                return $quiz->results->count();
            });

            // Calculate unique students safely
            $uniqueStudents = collect();
            foreach ($quizzes as $quiz) {
                $students = $quiz->results->groupBy(function ($result) {
                    return $result->user_id ?: ($result->guest_name ?? 'anonymous');
                });
                $uniqueStudents = $uniqueStudents->merge($students->keys());
            }
            $uniqueStudentsCount = $uniqueStudents->unique()->count();

            // Calculate average final scores safely
            $finalScores = collect();
            foreach ($quizzes as $quiz) {
                $quizResults = $quiz->results->groupBy(function ($result) {
                    return $result->user_id ?: ($result->guest_name ?? 'anonymous');
                });

                foreach ($quizResults as $studentResults) {
                    $finalScore = Result::getFinalScore($quiz->id, $studentResults->first()->user_id);
                    if ($finalScore !== null) {
                        $finalScores->push($finalScore);
                    }
                }
            }

            return [
                'total_quizzes' => $totalQuizzes,
                'active_quizzes' => $activeQuizzes,
                'inactive_quizzes' => $totalQuizzes - $activeQuizzes,
                'total_attempts' => $totalResults,
                'unique_students' => $uniqueStudentsCount,
                'average_score' => round($finalScores->avg() ?: 0, 1),
                'success_rate' => $finalScores->count() > 0
                    ? round($finalScores->where('>=', 60)->count() / $finalScores->count() * 100, 1)
                    : 0,
                'this_week_attempts' => $this->getThisWeekAttempts($quizzes),
            ];
        } catch (\Exception $e) {
            // Return safe defaults if there's an error
            return [
                'total_quizzes' => 0,
                'active_quizzes' => 0,
                'inactive_quizzes' => 0,
                'total_attempts' => 0,
                'unique_students' => 0,
                'average_score' => 0,
                'success_rate' => 0,
                'this_week_attempts' => 0,
            ];
        }
    }

    /**
     * Get quick action items for teachers with error handling.
     */
    private function getQuickActions($user, $quizzes): array
    {
        try {
            $actions = [];

            // Students needing attention
            $strugglingStudents = $this->getStrugglingStudents($quizzes);
            if ($strugglingStudents->count() > 0) {
                $actions[] = [
                    'type' => 'attention',
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => "{$strugglingStudents->count()} طلاب يحتاجون دعم",
                    'description' => 'طلاب حصلوا على درجات منخفضة في آخر محاولة',
                    'action_text' => 'مراجعة الآن',
                    'action_url' => route('results.index', ['filter' => 'struggling']),
                    'priority' => 'high',
                    'color' => 'red'
                ];
            }

            // Root-specific insights
            $weakRoots = $this->getWeakRoots($quizzes);
            if ($weakRoots->count() > 0) {
                $rootName = $weakRoots->first()['root_name'];
                $actions[] = [
                    'type' => 'insight',
                    'icon' => 'fas fa-lightbulb',
                    'title' => "تحسين أداء {$rootName}",
                    'description' => 'أداء الصف ضعيف في هذا الجذر',
                    'action_text' => 'إنشاء تمارين',
                    'action_url' => route('quizzes.create', ['focus_root' => $weakRoots->first()['root_type']]),
                    'priority' => 'medium',
                    'color' => 'yellow'
                ];
            }

            // Inactive quizzes
            $inactiveQuizzes = $quizzes->where('is_active', false)->count();
            if ($inactiveQuizzes > 0) {
                $actions[] = [
                    'type' => 'activation',
                    'icon' => 'fas fa-play-circle',
                    'title' => "{$inactiveQuizzes} اختبارات غير مفعلة",
                    'description' => 'قم بتفعيل الاختبارات لإتاحتها للطلاب',
                    'action_text' => 'تفعيل الآن',
                    'action_url' => route('quizzes.index', ['filter' => 'inactive']),
                    'priority' => 'low',
                    'color' => 'blue'
                ];
            }

            // Recent high performers
            $topPerformers = $this->getTopPerformers($quizzes);
            if ($topPerformers->count() > 0) {
                $actions[] = [
                    'type' => 'celebration',
                    'icon' => 'fas fa-star',
                    'title' => "{$topPerformers->count()} طلاب متميزون",
                    'description' => 'طلاب حققوا نتائج ممتازة مؤخراً',
                    'action_text' => 'إرسال تهنئة',
                    'action_url' => '#',
                    'priority' => 'low',
                    'color' => 'green'
                ];
            }

            return $actions;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get 4-roots analytics with null safety.
     */
    private function getRootsAnalytics($quizzes): array
    {
        try {
            $rootsData = [
                'jawhar' => ['name' => 'جَوهر', 'scores' => [], 'color' => '#8B5CF6'],
                'zihn' => ['name' => 'ذِهن', 'scores' => [], 'color' => '#06B6D4'],
                'waslat' => ['name' => 'وَصلات', 'scores' => [], 'color' => '#10B981'],
                'roaya' => ['name' => 'رُؤية', 'scores' => [], 'color' => '#F59E0B'],
            ];

            foreach ($quizzes as $quiz) {
                if ($quiz->results) {
                    foreach ($quiz->results as $result) {
                        $scores = $result->scores ?? [];
                        foreach ($rootsData as $rootType => $rootInfo) {
                            if (isset($scores[$rootType]) && is_numeric($scores[$rootType])) {
                                $rootsData[$rootType]['scores'][] = $scores[$rootType];
                            }
                        }
                    }
                }
            }

            // Calculate averages and statistics
            foreach ($rootsData as $rootType => &$rootInfo) {
                $scores = collect($rootInfo['scores']);
                $rootInfo['average'] = round($scores->avg() ?: 0, 1);
                $rootInfo['count'] = $scores->count();
                $rootInfo['success_rate'] = $scores->count() > 0
                    ? round($scores->where('>=', 60)->count() / $scores->count() * 100, 1)
                    : 0;
            }

            return $rootsData;
        } catch (\Exception $e) {
            // Return safe empty structure
            return [
                'jawhar' => ['name' => 'جَوهر', 'average' => 0, 'count' => 0, 'success_rate' => 0, 'color' => '#8B5CF6'],
                'zihn' => ['name' => 'ذِهن', 'average' => 0, 'count' => 0, 'success_rate' => 0, 'color' => '#06B6D4'],
                'waslat' => ['name' => 'وَصلات', 'average' => 0, 'count' => 0, 'success_rate' => 0, 'color' => '#10B981'],
                'roaya' => ['name' => 'رُؤية', 'average' => 0, 'count' => 0, 'success_rate' => 0, 'color' => '#F59E0B'],
            ];
        }
    }

    /**
     * Get student insights with proper null handling.
     */
    private function getStudentInsights($quizzes): array
    {
        try {
            $insights = [];

            foreach ($quizzes as $quiz) {
                if ($quiz->results && $quiz->results->count() > 0) {
                    $students = $quiz->results->groupBy(function ($result) {
                        return $result->user_id ?: ($result->guest_name ?? 'anonymous_' . $result->id);
                    });

                    foreach ($students as $studentId => $attempts) {
                        $firstAttempt = $attempts->first();
                        if ($firstAttempt) {
                            $finalScore = Result::getFinalScore($quiz->id, $firstAttempt->user_id);
                            $attemptCount = $attempts->count();

                            $insights[] = [
                                'student_identifier' => $studentId,
                                'quiz_id' => $quiz->id,
                                'quiz_title' => $quiz->title,
                                'final_score' => $finalScore ?? 0,
                                'attempt_count' => $attemptCount,
                                'needs_attention' => ($finalScore ?? 0) < 50,
                                'multiple_attempts' => $attemptCount > 1,
                                'latest_attempt' => $attempts->sortByDesc('created_at')->first(),
                            ];
                        }
                    }
                }
            }

            return collect($insights)->sortByDesc('needs_attention')->take(10)->values()->all();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get recent activity with null safety.
     */
    private function getRecentActivity($user): array
    {
        try {
            $recentResults = Result::whereHas('quiz', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->with(['quiz:id,title', 'user:id,name'])
                ->latest()
                ->take(5)
                ->get();

            return $recentResults->map(function ($result) {
                return [
                    'type' => 'quiz_attempt',
                    'title' => "محاولة جديدة في " . ($result->quiz?->title ?? 'اختبار محذوف'),
                    'description' => "الطالب: " . ($result->user?->name ?? $result->guest_name ?? 'ضيف'),
                    'score' => $result->total_score ?? 0,
                    'time' => $result->created_at,
                    'url' => route('results.show', $result),
                ];
            })->all();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get smart recommendations with error handling.
     */
    private function getSmartRecommendations($user, $quizzes): array
    {
        try {
            $recommendations = [];

            // Recommend creating quizzes for weak areas
            $weakRoots = $this->getWeakRoots($quizzes);
            if ($weakRoots->count() > 0) {
                $rootName = $weakRoots->first()['root_name'];
                $recommendations[] = [
                    'type' => 'create_quiz',
                    'title' => "إنشاء تمارين إضافية للجذر {$rootName}",
                    'description' => 'نلاحظ ضعف في هذا الجذر لدى الطلاب',
                    'action' => 'إنشاء اختبار',
                    'url' => route('quizzes.create'),
                ];
            }

            // Recommend activating quizzes if none are active
            if ($quizzes->where('is_active', true)->count() === 0 && $quizzes->count() > 0) {
                $recommendations[] = [
                    'type' => 'activate_quiz',
                    'title' => 'تفعيل الاختبارات',
                    'description' => 'لديك اختبارات غير مفعلة يمكن إتاحتها للطلاب',
                    'action' => 'تفعيل الآن',
                    'url' => route('quizzes.index'),
                ];
            }

            // Recommend first quiz creation if no quizzes exist
            if ($quizzes->count() === 0) {
                $recommendations[] = [
                    'type' => 'first_quiz',
                    'title' => 'إنشاء أول اختبار',
                    'description' => 'ابدأ رحلتك التعليمية بإنشاء اختبارك الأول',
                    'action' => 'إنشاء اختبار',
                    'url' => route('quizzes.create'),
                ];
            }

            return $recommendations;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get performance trends with error handling.
     */
    private function getPerformanceTrends($quizzes): array
    {
        try {
            $trends = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dayResults = collect();

                foreach ($quizzes as $quiz) {
                    if ($quiz->results) {
                        $dayAttempts = $quiz->results->filter(function ($result) use ($date) {
                            return $result->created_at && $result->created_at->isSameDay($date);
                        });
                        $dayResults = $dayResults->merge($dayAttempts);
                    }
                }

                $trends[] = [
                    'date' => $date->format('Y-m-d'),
                    'day_name' => $date->locale('ar')->translatedFormat('l'),
                    'attempts' => $dayResults->count(),
                    'average_score' => round($dayResults->avg('total_score') ?: 0, 1),
                ];
            }

            return $trends;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Helper methods with improved null safety
     */
    private function getStrugglingStudents($quizzes)
    {
        try {
            $strugglingStudents = collect();

            foreach ($quizzes as $quiz) {
                if ($quiz->results && $quiz->results->count() > 0) {
                    $students = $quiz->results->groupBy(function ($result) {
                        return $result->user_id ?: ($result->guest_name ?? 'anonymous_' . $result->id);
                    });

                    foreach ($students as $studentId => $attempts) {
                        $latestAttempt = $attempts->sortByDesc('created_at')->first();
                        if ($latestAttempt && ($latestAttempt->total_score ?? 0) < 50) {
                            $strugglingStudents->push([
                                'student_identifier' => $studentId,
                                'latest_score' => $latestAttempt->total_score ?? 0,
                                'quiz_title' => $quiz->title,
                            ]);
                        }
                    }
                }
            }

            return $strugglingStudents;
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getWeakRoots($quizzes)
    {
        try {
            $rootsPerformance = [
                'jawhar' => ['name' => 'جَوهر', 'scores' => [], 'type' => 'jawhar'],
                'zihn' => ['name' => 'ذِهن', 'scores' => [], 'type' => 'zihn'],
                'waslat' => ['name' => 'وَصلات', 'scores' => [], 'type' => 'waslat'],
                'roaya' => ['name' => 'رُؤية', 'scores' => [], 'type' => 'roaya'],
            ];

            foreach ($quizzes as $quiz) {
                if ($quiz->results) {
                    foreach ($quiz->results as $result) {
                        $scores = $result->scores ?? [];
                        foreach ($rootsPerformance as $rootType => $rootData) {
                            if (isset($scores[$rootType]) && is_numeric($scores[$rootType])) {
                                $rootsPerformance[$rootType]['scores'][] = $scores[$rootType];
                            }
                        }
                    }
                }
            }

            $weakRoots = collect();
            foreach ($rootsPerformance as $rootType => $rootData) {
                $average = collect($rootData['scores'])->avg();
                if ($average && $average < 60) {
                    $weakRoots->push([
                        'root_type' => $rootType,
                        'root_name' => $rootData['name'],
                        'average' => round($average, 1),
                    ]);
                }
            }

            return $weakRoots->sortBy('average');
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getTopPerformers($quizzes)
    {
        try {
            $topPerformers = collect();

            foreach ($quizzes as $quiz) {
                if ($quiz->results) {
                    $recentResults = $quiz->results->where('created_at', '>=', Carbon::now()->subDays(7));
                    foreach ($recentResults as $result) {
                        if (($result->total_score ?? 0) >= 85) {
                            $topPerformers->push([
                                'student_identifier' => $result->user_id ?: ($result->guest_name ?? 'ضيف'),
                                'score' => $result->total_score,
                                'quiz_title' => $quiz->title,
                            ]);
                        }
                    }
                }
            }

            return $topPerformers;
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getThisWeekAttempts($quizzes): int
    {
        try {
            $weekStart = Carbon::now()->startOfWeek();
            $count = 0;

            foreach ($quizzes as $quiz) {
                if ($quiz->results) {
                    $count += $quiz->results->where('created_at', '>=', $weekStart)->count();
                }
            }

            return $count;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get student statistics with null safety.
     */
    private function getStudentStats($user, $results): array
    {
        try {
            return [
                'total_attempts' => $results->count(),
                'average_score' => round($results->avg('total_score') ?: 0, 1),
                'best_score' => $results->max('total_score') ?: 0,
                'recent_attempts' => $results->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            ];
        } catch (\Exception $e) {
            return [
                'total_attempts' => 0,
                'average_score' => 0,
                'best_score' => 0,
                'recent_attempts' => 0,
            ];
        }
    }

    /**
     * Get student progress with comprehensive null safety.
     */
    private function getStudentProgress($results): array
    {
        try {
            $progress = [];

            // Filter out results with null quizzes and take only 5
            $validResults = $results->filter(function ($result) {
                return $result && $result->quiz !== null && $result->quiz->title !== null;
            })->take(5);

            foreach ($validResults as $result) {
                $progress[] = [
                    'quiz_title' => $result->quiz->title,
                    'score' => $result->total_score ?? 0,
                    'date' => $result->created_at,
                    'roots_scores' => $result->scores ?? [],
                ];
            }

            return $progress;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Profile management methods
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Clear profile cache when updated
        Cache::forget("profile_dashboard_{$user->id}_{$user->updated_at->timestamp}");

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['nullable', 'image', 'max:2048'],
            'remove_avatar' => ['sometimes', 'boolean'],
        ]);

        $user = Auth::user();

        // Handle avatar removal
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }
            $user->avatar = null;
            $user->save();

            Cache::forget("profile_dashboard_{$user->id}_{$user->updated_at->timestamp}");
            return Redirect::route('profile.edit')->with('status', 'avatar-updated');
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Remove old avatar if exists
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();

            Cache::forget("profile_dashboard_{$user->id}_{$user->updated_at->timestamp}");
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    public function completion(Request $request): View
    {
        $user = Auth::user();
        $completionPercentage = $this->calculateProfileCompletion($user);

        return view('profile.completion', [
            'user' => $user,
            'completion_percentage' => $completionPercentage,
        ]);
    }

    public function sessions(Request $request): View
    {
        return view('profile.sessions', [
            'user' => Auth::user(),
        ]);
    }

    public function logoutOtherDevices(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($request->password);

        return Redirect::route('profile.sessions')->with('status', 'other-devices-logged-out');
    }

    public function updatePreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language' => ['nullable', 'string', 'in:ar,en,he'],
            'notifications_enabled' => ['boolean'],
            'email_notifications' => ['boolean'],
            'theme' => ['nullable', 'string', 'in:light,dark,auto'],
        ]);

        $user = Auth::user();
        $preferences = $user->preferences ?? [];
        $preferences = array_merge($preferences, $validated);
        $user->preferences = $preferences;
        $user->save();

        Cache::forget("profile_dashboard_{$user->id}_{$user->updated_at->timestamp}");

        return Redirect::route('profile.edit')->with('status', 'preferences-updated');
    }

    public function updatePrivacy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_visibility' => ['required', 'string', 'in:public,private,teachers_only'],
            'show_email' => ['boolean'],
            'show_last_active' => ['boolean'],
            'allow_messages' => ['boolean'],
        ]);

        $user = Auth::user();
        $privacy = $user->privacy_settings ?? [];
        $privacy = array_merge($privacy, $validated);
        $user->privacy_settings = $privacy;
        $user->save();

        Cache::forget("profile_dashboard_{$user->id}_{$user->updated_at->timestamp}");

        return Redirect::route('profile.edit')->with('status', 'privacy-updated');
    }

    /**
     * Admin perspective switching
     */
    public function switchToTeacher(Request $request): RedirectResponse
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        session(['viewing_as' => 'teacher']);
        return redirect()->route('dashboard')->with('success', 'تم التبديل إلى منظور المعلم');
    }

    public function switchToAdmin(Request $request): RedirectResponse
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        session()->forget('viewing_as');
        return redirect()->route('admin.dashboard')->with('success', 'تم العودة إلى منظور الإدارة');
    }

    /**
     * Calculate profile completion percentage.
     */
    private function calculateProfileCompletion($user): int
    {
        try {
            $fields = [
                'name' => !empty($user->name),
                'email' => !empty($user->email),
                'avatar' => !empty($user->avatar),
                'bio' => !empty($user->bio),
                'phone' => !empty($user->phone),
            ];

            // Add user-type specific fields
            if ($user->user_type === 'teacher') {
                $fields['school_name'] = !empty($user->school_name);
                $fields['subjects_taught'] = !empty($user->subjects_taught);
                $fields['experience_years'] = !empty($user->experience_years);
            } elseif ($user->user_type === 'student') {
                $fields['grade_level'] = !empty($user->grade_level);
                $fields['favorite_subject'] = !empty($user->favorite_subject);
            }

            $completed = array_sum($fields);
            $total = count($fields);

            return $total > 0 ? round(($completed / $total) * 100) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}