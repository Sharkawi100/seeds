@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <a href="{{ route('quizzes.index') }}" 
                               class="p-2 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                                <i class="fas fa-arrow-right text-gray-600"></i>
                            </a>
                            <div>
                                <h1 class="text-4xl font-black text-gray-900 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                    📊 نتائج الاختبار
                                </h1>
                                <p class="text-xl text-gray-700 font-medium mt-1">{{ $quiz->title }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-6 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-book text-indigo-500"></i>
                                <span>{{ $quiz->subject->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-layer-group text-purple-500"></i>
                                <span>الصف {{ $quiz->grade_level }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar text-green-500"></i>
                                <span>{{ $quiz->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-3xl font-bold text-indigo-600">{{ $results->count() }}</div>
                        <div class="text-sm text-gray-500">إجمالي المحاولات</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-green-400 to-green-600 rounded-xl">
                        <i class="fas fa-percentage text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        @php
$uniqueStudents = $results->groupBy(function($result) {
    return $result->user_id ?: $result->guest_name;
});
$finalScores = $uniqueStudents->map(function($studentResults, $key) use ($quiz) {
    if ($studentResults->first()->user_id) {
        return \App\Models\Result::getFinalScore($quiz->id, $studentResults->first()->user_id);
    }
    return $studentResults->avg('total_score');
});
$avgFinalScore = round($finalScores->avg());
@endphp

<div class="text-2xl font-bold text-gray-900">{{ $avgFinalScore }}%</div>
<div class="text-sm text-gray-600">متوسط الدرجات النهائية</div>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl">
                        <i class="fas fa-trophy text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $results->max('total_score') }}%</div>
                        <div class="text-sm text-gray-600">أعلى درجة</div>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-orange-400 to-orange-600 rounded-xl">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        @php
$passedStudents = $finalScores->filter(function($score) {
    return $score >= 70;
})->count();
@endphp

<div class="text-2xl font-bold text-gray-900">{{ $passedStudents }}</div>
<div class="text-sm text-gray-600">طلاب نجحوا</div>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $uniqueStudents->count() }}</div>
<div class="text-sm text-gray-600">عدد الطلاب</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- 4-Roots Radar Chart -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-radar text-indigo-600"></i>
                    تحليل الجُذور الأربعة
                </h3>
                
                <div class="relative h-80">
                    <canvas id="rootsRadarChart"></canvas>
                </div>
                
                <!-- Legend -->
                <div class="grid grid-cols-2 gap-4 mt-6">
                    @php
                    $roots = [
                        'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => '#3B82F6', 'desc' => 'الجوهر والماهية'],
                        'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => '#8B5CF6', 'desc' => 'التفكير والتحليل'],
                        'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => '#10B981', 'desc' => 'الربط والتكامل'],
                        'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => '#F59E0B', 'desc' => 'التطبيق والإبداع']
                    ];
                    @endphp
                    
                    @foreach($roots as $key => $root)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="text-2xl">{{ $root['icon'] }}</div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $root['name'] }}</div>
                            <div class="text-xs text-gray-600">{{ $root['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Score Distribution Chart -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-purple-600"></i>
                    توزيع الدرجات
                </h3>
                
                <div class="relative h-80">
                    <canvas id="scoreDistributionChart"></canvas>
                </div>
                
                <!-- Distribution Summary -->
                <div class="grid grid-cols-4 gap-2 mt-6 text-center">
                    <div class="p-3 bg-red-50 rounded-xl">
                        <div class="text-lg font-bold text-red-600">{{ $results->where('total_score', '<', 50)->count() }}</div>
                        <div class="text-xs text-red-700">ضعيف</div>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-xl">
                        <div class="text-lg font-bold text-orange-600">{{ $results->whereBetween('total_score', [50, 69])->count() }}</div>
                        <div class="text-xs text-orange-700">مقبول</div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <div class="text-lg font-bold text-blue-600">{{ $results->whereBetween('total_score', [70, 89])->count() }}</div>
                        <div class="text-xs text-blue-700">جيد</div>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl">
                        <div class="text-lg font-bold text-green-600">{{ $results->where('total_score', '>=', 90)->count() }}</div>
                        <div class="text-xs text-green-700">ممتاز</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Results Table -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-table text-indigo-600"></i>
                        النتائج التفصيلية
                    </h3>
                    
                    <!-- AI Report Button -->
                    <div class="flex items-center gap-3">
                        @if(Auth::user()->canUseAI() && $results->count() >= 3)
                            <a href="{{ route('results.ai-report', $quiz->id) }}" 
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:from-purple-700 hover:to-blue-700 transition-all duration-300 shadow-md text-sm"
                               title="إنشاء تقرير تربوي ذكي باستخدام الذكاء الاصطناعي">
                                🤖 التقرير الذكي
                            </a>
                        @elseif(!Auth::user()->canUseAI())
                            <a href="{{ route('subscription.upgrade') }}" 
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-md text-sm"
                               title="اشترك للحصول على التقارير الذكية">
                                ⭐ اشترك للتقارير الذكية
                            </a>
                        @elseif($results->count() < 3)
                            <div class="inline-flex items-center gap-2 bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed text-sm"
                                 title="يحتاج الاختبار على الأقل 3 نتائج لإنشاء التقرير">
                                🤖 التقرير الذكي ({{ $results->count() }}/3)
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">الطالب</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">النتيجة الإجمالية</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">المحاولات</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">جَوهر</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">ذِهن</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">وَصلات</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">رُؤية</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($results->sortByDesc('total_score') as $index => $result)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $result->guest_name ?: ($result->user ? $result->user->name : 'ضيف') }}</div>
                                        <div class="text-sm text-gray-500">{{ $result->created_at->format('Y/m/d H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                @php
                                $finalScore = $result->user_id ? \App\Models\Result::getFinalScore($result->quiz_id, $result->user_id) : $result->total_score;
                                $attemptCount = $result->user_id 
    ? \App\Models\Result::where('quiz_id', $result->quiz_id)->where('user_id', $result->user_id)->count()
    : \App\Models\Result::where('quiz_id', $result->quiz_id)->where('guest_name', $result->guest_name)->count();                                @endphp
                                
                                <div class="flex items-center justify-center">
                                    <div class="text-lg font-bold {{ $finalScore >= 70 ? 'text-green-600' : ($finalScore >= 50 ? 'text-orange-600' : 'text-red-600') }}">
                                        {{ $finalScore }}%
                                    </div>
                                </div>
                                @if($result->user_id && $attemptCount > 1)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $attemptCount }} محاولات
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $attemptCount }}
                                </div>
                                @if($attemptCount > 1)
                                    <div class="text-xs text-indigo-600">متعددة</div>
                                @endif
                            </td>
                            
                            @php
                            $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true);
                            @endphp
                            
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-medium text-blue-600">{{ $scores['jawhar'] ?? 0 }}%</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-medium text-purple-600">{{ $scores['zihn'] ?? 0 }}%</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-medium text-green-600">{{ $scores['waslat'] ?? 0 }}%</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-medium text-orange-600">{{ $scores['roaya'] ?? 0 }}%</span>
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('results.show', $result) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors text-sm">
                                    <i class="fas fa-eye"></i>
                                    عرض
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.glass-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate average scores for each root
    const results = @json($results->values()); // Convert to array
    const rootAverages = {
        jawhar: 0,
        zihn: 0,
        waslat: 0,
        roaya: 0
    };
    
    let validResults = 0;
    results.forEach(result => {
        if (result.scores) {
            const scores = typeof result.scores === 'string' ? JSON.parse(result.scores) : result.scores;
            rootAverages.jawhar += scores.jawhar || 0;
            rootAverages.zihn += scores.zihn || 0;
            rootAverages.waslat += scores.waslat || 0;
            rootAverages.roaya += scores.roaya || 0;
            validResults++;
        }
    });
    
    // Calculate averages
    Object.keys(rootAverages).forEach(key => {
        rootAverages[key] = validResults > 0 ? rootAverages[key] / validResults : 0;
    });

    // 4-Roots Radar Chart
    const radarCanvas = document.getElementById('rootsRadarChart');
    if (radarCanvas) {
        const ctx1 = radarCanvas.getContext('2d');
        new Chart(ctx1, {
            type: 'radar',
            data: {
                labels: ['جَوهر (الماهية)', 'ذِهن (التحليل)', 'وَصلات (الربط)', 'رُؤية (التطبيق)'],
                datasets: [{
                    label: 'متوسط الأداء',
                    data: [rootAverages.jawhar, rootAverages.zihn, rootAverages.waslat, rootAverages.roaya],
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(99, 102, 241, 1)',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        angleLines: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        pointLabels: {
                            font: {
                                size: 12,
                                family: 'Tajawal'
                            },
                            color: '#374151'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6B7280',
                            backdropColor: 'transparent'
                        }
                    }
                }
            }
        });
    }

    // Score Distribution Chart
    const doughnutCanvas = document.getElementById('scoreDistributionChart');
    if (doughnutCanvas) {
        const ctx2 = doughnutCanvas.getContext('2d');
    const scoreRanges = {
        'ضعيف (0-49)': results.filter(r => r.total_score < 50).length,
        'مقبول (50-69)': results.filter(r => r.total_score >= 50 && r.total_score < 70).length,
        'جيد (70-89)': results.filter(r => r.total_score >= 70 && r.total_score < 90).length,
        'ممتاز (90-100)': results.filter(r => r.total_score >= 90).length
    };

        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: Object.keys(scoreRanges),
                datasets: [{
                    data: Object.values(scoreRanges),
                    backgroundColor: [
                        '#EF4444',
                        '#F59E0B', 
                        '#3B82F6',
                        '#10B981'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                family: 'Tajawal'
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush