@extends('layouts.app')

@section('content')
<style>
/* Enhanced animations and visual improvements */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.5s ease-out;
}

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.metric-card:hover {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(59, 130, 246, 0.1));
    transform: scale(1.02);
    transition: all 0.3s ease;
}

.progress-bar {
    position: relative;
    overflow: hidden;
}

.progress-bar::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.table-row-hover {
    transition: all 0.2s ease;
}

.table-row-hover:hover {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(59, 130, 246, 0.05));
    transform: translateX(4px);
}

.button-glow:hover {
    box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
    transform: translateY(-2px);
}

.icon-bounce:hover {
    animation: pulse 0.6s ease-in-out;
}

.glass-morphism {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
</style>

<div class="container mx-auto px-4 py-8" dir="rtl">
    <!-- Header -->
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-2">
            تقارير وتحليل
        </h1>
        <p class="text-gray-600">تحليل شامل لأداء الطلاب عبر جميع اختباراتك</p>
    </div>

    <!-- Filters & Controls -->
    <div class="glass-morphism rounded-2xl shadow-lg mb-8 animate-slide-in-right overflow-hidden">
        <!-- Filter Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
            <h3 class="text-white font-bold text-lg flex items-center">
                <i class="fas fa-filter ml-2"></i>
                فلاتر التقارير
            </h3>
        </div>
        
        <form method="GET" class="p-6">
            <!-- Primary Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-eye text-purple-500 ml-2"></i>
                        عرض النتائج
                    </label>
                    <select name="show_only_with_results" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-300 text-sm">
                        <option value="true" {{ $filters['show_only_with_results'] == 'true' ? 'selected' : '' }}>الاختبارات مع النتائج فقط</option>
                        <option value="false" {{ $filters['show_only_with_results'] == 'false' ? 'selected' : '' }}>جميع الاختبارات</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-book text-blue-500 ml-2"></i>
                        المادة الدراسية
                    </label>
                    <select name="subject_id" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 text-sm">
                        <option value="">جميع المواد</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $filters['subject_id'] == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-search text-green-500 ml-2"></i>
                        البحث في الاختبارات
                    </label>
                    <input type="text" name="quiz_search" value="{{ $filters['quiz_search'] }}" 
                           placeholder="اكتب اسم الاختبار..."
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300 text-sm">
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-user-search text-indigo-500 ml-2"></i>
                        البحث عن طالب
                    </label>
                    <input type="text" name="student_search" value="{{ $filters['student_search'] }}" 
                           placeholder="اكتب اسم الطالب..."
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 text-sm">
                </div>
            </div>

            <!-- Advanced Filters Collapsible -->
            <div class="border-t border-gray-200 pt-6">
                <button type="button" id="toggle-advanced" class="flex items-center text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors duration-200 mb-4">
                    <i class="fas fa-chevron-down transition-transform duration-200 ml-2" id="chevron-icon"></i>
                    فلاتر متقدمة
                </button>
                
                <div id="advanced-filters" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt text-orange-500 ml-2"></i>
                                من تاريخ
                            </label>
                            <input type="date" name="date_from" value="{{ $filters['date_from'] }}" 
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 text-sm">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar-check text-orange-500 ml-2"></i>
                                إلى تاريخ
                            </label>
                            <input type="date" name="date_to" value="{{ $filters['date_to'] }}" 
                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-seedling text-emerald-500 ml-2"></i>
                                تركيز الجذر
                            </label>
                            <select name="root_focus" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all duration-300 text-sm">
                                <option value="">جميع الجذور</option>
                                <option value="jawhar" {{ $filters['root_focus'] == 'jawhar' ? 'selected' : '' }}>جَوهر مهيمن</option>
                                <option value="zihn" {{ $filters['root_focus'] == 'zihn' ? 'selected' : '' }}>ذِهن مهيمن</option>
                                <option value="waslat" {{ $filters['root_focus'] == 'waslat' ? 'selected' : '' }}>وَصلات مهيمن</option>
                                <option value="roaya" {{ $filters['root_focus'] == 'roaya' ? 'selected' : '' }}>رُؤية مهيمن</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-chart-bar text-red-500 ml-2"></i>
                                نطاق الدرجات
                            </label>
                            <select name="score_range" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-300 text-sm">
                                <option value="">جميع الدرجات</option>
                                <option value="high" {{ $filters['score_range'] == 'high' ? 'selected' : '' }}>أداء عالي (80%+)</option>
                                <option value="medium" {{ $filters['score_range'] == 'medium' ? 'selected' : '' }}>أداء متوسط (60-80%)</option>
                                <option value="low" {{ $filters['score_range'] == 'low' ? 'selected' : '' }}>يحتاج دعم (<60%)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-layer-group text-teal-500 ml-2"></i>
                                تجميع البيانات حسب
                            </label>
                            <select name="group_by" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-300 text-sm">
                                <option value="quiz" {{ $filters['group_by'] == 'quiz' ? 'selected' : '' }}>الاختبار</option>
                                <option value="student" {{ $filters['group_by'] == 'student' ? 'selected' : '' }}>اسم الطالب</option>
                                <option value="date" {{ $filters['group_by'] == 'date' ? 'selected' : '' }}>التاريخ</option>
                                <option value="root" {{ $filters['group_by'] == 'root' ? 'selected' : '' }}>أداء الجذور</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <button type="submit" class="flex-1 sm:flex-initial bg-gradient-to-r from-purple-600 to-blue-600 text-white px-8 py-4 rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all duration-300 button-glow font-semibold">
                    <i class="fas fa-search ml-2"></i>
                    تطبيق الفلاتر
                </button>
                <a href="{{ route('reports.index') }}" class="flex-1 sm:flex-initial bg-gradient-to-r from-gray-300 to-gray-400 text-gray-700 px-8 py-4 rounded-xl hover:from-gray-400 hover:to-gray-500 transition-all duration-300 button-glow text-center font-semibold">
                    <i class="fas fa-refresh ml-2"></i>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- Quick Insights -->
    <div class="glass-morphism rounded-2xl p-8 shadow-lg mb-8 animate-fade-in-up">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-6 text-center">
            <div class="metric-card p-4 rounded-xl transition-all duration-300">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $quickInsights['total_quizzes'] }}</div>
                <div class="text-sm text-gray-600">📊 إجمالي الاختبارات</div>
                <div class="text-xs text-gray-500 mt-1">({{ $quickInsights['quizzes_with_results'] }} مع نتائج)</div>
            </div>
            <div class="metric-card p-4 rounded-xl transition-all duration-300">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $quickInsights['unique_students'] }}</div>
                <div class="text-sm text-gray-600">👥 طلاب فريدون</div>
            </div>
            <div class="metric-card p-4 rounded-xl transition-all duration-300">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $quickInsights['avg_score'] }}%</div>
                <div class="text-sm text-gray-600">📈 متوسط الدرجة</div>
            </div>
            <div class="metric-card p-4 rounded-xl transition-all duration-300">
                <div class="text-3xl font-bold text-orange-600 mb-2">
                    @switch($quickInsights['most_used_root'])
                        @case('jawhar') جَوهر @break
                        @case('zihn') ذِهن @break
                        @case('waslat') وَصلات @break
                        @case('roaya') رُؤية @break
                    @endswitch
                </div>
                <div class="text-sm text-gray-600">🎯 أكثر الجذور استخداماً</div>
            </div>
            <div class="metric-card p-4 rounded-xl transition-all duration-300">
                <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $quickInsights['active_period'] }}</div>
                <div class="text-sm text-gray-600">📅 فترة النشاط</div>
            </div>
            <div class="metric-card p-4 rounded-xl transition-all duration-300">
                <div class="text-3xl font-bold text-red-600 mb-2">{{ $quickInsights['peak_day'] }}</div>
                <div class="text-sm text-gray-600">🔥 يوم الذروة</div>
            </div>
        </div>
    </div>

    <!-- Roots Performance Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- 4-Roots Model Chart -->
        <div class="glass-morphism rounded-2xl p-6 shadow-lg card-hover animate-slide-in-right">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">نموذج الجذور الأربعة</h2>
            <div class="relative">
                <canvas id="rootsChart" width="400" height="400"></canvas>
            </div>
        </div>

        <!-- Performance Trends -->
        <div class="glass-morphism rounded-2xl p-6 shadow-lg card-hover animate-slide-in-right">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">📈 اتجاهات الأداء</h2>
            <div class="space-y-6">
                @foreach($rootPerformance as $root => $data)
                <div class="group">
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-semibold text-lg">
                            @switch($root)
                                @case('jawhar') جَوهر @break
                                @case('zihn') ذِهن @break
                                @case('waslat') وَصلات @break
                                @case('roaya') رُؤية @break
                            @endswitch
                        </span>
                        <span class="text-sm flex items-center">
                            <span class="text-2xl font-bold">{{ $data['average'] }}%</span>
                            @if($data['trend'] > 0)
                                <i class="fas fa-arrow-up text-green-500 mr-2 icon-bounce"></i>
                                <span class="text-green-500 font-semibold">+{{ $data['trend'] }}%</span>
                            @elseif($data['trend'] < 0)
                                <i class="fas fa-arrow-down text-red-500 mr-2 icon-bounce"></i>
                                <span class="text-red-500 font-semibold">{{ $data['trend'] }}%</span>
                            @else
                                <i class="fas fa-minus text-gray-500 mr-2"></i>
                            @endif
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 progress-bar overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-3 rounded-full transition-all duration-1000 ease-out" 
                             style="width: {{ $data['average'] }}%"></div>
                    </div>
                </div>
                @endforeach

                <div class="mt-8 p-6 bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl border border-purple-100">
                    <h3 class="font-bold text-lg mb-4 text-purple-800">💡 الملاحظات الذكية:</h3>
                    <ul class="text-sm space-y-3">
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full ml-3"></span>
                            التحسن الشامل: <span class="font-bold text-green-600">+{{ $performanceTrends['monthly_change'] }}%</span> هذا الشهر
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full ml-3"></span>
                            أفضل سلسلة اختبارات: <span class="font-bold">"{{ $performanceTrends['best_quiz_series']['title'] }}"</span> (<span class="text-blue-600">{{ $performanceTrends['best_quiz_series']['average'] }}%</span> متوسط)
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-purple-500 rounded-full ml-3"></span>
                            الطلاب الأكثر مشاركة: <span class="font-bold text-purple-600">{{ $performanceTrends['engaged_students'] }}</span> أكملوا 5+ اختبارات
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Progress View (if searching for specific student) -->
    @if($studentProgress && count($studentProgress) > 0)
    <div class="glass-morphism rounded-2xl p-6 shadow-lg mb-8 card-hover animate-fade-in-up">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
            📊 مسار تقدم الطالب: <span class="text-purple-600">{{ $studentProgress['student_name'] }}</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border border-blue-200 metric-card">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $studentProgress['total_quizzes'] }}</div>
                <div class="text-sm text-blue-700">إجمالي الاختبارات</div>
            </div>
            <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl border border-green-200 metric-card">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    @if($studentProgress['improvement'] > 0) +{{ $studentProgress['improvement'] }}% @else {{ $studentProgress['improvement'] }}% @endif
                </div>
                <div class="text-sm text-green-700">نسبة التحسن</div>
            </div>
            <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl border border-purple-200 metric-card">
                <div class="text-3xl font-bold text-purple-600 mb-2">
                    @if($studentProgress['improvement'] > 10) ممتاز @elseif($studentProgress['improvement'] > 5) جيد @else متوسط @endif
                </div>
                <div class="text-sm text-purple-700">مستوى التقدم</div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-right font-semibold">التاريخ</th>
                        <th class="px-6 py-4 text-right font-semibold">الاختبار</th>
                        <th class="px-6 py-4 text-right font-semibold">الإجمالي</th>
                        <th class="px-6 py-4 text-right font-semibold">جَوهر</th>
                        <th class="px-6 py-4 text-right font-semibold">ذِهن</th>
                        <th class="px-6 py-4 text-right font-semibold">وَصلات</th>
                        <th class="px-6 py-4 text-right font-semibold">رُؤية</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentProgress['timeline'] as $item)
                    <tr class="border-b table-row-hover">
                        <td class="px-6 py-3">{{ $item['date']->format('d.m.Y') }}</td>
                        <td class="px-6 py-3 font-medium">{{ $item['quiz'] }}</td>
                        <td class="px-6 py-3 font-bold text-lg">{{ $item['total_score'] }}%</td>
                        <td class="px-6 py-3">{{ $item['jawhar'] }}%</td>
                        <td class="px-6 py-3">{{ $item['zihn'] }}%</td>
                        <td class="px-6 py-3">{{ $item['waslat'] }}%</td>
                        <td class="px-6 py-3">{{ $item['roaya'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Main Data Table -->
    <div class="glass-morphism rounded-2xl shadow-lg animate-fade-in-up">
        <div class="px-8 py-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">
                @switch($filters['group_by'])
                    @case('student') تجميع حسب الطلاب @break
                    @case('date') تجميع حسب التاريخ @break
                    @case('root') تجميع حسب الجذور @break
                    @default تجميع حسب الاختبارات @break
                @endswitch
            </h2>
            <div class="flex space-x-3">
                <button id="analyze-selected-btn" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-2 rounded-xl text-sm hover:from-purple-700 hover:to-purple-800 transition-all duration-300 button-glow opacity-50" disabled>
                    <i class="fas fa-chart-line ml-2"></i>تحليل المحدد (<span id="selected-count">0</span>)
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-right">
                            <input type="checkbox" id="select-all" class="rounded focus:ring-purple-500">
                        </th>
                        @if($filters['group_by'] == 'student')
                            <th class="px-6 py-4 text-right">
                                <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="name">
                                    اسم الطالب
                                    <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'name' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                                </button>
                            </th>
                            <th class="px-6 py-4 text-right">
                                <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="quiz_count">
                                    الاختبارات
                                    <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'quiz_count' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                                </button>
                            </th>
                        @elseif($filters['group_by'] == 'date')
                            <th class="px-6 py-4 text-right">
                                <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="date">
                                    التاريخ
                                    <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'date' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                                </button>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">النشاط</th>
                        @elseif($filters['group_by'] == 'root')
                            <th class="px-6 py-4 text-right">
                                <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="root">
                                    الجذر
                                    <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'root' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                                </button>
                            </th>
                            <th class="px-6 py-4 text-right">
                                <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="dominant_count">
                                    الهيمنة
                                    <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'dominant_count' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                                </button>
                            </th>
                        @else
                            <th class="px-6 py-4 text-right">
                                <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="title">
                                    عنوان الاختبار
                                    <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'title' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                                </button>
                            </th>
                            <th class="px-6 py-4 text-right">
                                <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="created_at">
                                    التاريخ
                                    <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'created_at' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                                </button>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">رمز PIN</th>
                        @endif
                        <th class="px-6 py-4 text-right">
                            <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="student_count">
                                الطلاب
                                <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'student_count' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="avg_score">
                                المتوسط
                                <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'avg_score' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="jawhar_avg">
                                جَوهر
                                <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'jawhar_avg' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="zihn_avg">
                                ذِهن
                                <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'zihn_avg' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="waslat_avg">
                                وَصلات
                                <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'waslat_avg' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <button type="button" class="sortable-header group flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-purple-600 transition-colors duration-200" data-sort="roaya_avg">
                                رُؤية
                                <i class="fas fa-sort mr-2 group-hover:text-purple-600 {{ $filters['sort_by'] == 'roaya_avg' ? ($filters['sort_direction'] == 'asc' ? 'fa-sort-up text-purple-600' : 'fa-sort-down text-purple-600') : '' }}"></i>
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($groupedData as $index => $group)
                    <tr class="table-row-hover">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="group-checkbox rounded focus:ring-purple-500" value="{{ $index }}">
                        </td>
                        @if($filters['group_by'] == 'student')
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $group['name'] }}</td>
                            <td class="px-6 py-4">{{ $group['quiz_count'] }} اختبار</td>
                        @elseif($filters['group_by'] == 'date')
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ \Carbon\Carbon::parse($group['date'])->format('d.m.Y') }}</td>
                            <td class="px-6 py-4">{{ $group['quiz_count'] }} اختبار، {{ $group['student_count'] }} طالب</td>
                        @elseif($filters['group_by'] == 'root')
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                @switch($group['root'])
                                    @case('jawhar') جَوهر @break
                                    @case('zihn') ذِهن @break
                                    @case('waslat') وَصلات @break
                                    @case('roaya') رُؤية @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4">{{ $group['dominant_count'] }} حالة</td>
                        @else
                            <td class="px-6 py-4">
                                <a href="/roots/results/quiz/{{ $group['results']->first()->quiz_id }}" 
                                   class="font-semibold text-gray-900 hover:text-purple-600 transition-colors duration-200">
                                    {{ $group['title'] }}
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ $group['date']->format('d.m.Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-lg text-xs font-mono">{{ $group['pin'] }}</span>
                            </td>
                        @endif
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $group['student_count'] ?? $group['quiz_count'] ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-lg">{{ $group['avg_score'] }}%</td>
                        <td class="px-6 py-4 font-semibold">{{ $group['jawhar_avg'] }}%</td>
                        <td class="px-6 py-4 font-semibold">{{ $group['zihn_avg'] }}%</td>
                        <td class="px-6 py-4 font-semibold">{{ $group['waslat_avg'] }}%</td>
                        <td class="px-6 py-4 font-semibold">{{ $group['roaya_avg'] }}%</td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 details-btn font-semibold transition-colors duration-200" data-index="{{ $index }}">
                                عرض التفاصيل
                            </button>
                        </td>
                    </tr>
                    <!-- Expandable details -->
                    <tr class="details-row hidden bg-gradient-to-r from-gray-50 to-blue-50" id="details-{{ $index }}">
                        <td colspan="11" class="px-6 py-6">
                            <div class="max-h-64 overflow-y-auto">
                                <h4 class="font-bold text-lg mb-4 text-gray-800">تفاصيل الطلاب:</h4>
                                <div class="grid gap-2">
                                    @foreach($group['results'] as $result)
                                    <div class="flex justify-between items-center py-3 px-4 bg-white rounded-lg border border-gray-200 table-row-hover">
                                        <button class="font-medium text-purple-600 hover:text-purple-800 transition-colors duration-200 cursor-pointer student-result-btn" 
                                                data-student="{{ $result->guest_name }}" 
                                                data-quiz="{{ $result->quiz_id }}"
                                                data-result="{{ $result->id }}">
                                            👤 {{ $result->guest_name }}
                                        </button>
                                        <span class="font-bold text-lg">{{ $result->total_score }}%</span>
                                        <span class="text-xs text-gray-500">{{ $result->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg">لا توجد بيانات للعرض</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js and Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Advanced filters toggle
    const toggleAdvanced = document.getElementById('toggle-advanced');
    const advancedFilters = document.getElementById('advanced-filters');
    const chevronIcon = document.getElementById('chevron-icon');
    
    if (toggleAdvanced) {
        toggleAdvanced.addEventListener('click', function() {
            advancedFilters.classList.toggle('hidden');
            chevronIcon.classList.toggle('rotate-180');
        });
    }

    // Sortable headers functionality
    document.querySelectorAll('.sortable-header').forEach(header => {
        header.addEventListener('click', function() {
            const sortBy = this.dataset.sort;
            const currentSort = '{{ $filters["sort_by"] ?? "" }}';
            const currentDirection = '{{ $filters["sort_direction"] ?? "desc" }}';
            
            let newDirection = 'desc';
            if (sortBy === currentSort) {
                newDirection = currentDirection === 'desc' ? 'asc' : 'desc';
            }
            
            // Get current URL parameters
            const url = new URL(window.location);
            url.searchParams.set('sort_by', sortBy);
            url.searchParams.set('sort_direction', newDirection);
            
            // Navigate to new URL
            window.location.href = url.toString();
        });
    });

    // 4-Roots Model Chart
    const ctx = document.getElementById('rootsChart');
    if (ctx) {
        const rootData = @json($rootPerformance);
        
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['جَوهر', 'ذِهن', 'وَصلات', 'رُؤية'],
                datasets: [{
                    label: 'متوسط الأداء',
                    data: [
                        rootData.jawhar?.average || 0,
                        rootData.zihn?.average || 0,
                        rootData.waslat?.average || 0,
                        rootData.roaya?.average || 0
                    ],
                    backgroundColor: 'rgba(139, 92, 246, 0.15)',
                    borderColor: 'rgba(139, 92, 246, 1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(139, 92, 246, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 8,
                    pointHoverRadius: 10
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            color: '#6B7280'
                        },
                        grid: {
                            color: '#E5E7EB'
                        },
                        angleLines: {
                            color: '#E5E7EB'
                        },
                        pointLabels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#374151'
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    // Details toggle functionality
    document.querySelectorAll('.details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.dataset.index;
            const detailsRow = document.getElementById(`details-${index}`);
            if (detailsRow) {
                detailsRow.classList.toggle('hidden');
                this.textContent = detailsRow.classList.contains('hidden') ? 'عرض التفاصيل' : 'إخفاء التفاصيل';
            }
        });
    });

    // Student result buttons
    document.querySelectorAll('.student-result-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const studentName = this.dataset.student;
            const quizId = this.dataset.quiz;
            const resultId = this.dataset.result;
            showStudentResults(studentName, quizId, resultId);
        });
    });

    // Bulk selection functionality
    const selectAll = document.getElementById('select-all');
    const groupCheckboxes = document.querySelectorAll('.group-checkbox');
    const analyzeSelectedBtn = document.getElementById('analyze-selected-btn');
    const selectedCountSpan = document.getElementById('selected-count');

    if (selectAll && analyzeSelectedBtn) {
        selectAll.addEventListener('change', function() {
            groupCheckboxes.forEach(cb => cb.checked = this.checked);
            updateAnalyzeButton();
        });

        groupCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateAnalyzeButton);
        });

        function updateAnalyzeButton() {
            const checkedBoxes = document.querySelectorAll('.group-checkbox:checked');
            analyzeSelectedBtn.disabled = checkedBoxes.length === 0;
            selectedCountSpan.textContent = checkedBoxes.length;
            selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < groupCheckboxes.length;
            selectAll.checked = checkedBoxes.length === groupCheckboxes.length;
            
            if (checkedBoxes.length > 0) {
                analyzeSelectedBtn.classList.remove('opacity-50');
            } else {
                analyzeSelectedBtn.classList.add('opacity-50');
            }
        }

        // Analyze selected items
        analyzeSelectedBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.group-checkbox:checked');
            if (checkedBoxes.length === 0) return;
            
            let totalScore = 0;
            let totalCount = 0;
            let rootTotals = { jawhar: 0, zihn: 0, waslat: 0, roaya: 0 };
            
            checkedBoxes.forEach(cb => {
                const row = cb.closest('tr');
                const cells = row.querySelectorAll('td');
                
                // Find score cells based on table structure
                const scoreCell = cells[cells.length - 6]; // المتوسط
                const jawharCell = cells[cells.length - 5]; // جَوهر
                const zihnCell = cells[cells.length - 4]; // ذِهن
                const waslatCell = cells[cells.length - 3]; // وَصلات
                const roayaCell = cells[cells.length - 2]; // رُؤية
                
                if (scoreCell) {
                    const score = parseFloat(scoreCell.textContent.replace('%', '')) || 0;
                    totalScore += score;
                    totalCount++;
                    
                    rootTotals.jawhar += parseFloat(jawharCell.textContent.replace('%', '')) || 0;
                    rootTotals.zihn += parseFloat(zihnCell.textContent.replace('%', '')) || 0;
                    rootTotals.waslat += parseFloat(waslatCell.textContent.replace('%', '')) || 0;
                    rootTotals.roaya += parseFloat(roayaCell.textContent.replace('%', '')) || 0;
                }
            });
            
            const avgScore = totalCount > 0 ? (totalScore / totalCount).toFixed(1) : 0;
            const avgJawhar = totalCount > 0 ? (rootTotals.jawhar / totalCount).toFixed(1) : 0;
            const avgZihn = totalCount > 0 ? (rootTotals.zihn / totalCount).toFixed(1) : 0;
            const avgWaslat = totalCount > 0 ? (rootTotals.waslat / totalCount).toFixed(1) : 0;
            const avgRoaya = totalCount > 0 ? (rootTotals.roaya / totalCount).toFixed(1) : 0;
            
            showAnalyticsModal({
                count: checkedBoxes.length,
                avgScore: avgScore,
                roots: {
                    jawhar: avgJawhar,
                    zihn: avgZihn,
                    waslat: avgWaslat,
                    roaya: avgRoaya
                }
            });
        });
    }

    // Student Results Modal
    function showStudentResults(studentName, quizId, resultId) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 glass-morphism max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">📊 نتائج الطالب: ${studentName}</h3>
                    <button class="close-modal text-gray-500 hover:text-gray-700 text-2xl">×</button>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-xl">
                            <div class="text-2xl font-bold text-blue-600">1</div>
                            <div class="text-sm text-blue-700">إجمالي الاختبارات</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-xl">
                            <div class="text-2xl font-bold text-green-600">85%</div>
                            <div class="text-sm text-green-700">متوسط الدرجة</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-xl">
                            <div class="text-2xl font-bold text-purple-600">A</div>
                            <div class="text-sm text-purple-700">التقدير</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-xl">
                            <div class="text-2xl font-bold text-orange-600">جَوهر</div>
                            <div class="text-sm text-orange-700">أقوى جذر</div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="font-bold text-lg mb-4">تفصيل الجذور:</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-lg">
                                <div class="flex justify-between mb-2">
                                    <span class="font-semibold">جَوهر</span>
                                    <span class="font-bold text-purple-600">90%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <div class="flex justify-between mb-2">
                                    <span class="font-semibold">ذِهن</span>
                                    <span class="font-bold text-blue-600">85%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <div class="flex justify-between mb-2">
                                    <span class="font-semibold">وَصلات</span>
                                    <span class="font-bold text-green-600">80%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 80%"></div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <div class="flex justify-between mb-2">
                                    <span class="font-semibold">رُؤية</span>
                                    <span class="font-bold text-orange-600">85%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-600 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <a href="/roots/results/${resultId}" 
                           class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-4 rounded-xl text-center hover:from-green-700 hover:to-green-800 transition-all duration-300">
                            <i class="fas fa-eye ml-2"></i>
                            عرض النتيجة الكاملة
                        </a>
                        <a href="/roots/results/${resultId}" 
                           class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 px-4 rounded-xl text-center hover:from-purple-700 hover:to-blue-700 transition-all duration-300">
                            <i class="fas fa-chart-line ml-2"></i>
                            تقرير الطالب المفصل
                        </a>
                    </div>
                    
                    <div class="flex justify-center">
                        <button class="close-modal bg-gray-300 text-gray-700 py-3 px-6 rounded-xl hover:bg-gray-400 transition-all duration-300">
                            <i class="fas fa-times ml-2"></i>
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Close modal functionality
        modal.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => modal.remove());
        });
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    // Analytics Modal
    function showAnalyticsModal(data) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 glass-morphism">
                <h3 class="text-2xl font-bold mb-6 text-gray-900">📊 تحليل العناصر المحددة</h3>
                <div class="space-y-4">
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <div class="text-3xl font-bold text-blue-600">${data.count}</div>
                        <div class="text-sm text-blue-700">عنصر محدد</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <div class="text-3xl font-bold text-green-600">${data.avgScore}%</div>
                        <div class="text-sm text-green-700">متوسط الدرجة</div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <div class="text-lg font-bold text-purple-600">${data.roots.jawhar}%</div>
                            <div class="text-xs text-purple-700">جَوهر</div>
                        </div>
                        <div class="text-center p-3 bg-indigo-50 rounded-lg">
                            <div class="text-lg font-bold text-indigo-600">${data.roots.zihn}%</div>
                            <div class="text-xs text-indigo-700">ذِهن</div>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-lg">
                            <div class="text-lg font-bold text-orange-600">${data.roots.waslat}%</div>
                            <div class="text-xs text-orange-700">وَصلات</div>
                        </div>
                        <div class="text-center p-3 bg-pink-50 rounded-lg">
                            <div class="text-lg font-bold text-pink-600">${data.roots.roaya}%</div>
                            <div class="text-xs text-pink-700">رُؤية</div>
                        </div>
                    </div>
                </div>
                <button class="close-analytics w-full mt-6 bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all duration-300">
                    إغلاق
                </button>
            </div>
        `;
        document.body.appendChild(modal);
        
        modal.querySelector('.close-analytics').addEventListener('click', () => modal.remove());
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    // Make functions available globally
    window.showStudentResults = showStudentResults;
    window.showAnalyticsModal = showAnalyticsModal;

    // Form loading animation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري التحميل...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endsection