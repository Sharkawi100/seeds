@extends('layouts.app')

@section('content')
@php
// Calculate comprehensive statistics
$uniqueStudentResults = $results->groupBy(function($result) {
    return ($result->quiz_id . '_' . ($result->user_id ?: $result->guest_name));
});

$finalScores = $uniqueStudentResults->map(function($studentQuizResults) {
    $firstResult = $studentQuizResults->first();
    if ($firstResult->user_id) {
        return \App\Models\Result::getFinalScore($firstResult->quiz_id, $firstResult->user_id);
    }
    return $studentQuizResults->avg('total_score');
});

$avgFinalScore = round($finalScores->avg());
$excellentCount = $finalScores->filter(function($score) { return $score >= 90; })->count();
$goodCount = $finalScores->filter(function($score) { return $score >= 70 && $score < 90; })->count();
$passedCount = $finalScores->filter(function($score) { return $score >= 70; })->count();
$totalUniqueStudents = $uniqueStudentResults->count();
$todayResults = $results->whereDate('created_at', today())->count();

// Calculate root performance averages
$rootAverages = ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
$validScores = 0;

foreach($results as $result) {
    $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true);
    if ($scores) {
        foreach(['jawhar', 'zihn', 'waslat', 'roaya'] as $root) {
            $rootAverages[$root] += $scores[$root] ?? 0;
        }
        $validScores++;
    }
}

if ($validScores > 0) {
    foreach($rootAverages as $key => $value) {
        $rootAverages[$key] = round($value / $validScores);
    }
}
@endphp

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Enhanced Header Section -->
        <div class="mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 mb-2 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            📊 نتائج الاختبارات
                        </h1>
                        <p class="text-lg text-gray-600">تتبع أداء الطلاب وتحليل النتائج الشامل</p>
                        
                        <!-- Quick Stats -->
                        <div class="flex items-center gap-6 mt-4 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-gray-600">{{ $passedCount }} نجح</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                                <span class="text-gray-600">{{ $totalUniqueStudents }} طلاب</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                <span class="text-gray-600">{{ $results->groupBy('quiz_id')->count() }} اختبارات</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-3xl font-bold text-indigo-600">{{ $results->count() }}</div>
                        <div class="text-sm text-gray-500">إجمالي المحاولات</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $totalUniqueStudents }} نتيجة نهائية</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Dashboard -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
            <!-- Excellent Results -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="text-center">
                    <div class="p-3 bg-gradient-to-r from-green-400 to-green-600 rounded-xl mx-auto w-fit mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-trophy text-white text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $excellentCount }}</div>
                    <div class="text-sm text-gray-600">ممتاز (90%+)</div>
                    <div class="text-xs text-gray-400">{{ $totalUniqueStudents > 0 ? round(($excellentCount / $totalUniqueStudents) * 100) : 0 }}%</div>
                </div>
            </div>

            <!-- Good Results -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="text-center">
                    <div class="p-3 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl mx-auto w-fit mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-thumbs-up text-white text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $goodCount }}</div>
                    <div class="text-sm text-gray-600">جيد (70-89%)</div>
                    <div class="text-xs text-gray-400">{{ $totalUniqueStudents > 0 ? round(($goodCount / $totalUniqueStudents) * 100) : 0 }}%</div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="text-center">
                    <div class="p-3 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl mx-auto w-fit mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $avgFinalScore }}%</div>
                    <div class="text-sm text-gray-600">متوسط النهائي</div>
                    <div class="text-xs text-gray-400">درجة شاملة</div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="text-center">
                    <div class="p-3 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-xl mx-auto w-fit mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalUniqueStudents }}</div>
                    <div class="text-sm text-gray-600">طلاب فريدين</div>
                    <div class="text-xs text-gray-400">نتائج نهائية</div>
                </div>
            </div>

            <!-- Today's Activity -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="text-center">
                    <div class="p-3 bg-gradient-to-r from-orange-400 to-orange-600 rounded-xl mx-auto w-fit mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-day text-white text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $todayResults }}</div>
                    <div class="text-sm text-gray-600">اليوم</div>
                    <div class="text-xs text-gray-400">محاولات جديدة</div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 group">
                <div class="text-center">
                    <div class="p-3 bg-gradient-to-r from-teal-400 to-teal-600 rounded-xl mx-auto w-fit mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-percentage text-white text-xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalUniqueStudents > 0 ? round(($passedCount / $totalUniqueStudents) * 100) : 0 }}%</div>
                    <div class="text-sm text-gray-600">معدل النجاح</div>
                    <div class="text-xs text-gray-400">70% أو أكثر</div>
                </div>
            </div>
        </div>

        <!-- 4-Roots Performance Overview -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-seedling text-green-600"></i>
                أداء الجُذور الأربعة
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                $roots = [
                    'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'blue', 'desc' => 'الماهية والجوهر'],
                    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'purple', 'desc' => 'التفكير والتحليل'],
                    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'green', 'desc' => 'الربط والتكامل'],
                    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'orange', 'desc' => 'التطبيق والإبداع']
                ];
                @endphp

                @foreach($roots as $key => $root)
                <div class="text-center p-4 bg-{{ $root['color'] }}-50 rounded-xl border border-{{ $root['color'] }}-100 hover:shadow-md transition-all">
                    <div class="text-3xl mb-2">{{ $root['icon'] }}</div>
                    <div class="font-bold text-gray-900 mb-1">{{ $root['name'] }}</div>
                    <div class="text-2xl font-bold text-{{ $root['color'] }}-600 mb-1">{{ $rootAverages[$key] }}%</div>
                    <div class="text-xs text-gray-600">{{ $root['desc'] }}</div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="h-2 bg-gradient-to-r from-{{ $root['color'] }}-400 to-{{ $root['color'] }}-600 rounded-full transition-all duration-500" 
                             style="width: {{ $rootAverages[$key] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Enhanced Filters Section -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search text-indigo-500 mr-1"></i>
                        البحث
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="اسم الطالب أو عنوان الاختبار..."
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clipboard-list text-purple-500 mr-1"></i>
                        الاختبار
                    </label>
                    <select name="quiz_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                        <option value="">جميع الاختبارات</option>
                        @foreach($quizzes as $quiz)
                        <option value="{{ $quiz->id }}" {{ request('quiz_id') == $quiz->id ? 'selected' : '' }}>
                            {{ $quiz->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                        مستوى الأداء
                    </label>
                    <select name="score_range" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                        <option value="">جميع المستويات</option>
                        <option value="excellent" {{ request('score_range') == 'excellent' ? 'selected' : '' }}>متقن (90-100%)</option>
                        <option value="good" {{ request('score_range') == 'good' ? 'selected' : '' }}>متقدم (70-89%)</option>
                        <option value="fair" {{ request('score_range') == 'fair' ? 'selected' : '' }}>متطور (50-69%)</option>
                        <option value="poor" {{ request('score_range') == 'poor' ? 'selected' : '' }}>مبتدئ (أقل من 50%)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar text-green-500 mr-1"></i>
                        التاريخ
                    </label>
                    <select name="date_range" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                        <option value="">جميع التواريخ</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>هذا الشهر</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-filter mr-2"></i>
                        تطبيق المرشحات
                    </button>
                </div>
            </form>
        </div>

        <!-- Enhanced Results Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($results as $result)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <!-- Result Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md">
                            {{ substr($result->guest_name ?: ($result->user ? $result->user->name : 'ضيف'), 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $result->guest_name ?: ($result->user ? $result->user->name : 'ضيف') }}
                            </h3>
                            <p class="text-sm text-gray-500 truncate max-w-40">{{ $result->quiz->title }}</p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        @php
                        $finalScore = $result->user_id ? \App\Models\Result::getFinalScore($result->quiz_id, $result->user_id) : $result->total_score;
                        $attemptCount = $result->user_id 
                            ? \App\Models\Result::where('quiz_id', $result->quiz_id)->where('user_id', $result->user_id)->count()
                            : \App\Models\Result::where('quiz_id', $result->quiz_id)->where('guest_name', $result->guest_name)->count();
                        @endphp
                        
                        <div class="text-2xl font-bold {{ $finalScore >= 90 ? 'text-green-600' : ($finalScore >= 70 ? 'text-blue-600' : ($finalScore >= 50 ? 'text-orange-600' : 'text-red-600')) }}">
                            {{ $finalScore }}%
                        </div>
                        @if($attemptCount > 1)
                            <div class="text-xs text-indigo-600 font-medium bg-indigo-50 px-2 py-1 rounded-full">
                                {{ $attemptCount }} محاولات
                            </div>
                        @endif
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $result->created_at->format('m/d H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Enhanced Score Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>الدرجة النهائية</span>
                        <span>{{ $finalScore }}/100</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 shadow-inner">
                        <div class="h-3 rounded-full bg-gradient-to-r {{ $finalScore >= 90 ? 'from-green-400 to-green-600' : ($finalScore >= 70 ? 'from-blue-400 to-blue-600' : ($finalScore >= 50 ? 'from-orange-400 to-orange-600' : 'from-red-400 to-red-600')) }} transition-all duration-700 shadow-sm" 
                             style="width: {{ $finalScore }}%"></div>
                    </div>
                    @if($finalScore != $result->total_score)
                        <div class="text-xs text-gray-500 mt-1">
                            هذه المحاولة: {{ $result->total_score }}%
                        </div>
                    @endif
                </div>

                <!-- Enhanced Roots Performance -->
                <div class="grid grid-cols-2 gap-2 mb-4">
                    @php
                    $roots = [
                        'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'blue'],
                        'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'purple'], 
                        'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'green'],
                        'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'orange']
                    ];
                    $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true);
                    @endphp

                    @foreach($roots as $key => $root)
                    <div class="text-center p-2 bg-{{ $root['color'] }}-50 rounded-lg border border-{{ $root['color'] }}-100 hover:bg-{{ $root['color'] }}-100 transition-colors">
                        <div class="text-lg mb-1">{{ $root['icon'] }}</div>
                        <div class="text-xs text-gray-600 mb-1">{{ $root['name'] }}</div>
                        <div class="text-sm font-bold text-{{ $root['color'] }}-600">
                            {{ $scores[$key] ?? 0 }}%
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Enhanced Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('results.show', $result) }}" 
                       class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-center py-2 rounded-lg font-medium hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-eye mr-1"></i>
                        عرض التفاصيل
                    </a>
                    
                    @if($result->quiz->user_id == auth()->id())
                    <a href="{{ route('results.quiz', $result->quiz) }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transform hover:-translate-y-0.5 transition-all duration-300"
                       title="عرض جميع نتائج الاختبار">
                        <i class="fas fa-chart-bar"></i>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <!-- Enhanced Empty State -->
            <div class="col-span-full">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-3">لا توجد نتائج حالياً</h3>
                    <p class="text-gray-500 mb-6">لم يتم العثور على نتائج تطابق المعايير المحددة</p>
                    <a href="{{ route('quizzes.index') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                        <i class="fas fa-plus"></i>
                        إنشاء اختبار جديد
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Enhanced Pagination -->
        @if($results instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-8 flex justify-center">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-4">
                {{ $results->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.animate-fade-in {
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.glass-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Enhanced scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: linear-gradient(45deg, #f1f1f1, #e1e1e1);
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 8px;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #5a67d8, #6b46c1);
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Better responsive text */
@media (max-width: 640px) {
    .text-4xl { font-size: 2rem; }
    .text-3xl { font-size: 1.8rem; }
    .text-2xl { font-size: 1.5rem; }
}
</style>
@endpush

@push('scripts')
<script>
// Add smooth scrolling and enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on scroll
    const cards = document.querySelectorAll('.group');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => observer.observe(card));

    // Auto-submit form on select change
    const selects = document.querySelectorAll('select[name="quiz_id"], select[name="score_range"], select[name="date_range"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Enhanced search with debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
});
</script>
@endpush