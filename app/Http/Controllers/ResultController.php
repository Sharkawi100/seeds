<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    /**
     * Show AI pedagogical report page for a quiz
     */
    public function showAiReport($quizId)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $quiz = \App\Models\Quiz::with(['questions', 'subject'])->findOrFail($quizId);

        // Check if user owns the quiz
        if ((int) $quiz->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بعرض تقرير هذا الاختبار');
        }

        // Get all results for this quiz
        $results = \App\Models\Result::where('quiz_id', $quizId)->get();

        if ($results->count() < 3) {
            return redirect()->route('results.quiz', $quiz->id)
                ->with('error', 'يحتاج الاختبار على الأقل 3 نتائج لإنشاء التقرير التربوي');
        }

        // Calculate statistics (SQL data - no AI needed)
        $overallAverage = $results->avg('total_score');
        $passRate = $results->where('total_score', '>=', 60)->count() / $results->count() * 100;
        $excellentCount = $results->where('total_score', '>=', 90)->count();

        // Calculate roots performance
        $rootsPerformance = $this->calculateRootsPerformance($results, $quiz);

        // Get current quota
        $quota = \App\Models\MonthlyQuota::getOrCreateCurrent(Auth::id());
        $remainingQuota = $quota->getRemainingQuota();

        // Get all completed reports for this quiz by this user
        $allReports = \App\Models\QuizAiReport::where('quiz_id', $quizId)
            ->where('user_id', Auth::id())
            ->where('generation_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current report index from request (default to latest)
        $reportIndex = (int) request('report_index', 0);
        $reportIndex = max(0, min($reportIndex, $allReports->count() - 1));

        // Prepare AI report data
        $currentReport = $allReports->get($reportIndex);
        $aiReportData = null;
        $reportAge = null;
        $hasExistingReport = false;
        $reportNavigation = [
            'total_reports' => $allReports->count(),
            'current_index' => $reportIndex,
            'current_number' => $reportIndex + 1,
            'has_previous' => $reportIndex > 0,
            'has_next' => $reportIndex < ($allReports->count() - 1),
            'previous_index' => $reportIndex > 0 ? $reportIndex - 1 : null,
            'next_index' => $reportIndex < ($allReports->count() - 1) ? $reportIndex + 1 : null,
            'reports_list' => []
        ];

        if ($currentReport) {
            $aiReportData = $currentReport->report_data;
            $reportAge = $currentReport->created_at->diffForHumans();
            $hasExistingReport = true;

            // Build reports list for dropdown
            foreach ($allReports as $index => $report) {
                $reportNavigation['reports_list'][] = [
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
        }

        return view('results.ai-report', compact(
            'quiz',
            'results',
            'overallAverage',
            'passRate',
            'excellentCount',
            'rootsPerformance',
            'remainingQuota',
            'aiReportData',
            'reportAge',
            'hasExistingReport',
            'currentReport',
            'reportNavigation',
            'allReports'
        ));
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
        if ((int) $quiz->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'غير مصرح لك بعرض نتائج هذا الاختبار');
        }

        // Get ALL results for this specific quiz
        $results = \App\Models\Result::where('quiz_id', $quizId)
            ->with(['user'])
            ->latest()
            ->paginate(20);

        return view('results.quiz-results', compact('quiz', 'results'));
    }

    /**
     * Generate AI pedagogical report for a quiz (Pro Teachers only)
     */
    public function generateAiReport($quizId)
    {
        try {
            // Authentication check
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً'
                ], 401);
            }

            // Find quiz
            $quiz = \App\Models\Quiz::with(['questions', 'subject'])->find($quizId);
            if (!$quiz) {
                return response()->json([
                    'success' => false,
                    'message' => 'الاختبار غير موجود'
                ], 404);
            }

            // Check ownership
            if ((int) $quiz->user_id !== Auth::id() && !Auth::user()->is_admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بإنشاء تقرير لهذا الاختبار'
                ], 403);
            }

            // Check subscription
            if (!Auth::user()->canUseAI()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يتطلب إنشاء التقارير الذكية اشتراك نشط',
                    'upgrade_required' => true
                ], 403);
            }

            // Get quota
            $quota = \App\Models\MonthlyQuota::getOrCreateCurrent(Auth::id());
            if (!$quota->hasRemainingAiReportQuota()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لقد استنفدت رصيدك الشهري من التقارير الذكية',
                    'quota_exceeded' => true
                ], 429);
            }

            // Get results
            $results = \App\Models\Result::where('quiz_id', $quizId)->get();
            if ($results->count() < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'يحتاج الاختبار على الأقل 3 نتائج لإنشاء تقرير مفيد',
                    'insufficient_data' => true
                ], 400);
            }

            // Check if recent report exists (within 1 hour to allow regeneration)
            $existingReport = \App\Models\QuizAiReport::where('quiz_id', $quizId)
                ->where('user_id', Auth::id())
                ->where('created_at', '>=', now()->subHour())
                ->where('generation_status', 'completed')
                ->first();

            if ($existingReport) {
                return response()->json([
                    'success' => true,
                    'message' => 'يوجد تقرير حديث لهذا الاختبار',
                    'report_exists' => true,
                    'report_id' => $existingReport->id,
                    'report_data' => $existingReport->report_data,
                    'remaining_quota' => $quota->getRemainingQuota()
                ]);
            }

            // Calculate statistics
            $rootsPerformance = $this->calculateRootsPerformance($results, $quiz);
            $questionStats = $this->calculateQuestionStats($results, $quiz);

            // Create pending report record
            $reportRecord = \App\Models\QuizAiReport::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id(),
                'student_count' => $results->count(),
                'generation_status' => 'pending',
                'report_data' => []
            ]);

            // Generate AI report with fallback
            $aiReportData = $this->generateAiReportWithFallback($quiz, $results, $rootsPerformance, $questionStats);

            if ($aiReportData['success']) {
                // Update report with generated content
                $reportRecord->update([
                    'report_data' => $aiReportData['sections'],
                    'generation_status' => 'completed',
                    'tokens_used' => $aiReportData['tokens_used'] ?? 1500
                ]);

                // Increment quota
                $quota->incrementAiReportRequests();

                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء التقرير التربوي بنجاح',
                    'report_id' => $reportRecord->id,
                    'report_data' => $aiReportData['sections'],
                    'remaining_quota' => $quota->getRemainingQuota()
                ]);
            } else {
                // Update report as failed
                $reportRecord->update(['generation_status' => 'failed']);

                return response()->json([
                    'success' => false,
                    'message' => 'فشل في إنشاء التقرير: ' . $aiReportData['error'],
                    'debug_info' => $aiReportData
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('AI Report generation exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'quiz_id' => $quizId ?? null,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء التقرير',
                'debug_info' => [
                    'error' => $e->getMessage(),
                    'type' => get_class($e)
                ]
            ], 500);
        }
    }

    /**
     * Generate AI report with fallback to template-based content
     */
    private function generateAiReportWithFallback($quiz, $results, $rootsPerformance, $questionStats)
    {
        try {
            // Try AI generation first
            $claudeService = app(\App\Services\ClaudeService::class);
            $aiReport = $claudeService->generatePedagogicalReport($quiz, $results, $rootsPerformance, $questionStats);

            if ($aiReport['success'] && isset($aiReport['report_sections'])) {
                return [
                    'success' => true,
                    'sections' => $aiReport['report_sections'],
                    'method' => 'ai',
                    'tokens_used' => 1500
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('AI generation failed, falling back to template', [
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
        $studentCount = $results->count();
        $averageScore = round($results->avg('total_score'));
        $passRate = round(($results->where('total_score', '>=', 60)->count() / $studentCount) * 100);

        // Generate overview
        $overview = "تم تحليل أداء {$studentCount} طالب في اختبار {$quiz->title}. ";
        $overview .= "المعدل العام للصف {$averageScore}% ونسبة النجاح {$passRate}%. ";

        if ($averageScore >= 80) {
            $overview .= "يظهر الصف أداءً ممتازاً بشكل عام.";
        } elseif ($averageScore >= 70) {
            $overview .= "يظهر الصف أداءً جيداً مع إمكانية للتحسن.";
        } else {
            $overview .= "يحتاج الصف إلى تدخل تعليمي لتحسين الأداء.";
        }

        // Generate root analyses
        $sections = [
            'overview' => $overview,
            'jawhar_analysis' => $this->generateRootAnalysis('jawhar', 'الجوهر', $rootsPerformance['jawhar']),
            'zihn_analysis' => $this->generateRootAnalysis('zihn', 'الذهن', $rootsPerformance['zihn']),
            'waslat_analysis' => $this->generateRootAnalysis('waslat', 'الوصلات', $rootsPerformance['waslat']),
            'roaya_analysis' => $this->generateRootAnalysis('roaya', 'الرؤية', $rootsPerformance['roaya']),
            'group_tips' => $this->generateGroupTips($rootsPerformance, $averageScore),
            'immediate_actions' => $this->generateImmediateActions($rootsPerformance),
            'longterm_strategies' => $this->generateLongtermStrategies($rootsPerformance, $averageScore),
            'educational_alerts' => $this->generateEducationalAlerts($rootsPerformance, $questionStats),
            'bright_spots' => $this->generateBrightSpots($rootsPerformance, $averageScore)
        ];

        return [
            'success' => true,
            'sections' => $sections,
            'method' => 'template',
            'tokens_used' => 0
        ];
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
                $correctAnswers = \App\Models\Answer::where('question_id', $question->id)
                    ->where('is_correct', true)
                    ->count();
                $totalAnswers = \App\Models\Answer::where('question_id', $question->id)->count();

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
            $correctAnswers = \App\Models\Answer::where('question_id', $question->id)
                ->where('is_correct', true)
                ->count();
            $totalAnswers = \App\Models\Answer::where('question_id', $question->id)->count();

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

    // Helper methods
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
        $wrongAnswers = \App\Models\Answer::where('question_id', $questionId)
            ->where('is_correct', false)
            ->get();

        $patterns = [];
        $answerCounts = $wrongAnswers->groupBy('selected_answer');

        foreach ($answerCounts as $answer => $group) {
            if ($group->count() > 1) {
                $patterns[] = [
                    'wrong_answer' => $answer,
                    'student_count' => $group->count(),
                    'percentage_of_wrong' => $wrongAnswers->count() > 0 ? round(($group->count() / $wrongAnswers->count()) * 100) : 0
                ];
            }
        }

        return $patterns;
    }

    private function determineQuestionCharacteristics($question, $percentage): array
    {
        $characteristics = [];

        switch ($question->root_type) {
            case 'jawhar':
                if ($percentage < 60) {
                    $characteristics[] = 'يحتاج تعزيز المعرفة الأساسية';
                }
                break;
            case 'zihn':
                if ($percentage < 60) {
                    $characteristics[] = 'يحتاج تطوير مهارات التفكير النقدي';
                }
                break;
            case 'waslat':
                if ($percentage < 60) {
                    $characteristics[] = 'يحتاج تدريب على ربط المفاهيم';
                }
                break;
            case 'roaya':
                if ($percentage < 60) {
                    $characteristics[] = 'يحتاج المزيد من التطبيق العملي';
                }
                break;
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

        $actions .= "1. ركز على تحسين " . $this->getRootName($weakestRoot) . " (الدرجة الحالية: {$lowestScore}%)\n";
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

        foreach ($rootsPerformance as $root => $data) {
            $score = $data['overall'] ?? 0;
            if ($score < 50) {
                $alerts .= "🚨 " . $this->getRootName($root) . ": أداء ضعيف جداً يتطلب تدخل فوري\n";
            }
        }

        if (empty(trim(str_replace("تنبيهات تربوية مهمة:\n\n", "", $alerts)))) {
            $alerts .= "✅ لا توجد تنبيهات حرجة، الأداء العام مقبول.";
        }

        return $alerts;
    }

    private function generateBrightSpots($rootsPerformance, $averageScore)
    {
        $spots = "النقاط المضيئة:\n\n";

        foreach ($rootsPerformance as $root => $data) {
            $score = $data['overall'] ?? 0;
            if ($score >= 85) {
                $spots .= "⭐ " . $this->getRootName($root) . ": أداء ممتاز ({$score}%)\n";
            }
        }

        if ($averageScore >= 75) {
            $spots .= "🎉 المعدل العام للصف مرتفع، مما يدل على جودة التعليم\n";
        }

        if (empty(trim(str_replace("النقاط المضيئة:\n\n", "", $spots)))) {
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