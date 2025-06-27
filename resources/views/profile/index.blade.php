@extends('layouts.app')

@section('title', 'لوحة التحكم الشخصية')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-xl shadow-xl p-6 text-white mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- Avatar -->
                    <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur flex items-center justify-center overflow-hidden border-2 border-white/30">
                        @if(Auth::user()->avatar)
                            @if(str_starts_with(Auth::user()->avatar, 'http'))
                                <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @endif
                        @else
                            <span class="text-2xl text-white font-bold">
                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- User Info -->
                    <div>
                        <h1 class="text-2xl font-bold">{{ Auth::user()->name }}</h1>
                        <p class="text-blue-100">{{ Auth::user()->email }}</p>
                        <div class="flex items-center space-x-2 space-x-reverse mt-1">
                            <span class="bg-white/20 px-2 py-1 rounded-full text-xs font-medium">
                                @if(Auth::user()->user_type === 'teacher')
                                    <i class="fas fa-chalkboard-teacher ml-1"></i>معلم
                                @elseif(Auth::user()->user_type === 'admin')
                                    <i class="fas fa-crown ml-1"></i>مدير
                                @else
                                    <i class="fas fa-graduation-cap ml-1"></i>طالب
                                @endif
                            </span>
                            
                            @if(Auth::user()->subscription_active)
                                <span class="bg-green-500/20 px-2 py-1 rounded-full text-xs font-medium border border-green-300/30">
                                    <i class="fas fa-star ml-1"></i>مشترك
                                </span>
                            @else
                                <span class="bg-yellow-500/20 px-2 py-1 rounded-full text-xs font-medium border border-yellow-300/30">
                                    <i class="fas fa-gift ml-1"></i>مجاني
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Profile Completion -->
                @php
                    $completion = 0;
                    $fields = [
                        'name' => !empty(Auth::user()->name),
                        'email' => !empty(Auth::user()->email),
                        'avatar' => !empty(Auth::user()->avatar),
                        'bio' => !empty(Auth::user()->bio),
                        'phone' => !empty(Auth::user()->phone),
                    ];
                    
                    if(Auth::user()->user_type === 'teacher') {
                        $fields['school_name'] = !empty(Auth::user()->school_name);
                        $fields['subjects_taught'] = !empty(Auth::user()->subjects_taught);
                    } elseif(Auth::user()->user_type === 'student') {
                        $fields['grade_level'] = !empty(Auth::user()->grade_level);
                        $fields['favorite_subject'] = !empty(Auth::user()->favorite_subject);
                    }
                    
                    $completed = array_sum($fields);
                    $total = count($fields);
                    $completion = round(($completed / $total) * 100);
                @endphp
                
                <div class="text-left">
                    <div class="text-sm text-blue-100 mb-1">اكتمال الملف الشخصي</div>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <div class="w-24 bg-white/30 rounded-full h-2">
                            <div class="bg-white rounded-full h-2 transition-all duration-500" 
                                 style="width: {{ $completion }}%"></div>
                        </div>
                        <span class="text-white font-bold text-sm">{{ $completion }}%</span>
                    </div>
                    @if($completion < 100)
                        <a href="{{ route('profile.edit') }}" 
                           class="text-xs text-blue-100 hover:text-white transition mt-1 inline-block">
                            أكمل ملفك الشخصي
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subscription Status Card -->
        @if(!Auth::user()->is_admin)
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        <i class="fas fa-credit-card ml-2 text-blue-600"></i>حالة الاشتراك
                    </h3>
                    
                    @if(Auth::user()->subscription_active)
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-check-circle ml-1"></i>نشط - {{ Auth::user()->subscription_plan }}
                            </span>
                            @if(Auth::user()->subscription_expires_at)
                                <span class="text-gray-600 text-sm">
                                    ينتهي في: {{ Auth::user()->subscription_expires_at->format('Y/m/d') }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Usage Meters for Subscribers -->
                        @php
                            // Get current month usage (you'll need to implement this)
                            $monthlyQuizUsage = Auth::user()->quizzes()->whereMonth('created_at', now()->month)->count();
                            $monthlyQuizLimit = 40; // Default pro limit
                        @endphp
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">الاختبارات الشهرية</span>
                                    <span class="text-sm text-gray-600">{{ $monthlyQuizUsage }}/{{ $monthlyQuizLimit }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ min(($monthlyQuizUsage / $monthlyQuizLimit) * 100, 100) }}%"></div>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">ميزات الذكاء الاصطناعي</span>
                                    <span class="text-sm text-green-600">
                                        <i class="fas fa-infinity ml-1"></i>مفتوحة
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-gift ml-1"></i>خطة مجانية
                                </span>
                                <p class="text-gray-600 text-sm mt-2">محدود بـ 5 اختبارات شهرياً - بدون ميزات الذكاء الاصطناعي</p>
                                
                                @php
                                    $freeQuizUsage = Auth::user()->quizzes()->whereMonth('created_at', now()->month)->count();
                                    $freeLimit = 5;
                                @endphp
                                
                                <div class="mt-3">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">الاستخدام الشهري</span>
                                        <span class="text-xs text-gray-600">{{ $freeQuizUsage }}/{{ $freeLimit }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-yellow-500 h-1.5 rounded-full transition-all duration-500" 
                                             style="width: {{ min(($freeQuizUsage / $freeLimit) * 100, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="flex flex-col space-y-2">
                    @if(!Auth::user()->subscription_active)
                        <a href="{{ route('subscription.upgrade') }}" 
                           class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition-all">
                            <i class="fas fa-rocket ml-1"></i>ترقية الآن
                        </a>
                    @else
                        <a href="{{ route('subscription.manage') }}" 
                           class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-all">
                            <i class="fas fa-cog ml-1"></i>إدارة الاشتراك
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-bolt ml-2 text-yellow-500"></i>الإجراءات السريعة
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(Auth::user()->user_type !== 'student')
                            <a href="{{ route('quizzes.create') }}" 
                               class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">إنشاء اختبار</span>
                            </a>
                        @endif
                        
                        <a href="{{ route('results.index') }}" 
                           class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors group">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">عرض النتائج</span>
                        </a>
                        
                        @if(Auth::user()->user_type !== 'student')
                            <a href="{{ route('quizzes.index') }}" 
                               class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
                                <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-list text-white"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">اختباراتي</span>
                            </a>
                        @endif
                        
                        <a href="{{ route('profile.edit') }}" 
                           class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                            <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-edit text-white"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">تعديل الملف</span>
                        </a>
                    </div>
                </div>

                <!-- User Statistics -->
                @php
                    // Get user statistics
                    $totalQuizzes = Auth::user()->user_type !== 'student' ? Auth::user()->quizzes()->count() : 0;
                    $totalResults = Auth::user()->user_type !== 'student' 
                        ? \App\Models\Result::whereHas('quiz', function($q) { $q->where('user_id', Auth::id()); })->count()
                        : Auth::user()->results()->count();
                    $averageScore = Auth::user()->user_type !== 'student'
                        ? \App\Models\Result::whereHas('quiz', function($q) { $q->where('user_id', Auth::id()); })->avg('total_score')
                        : Auth::user()->results()->avg('total_score');
                    $thisWeekQuizzes = Auth::user()->user_type !== 'student' 
                        ? Auth::user()->quizzes()->where('created_at', '>=', now()->startOfWeek())->count()
                        : 0;
                @endphp
                
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-chart-bar ml-2 text-green-600"></i>إحصائياتي
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(Auth::user()->user_type !== 'student')
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $totalQuizzes }}</div>
                                <div class="text-sm text-gray-600">إجمالي الاختبارات</div>
                            </div>
                        @endif
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $totalResults }}</div>
                            <div class="text-sm text-gray-600">
                                @if(Auth::user()->user_type !== 'student')
                                    محاولات الطلاب
                                @else
                                    محاولاتي
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ round($averageScore ?? 0) }}%</div>
                            <div class="text-sm text-gray-600">متوسط النتائج</div>
                        </div>
                        
                        @if(Auth::user()->user_type !== 'student')
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">{{ $thisWeekQuizzes }}</div>
                                <div class="text-sm text-gray-600">هذا الأسبوع</div>
                            </div>
                        @else
                            <div class="text-center p-4 bg-indigo-50 rounded-lg">
                                <div class="text-2xl font-bold text-indigo-600">
                                    {{ Auth::user()->grade_level ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-600">صفي الدراسي</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-clock ml-2 text-orange-600"></i>النشاط الأخير
                    </h3>
                    
                    @if(Auth::user()->user_type !== 'student')
                        @php
                            $recentQuizzes = Auth::user()->quizzes()->latest()->take(5)->get();
                        @endphp
                        
                        @if($recentQuizzes->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentQuizzes as $quiz)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-file-alt text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $quiz->title }}</div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $quiz->questions->count() }} أسئلة • 
                                                    {{ $quiz->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('quizzes.show', $quiz) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            عرض
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-600">لم تقم بإنشاء أي اختبارات بعد</p>
                                <a href="{{ route('quizzes.create') }}" 
                                   class="inline-flex items-center mt-3 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-plus ml-1"></i>إنشاء أول اختبار
                                </a>
                            </div>
                        @endif
                    @else
                        @php
                            $recentResults = Auth::user()->results()->latest()->take(5)->with('quiz')->get();
                        @endphp
                        
                        @if($recentResults->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentResults as $result)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-chart-line text-green-600"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $result->quiz->title }}</div>
                                                <div class="text-sm text-gray-600">
                                                    نتيجة: {{ $result->total_score }}% • 
                                                    {{ $result->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('results.show', $result) }}" 
                                           class="text-green-600 hover:text-green-800 text-sm">
                                            عرض
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-chart-line text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-600">لم تقم بأي اختبارات بعد</p>
                                <a href="{{ route('dashboard') }}" 
                                   class="inline-flex items-center mt-3 text-green-600 hover:text-green-800">
                                    <i class="fas fa-search ml-1"></i>ابحث عن اختبارات
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                
                <!-- Profile Card -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-id-card ml-2 text-blue-600"></i>بطاقة المعرف
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ الانضمام:</span>
                            <span class="font-medium">{{ Auth::user()->created_at->format('Y/m/d') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">آخر دخول:</span>
                            <span class="font-medium">{{ Auth::user()->last_login_at?->diffForHumans() ?? 'غير متاح' }}</span>
                        </div>
                        
                        @if(Auth::user()->school_name)
                            <div class="flex justify-between">
                                <span class="text-gray-600">المدرسة:</span>
                                <span class="font-medium">{{ Auth::user()->school_name }}</span>
                            </div>
                        @endif
                        
                        @if(Auth::user()->grade_level)
                            <div class="flex justify-between">
                                <span class="text-gray-600">الصف:</span>
                                <span class="font-medium">{{ Auth::user()->grade_level }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">حالة الحساب:</span>
                            <span class="font-medium text-green-600">
                                <i class="fas fa-check-circle ml-1"></i>نشط
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Achievements -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-trophy ml-2 text-yellow-600"></i>الإنجازات
                    </h3>
                    
                    <div class="space-y-3">
                        @if(Auth::user()->user_type !== 'student')
                            @if($totalQuizzes >= 1)
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-star text-blue-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">أول اختبار</span>
                                </div>
                            @endif
                            
                            @if($totalQuizzes >= 5)
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-medal text-green-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">منشئ نشيط</span>
                                </div>
                            @endif
                            
                            @if($totalQuizzes >= 10)
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-crown text-purple-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">خبير الاختبارات</span>
                                </div>
                            @endif
                        @else
                            @if($totalResults >= 1)
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-star text-green-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">أول اختبار</span>
                                </div>
                            @endif
                            
                            @if($averageScore >= 80)
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-medal text-yellow-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">متفوق</span>
                                </div>
                            @endif
                        @endif
                        
                        @if(Auth::user()->subscription_active)
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-gem text-indigo-600 text-xs"></i>
                                </div>
                                <span class="text-sm text-gray-700">عضو مميز</span>
                            </div>
                        @endif
                        
                        @if($completion >= 100)
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-certificate text-pink-600 text-xs"></i>
                                </div>
                                <span class="text-sm text-gray-700">ملف مكتمل</span>
                            </div>
                        @endif
                        
                        @if(!Auth::user()->subscription_active && !$totalQuizzes && !$totalResults)
                            <div class="text-center py-4">
                                <i class="fas fa-medal text-gray-300 text-2xl mb-2"></i>
                                <p class="text-gray-600 text-sm">ابدأ استخدام المنصة لكسب الإنجازات</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Help & Support -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">
                        <i class="fas fa-question-circle ml-2 text-blue-600"></i>المساعدة والدعم
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="/juzoor-model" 
                           class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-book-open ml-2"></i>تعرف على نموذج الجذور
                        </a>
                        
                        <a href="/contact" 
                           class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-envelope ml-2"></i>تواصل معنا
                        </a>
                        
                        @if(Auth::user()->user_type === 'teacher')
                            <a href="/for-teachers" 
                               class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-chalkboard-teacher ml-2"></i>دليل المعلمين
                            </a>
                        @endif
                        
                        @if(Auth::user()->user_type === 'student')
                            <a href="/for-students" 
                               class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-graduation-cap ml-2"></i>دليل الطلاب
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection