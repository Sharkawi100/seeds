{{-- Teacher Dashboard --}}
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
<style>
    body, * {
        font-family: 'Tajawal', sans-serif !important;
    }
    .teacher-card {
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    .teacher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        background: rgba(0, 0, 0, 0.55);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .stat-number {
        background: linear-gradient(135deg, #fff, #e0e0e0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }
    .action-card {
        position: relative;
        overflow: hidden;
    }
    .action-card::before {
        content: '';
        position: absolute;
        top: -100%;
        left: -100%;
        width: 300%;
        height: 300%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: rotate(45deg);
        transition: all 0.6s;
    }
    .action-card:hover::before {
        top: -50%;
        left: -50%;
    }
</style>
@endpush

<!-- Teacher Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- My Quizzes -->
    <div class="teacher-card rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-32 h-32 bg-pink-500 rounded-full filter blur-3xl opacity-20 -translate-x-16 -translate-y-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-pink-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
                <span class="text-pink-300 text-sm font-medium">
                    <i class="fas fa-arrow-up ml-1"></i>
                    12% هذا الشهر
                </span>
            </div>
            <div class="stat-number text-4xl font-black mb-1">{{ Auth::user()->quizzes->count() }}</div>
            <div class="text-gray-200 font-medium">اختبار تم إنشاؤه</div>
        </div>
    </div>
    
    <!-- Total Students -->
    <div class="teacher-card rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-32 h-32 bg-blue-500 rounded-full filter blur-3xl opacity-20 -translate-x-16 -translate-y-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <span class="text-blue-300 text-sm font-medium">
                    <i class="fas fa-circle text-xs ml-1 animate-pulse"></i>
                    نشط الآن
                </span>
            </div>
            <div class="stat-number text-4xl font-black mb-1">{{ Auth::user()->quizzes->sum(function($quiz) { return $quiz->results->count(); }) }}</div>
            <div class="text-gray-200 font-medium">طالب شارك</div>
        </div>
    </div>
    
    <!-- Average Performance -->
    <div class="teacher-card rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-32 h-32 bg-green-500 rounded-full filter blur-3xl opacity-20 -translate-x-16 -translate-y-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <span class="text-green-300 text-sm font-medium">
                    <i class="fas fa-arrow-up ml-1"></i>
                    جيد جداً
                </span>
            </div>
            <div class="stat-number text-4xl font-black mb-1">{{ number_format(Auth::user()->quizzes->flatMap->results->avg('total_score') ?? 0, 1) }}%</div>
            <div class="text-gray-200 font-medium">متوسط الأداء</div>
        </div>
    </div>
    
    <!-- This Week Activity -->
    <div class="teacher-card rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-32 h-32 bg-orange-500 rounded-full filter blur-3xl opacity-20 -translate-x-16 -translate-y-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-red-600 rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                    <i class="fas fa-fire text-2xl"></i>
                </div>
                <span class="text-orange-300 text-sm font-medium">
                    أسبوع نشط!
                </span>
            </div>
            <div class="stat-number text-4xl font-black mb-1">{{ Auth::user()->quizzes->flatMap->results->where('created_at', '>=', now()->subDays(7))->count() }}</div>
            <div class="text-gray-200 font-medium">نشاط هذا الأسبوع</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Create New Quiz -->
    <a href="{{ route('quizzes.create') }}" class="group">
        <div class="teacher-card action-card rounded-2xl p-8 text-center relative">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-plus-circle text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">إنشاء اختبار جديد</h3>
            <p class="text-gray-200 text-sm">صمم تحدي جديد بنموذج جُذور</p>
            <div class="mt-4 inline-flex items-center gap-2 text-purple-300 group-hover:text-purple-200 transition">
                <span class="font-medium">ابدأ الآن</span>
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition"></i>
            </div>
        </div>
    </a>
    
    <!-- My Quizzes -->
    <a href="{{ route('quizzes.index') }}" class="group">
        <div class="teacher-card action-card rounded-2xl p-8 text-center relative">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-folder-open text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">اختباراتي</h3>
            <p class="text-gray-200 text-sm">إدارة وتعديل اختباراتك</p>
            <div class="mt-4 inline-flex items-center gap-2 text-blue-300 group-hover:text-blue-200 transition">
                <span class="font-medium">عرض الكل</span>
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition"></i>
            </div>
        </div>
    </a>
    
    <!-- Student Results -->
    <a href="{{ route('results.index') }}" class="group">
        <div class="teacher-card action-card rounded-2xl p-8 text-center relative">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-chart-bar text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">نتائج الطلاب</h3>
            <p class="text-gray-200 text-sm">تحليل أداء طلابك</p>
            <div class="mt-4 inline-flex items-center gap-2 text-green-300 group-hover:text-green-200 transition">
                <span class="font-medium">عرض التقارير</span>
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition"></i>
            </div>
        </div>
    </a>
</div>

<!-- Recent Activity & Tips -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Quiz Results -->
    <div class="lg:col-span-2 teacher-card rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-clock ml-3 text-blue-400"></i>
            آخر النتائج
        </h2>
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @foreach(Auth::user()->quizzes->flatMap->results->sortByDesc('created_at')->take(10) as $result)
            <div class="flex items-center justify-between p-4 bg-black/40 rounded-xl hover:bg-black/50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ mb_substr($result->user->name ?? 'ض', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-white font-semibold">{{ $result->user->name ?? 'طالب ضيف' }}</p>
                        <p class="text-gray-300 text-sm">{{ $result->quiz->title }}</p>
                    </div>
                </div>
                <div class="text-left">
                    <div class="flex items-center gap-2">
                        <div class="text-2xl font-bold {{ $result->total_score >= 80 ? 'text-green-400' : ($result->total_score >= 60 ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ number_format($result->total_score, 0) }}%
                        </div>
                        @if($result->total_score >= 80)
                            <i class="fas fa-trophy text-yellow-400"></i>
                        @endif
                    </div>
                    <p class="text-gray-400 text-xs">{{ $result->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Teaching Tips -->
    <div class="teacher-card rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-lightbulb ml-3 text-yellow-400"></i>
            نصائح تعليمية
        </h2>
        <div class="space-y-4">
            <div class="p-4 bg-yellow-500/20 border border-yellow-500/40 rounded-xl backdrop-blur">
                <h4 class="text-yellow-300 font-bold mb-2">💡 نصيحة اليوم</h4>
                <p class="text-gray-200 text-sm leading-relaxed">
                    استخدم مزيج من الأسئلة السطحية والعميقة في كل جذر لتقييم شامل. الأسئلة السطحية تبني الثقة، بينما العميقة تحفز التفكير النقدي.
                </p>
            </div>
            
            <div class="p-4 bg-blue-500/20 border border-blue-500/40 rounded-xl backdrop-blur">
                <h4 class="text-blue-300 font-bold mb-2">📊 تحليل سريع</h4>
                <p class="text-gray-200 text-sm leading-relaxed">
                    طلابك يتفوقون في جذر "{{ ['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'][array_keys(Auth::user()->quizzes->flatMap->results->flatMap->scores->groupBy(function($score, $key) { return $key; })->map->average()->sort()->reverse()->toArray())[0] ?? 'jawhar'] ?? 'جَوهر' }}". 
                    ركز على تعزيز الجذور الأخرى.
                </p>
            </div>
            
            <div class="p-4 bg-purple-500/20 border border-purple-500/40 rounded-xl backdrop-blur">
                <h4 class="text-purple-300 font-bold mb-2">🎯 هدف الأسبوع</h4>
                <p class="text-gray-200 text-sm leading-relaxed">
                    حاول إنشاء اختبار يركز على جذر "رُؤية" لتطوير مهارات التطبيق العملي لدى طلابك.
                </p>
            </div>
        </div>
    </div>
</div>