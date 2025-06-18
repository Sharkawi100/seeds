<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $filters = $this->getFilters($request);

        // Get base data
        $teacherQuizzes = $this->getTeacherQuizzes($filters);
        $allResults = $this->getFilteredResults($teacherQuizzes, $filters);

        // Calculate total attempts for quick insights
        $totalAttempts = $allResults->count();

        // Calculate analytics
        $quickInsights = $this->calculateQuickInsights($teacherQuizzes, $allResults, $totalAttempts, $filters);
        $rootPerformance = $this->calculateRootPerformance($allResults);
        $performanceTrends = $this->calculatePerformanceTrends($allResults, $filters);
        $groupedData = $this->groupData($allResults, $filters['group_by'] ?? 'quiz', $filters);

        // Get all teacher quizzes (including those without results) for accurate count
        $allTeacherQuizzes = Quiz::where('user_id', Auth::id())->count();

        // Get filter options
        $subjects = Subject::all();
        $studentProgress = $this->getStudentProgress($allResults, $filters);

        return view('reports.index', compact(
            'teacherQuizzes',
            'allResults',
            'quickInsights',
            'rootPerformance',
            'performanceTrends',
            'groupedData',
            'subjects',
            'studentProgress',
            'totalAttempts',
            'allTeacherQuizzes',
            'filters'
        ));
    }

    private function getFilters(Request $request)
    {
        return [
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'subject_id' => $request->get('subject_id'),
            'quiz_search' => $request->get('quiz_search'),
            'root_focus' => $request->get('root_focus'),
            'score_range' => $request->get('score_range'),
            'group_size' => $request->get('group_size'),
            'group_by' => $request->get('group_by', 'quiz'),
            'student_search' => $request->get('student_search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
            'show_only_with_results' => $request->get('show_only_with_results', 'true')
        ];
    }

    private function getTeacherQuizzes($filters)
    {
        $query = Quiz::where('user_id', Auth::id())->with(['subject']);

        // Only include quizzes with results if filter is enabled
        if ($filters['show_only_with_results'] === 'true') {
            $query->with([
                'results' => function ($q) {
                    $q->whereNotNull('guest_name')->orderBy('created_at');
                }
            ])->whereHas('results', function ($q) {
                $q->whereNotNull('guest_name');
            });
        } else {
            $query->with([
                'results' => function ($q) {
                    $q->whereNotNull('guest_name')->orderBy('created_at');
                }
            ]);
        }

        if ($filters['subject_id']) {
            $query->where('subject_id', $filters['subject_id']);
        }

        if ($filters['quiz_search']) {
            $query->where('title', 'like', '%' . $filters['quiz_search'] . '%');
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sortDirection);
                break;
            case 'results_count':
                $query->withCount('results')->orderBy('results_count', $sortDirection);
                break;
            case 'avg_score':
                $query->withAvg('results', 'total_score')->orderBy('results_avg_total_score', $sortDirection);
                break;
            default:
                $query->orderBy('created_at', $sortDirection);
        }

        return $query->get();
    }

    private function getFilteredResults($teacherQuizzes, $filters)
    {
        $query = Result::whereIn('quiz_id', $teacherQuizzes->pluck('id'))
            ->whereNotNull('guest_name')
            ->with('quiz');

        if ($filters['date_from']) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to']) {
            $query->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        if ($filters['student_search']) {
            $query->where('guest_name', 'like', '%' . $filters['student_search'] . '%');
        }

        if ($filters['score_range']) {
            switch ($filters['score_range']) {
                case 'high':
                    $query->where('total_score', '>=', 80);
                    break;
                case 'medium':
                    $query->whereBetween('total_score', [60, 79]);
                    break;
                case 'low':
                    $query->where('total_score', '<', 60);
                    break;
            }
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    private function calculateQuickInsights($quizzes, $results, $totalAttempts, $filters)
    {
        $studentCount = $results->pluck('guest_name')->unique()->count();
        $avgScore = $results->avg('total_score');

        // Get actual total quiz count (including those without results)
        $totalQuizzesCount = Quiz::where('user_id', Auth::id())->count();

        // Find most used root
        $rootTotals = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
        foreach ($results as $result) {
            $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
            foreach ($rootTotals as $root => $total) {
                $rootTotals[$root] += $scores[$root] ?? 0;
            }
        }
        $mostUsedRoot = array_keys($rootTotals, max($rootTotals))[0];

        // Active period in Arabic
        $firstResult = $results->sortBy('created_at')->first();
        $lastResult = $results->sortByDesc('created_at')->first();
        $activePeriod = $firstResult && $lastResult ?
            $this->getArabicPeriod($firstResult->created_at, $lastResult->created_at) : 'غير محدد';

        // Peak day in Arabic
        $dayStats = $results->groupBy(function ($result) {
            return $result->created_at->format('w'); // Day of week (0-6)
        })->map->count();

        $peakDayIndex = $dayStats->count() ? $dayStats->keys()->first() : 0;
        $peakDay = $this->getArabicDayName($peakDayIndex);

        return [
            'total_quizzes' => $totalQuizzesCount,
            'quizzes_with_results' => $quizzes->count(),
            'unique_students' => $studentCount,
            'avg_score' => round($avgScore, 1),
            'most_used_root' => $mostUsedRoot,
            'active_period' => $activePeriod,
            'peak_day' => $peakDay
        ];
    }

    private function getArabicDayName($dayIndex)
    {
        $arabicDays = [
            0 => 'الأحد',
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت'
        ];

        return $arabicDays[$dayIndex] ?? 'غير محدد';
    }

    private function getArabicPeriod($start, $end)
    {
        $diffInDays = $start->diffInDays($end);

        if ($diffInDays < 7) {
            return $diffInDays . ' أيام';
        } elseif ($diffInDays < 30) {
            return round($diffInDays / 7) . ' أسابيع';
        } else {
            return round($diffInDays / 30) . ' أشهر';
        }
    }

    private function generateShortPin()
    {
        return strtoupper(substr(md5(uniqid()), 0, 6));
    }

    private function calculateRootPerformance($results)
    {
        $roots = ['jawhar', 'zihn', 'waslat', 'roaya'];
        $performance = [];

        foreach ($roots as $root) {
            $scores = $results->map(function ($result) use ($root) {
                $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
                return $scores[$root] ?? 0;
            })->filter();

            $performance[$root] = [
                'average' => round($scores->avg(), 1),
                'count' => $scores->count(),
                'highest' => $scores->max(),
                'lowest' => $scores->min(),
                'trend' => $this->calculateRootTrend($results, $root)
            ];
        }

        return $performance;
    }

    private function calculateRootTrend($results, $root)
    {
        $recent = $results->where('created_at', '>=', now()->subDays(30));
        $older = $results->where('created_at', '<', now()->subDays(30));

        if ($recent->count() < 3 || $older->count() < 3) {
            return 0;
        }

        $recentAvg = $recent->map(function ($result) use ($root) {
            $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
            return $scores[$root] ?? 0;
        })->avg();

        $olderAvg = $older->map(function ($result) use ($root) {
            $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
            return $scores[$root] ?? 0;
        })->avg();

        return round($recentAvg - $olderAvg, 1);
    }

    private function calculatePerformanceTrends($results, $filters)
    {
        $thisMonth = $results->where('created_at', '>=', now()->subDays(30));
        $lastMonth = $results->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)]);

        $thisMonthAvg = $thisMonth->avg('total_score');
        $lastMonthAvg = $lastMonth->avg('total_score');

        return [
            'monthly_change' => $thisMonthAvg && $lastMonthAvg ?
                round($thisMonthAvg - $lastMonthAvg, 1) : 0,
            'best_quiz_series' => $this->getBestQuizSeries($results),
            'engaged_students' => $this->getEngagedStudents($results)
        ];
    }

    private function getBestQuizSeries($results)
    {
        $quizAvgs = $results->groupBy('quiz.title')
            ->map(function ($group) {
                return [
                    'title' => $group->first()->quiz->title,
                    'average' => round($group->avg('total_score'), 1),
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('average')
            ->first();

        return $quizAvgs ?? ['title' => 'غير محدد', 'average' => 0];
    }

    private function getEngagedStudents($results)
    {
        return $results->groupBy('guest_name')
            ->filter(function ($group) {
                return $group->count() >= 5;
            })
            ->count();
    }

    private function groupData($results, $groupBy, $filters)
    {
        switch ($groupBy) {
            case 'student':
                return $this->groupByStudent($results);
            case 'date':
                return $this->groupByDate($results);
            case 'root':
                return $this->groupByRoot($results);
            default:
                return $this->groupByQuiz($results);
        }
    }

    private function groupByQuiz($results)
    {
        return $results->groupBy('quiz_id')->map(function ($group) {
            $quiz = $group->first()->quiz;
            $scores = $group->map(function ($result) {
                return is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
            });

            return [
                'type' => 'quiz',
                'title' => $quiz->title,
                'date' => $group->first()->created_at,
                'pin' => $this->generateShortPin(),
                'student_count' => $group->count(),
                'avg_score' => round($group->avg('total_score'), 1),
                'jawhar_avg' => round($scores->avg('jawhar'), 1),
                'zihn_avg' => round($scores->avg('zihn'), 1),
                'waslat_avg' => round($scores->avg('waslat'), 1),
                'roaya_avg' => round($scores->avg('roaya'), 1),
                'results' => $group
            ];
        })->values();
    }

    private function groupByStudent($results)
    {
        return $results->groupBy('guest_name')->map(function ($group) {
            $scores = $group->map(function ($result) {
                return is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
            });

            return [
                'type' => 'student',
                'name' => $group->first()->guest_name,
                'quiz_count' => $group->count(),
                'avg_score' => round($group->avg('total_score'), 1),
                'latest_attempt' => $group->sortByDesc('created_at')->first()->created_at,
                'jawhar_avg' => round($scores->avg('jawhar'), 1),
                'zihn_avg' => round($scores->avg('zihn'), 1),
                'waslat_avg' => round($scores->avg('waslat'), 1),
                'roaya_avg' => round($scores->avg('roaya'), 1),
                'results' => $group->sortByDesc('created_at')
            ];
        })->values();
    }

    private function groupByDate($results)
    {
        return $results->groupBy(function ($result) {
            return $result->created_at->format('Y-m-d');
        })->map(function ($group, $date) {
            $scores = $group->map(function ($result) {
                return is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
            });

            return [
                'type' => 'date',
                'date' => $date,
                'quiz_count' => $group->pluck('quiz_id')->unique()->count(),
                'student_count' => $group->count(),
                'avg_score' => round($group->avg('total_score'), 1),
                'jawhar_avg' => round($scores->avg('jawhar'), 1),
                'zihn_avg' => round($scores->avg('zihn'), 1),
                'waslat_avg' => round($scores->avg('waslat'), 1),
                'roaya_avg' => round($scores->avg('roaya'), 1),
                'results' => $group
            ];
        })->values();
    }

    private function groupByRoot($results)
    {
        $roots = ['jawhar', 'zihn', 'waslat', 'roaya'];
        $analysis = [];

        foreach ($roots as $root) {
            $dominantResults = $results->filter(function ($result) use ($root) {
                $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
                $maxScore = max($scores);
                return $scores[$root] == $maxScore;
            });

            $analysis[$root] = [
                'type' => 'root',
                'root' => $root,
                'dominant_count' => $dominantResults->count(),
                'avg_score' => round($dominantResults->avg('total_score'), 1),
                'results' => $dominantResults
            ];
        }

        return collect($analysis);
    }

    private function getStudentProgress($results, $filters)
    {
        if (!$filters['student_search']) {
            return collect();
        }

        $studentResults = $results->where('guest_name', 'like', '%' . $filters['student_search'] . '%')
            ->sortBy('created_at');

        if ($studentResults->isEmpty()) {
            return collect();
        }

        // Calculate trends
        $firstScore = $studentResults->first()->total_score;
        $lastScore = $studentResults->last()->total_score;
        $improvement = $lastScore - $firstScore;

        return [
            'student_name' => $studentResults->first()->guest_name,
            'total_quizzes' => $studentResults->count(),
            'improvement' => round($improvement, 1),
            'timeline' => $studentResults->map(function ($result) {
                $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores, true);
                return [
                    'date' => $result->created_at,
                    'quiz' => $result->quiz->title,
                    'total_score' => $result->total_score,
                    'jawhar' => $scores['jawhar'] ?? 0,
                    'zihn' => $scores['zihn'] ?? 0,
                    'waslat' => $scores['waslat'] ?? 0,
                    'roaya' => $scores['roaya'] ?? 0,
                ];
            })
        ];
    }
}