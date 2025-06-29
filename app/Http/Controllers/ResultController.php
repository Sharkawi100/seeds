<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Result;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\MonthlyQuota;
use App\Models\QuizAiReport;
use App\Services\ClaudeService;

class ResultController extends Controller
{
    protected $claudeService;

    public function __construct(ClaudeService $claudeService)
    {
        $this->claudeService = $claudeService;
    }

    /**
     * Display recent quiz activity dashboard for teachers
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $user = Auth::user();

            if ($this->isTeacherOrAdmin($user)) {
                return $this->getTeacherDashboard($user);
            } else {
                return $this->getStudentResults($user);
            }

        } catch (\Exception $e) {
            return $this->handleIndexError($e);
        }
    }

    /**
     * Check if user is teacher or admin
     */
    private function isTeacherOrAdmin($user): bool
    {
        return $user->user_type === 'teacher' || $user->is_admin;
    }

    /**
     * Get teacher dashboard with recent activity
     */
    private function getTeacherDashboard($user)
    {
        // Get recent results for teacher's quizzes
        $allResults = Result::whereHas('quiz', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with([
                'quiz:id,title,subject_id,grade_level,pin,created_at',
                'quiz.subject:id,name',
                'user:id,name'
            ])
            ->latest('created_at')
            ->limit(100)
            ->get();

        // Group by quiz and get the latest activity for each quiz
        $uniqueResults = $allResults->groupBy('quiz_id')
            ->map(function ($quizResults) {
                // Return the most recent result for this quiz
                return $quizResults->first();
            })
            ->sortByDesc('created_at')
            ->take(15);

        $results = $uniqueResults->values();

        // Update recent_activity count in stats
        $dashboardStats = $this->getTeacherStats($user);
        $dashboardStats['recent_activity'] = $results->count();

        return view('results.index', compact('results', 'dashboardStats'));
    }

    /**
     * Get teacher dashboard statistics
     */
    private function getTeacherStats($user): array
    {
        $totalQuizzes = Quiz::where('user_id', $user->id)->count();
        $activeQuizzes = Quiz::where('user_id', $user->id)->where('is_active', true)->count();
        $totalResults = Result::whereHas('quiz', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        $uniqueStudents = Result::whereHas('quiz', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->select(DB::raw('COALESCE(user_id, guest_name) as student_identifier'))
            ->distinct()
            ->count();

        $todayResults = Result::whereHas('quiz', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('created_at', today())
            ->count();

        return [
            'total_quizzes' => $totalQuizzes,
            'active_quizzes' => $activeQuizzes,
            'total_results' => $totalResults,
            'unique_students' => $uniqueStudents,
            'today_results' => $todayResults,
            'recent_activity' => 15 // Will be updated with actual count
        ];
    }

    /**
     * Get student results with pagination
     */
    private function getStudentResults($user)
    {
        $results = Result::where('user_id', $user->id)
            ->whereHas('quiz')
            ->with([
                'quiz:id,title,subject_id,grade_level,pin,created_at',
                'quiz.subject:id,name'
            ])
            ->latest('created_at')
            ->paginate(20);

        $studentStats = $this->getStudentStats($user);

        return view('results.index', compact('results', 'studentStats'));
    }

    /**
     * Get student statistics
     */
    private function getStudentStats($user): array
    {
        return [
            'total_attempts' => Result::where('user_id', $user->id)->whereHas('quiz')->count(),
            'quizzes_taken' => Result::where('user_id', $user->id)->whereHas('quiz')->distinct('quiz_id')->count(),
            'average_score' => round(Result::where('user_id', $user->id)->whereHas('quiz')->avg('total_score') ?? 0),
            'best_score' => Result::where('user_id', $user->id)->whereHas('quiz')->max('total_score') ?? 0
        ];
    }

    /**
     * Handle errors in index method
     */
    private function handleIndexError(\Exception $e)
    {
        Log::error('Failed to load user results', [
            'user_id' => Auth::id(),
            'user_type' => Auth::user()->user_type ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        $emptyData = $this->isTeacherOrAdmin(Auth::user())
            ? ['results' => collect(), 'dashboardStats' => []]
            : ['results' => collect(), 'studentStats' => []];

        return view('results.index', $emptyData);
    }

    /**
     * Display a specific result for authenticated user
     */
    public function show(Result $result)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Authorization check: Allow if user is:
        // 1. The quiz taker (for students viewing their own results)
        // 2. The quiz creator (for teachers viewing student results)  
        // 3. An admin (can view all results)
        $canView = false;

        if ($user->is_admin) {
            // Admin can view all results
            $canView = true;
        } elseif ($result->user_id && (int) $result->user_id === $user->id) {
            // User can view their own results
            $canView = true;
        } elseif ($result->quiz && (int) $result->quiz->user_id === $user->id) {
            // Teacher can view results for their own quizzes
            $canView = true;
        }

        if (!$canView) {
            abort(403, 'غير مصرح لك بعرض هذه النتيجة');
        }

        // Load necessary relationships
        $result->load([
            'quiz:id,title,subject_id,grade_level,max_attempts,scoring_method',
            'quiz.subject:id,name',
            'answers.question:id,question,root_type,depth_level,correct_answer,options'
        ]);

        return view('results.show', compact('result'));
    }

    /**
     * Display a specific result for guest using token
     */
    public function guestShow($token)
    {
        try {
            // Find result by guest token
            $result = Result::where('guest_token', $token)
                ->with([
                    'quiz:id,title,subject_id,grade_level,pin,created_at',
                    'quiz.subject:id,name'
                ])
                ->first();

            if (!$result) {
                return view('quiz.error', [
                    'title' => 'نتيجة غير موجودة',
                    'message' => 'لم يتم العثور على النتيجة المطلوبة',
                    'description' => 'ربما انتهت صلاحية الرابط أو تم حذف النتيجة. يرجى التواصل مع معلمك للحصول على مزيد من المعلومات.',
                    'back_url' => route('home')
                ]);
            }

            // Check if token is expired (7 days)
            if ($result->created_at->addDays(7)->isPast()) {
                return view('quiz.error', [
                    'title' => 'انتهت صلاحية الرابط',
                    'message' => 'انتهت صلاحية رابط النتيجة',
                    'description' => 'روابط النتائج تنتهي صلاحيتها بعد 7 أيام لأغراض الأمان. يرجى التواصل مع معلمك للحصول على النتيجة.',
                    'back_url' => route('home')
                ]);
            }

            return view('results.guest-show', compact('result'));

        } catch (\Exception $e) {
            Log::error('Failed to load guest result', [
                'token' => $token,
                'error' => $e->getMessage()
            ]);

            return view('quiz.error', [
                'title' => 'خطأ في النظام',
                'message' => 'حدث خطأ أثناء تحميل النتيجة',
                'description' => 'نعتذر عن هذا الخطأ. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.',
                'back_url' => route('home')
            ]);
        }
    }

    /**
     * Show AI pedagogical report page for a quiz
     */
    public function showAiReport($quizId)
    {
        $this->authorizeQuizAccess($quizId);

        $quiz = Quiz::with(['questions', 'subject'])->findOrFail($quizId);
        $results = Result::where('quiz_id', $quizId)->get();

        if ($results->count() < 3) {
            return redirect()->route('results.quiz', $quiz->id)
                ->with('error', 'يحتاج الاختبار على الأقل 3 نتائج لإنشاء التقرير التربوي');
        }

        // Calculate basic statistics
        $statistics = $this->calculateQuizStatistics($results);
        $rootsPerformance = $this->calculateRootsPerformance($results, $quiz);

        // Get quota information
        $quota = MonthlyQuota::getOrCreateCurrent(Auth::id());
        $remainingQuota = $quota->getRemainingQuota();

        // Get report navigation data
        $reportData = $this->getReportNavigationData($quizId, request('report_index', 0));

        return view('results.ai-report', array_merge([
            'quiz' => $quiz,
            'results' => $results,
            'rootsPerformance' => $rootsPerformance,
            'remainingQuota' => $remainingQuota
        ], $statistics, $reportData));
    }

    /**
     * Show results for a specific quiz (for quiz owners)
     */
    public function quizResults($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        // Check authorization
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->is_admin && (int) $quiz->user_id !== $user->id) {
            abort(403, 'غير مصرح لك بعرض نتائج هذا الاختبار');
        }

        // Get results with optimized pagination
        $results = Result::where('quiz_id', $quizId)
            ->with(['user:id,name,email'])
            ->latest('created_at')
            ->paginate(20);

        // Calculate summary statistics
        $statistics = $this->calculateQuizStatistics($results->getCollection());

        return view('results.quiz-results', compact('quiz', 'results', 'statistics'));
    }

    /**
     * Generate AI pedagogical report for a quiz (Pro Teachers only)
     */
    public function generateAiReport($quizId)
    {
        try {
            $this->authorizeQuizAccess($quizId);

            $quiz = Quiz::with(['questions', 'subject'])->findOrFail($quizId);

            // Check subscription
            if (!Auth::user()->canUseAI()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يتطلب إنشاء التقارير الذكية اشتراك نشط',
                    'upgrade_required' => true
                ], 403);
            }

            // Check quota
            $quota = MonthlyQuota::getOrCreateCurrent(Auth::id());
            if (!$quota->hasRemainingAiReportQuota()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لقد استنفدت رصيدك الشهري من التقارير الذكية',
                    'quota_exceeded' => true
                ], 429);
            }

            // Validate data sufficiency
            $results = Result::where('quiz_id', $quizId)->get();
            if ($results->count() < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'يحتاج الاختبار على الأقل 3 نتائج لإنشاء تقرير مفيد',
                    'insufficient_data' => true
                ], 400);
            }

