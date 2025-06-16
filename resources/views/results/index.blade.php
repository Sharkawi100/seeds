@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 mb-2 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            📊 نتائج الاختبارات
                        </h1>
                        <p class="text-lg text-gray-600">تتبع أداء الطلاب وتحليل النتائج</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-indigo-600">{{ $results->count() }}</div>
                        <div class="text-sm text-gray-500">إجمالي النتائج</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-green-400 to-green-600 rounded-xl">
                        <i class="fas fa-trophy text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $results->where('total_score', '>=', 70)->count() }}</div>
                        <div class="text-sm text-gray-600">نتائج ممتازة</div>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <div class="text-2xl font-bold text-gray-900">{{ round($results->avg('total_score')) }}%</div>
                        <div class="text-sm text-gray-600">متوسط الدرجات</div>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $results->groupBy('user_id')->count() }}</div>
                        <div class="text-sm text-gray-600">عدد الطلاب</div>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-orange-400 to-orange-600 rounded-xl">
                        <i class="fas fa-calendar text-white text-2xl"></i>
                    </div>
                    <div class="mr-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $results->whereDate('created_at', today())->count() }}</div>
                        <div class="text-sm text-gray-600">اليوم</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="ابحث عن طالب أو اختبار..."
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاختبار</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">الدرجة</label>
                    <select name="score_range" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                        <option value="">جميع الدرجات</option>
                        <option value="excellent" {{ request('score_range') == 'excellent' ? 'selected' : '' }}>ممتاز (90-100%)</option>
                        <option value="good" {{ request('score_range') == 'good' ? 'selected' : '' }}>جيد (70-89%)</option>
                        <option value="fair" {{ request('score_range') == 'fair' ? 'selected' : '' }}>مقبول (50-69%)</option>
                        <option value="poor" {{ request('score_range') == 'poor' ? 'selected' : '' }}>ضعيف (أقل من 50%)</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($results as $result)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <!-- Result Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($result->guest_name ?: ($result->user ? $result->user->name : 'ضيف'), 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $result->guest_name ?: ($result->user ? $result->user->name : 'ضيف') }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ $result->quiz->title }}</p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-2xl font-bold {{ $result->total_score >= 70 ? 'text-green-600' : ($result->total_score >= 50 ? 'text-orange-600' : 'text-red-600') }}">
                            {{ $result->total_score }}%
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $result->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <!-- Score Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>النتيجة</span>
                        <span>{{ $result->total_score }}/100</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full bg-gradient-to-r {{ $result->total_score >= 70 ? 'from-green-400 to-green-600' : ($result->total_score >= 50 ? 'from-orange-400 to-orange-600' : 'from-red-400 to-red-600') }} transition-all duration-500" 
                             style="width: {{ $result->total_score }}%"></div>
                    </div>
                </div>

                <!-- Roots Performance -->
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
                    <div class="text-center">
                        <div class="text-lg mb-1">{{ $root['icon'] }}</div>
                        <div class="text-xs text-gray-600 mb-1">{{ $root['name'] }}</div>
                        <div class="text-sm font-bold text-{{ $root['color'] }}-600">
                            {{ $scores[$key] ?? 0 }}%
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('results.show', $result) }}" 
                       class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-center py-2 rounded-lg font-medium hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-eye mr-1"></i>
                        عرض التفاصيل
                    </a>
                    
                    @if($result->quiz->user_id == auth()->id())
                    <a href="{{ route('results.quiz', $result->quiz) }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-chart-bar"></i>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد نتائج</h3>
                    <p class="text-gray-500">لم يتم العثور على نتائج تطابق المعايير المحددة</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($results instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-8 flex justify-center">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-4">
                {{ $results->links() }}
            </div>
        </div>
        @endif
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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 6px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 6px;
}
</style>
@endpush