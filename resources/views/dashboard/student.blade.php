{{-- Student Dashboard --}}
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
<style>
    body, * {
        font-family: 'Tajawal', sans-serif !important;
    }
    .student-card {
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }
    .student-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .student-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(0, 0, 0, 0.5);
    }
    .student-card:hover::before {
        opacity: 1;
    }
    .progress-ring {
        transform: rotate(-90deg);
    }
    .badge-shine {
        position: relative;
        overflow: hidden;
    }
    .badge-shine::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }
    @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }
    .float-animation {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endpush

<!-- Student Progress Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Completed Quizzes -->
    <div class="student-card rounded-2xl p-6 text-white">
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-xl flex items-center justify-center shadow-xl">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="text-blue-300 text-sm font-medium badge-shine px-3 py-1 bg-blue-400/20 rounded-full">
                <i class="fas fa-star ml-1"></i>
                إنجاز
            </div>
        </div>
        <div class="text-4xl font-black mb-1">{{ Auth::user()->results->count() }}</div>
        <div class="text-gray-200 font-medium">اختبار مكتمل</div>
        <div class="mt-3 text-blue-300 text-sm">
            آخر اختبار {{ Auth::user()->results->sortByDesc('created_at')->first()?->created_at->diffForHumans() ?? 'لم تبدأ بعد' }}
        </div>
    </div>
    
    <!-- Average Score -->
    <div class="student-card rounded-2xl p-6 text-white">
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-xl">
                <i class="fas fa-percentage text-2xl"></i>
            </div>
            @if(Auth::user()->results->avg('total_score') >= 80)
            <div class="text-green-400 text-sm font-medium px-3 py-1 bg-green-400/20 rounded-full animate-pulse">
                ممتاز!
            </div>
            @endif
        </div>
        <div class="text-4xl font-black mb-1">{{ number_format(Auth::user()->results->avg('total_score') ?? 0, 1) }}%</div>
        <div class="text-gray-200 font-medium">متوسط النتائج</div>
        <div class="mt-3">
            <div class="w-full bg-gray-700 rounded-full h-2">
                <div class="bg-gradient-to-r from-green-400 to-emerald-600 h-2 rounded-full transition-all duration-1000" 
                     style="width: {{ Auth::user()->results->avg('total_score') ?? 0 }}%"></div>
            </div>
        </div>
    </div>
    
    <!-- Recent Improvement -->
    <div class="student-card rounded-2xl p-6 text-white">
        @php
            $lastTwo = Auth::user()->results->sortByDesc('created_at')->take(2);
            $improvement = $lastTwo->count() == 2 ? 
                $lastTwo->first()->total_score - $lastTwo->last()->total_score : 0;
        @endphp
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 bg-gradient-to-br {{ $improvement >= 0 ? 'from-purple-400 to-pink-600' : 'from-orange-400 to-red-600' }} rounded-xl flex items-center justify-center shadow-xl">
                <i class="fas {{ $improvement >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-2xl"></i>
            </div>
            @if($improvement > 10)
            <div class="text-purple-400 text-sm font-medium px-3 py-1 bg-purple-400/20 rounded-full">
                تقدم رائع!
            </div>
            @endif
        </div>
        <div class="text-4xl font-black mb-1">{{ $improvement > 0 ? '+' : '' }}{{ number_format($improvement, 1) }}%</div>
        <div class="text-gray-200 font-medium">التحسن الأخير</div>
        <div class="mt-3 text-{{ $improvement >= 0 ? 'purple' : 'orange' }}-300 text-sm">
            {{ $improvement >= 0 ? 'أنت في الطريق الصحيح!' : 'لا تيأس، حاول مرة أخرى!' }}
        </div>
    </div>
    
    <!-- Weekly Streak -->
    <div class="student-card rounded-2xl p-6 text-white">
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-red-600 rounded-xl flex items-center justify-center shadow-xl animate-pulse">
                <i class="fas fa-fire text-2xl"></i>
            </div>
            <div class="text-orange-300 text-sm font-medium">
                <i class="fas fa-calendar-week ml-1"></i>
                هذا الأسبوع
            </div>
        </div>
        <div class="text-4xl font-black mb-1">{{ Auth::user()->results->where('created_at', '>=', now()->subDays(7))->count() }}</div>
        <div class="text-gray-200 font-medium">اختبارات هذا الأسبوع</div>
        <div class="mt-3 flex gap-1">
            @for($i = 0; $i < 7; $i++)
                @php
                    $date = now()->subDays(6 - $i);
                    $hasActivity = Auth::user()->results->where('created_at', '>=', $date->startOfDay())->where('created_at', '<=', $date->endOfDay())->count() > 0;
                @endphp
                <div class="flex-1 h-8 rounded {{ $hasActivity ? 'bg-orange-500' : 'bg-gray-700' }} relative group">
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-xs text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                        {{ $date->format('D') }}
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Quick Actions for Students -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Enter Quiz PIN -->
    <a href="{{ route('home') }}#pin-section" class="group">
        <div class="student-card rounded-2xl p-8 text-center relative">
            <div class="absolute top-4 left-4 w-20 h-20 bg-blue-500 rounded-full filter blur-2xl opacity-20 float-animation"></div>
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform relative z-10">
                <i class="fas fa-keyboard text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">أدخل رمز اختبار</h3>
            <p class="text-gray-200 text-sm">ابدأ اختباراً جديداً برمز PIN</p>
            <div class="mt-4 inline-flex items-center gap-2 text-blue-300 group-hover:text-blue-200 transition">
                <span class="font-medium">ابدأ الآن</span>
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition"></i>
            </div>
        </div>
    </a>
    
    <!-- My Results -->
    <a href="{{ route('results.index') }}" class="group">
        <div class="student-card rounded-2xl p-8 text-center relative">
            <div class="absolute top-4 left-4 w-20 h-20 bg-green-500 rounded-full filter blur-2xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform relative z-10">
                <i class="fas fa-chart-pie text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">نتائجي</h3>
            <p class="text-gray-200 text-sm">شاهد تقدمك وتحليل الأداء</p>
            <div class="mt-4 inline-flex items-center gap-2 text-green-300 group-hover:text-green-200 transition">
                <span class="font-medium">عرض النتائج</span>
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition"></i>
            </div>
        </div>
    </a>
    
    <!-- Demo Quiz -->
    <a href="{{ route('quiz.demo') }}" class="group">
        <div class="student-card rounded-2xl p-8 text-center relative">
            <div class="absolute top-4 left-4 w-20 h-20 bg-purple-500 rounded-full filter blur-2xl opacity-20 float-animation" style="animation-delay: 4s;"></div>
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform relative z-10">
                <i class="fas fa-gamepad text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">اختبار تجريبي</h3>
            <p class="text-gray-200 text-sm">جرب نموذج جُذور بدون تسجيل</p>
            <div class="mt-4 inline-flex items-center gap-2 text-purple-300 group-hover:text-purple-200 transition">
                <span class="font-medium">جرب الآن</span>
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition"></i>
            </div>
        </div>
    </a>
</div>

<!-- Progress Analysis -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Roots Progress -->
    <div class="lg:col-span-2 student-card rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">🌱 تقدمي في الجذور الأربعة</h2>
        @php
            $latestResult = Auth::user()->results->sortByDesc('created_at')->first();
            $averageScores = [
    'jawhar' => Auth::user()->results->avg(function($r) { return $r->scores['jawhar'] ?? 0; }) ?? 0,
    'zihn' => Auth::user()->results->avg(function($r) { return $r->scores['zihn'] ?? 0; }) ?? 0,
    'waslat' => Auth::user()->results->avg(function($r) { return $r->scores['waslat'] ?? 0; }) ?? 0,
    'roaya' => Auth::user()->results->avg(function($r) { return $r->scores['roaya'] ?? 0; }) ?? 0
];
        @endphp
        
        @if($latestResult)
            <x-juzoor-chart :scores="$averageScores" size="large" />
            
            <!-- Root Details -->
            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="text-center p-4 bg-red-500/20 rounded-xl border border-red-500/40 backdrop-blur">
                    <div class="text-3xl mb-2">🎯</div>
                    <h4 class="text-red-300 font-bold">جَوهر</h4>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($averageScores['jawhar'], 0) }}%</p>
                </div>
                <div class="text-center p-4 bg-teal-500/20 rounded-xl border border-teal-500/40 backdrop-blur">
                    <div class="text-3xl mb-2">🧠</div>
                    <h4 class="text-teal-300 font-bold">ذِهن</h4>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($averageScores['zihn'], 0) }}%</p>
                </div>
                <div class="text-center p-4 bg-yellow-500/20 rounded-xl border border-yellow-500/40 backdrop-blur">
                    <div class="text-3xl mb-2">🔗</div>
                    <h4 class="text-yellow-300 font-bold">وَصلات</h4>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($averageScores['waslat'], 0) }}%</p>
                </div>
                <div class="text-center p-4 bg-purple-500/20 rounded-xl border border-purple-500/40 backdrop-blur">
                    <div class="text-3xl mb-2">👁️</div>
                    <h4 class="text-purple-300 font-bold">رُؤية</h4>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($averageScores['roaya'], 0) }}%</p>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4 opacity-50">🌱</div>
                <p class="text-xl text-gray-300">لم تأخذ أي اختبار بعد!</p>
                <p class="text-gray-400 mt-2">ابدأ رحلتك التعليمية اليوم</p>
                <a href="{{ route('home') }}#pin-section" class="inline-flex items-center gap-2 mt-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-play"></i>
                    ابدأ أول اختبار
                </a>
            </div>
        @endif
    </div>
    
    <!-- Achievements & Tips -->
    <div class="student-card rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-trophy ml-3 text-yellow-400"></i>
            إنجازاتي
        </h2>
        
        <!-- Achievement Badges -->
        <div class="space-y-4 mb-6">
            @if(Auth::user()->results->count() >= 1)
            <div class="flex items-center gap-3 p-3 bg-yellow-500/20 rounded-xl border border-yellow-500/40 backdrop-blur">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-star text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-yellow-300 font-bold">البداية الموفقة</p>
                    <p class="text-gray-200 text-sm">أكملت أول اختبار!</p>
                </div>
            </div>
            @endif
            
            @if(Auth::user()->results->count() >= 5)
            <div class="flex items-center gap-3 p-3 bg-blue-500/20 rounded-xl border border-blue-500/40 backdrop-blur">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-600 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-medal text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-blue-300 font-bold">مثابر</p>
                    <p class="text-gray-200 text-sm">أكملت 5 اختبارات!</p>
                </div>
            </div>
            @endif
            
            @if(Auth::user()->results->max('total_score') >= 90)
            <div class="flex items-center gap-3 p-3 bg-purple-500/20 rounded-xl border border-purple-500/40 backdrop-blur">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-600 rounded-full flex items-center justify-center badge-shine shadow-lg">
                    <i class="fas fa-crown text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-purple-300 font-bold">متميز</p>
                    <p class="text-gray-200 text-sm">حصلت على 90% أو أكثر!</p>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Next Goal -->
        <div class="p-4 bg-gradient-to-br from-blue-500/25 to-purple-500/25 rounded-xl border border-white/20 backdrop-blur">
            <h4 class="text-white font-bold mb-2">🎯 الهدف التالي</h4>
            <p class="text-gray-200 text-sm">
                @if(Auth::user()->results->count() < 5)
                    أكمل {{ 5 - Auth::user()->results->count() }} اختبارات إضافية لفتح إنجاز "مثابر"!
                @elseif(Auth::user()->results->max('total_score') < 90)
                    احصل على 90% أو أكثر في اختبار واحد لفتح إنجاز "متميز"!
                @else
                    واصل التميز وحافظ على مستواك الرائع! 🌟
                @endif
            </p>
        </div>
    </div>
</div>