            // Check for recent report
            $existingReport = $this->checkExistingReport($quizId);
            if ($existingReport) {
                return response()->json([
                    'success' => true,
                    'message' => 'يوجد تقرير حديث لهذا الاختبار',
                    'report_exists' => true,
                    'report_data' => $existingReport->report_data,
                    'remaining_quota' => $quota->getRemainingQuota()
                ]);
            }

            // Generate the report
            $reportData = $this->processAiReportGeneration($quiz, $results, $quota);

            return response()->json($reportData);

        } catch (\Exception $e) {
            Log::error('AI Report generation exception', [
                'error' => $e->getMessage(),
                'quiz_id' => $quizId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء التقرير',
                'error_type' => get_class($e)
            ], 500);
        }
    }

    /**
     * Process AI report generation with fallback
     */
    private function processAiReportGeneration($quiz, $results, $quota)
    {
        // Calculate performance data
        $rootsPerformance = $this->calculateRootsPerformance($results, $quiz);
        $questionStats = $this->calculateQuestionStats($results, $quiz);

        // Create pending report record
        $reportRecord = QuizAiReport::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'student_count' => $results->count(),
            'generation_status' => 'pending',
            'report_data' => []
        ]);

        // Generate report with fallback
        $aiReportData = $this->generateAiReportWithFallback($quiz, $results, $rootsPerformance, $questionStats);

        if ($aiReportData['success']) {
            // Update successful report
            $reportRecord->update([
                'report_data' => $aiReportData['sections'],
                'generation_status' => 'completed',
                'tokens_used' => $aiReportData['tokens_used'] ?? 0
            ]);

            // Increment quota only for AI-generated reports
            if ($aiReportData['method'] === 'ai') {
                $quota->incrementAiReportRequests();
            }

            return [
                'success' => true,
                'message' => 'تم إنشاء التقرير التربوي بنجاح',
                'report_data' => $aiReportData['sections'],
                'generation_method' => $aiReportData['method'],
                'remaining_quota' => $quota->getRemainingQuota()
            ];
        } else {
            // Update failed report
            $reportRecord->update(['generation_status' => 'failed']);

            return [
                'success' => false,
                'message' => 'فشل في إنشاء التقرير: ' . ($aiReportData['error'] ?? 'خطأ غير معروف')
            ];
        }
    }

    /**
     * Check for existing recent report
     */
    private function checkExistingReport($quizId)
    {
        return QuizAiReport::where('quiz_id', $quizId)
            ->where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subHour())
            ->where('generation_status', 'completed')
            ->first();
    }

    /**
     * Authorize quiz access for current user
     */
    private function authorizeQuizAccess($quizId)
    {
        if (!Auth::check()) {
            abort(403, 'يجب تسجيل الدخول أولاً');
        }

        $quiz = Quiz::findOrFail($quizId);

        if ((int) $quiz->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بعرض نتائج هذا الاختبار');
        }
    }

    /**
     * Calculate basic quiz statistics
     */
    private function calculateQuizStatistics($results)
    {
        if ($results->isEmpty()) {
            return [
                'overallAverage' => 0,
                'passRate' => 0,
                'excellentCount' => 0,
                'totalStudents' => 0
            ];
        }

        $totalStudents = $results->count();
        $overallAverage = round($results->avg('total_score'), 1);
        $passCount = $results->where('total_score', '>=', 60)->count();
        $excellentCount = $results->where('total_score', '>=', 90)->count();
        $passRate = round(($passCount / $totalStudents) * 100, 1);

        return [
            'overallAverage' => $overallAverage,
            'passRate' => $passRate,
            'excellentCount' => $excellentCount,
            'totalStudents' => $totalStudents
        ];
    }

    /**
     * Get report navigation data
     */
    private function getReportNavigationData($quizId, $reportIndex)
    {
        $allReports = QuizAiReport::where('quiz_id', $quizId)
            ->where('user_id', Auth::id())
            ->where('generation_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $reportIndex = max(0, min($reportIndex, $allReports->count() - 1));
        $currentReport = $allReports->get($reportIndex);

        $navigation = [
            'total_reports' => $allReports->count(),
            'current_index' => $reportIndex,
            'current_number' => $reportIndex + 1,
            'has_previous' => $reportIndex > 0,
            'has_next' => $reportIndex < ($allReports->count() - 1),
            'previous_index' => $reportIndex > 0 ? $reportIndex - 1 : null,
            'next_index' => $reportIndex < ($allReports->count() - 1) ? $reportIndex + 1 : null,
            'reports_list' => []
        ];

        // Build reports list for dropdown
        foreach ($allReports as $index => $report) {
            $navigation['reports_list'][] = [
                'index' => $index,
                'number' => $index + 1,
                'date' => $report->created_at->format('Y-m-d'),
                'time' => $report->created_at->format('H:i'),
                'student_count' => $report->student_count,
                'age' => $report->created_at->diffForHumans(),
                'is_current' => $index === $reportIndex,
                'method' => $report->tokens_used > 0 ? 'ذكاء اصطناعي' : 'قالب تلقائي'
            ];
        }

        return [
            'aiReportData' => $currentReport ? $currentReport->report_data : null,
            'reportAge' => $currentReport ? $currentReport->created_at->diffForHumans() : null,
            'hasExistingReport' => (bool) $currentReport,
            'currentReport' => $currentReport,
            'reportNavigation' => $navigation,
            'allReports' => $allReports
        ];
    }

    /**
     * Generate AI report with fallback to template-based content
     */
    private function generateAiReportWithFallback($quiz, $results, $rootsPerformance, $questionStats)
    {
        try {
            // Try AI generation first
            $aiReport = $this->claudeService->generatePedagogicalReport($quiz, $results, $rootsPerformance, $questionStats);

            if ($aiReport['success'] && isset($aiReport['report_sections'])) {
                return [
                    'success' => true,
                    'sections' => $aiReport['report_sections'],
                    'method' => 'ai',
                    'tokens_used' => 1500
                ];
            }
        } catch (\Exception $e) {
            Log::warning('AI generation failed, falling back to template', [
                'error' => $e->getMessage(),
                'quiz_id' => $quiz->id
            ]);
        }

        // Fallback to template-based report
        return $this->generateTemplateBasedReport($quiz, $results, $rootsPerformance, $questionStats);
    }

    /**
     * Generate template-based report as fallback
     */
    private function generateTemplateBasedReport($quiz, $results, $rootsPerformance, $questionStats)
    {
        $statistics = $this->calculateQuizStatistics($results);

        // Generate overview
        $overview = $this->buildOverviewSection($quiz, $statistics);

        // Generate root analyses
        $sections = [
            'overview' => $overview,
            'jawhar_analysis' => $this->generateRootAnalysis('jawhar', 'الجوهر', $rootsPerformance['jawhar'] ?? []),
            'zihn_analysis' => $this->generateRootAnalysis('zihn', 'الذهن', $rootsPerformance['zihn'] ?? []),
            'waslat_analysis' => $this->generateRootAnalysis('waslat', 'الوصلات', $rootsPerformance['waslat'] ?? []),
            'roaya_analysis' => $this->generateRootAnalysis('roaya', 'الرؤية', $rootsPerformance['roaya'] ?? []),
            'group_tips' => $this->generateGroupTips($rootsPerformance, $statistics['overallAverage']),
            'immediate_actions' => $this->generateImmediateActions($rootsPerformance),
            'longterm_strategies' => $this->generateLongtermStrategies($rootsPerformance, $statistics['overallAverage']),
            'educational_alerts' => $this->generateEducationalAlerts($rootsPerformance, $questionStats),
            'bright_spots' => $this->generateBrightSpots($rootsPerformance, $statistics['overallAverage'])
        ];

        return [
            'success' => true,
            'sections' => $sections,
            'method' => 'template',
            'tokens_used' => 0
        ];
    }

    /**
     * Build overview section for template report
     */
    private function buildOverviewSection($quiz, $statistics)
    {
        $overview = "تم تحليل أداء {$statistics['totalStudents']} طالب في اختبار {$quiz->title}. ";
        $overview .= "المعدل العام للصف {$statistics['overallAverage']}% ونسبة النجاح {$statistics['passRate']}%. ";

        if ($statistics['overallAverage'] >= 80) {
            $overview .= "يظهر الصف أداءً ممتازاً بشكل عام.";
        } elseif ($statistics['overallAverage'] >= 70) {
            $overview .= "يظهر الصف أداءً جيداً مع إمكانية للتحسن.";
        } else {
            $overview .= "يحتاج الصف إلى تدخل تعليمي لتحسين الأداء.";
        }

        return $overview;
    }

    /**
     * Calculate enhanced roots performance data with detailed insights
     */
    private function calculateRootsPerformance($results, $quiz)
    {
        $rootsData = ['jawhar' => [], 'zihn' => [], 'waslat' => [], 'roaya' => []];

        // Group questions by root and depth
        $questionsByRoot = $quiz->questions->groupBy('root_type');

        foreach ($questionsByRoot as $rootType => $questions) {
            $levelData = [1 => [], 2 => [], 3 => []];
            $rootQuestions = [];

            foreach ($questions as $question) {
                // Get detailed answer statistics
                $correctAnswers = Answer::where('question_id', $question->id)
                    ->where('is_correct', true)
                    ->count();
                $totalAnswers = Answer::where('question_id', $question->id)->count();

                $percentage = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100) : 0;

                $levelData[$question->depth_level][] = $percentage;

                // Collect question-level insights
                $rootQuestions[] = [
                    'id' => $question->id,
                    'text' => substr($question->question, 0, 100),
                    'correct_rate' => $percentage,
                    'depth_level' => $question->depth_level,
                    'total_attempts' => $totalAnswers,
                    'difficulty_assessment' => $this->assessQuestionDifficulty($percentage)
                ];
            }

            // Calculate averages for each level
            $rootsData[$rootType] = [
                'level_1' => !empty($levelData[1]) ? round(array_sum($levelData[1]) / count($levelData[1])) : 0,
                'level_2' => !empty($levelData[2]) ? round(array_sum($levelData[2]) / count($levelData[2])) : 0,
                'level_3' => !empty($levelData[3]) ? round(array_sum($levelData[3]) / count($levelData[3])) : 0,
                'questions' => $rootQuestions,
                'question_count' => $questions->count()
            ];

            // Calculate overall average
            $allLevels = array_merge($levelData[1], $levelData[2], $levelData[3]);
            $rootsData[$rootType]['overall'] = !empty($allLevels) ? round(array_sum($allLevels) / count($allLevels)) : 0;

            // Add performance classification
            $rootsData[$rootType]['performance_level'] = $this->classifyRootPerformance($rootsData[$rootType]['overall']);
        }

        return $rootsData;
    }

    /**
     * Calculate enhanced question statistics with misconception analysis
     */
    private function calculateQuestionStats($results, $quiz)
    {
        $questionStats = [];
        $questionNumber = 1;

        foreach ($quiz->questions as $question) {
            // Basic statistics
            $correctAnswers = Answer::where('question_id', $question->id)
                ->where('is_correct', true)
                ->count();
            $totalAnswers = Answer::where('question_id', $question->id)->count();

            $percentage = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100) : 0;

            // Analyze wrong answer patterns
            $wrongAnswerPatterns = $this->analyzeWrongAnswerPatterns($question->id);

            // Determine question characteristics
            $questionCharacteristics = $this->determineQuestionCharacteristics($question, $percentage);

            $questionStats[] = [
                'question_number' => $questionNumber,
                'question_id' => $question->id,
                'question_text' => substr($question->question, 0, 150) . '...',
                'correct_percentage' => $percentage,
                'total_attempts' => $totalAnswers,
                'root_type' => $question->root_type,
                'depth_level' => $question->depth_level,
                'difficulty_level' => $this->assessQuestionDifficulty($percentage),
                'wrong_answer_patterns' => $wrongAnswerPatterns,
                'characteristics' => $questionCharacteristics,
                'needs_attention' => $percentage < 60,
                'misconception_indicators' => $this->detectMisconceptions($question->id, $wrongAnswerPatterns)
            ];

            $questionNumber++;
        }

        return $questionStats;
    }

    // Helper methods for analysis
    private function assessQuestionDifficulty($percentage): string
    {
        if ($percentage >= 85)
            return 'سهل';
        if ($percentage >= 70)
            return 'متوسط';
        if ($percentage >= 50)
            return 'صعب';
        return 'صعب جداً';
    }

    private function classifyRootPerformance($percentage): string
    {
        if ($percentage >= 90)
            return 'متميز';
        if ($percentage >= 80)
            return 'جيد جداً';
        if ($percentage >= 70)
            return 'جيد';
        if ($percentage >= 60)
            return 'مقبول';
        if ($percentage >= 50)
            return 'ضعيف';
        return 'ضعيف جداً';
    }

    private function analyzeWrongAnswerPatterns($questionId): array
    {
        $wrongAnswers = Answer::where('question_id', $questionId)
            ->where('is_correct', false)
            ->get();

        $patterns = [];
        $answerCounts = $wrongAnswers->groupBy('selected_answer');

        foreach ($answerCounts as $answer => $group) {
            if ($group->count() > 1) {
                $patterns[] = [
                    'wrong_answer' => $answer,
                    'student_count' => $group->count(),
                    'percentage_of_wrong' => $wrongAnswers->count() > 0 ?
                        round(($group->count() / $wrongAnswers->count()) * 100) : 0
                ];
            }
        }

        return $patterns;
    }

    private function determineQuestionCharacteristics($question, $percentage): array
    {
        $characteristics = [];

        $rootCharacteristics = [
            'jawhar' => 'يحتاج تعزيز المعرفة الأساسية',
            'zihn' => 'يحتاج تطوير مهارات التفكير النقدي',
            'waslat' => 'يحتاج تدريب على ربط المفاهيم',
            'roaya' => 'يحتاج المزيد من التطبيق العملي'
        ];

        if ($percentage < 60 && isset($rootCharacteristics[$question->root_type])) {
            $characteristics[] = $rootCharacteristics[$question->root_type];
        }

        if ($question->depth_level == 3 && $percentage < 50) {
            $characteristics[] = 'مفهوم عميق يحتاج تبسيط';
        }

        return $characteristics;
    }

    private function detectMisconceptions($questionId, $wrongAnswerPatterns): array
    {
        $misconceptions = [];

        foreach ($wrongAnswerPatterns as $pattern) {
            if ($pattern['student_count'] >= 3) {
                $misconceptions[] = [
                    'type' => 'common_error',
                    'description' => "خطأ شائع: {$pattern['student_count']} طلاب اختاروا نفس الإجابة الخاطئة",
                    'wrong_answer' => $pattern['wrong_answer'],
                    'student_count' => $pattern['student_count']
                ];
            }
        }

        return $misconceptions;
    }

    // Template generation methods
    private function generateRootAnalysis($rootKey, $rootName, $rootData)
    {
        $overall = $rootData['overall'] ?? 0;
        $level1 = $rootData['level_1'] ?? 0;
        $level2 = $rootData['level_2'] ?? 0;
        $level3 = $rootData['level_3'] ?? 0;

        $analysis = "أداء {$rootName}: {$overall}%\n\n";
        $analysis .= "تفصيل المستويات:\n";
        $analysis .= "• المستوى الأول (أساسي): {$level1}%\n";
        $analysis .= "• المستوى الثاني (متوسط): {$level2}%\n";
        $analysis .= "• المستوى الثالث (متقدم): {$level3}%\n\n";

        if ($overall >= 80) {
            $analysis .= "✅ نقاط القوة: أداء ممتاز في {$rootName}، يمكن الاستفادة من هذه القوة لدعم الجوانب الأخرى.\n\n";
        } elseif ($overall >= 60) {
            $analysis .= "📈 التوصية: أداء مقبول في {$rootName}، يحتاج تعزيز خاصة في المستويات العليا.\n\n";
        } else {
            $analysis .= "⚠️ يحتاج تدخل: أداء ضعيف في {$rootName}، يتطلب تركيز خاص في التدريس.\n\n";
        }

        return $analysis;
    }

    private function generateGroupTips($rootsPerformance, $averageScore)
    {
        $tips = "نصائح للتعامل مع هذه المجموعة:\n\n";

        $strengths = [];
        $weaknesses = [];

        foreach ($rootsPerformance as $root => $data) {
            $score = $data['overall'] ?? 0;
            if ($score >= 75) {
                $strengths[] = $this->getRootName($root);
            } elseif ($score < 60) {
                $weaknesses[] = $this->getRootName($root);
            }
        }

        if (!empty($strengths)) {
            $tips .= "🌟 نقاط القوة: " . implode('، ', $strengths) . "\n";
            $tips .= "استخدم هذه النقاط لبناء الثقة وتعزيز التعلم في الجوانب الأخرى.\n\n";
        }

        if (!empty($weaknesses)) {
            $tips .= "🎯 التركيز المطلوب: " . implode('، ', $weaknesses) . "\n";
            $tips .= "هذه الجوانب تحتاج اهتمام خاص في الدروس القادمة.\n\n";
        }

        if ($averageScore >= 80) {
            $tips .= "💡 هذا صف متميز، يمكن تحديهم بأنشطة إضافية ومشاريع متقدمة.";
        } elseif ($averageScore < 60) {
            $tips .= "🔄 يحتاج هذا الصف إلى مراجعة المفاهيم الأساسية قبل الانتقال لمواضيع جديدة.";
        }

        return $tips;
    }

    private function generateImmediateActions($rootsPerformance)
    {
        $actions = "الإجراءات الفورية للأسبوع القادم:\n\n";

        $weakestRoot = '';
        $lowestScore = 100;

        foreach ($rootsPerformance as $root => $data) {
            $score = $data['overall'] ?? 0;
            if ($score < $lowestScore) {
                $lowestScore = $score;
                $weakestRoot = $root;
            }
        }

        if ($weakestRoot) {
            $actions .= "1. ركز على تحسين " . $this->getRootName($weakestRoot) . " (الدرجة الحالية: {$lowestScore}%)\n";
        }
        $actions .= "2. راجع الأسئلة التي حصلت على أقل معدل إجابات صحيحة\n";
        $actions .= "3. اعمل أنشطة تفاعلية لتعزيز المفاهيم الضعيفة\n";

        return $actions;
    }

    private function generateLongtermStrategies($rootsPerformance, $averageScore)
    {
        $strategies = "الاستراتيجيات طويلة المدى:\n\n";

        if ($averageScore >= 80) {
            $strategies .= "• تطوير مهارات التفكير النقدي المتقدمة\n";
            $strategies .= "• إدماج مشاريع بحثية وأنشطة إبداعية\n";
            $strategies .= "• تحدي الطلاب بمسائل معقدة\n";
        } else {
            $strategies .= "• تعزيز المفاهيم الأساسية تدريجياً\n";
            $strategies .= "• استخدام استراتيجيات التعلم التفاعلي\n";
            $strategies .= "• توفير دعم إضافي للطلاب المتعثرين\n";
        }

        return $strategies;
    }

    private function generateEducationalAlerts($rootsPerformance, $questionStats)
    {
        $alerts = "تنبيهات تربوية مهمة:\n\n";
        $hasAlerts = false;

        foreach ($rootsPerformance as $root => $data) {
            $score = $data['overall'] ?? 0;
            if ($score < 50) {
                $alerts .= "🚨 " . $this->getRootName($root) . ": أداء ضعيف جداً يتطلب تدخل فوري\n";
                $hasAlerts = true;
            }
        }

        if (!$hasAlerts) {
            $alerts .= "✅ لا توجد تنبيهات حرجة، الأداء العام مقبول.";
        }

        return $alerts;
    }

    private function generateBrightSpots($rootsPerformance, $averageScore)
    {
        $spots = "النقاط المضيئة:\n\n";
        $hasSpots = false;

        foreach ($rootsPerformance as $root => $data) {
            $score = $data['overall'] ?? 0;
            if ($score >= 85) {
                $spots .= "⭐ " . $this->getRootName($root) . ": أداء ممتاز ({$score}%)\n";
                $hasSpots = true;
            }
        }

        if ($averageScore >= 75) {
            $spots .= "🎉 المعدل العام للصف مرتفع، مما يدل على جودة التعليم\n";
            $hasSpots = true;
        }

        if (!$hasSpots) {
            $spots .= "💪 الطلاب يبذلون جهداً جيداً، استمر في التشجيع والدعم.";
        }

        return $spots;
    }

    private function getRootName($rootKey)
    {
        $names = [
            'jawhar' => 'الجوهر',
            'zihn' => 'الذهن',
            'waslat' => 'الوصلات',
            'roaya' => 'الرؤية'
        ];

        return $names[$rootKey] ?? $rootKey;
    }

}