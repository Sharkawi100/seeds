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
     * Get comprehensive teacher dashboard data
     */
    private function getTeacherDashboardData($user): array
    {
        // Cache teacher dashboard data for 10 minutes
        return Cache::remember("teacher_dashboard_{$user->id}", 600, function () use ($user) {
            $quizzes = Quiz::where('user_id', $user->id)->with(['results', 'questions'])->get();

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
     * Get student dashboard data
     */
    private function getStudentDashboardData($user): array
    {
        $results = Result::where('user_id', $user->id)
            ->with(['quiz'])
            ->latest()
            ->take(10)
            ->get();

        return [
            'student_stats' => $this->getStudentStats($user, $results),
            'recent_results' => $results,
            'progress_overview' => $this->getStudentProgress($results),
        ];
    }

    /**
     * Get teacher statistics
     */
    private function getTeacherStats($user, $quizzes): array
    {
        $totalQuizzes = $quizzes->count();
        $activeQuizzes = $quizzes->where('is_active', true)->count();
        $totalResults = $quizzes->sum(function ($quiz) {
            return $quiz->results->count();
        });

        // Calculate unique students (avoid double counting multiple attempts)
        $uniqueStudents = collect();
        foreach ($quizzes as $quiz) {
            $students = $quiz->results->groupBy(function ($result) {
                return $result->user_id ?: $result->guest_name;
            });
            $uniqueStudents = $uniqueStudents->merge($students->keys());
        }
        $uniqueStudentsCount = $uniqueStudents->unique()->count();

        // Calculate average final scores
        $finalScores = collect();
        foreach ($quizzes as $quiz) {
            $quizResults = $quiz->results->groupBy(function ($result) {
                return $result->user_id ?: $result->guest_name;
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
            'average_score' => $finalScores->avg() ?: 0,
            'success_rate' => $finalScores->count() > 0
                ? round($finalScores->where('>=', 60)->count() / $finalScores->count() * 100, 1)
                : 0,
            'this_week_attempts' => $this->getThisWeekAttempts($quizzes),
        ];
    }

    /**
     * Get quick action items for teachers
     */
    private function getQuickActions($user, $quizzes): array
    {
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

        // Recent high performers (for positive reinforcement)
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
    }

    /**
     * Get 4-roots analytics
     */
    private function getRootsAnalytics($quizzes): array
    {
        $rootsData = [
            'jawhar' => ['name' => 'جَوهر', 'scores' => [], 'color' => '#8B5CF6'],
            'zihn' => ['name' => 'ذِهن', 'scores' => [], 'color' => '#06B6D4'],
            'waslat' => ['name' => 'وَصلات', 'scores' => [], 'color' => '#10B981'],
            'roaya' => ['name' => 'رُؤية', 'scores' => [], 'color' => '#F59E0B'],
        ];

        foreach ($quizzes as $quiz) {
            $results = $quiz->results;
            foreach ($results as $result) {
                $scores = $result->scores ?? [];
                foreach ($rootsData as $rootType => $rootInfo) {
                    if (isset($scores[$rootType])) {
                        $rootsData[$rootType]['scores'][] = $scores[$rootType];
                    }
                }
            }
        }

        // Calculate averages and statistics
        foreach ($rootsData as $rootType => &$rootInfo) {
            $scores = collect($rootInfo['scores']);
            $rootInfo['average'] = $scores->avg() ?: 0;
            $rootInfo['count'] = $scores->count();
            $rootInfo['success_rate'] = $scores->count() > 0
                ? round($scores->where('>=', 60)->count() / $scores->count() * 100, 1)
                : 0;
        }

        return $rootsData;
    }

    /**
     * Get student insights
     */
    private function getStudentInsights($quizzes): array
    {
        $insights = [];

        foreach ($quizzes as $quiz) {
            $students = $quiz->results->groupBy(function ($result) {
                return $result->user_id ?: $result->guest_name;
            });

            foreach ($students as $studentId => $attempts) {
                $finalScore = Result::getFinalScore($quiz->id, $attempts->first()->user_id);
                $attemptCount = $attempts->count();

                $insights[] = [
                    'student_identifier' => $studentId,
                    'quiz_id' => $quiz->id,
                    'quiz_title' => $quiz->title,
                    'final_score' => $finalScore,
                    'attempt_count' => $attemptCount,
                    'needs_attention' => $finalScore < 50,
                    'multiple_attempts' => $attemptCount > 1,
                    'latest_attempt' => $attempts->sortByDesc('created_at')->first(),
                ];
            }
        }

        return collect($insights)->sortByDesc('needs_attention')->take(10)->values()->all();
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity($user): array
    {
        $recentResults = Result::whereHas('quiz', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['quiz'])
            ->latest()
            ->take(5)
            ->get();

        return $recentResults->map(function ($result) {
            return [
                'type' => 'quiz_attempt',
                'title' => "محاولة جديدة في {$result->quiz->title}",
                'description' => "الطالب: " . ($result->user ? $result->user->name : $result->guest_name),
                'score' => $result->total_score,
                'time' => $result->created_at,
                'url' => route('results.show', $result),
            ];
        })->all();
    }

    /**
     * Get smart recommendations
     */
    private function getSmartRecommendations($user, $quizzes): array
    {
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
    }

    /**
     * Get performance trends
     */
    private function getPerformanceTrends($quizzes): array
    {
        $trends = [];
        $last7Days = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayResults = collect();

            foreach ($quizzes as $quiz) {
                $dayAttempts = $quiz->results->filter(function ($result) use ($date) {
                    return $result->created_at->isSameDay($date);
                });
                $dayResults = $dayResults->merge($dayAttempts);
            }

            $trends[] = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->locale('ar')->translatedFormat('l'),
                'attempts' => $dayResults->count(),
                'average_score' => $dayResults->avg('total_score') ?: 0,
            ];
        }

        return $trends;
    }

    /**
     * Helper method to get struggling students
     */
    private function getStrugglingStudents($quizzes)
    {
        $strugglingStudents = collect();

        foreach ($quizzes as $quiz) {
            $students = $quiz->results->groupBy(function ($result) {
                return $result->user_id ?: $result->guest_name;
            });

            foreach ($students as $studentId => $attempts) {
                $latestAttempt = $attempts->sortByDesc('created_at')->first();
                if ($latestAttempt->total_score < 50) {
                    $strugglingStudents->push([
                        'student_identifier' => $studentId,
                        'latest_score' => $latestAttempt->total_score,
                        'quiz_title' => $quiz->title,
                    ]);
                }
            }
        }

        return $strugglingStudents;
    }

    /**
     * Helper method to get weak roots
     */
    private function getWeakRoots($quizzes)
    {
        $rootsPerformance = [
            'jawhar' => ['name' => 'جَوهر', 'scores' => [], 'type' => 'jawhar'],
            'zihn' => ['name' => 'ذِهن', 'scores' => [], 'type' => 'zihn'],
            'waslat' => ['name' => 'وَصلات', 'scores' => [], 'type' => 'waslat'],
            'roaya' => ['name' => 'رُؤية', 'scores' => [], 'type' => 'roaya'],
        ];

        foreach ($quizzes as $quiz) {
            foreach ($quiz->results as $result) {
                $scores = $result->scores ?? [];
                foreach ($rootsPerformance as $rootType => $rootData) {
                    if (isset($scores[$rootType])) {
                        $rootsPerformance[$rootType]['scores'][] = $scores[$rootType];
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
                    'average' => $average,
                ]);
            }
        }

        return $weakRoots->sortBy('average');
    }

    /**
     * Helper method to get top performers
     */
    private function getTopPerformers($quizzes)
    {
        $topPerformers = collect();

        foreach ($quizzes as $quiz) {
            $recentResults = $quiz->results->where('created_at', '>=', Carbon::now()->subDays(7));
            foreach ($recentResults as $result) {
                if ($result->total_score >= 85) {
                    $topPerformers->push([
                        'student_identifier' => $result->user_id ?: $result->guest_name,
                        'score' => $result->total_score,
                        'quiz_title' => $quiz->title,
                    ]);
                }
            }
        }

        return $topPerformers;
    }

    /**
     * Helper method to get this week's attempts
     */
    private function getThisWeekAttempts($quizzes): int
    {
        $weekStart = Carbon::now()->startOfWeek();
        $count = 0;

        foreach ($quizzes as $quiz) {
            $count += $quiz->results->where('created_at', '>=', $weekStart)->count();
        }

        return $count;
    }

    /**
     * Get student statistics
     */
    private function getStudentStats($user, $results): array
    {
        return [
            'total_attempts' => $results->count(),
            'average_score' => $results->avg('total_score') ?: 0,
            'best_score' => $results->max('total_score') ?: 0,
            'recent_attempts' => $results->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
        ];
    }

    /**
     * Get student progress
     */
    private function getStudentProgress($results): array
    {
        $progress = [];

        foreach ($results->take(5) as $result) {
            $progress[] = [
                'quiz_title' => $result->quiz->title,
                'score' => $result->total_score,
                'date' => $result->created_at,
                'roots_scores' => $result->scores ?? [],
            ];
        }

        return $progress;
    }

    /**
     * Switch admin to teacher perspective.
     */
    public function switchToTeacher(Request $request): RedirectResponse
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        session(['viewing_as' => 'teacher']);

        return redirect()->route('dashboard')->with('success', 'تم التبديل إلى منظور المعلم');
    }

    /**
     * Switch back to admin perspective.
     */
    public function switchToAdmin(Request $request): RedirectResponse
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        session()->forget('viewing_as');

        return redirect()->route('admin.dashboard')->with('success', 'تم العودة إلى منظور الإدارة');
    }

    /**
     * Display the user's profile dashboard.
     */
    public function profileDashboard(Request $request): RedirectResponse
    {
        return redirect()->route('profile.edit');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
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

    /**
     * Update user avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Show profile completion status.
     */
    public function completion(Request $request): View
    {
        $user = Auth::user();
        $completionPercentage = $this->calculateProfileCompletion($user);

        return view('profile.completion', [
            'user' => $user,
            'completion_percentage' => $completionPercentage,
        ]);
    }

    /**
     * Show active sessions.
     */
    public function sessions(Request $request): View
    {
        return view('profile.sessions', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Logout other devices.
     */
    public function logoutOtherDevices(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($request->password);

        return Redirect::route('profile.sessions')->with('status', 'other-devices-logged-out');
    }

    /**
     * Update user preferences.
     */
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

        return Redirect::route('profile.edit')->with('status', 'preferences-updated');
    }

    /**
     * Update privacy settings.
     */
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

        return Redirect::route('profile.edit')->with('status', 'privacy-updated');
    }

    /**
     * Calculate profile completion percentage.
     */
    private function calculateProfileCompletion($user): int
    {
        $fields = [
            'name' => !empty($user->name),
            'email' => !empty($user->email),
            'avatar' => !empty($user->avatar),
            'bio' => !empty($user->bio),
            'school' => !empty($user->school),
            'grade_level' => !empty($user->grade_level),
        ];

        $completed = array_sum($fields);
        $total = count($fields);

        return round(($completed / $total) * 100);
    }
}