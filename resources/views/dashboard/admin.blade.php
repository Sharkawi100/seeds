{{-- Admin Dashboard --}}
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
<style>
    body, * {
        font-family: 'Tajawal', sans-serif !important;
    }
    .admin-card {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    .admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        background: rgba(0, 0, 0, 0.6);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .stat-card {
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: rotate(45deg);
    }
    .activity-pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endpush

<!-- Admin Welcome Section with Dark Background -->
<div class="bg-black/50 backdrop-blur-md rounded-3xl p-8 mb-8 border border-white/10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-white mb-2">مرحباً بك في لوحة التحكم</h2>
            <p class="text-gray-300 text-lg">إدارة شاملة لمنصة جُذور التعليمية</p>
        </div>
        <div class="text-6xl opacity-20">
            <i class="fas fa-shield-alt text-red-400"></i>
        </div>
    </div>
    
    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-4 gap-4 mt-6 pt-6 border-t border-white/10">
        <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ \App\Models\User::whereDate('created_at', today())->count() }}</div>
            <div class="text-sm text-gray-400">مستخدمين جدد اليوم</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ \App\Models\Quiz::whereDate('created_at', today())->count() }}</div>
            <div class="text-sm text-gray-400">اختبارات جديدة</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ \App\Models\Result::whereDate('created_at', today())->count() }}</div>
            <div class="text-sm text-gray-400">نتائج اليوم</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ \App\Models\User::where('last_login_at', '>=', now()->subHours(1))->count() }}</div>
            <div class="text-sm text-gray-400">نشط الآن</div>
        </div>
    </div>
</div>

<!-- Admin Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="admin-card stat-card rounded-2xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-5xl font-black mb-2">{{ \App\Models\User::count() }}</div>
                <div class="text-lg opacity-90 font-medium">إجمالي المستخدمين</div>
                <div class="text-sm opacity-70 mt-1">
                    <span class="text-green-300">↑ {{ \App\Models\User::where('created_at', '>=', now()->subDays(7))->count() }}</span> 
                    هذا الأسبوع
                </div>
            </div>
            <div class="text-6xl opacity-30">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <!-- Active Quizzes Card -->
    <div class="admin-card stat-card rounded-2xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-5xl font-black mb-2">{{ \App\Models\Quiz::where('is_active', true)->count() }}</div>
                <div class="text-lg opacity-90 font-medium">اختبارات نشطة</div>
                <div class="text-sm opacity-70 mt-1">
                    من أصل {{ \App\Models\Quiz::count() }} اختبار
                </div>
            </div>
            <div class="text-6xl opacity-30">
                <i class="fas fa-clipboard-check"></i>
            </div>
        </div>
    </div>
    
    <!-- Today's Activity -->
    <div class="admin-card stat-card rounded-2xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-5xl font-black mb-2 activity-pulse">{{ \App\Models\Result::whereDate('created_at', today())->count() }}</div>
                <div class="text-lg opacity-90 font-medium">نشاط اليوم</div>
                <div class="text-sm opacity-70 mt-1">
                    اختبارات مكتملة
                </div>
            </div>
            <div class="text-6xl opacity-30">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    
    <!-- Active Teachers -->
    <div class="admin-card stat-card rounded-2xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-5xl font-black mb-2">{{ \App\Models\User::where('user_type', 'teacher')->count() }}</div>
                <div class="text-lg opacity-90 font-medium">معلمين مسجلين</div>
                <div class="text-sm opacity-70 mt-1">
                    <span class="text-yellow-300">{{ \App\Models\User::where('user_type', 'teacher')->whereHas('quizzes')->count() }}</span> 
                    نشطين
                </div>
            </div>
            <div class="text-6xl opacity-30">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Users Management -->
    <a href="{{ route('admin.users.index') }}" class="group">
        <div class="admin-card rounded-2xl p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-users-cog text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">إدارة المستخدمين</h3>
            <p class="text-gray-200 text-sm">عرض وإدارة جميع المستخدمين والصلاحيات</p>
            <div class="mt-4 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-arrow-left text-xl"></i>
            </div>
        </div>
    </a>
    
    <!-- Quizzes Management -->
    <a href="{{ route('admin.quizzes.index') }}" class="group">
        <div class="admin-card rounded-2xl p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-tasks text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">إدارة الاختبارات</h3>
            <p class="text-gray-200 text-sm">مراجعة وإدارة جميع الاختبارات</p>
            <div class="mt-4 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-arrow-left text-xl"></i>
            </div>
        </div>
    </a>
    
    <!-- Reports & Analytics -->
    <a href="{{ route('admin.dashboard') }}" class="group">
        <div class="admin-card rounded-2xl p-8 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-chart-bar text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">التقارير والإحصائيات</h3>
            <p class="text-gray-200 text-sm">تحليلات شاملة وتقارير مفصلة</p>
            <div class="mt-4 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-arrow-left text-xl"></i>
            </div>
        </div>
    </a>
</div>

<!-- System Overview -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activity -->
    <div class="admin-card rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-history ml-3 text-yellow-400"></i>
            النشاط الأخير
        </h2>
        <div class="space-y-4 max-h-96 overflow-y-auto">
            @foreach(\App\Models\Result::with(['user', 'quiz'])->latest()->take(10)->get() as $result)
            @if($result->quiz)
                <div class="flex items-center justify-between p-3 bg-black/40 rounded-lg hover:bg-black/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold">
                            {{ mb_substr($result->user->name ?? 'ض', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ $result->user->name ?? 'ضيف' }}</p>
                            <p class="text-gray-300 text-sm">أكمل {{ $result->quiz->title }}</p>
                        </div>
                    </div>
                    <div class="text-left">
                        <p class="text-white font-bold">{{ number_format($result->total_score, 1) }}%</p>
                        <p class="text-gray-300 text-xs">{{ $result->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endif
        @endforeach
        </div>
    </div>
    
    <!-- System Health -->
    <div class="admin-card rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-heartbeat ml-3 text-green-400"></i>
            صحة النظام
        </h2>
        <div class="space-y-6">
            <!-- Database Status -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-200 font-medium">قاعدة البيانات</span>
                    <span class="text-green-400 font-bold">متصل</span>
                </div>
                <div class="w-full bg-gray-700/50 rounded-full h-3 backdrop-blur">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full shadow-sm" style="width: 95%"></div>
                </div>
            </div>
            
            <!-- Storage Usage -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-200 font-medium">مساحة التخزين</span>
                    <span class="text-yellow-400 font-bold">68%</span>
                </div>
                <div class="w-full bg-gray-700/50 rounded-full h-3 backdrop-blur">
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 h-3 rounded-full shadow-sm" style="width: 68%"></div>
                </div>
            </div>
            
            <!-- API Usage -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-200 font-medium">استخدام Claude AI</span>
                    <span class="text-blue-400 font-bold">42%</span>
                </div>
                <div class="w-full bg-gray-700/50 rounded-full h-3 backdrop-blur">
                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full shadow-sm" style="width: 42%"></div>
                </div>
            </div>
            
            <!-- Server Response -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-200 font-medium">سرعة الاستجابة</span>
                    <span class="text-green-400 font-bold">128ms</span>
                </div>
                <div class="w-full bg-gray-700/50 rounded-full h-3 backdrop-blur">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full shadow-sm" style="width: 88%"></div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-6 pt-6 border-t border-gray-700">
            <div class="grid grid-cols-2 gap-3">
                <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    تحديث النظام
                </button>
                <button class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-trash-alt"></i>
                    تنظيف الملفات
                </button>
            </div>
        </div>
    </div>
</div>