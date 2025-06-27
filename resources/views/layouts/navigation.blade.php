<nav x-data="{ 
    open: false, 
    userMenuOpen: false, 
    adminMenuOpen: false,
    scrolled: false 
}" 
@scroll.window="scrolled = window.pageYOffset > 10"
:class="{ 'bg-white/95 backdrop-blur-lg shadow-lg': scrolled, 'bg-white/80 backdrop-blur-md': !scrolled }"
class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b border-gray-200/50">

    <!-- Impersonation Banner -->
    @if(session('impersonating'))
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2 text-sm font-medium text-center">
        <i class="fas fa-user-secret mr-2"></i>
        تقوم بتصفح الموقع كـ {{ Auth::user()->name }} 
        ({{ Auth::user()->user_type === 'admin' ? 'مدير' : (Auth::user()->user_type === 'teacher' ? 'معلم' : 'طالب') }})
        <a href="{{ route('admin.stop-impersonation') }}" class="inline mr-4 underline hover:no-underline">
            <i class="fas fa-undo mr-1"></i>
            العودة لحسابك الأصلي
        </a>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center group">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                            ج
                        </div>
                        <span class="mr-3 text-xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                            جُذور
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center mr-8 space-x-8">
                    @auth
                        <!-- Dashboard -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <i class="fas fa-tachometer-alt ml-2"></i>
                            الرئيسية
                        </x-nav-link>

                        @if(Auth::user()->user_type === 'teacher' || Auth::user()->is_admin)
                            <!-- Quiz Management -->
                            <x-nav-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')">
                                <i class="fas fa-clipboard-list ml-2"></i>
                                الاختبارات
                                @php
                                    $userQuizzesCount = Auth::user()->quizzes()->count();
                                @endphp
                                @if($userQuizzesCount > 0)
                                    <span class="mr-1 bg-blue-100 text-blue-600 text-xs px-1.5 py-0.5 rounded-full">{{ $userQuizzesCount }}</span>
                                @endif
                            </x-nav-link>

                            <!-- Results -->
                            <x-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                                <i class="fas fa-chart-bar ml-2"></i>
                                النتائج
                            </x-nav-link>

                            <!-- Reports & Analytics -->
                            <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                                <i class="fas fa-chart-line ml-2"></i>
                                التقارير
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->user_type === 'student')
                            <!-- Student Results -->
                            <x-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                                <i class="fas fa-trophy ml-2"></i>
                                إنجازاتي
                            </x-nav-link>
                        @endif
                        
                        <!-- Admin Menu Dropdown -->
                        @if(Auth::user()->is_admin)
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 hover:text-purple-600 hover:bg-purple-50 focus:outline-none transition-all duration-200"
                                    :class="{ 'bg-purple-50 text-purple-600': open }">
                                <i class="fas fa-shield-alt ml-2"></i>
                                الإدارة
                                <i class="fas fa-chevron-down mr-2 text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 @click.outside="open = false"
                                 class="absolute left-0 mt-2 w-72 rounded-xl shadow-xl bg-white/95 backdrop-blur-lg ring-1 ring-black ring-opacity-5 border border-gray-200/50 overflow-hidden">
                                
                                <!-- Admin Dashboard -->
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-chart-pie text-purple-500 w-5 ml-3"></i>
                                    <div>
                                        <div class="font-medium">لوحة الإدارة</div>
                                        <div class="text-xs text-gray-500">إحصائيات وتحليلات شاملة</div>
                                    </div>
                                </a>

                                <div class="border-t border-gray-200/50"></div>

                                <!-- User Management -->
                                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-users text-blue-500 w-5 ml-3"></i>
                                    <div>
                                        <div class="font-medium">إدارة المستخدمين</div>
                                        <div class="text-xs text-gray-500">المعلمين والطلاب</div>
                                    </div>
                                </a>

                                <!-- Subscription Management -->
                                <a href="{{ route('admin.subscription-plans.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-credit-card text-indigo-500 w-5 ml-3"></i>
                                    <div>
                                        <div class="font-medium">إدارة الاشتراكات</div>
                                        <div class="text-xs text-gray-500">الخطط والمدفوعات</div>
                                    </div>
                                </a>

                                <!-- Contact Messages -->
                                <a href="{{ route('admin.contact.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                                    <i class="fas fa-envelope text-green-500 w-5 ml-3"></i>
                                    <div>
                                        <div class="font-medium">رسائل التواصل</div>
                                        <div class="text-xs text-gray-500">استفسارات العملاء</div>
                                    </div>
                                </a>

                                <div class="border-t border-gray-200/50"></div>

                                <!-- Subject Management -->
                                <a href="{{ route('admin.subjects.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                    <i class="fas fa-book text-amber-500 w-5 ml-3"></i>
                                    المواد الدراسية
                                </a>

                                <!-- Quiz Management -->
                                <a href="{{ route('admin.quizzes.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                                    <i class="fas fa-clipboard-check text-teal-500 w-5 ml-3"></i>
                                    إدارة الاختبارات
                                </a>

                                <!-- AI Management -->
                                <a href="{{ route('admin.ai.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-robot text-purple-500 w-5 ml-3"></i>
                                    إدارة الذكاء الاصطناعي
                                </a>

                                <div class="border-t border-gray-200/50"></div>

                                <!-- Log Analyzer -->
                                <a href="{{ route('admin.logs.analyzer') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class="fas fa-bug text-red-500 w-5 ml-3"></i>
                                    محلل السجلات
                                    <span class="mr-auto bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">جديد</span>
                                </a>

                                <!-- Settings -->
                                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-cog text-gray-500 w-5 ml-3"></i>
                                    إعدادات النظام
                                </a>
                            </div>
                        </div>
                        @endif

                    @else
                        <!-- Guest Navigation -->
                        <a href="/for-teachers" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 hover:text-purple-600 hover:bg-purple-50 focus:outline-none transition-all duration-200">
                            <i class="fas fa-chalkboard-teacher ml-2"></i>
                            للمعلمين
                        </a>

                        <a href="/for-students" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 hover:text-purple-600 hover:bg-purple-50 focus:outline-none transition-all duration-200">
                            <i class="fas fa-user-graduate ml-2"></i>
                            للطلاب
                        </a>

                        <a href="/about" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 hover:text-purple-600 hover:bg-purple-50 focus:outline-none transition-all duration-200">
                            <i class="fas fa-info-circle ml-2"></i>
                            عن جُذور
                        </a>

                        <a href="/juzoor-model" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 hover:text-purple-600 hover:bg-purple-50 focus:outline-none transition-all duration-200">
                            <i class="fas fa-seedling ml-2"></i>
                            نموذج جُذور
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center">
                @auth
                    <!-- Subscription Status Indicator -->
                    @if(!Auth::user()->is_admin)
                        <div class="hidden md:flex items-center mr-4">
                            @if(Auth::user()->subscription_active)
                                <a href="{{ route('subscription.manage') }}" 
                                   class="flex items-center px-3 py-1.5 bg-green-50 border border-green-200 rounded-full text-xs font-medium text-green-700 hover:bg-green-100 transition-colors">
                                    <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                                    مشترك نشط
                                </a>
                            @else
                                <a href="{{ route('subscription.upgrade') }}" 
                                   class="flex items-center px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-full text-xs font-medium text-amber-700 hover:bg-amber-100 transition-colors">
                                    <span class="w-2 h-2 bg-amber-500 rounded-full ml-2"></span>
                                    ترقية الحساب
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Notifications (Enhanced) -->
                    <button class="hidden md:flex items-center justify-center w-10 h-10 rounded-lg text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200 mr-3 relative group">
                        <i class="fas fa-bell"></i>
                        <!-- Notification badge (could be dynamic in future) -->
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        <!-- Tooltip -->
                        <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            الإشعارات
                        </div>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center max-w-xs bg-white/50 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 hover:bg-white/80 hover:shadow-md">
                            <div class="flex items-center">
                                <!-- Enhanced Avatar -->
                                <div class="relative">
                                    @if(Auth::user()->avatar)
                                        @if(str_starts_with(Auth::user()->avatar, 'http'))
                                            <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 shadow-sm" 
                                                 src="{{ Auth::user()->avatar }}" 
                                                 alt="{{ Auth::user()->name }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <!-- Fallback -->
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-gray-200 shadow-sm" style="display: none;">
                                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                        @else
                                            <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 shadow-sm" 
                                                 src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                                 alt="{{ Auth::user()->name }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <!-- Fallback -->
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-gray-200 shadow-sm" style="display: none;">
                                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-gray-200 shadow-sm">
                                            {{ mb_substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    
                                    <!-- Status Indicator -->
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></div>
                                </div>
                                
                                <div class="mr-3 text-right hidden lg:block">
                                    <div class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500 flex items-center">
                                        @if(Auth::user()->is_admin)
                                            <i class="fas fa-crown text-yellow-500 ml-1"></i>
                                            مدير النظام
                                        @elseif(Auth::user()->user_type === 'teacher')
                                            <i class="fas fa-chalkboard-teacher text-blue-500 ml-1"></i>
                                            معلم
                                        @else
                                            <i class="fas fa-graduation-cap text-green-500 ml-1"></i>
                                            طالب
                                        @endif
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-500 mr-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </div>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             @click.outside="open = false"
                             class="absolute left-0 mt-2 w-80 rounded-xl shadow-xl bg-white/95 backdrop-blur-lg ring-1 ring-black ring-opacity-5 border border-gray-200/50 overflow-hidden">
                            
                            <!-- Enhanced Profile Header -->
                            <div class="px-4 py-4 border-b border-gray-200/50 bg-gradient-to-r from-purple-50 to-blue-50">
                                <div class="flex items-center">
                                    <div class="relative">
                                        @if(Auth::user()->avatar)
                                            @if(str_starts_with(Auth::user()->avatar, 'http'))
                                                <img class="h-14 w-14 rounded-full object-cover border-2 border-white shadow-lg" 
                                                     src="{{ Auth::user()->avatar }}" 
                                                     alt="{{ Auth::user()->name }}"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <!-- Fallback -->
                                                <div class="h-14 w-14 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-white shadow-lg text-lg" style="display: none;">
                                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                                </div>
                                            @else
                                                <img class="h-14 w-14 rounded-full object-cover border-2 border-white shadow-lg" 
                                                     src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                                     alt="{{ Auth::user()->name }}"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <!-- Fallback -->
                                                <div class="h-14 w-14 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-white shadow-lg text-lg" style="display: none;">
                                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-white shadow-lg text-lg">
                                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div class="mr-3 flex-1">
                                        <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-600 break-all">{{ Auth::user()->email }}</div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center">
                                            @if(Auth::user()->is_admin)
                                                <i class="fas fa-crown text-yellow-500 ml-1"></i>
                                                مدير النظام
                                            @elseif(Auth::user()->user_type === 'teacher')
                                                <i class="fas fa-chalkboard-teacher text-blue-500 ml-1"></i>
                                                معلم
                                            @else
                                                <i class="fas fa-graduation-cap text-green-500 ml-1"></i>
                                                طالب
                                            @endif
                                            
                                            @if(!Auth::user()->is_admin)
                                                <span class="mr-2">•</span>
                                                @if(Auth::user()->subscription_active)
                                                    <span class="text-green-600 font-medium">مشترك نشط</span>
                                                @else
                                                    <span class="text-amber-600 font-medium">حساب مجاني</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Profile Completion Bar -->
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
                                    } elseif(Auth::user()->user_type === 'student') {
                                        $fields['grade_level'] = !empty(Auth::user()->grade_level);
                                    }
                                    $completed = array_sum($fields);
                                    $total = count($fields);
                                    $completion = round(($completed / $total) * 100);
                                @endphp
                                
                                @if($completion < 100)
                                <div class="mt-3 pt-3 border-t border-gray-200/50">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-600">اكتمال الملف الشخصي</span>
                                        <span class="text-xs font-medium text-gray-700">{{ $completion }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-1.5 rounded-full transition-all duration-500" 
                                             style="width: {{ $completion }}%"></div>
                                    </div>
                                </div>
                                @else
                                <div class="mt-3 pt-3 border-t border-gray-200/50">
                                    <div class="flex items-center text-xs text-green-600">
                                        <i class="fas fa-check-circle ml-1"></i>
                                        ملف شخصي مكتمل
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Profile Menu Items -->
                            <div class="py-1">
                                <!-- Profile Dashboard -->
                                <a href="{{ route('profile.dashboard') }}" 
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-user text-purple-500 w-5 ml-3"></i>
                                    <div>
                                        <div class="font-medium">الملف الشخصي</div>
                                        <div class="text-xs text-gray-500">لوحة التحكم الشخصية</div>
                                    </div>
                                </a>

                                <!-- Profile Edit -->
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-edit text-blue-500 w-5 ml-3"></i>
                                    <div>
                                        <div class="font-medium">تعديل الملف</div>
                                        <div class="text-xs text-gray-500">تحديث البيانات الشخصية</div>
                                    </div>
                                    @if($completion < 100)
                                        <span class="mr-auto bg-amber-100 text-amber-600 text-xs px-2 py-1 rounded-full">
                                            {{ 100 - $completion }}% متبقي
                                        </span>
                                    @endif
                                </a>

                                <!-- Subscription Management -->
                                @if(!Auth::user()->is_admin)
                                <div class="border-t border-gray-200/50"></div>
                                
                                @if(Auth::user()->subscription_active)
                                    <a href="{{ route('subscription.manage') }}" 
                                       class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                                        <i class="fas fa-crown text-green-500 w-5 ml-3"></i>
                                        <div>
                                            <div class="font-medium">إدارة الاشتراك</div>
                                            <div class="text-xs text-gray-500">خطة {{ Auth::user()->subscription_plan ?? 'Pro Teacher' }}</div>
                                        </div>
                                        <span class="mr-auto bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">نشط</span>
                                    </a>
                                @else
                                    <a href="{{ route('subscription.upgrade') }}" 
                                       class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 hover:text-purple-600 transition-all">
                                        <i class="fas fa-rocket text-purple-500 w-5 ml-3"></i>
                                        <div>
                                            <div class="font-medium">ترقية الحساب</div>
                                            <div class="text-xs text-gray-500">الحصول على ميزات متقدمة</div>
                                        </div>
                                        <span class="mr-auto bg-gradient-to-r from-purple-100 to-blue-100 text-purple-600 text-xs px-2 py-1 rounded-full">ترقية</span>
                                    </a>
                                @endif
                                @endif

                                <div class="border-t border-gray-200/50"></div>

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-700 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt text-red-500 w-5 ml-3"></i>
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Enhanced Guest Buttons -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-4 py-2 border border-purple-600 rounded-lg text-sm font-medium text-purple-600 hover:bg-purple-600 hover:text-white transition-all duration-200 hover:shadow-md">
                            <i class="fas fa-sign-in-alt ml-2"></i>
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                            <i class="fas fa-user-plus ml-2"></i>
                            إنشاء حساب
                        </a>
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <div class="md:hidden mr-2">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500 transition-all duration-200">
                        <i class="fas fa-bars text-lg" x-show="!open"></i>
                        <i class="fas fa-times text-lg" x-show="open"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Mobile Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-1"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-1"
         class="md:hidden bg-white/95 backdrop-blur-lg border-t border-gray-200/50">
        
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                <!-- Enhanced User Info for Mobile -->
                <div class="px-3 py-4 border-b border-gray-200/50 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg mb-3">
                    <div class="flex items-center">
                        <div class="relative">
                            @if(Auth::user()->avatar)
                                @if(str_starts_with(Auth::user()->avatar, 'http'))
                                    <img class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-md" 
                                         src="{{ Auth::user()->avatar }}" 
                                         alt="{{ Auth::user()->name }}"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Fallback -->
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-white shadow-md" style="display: none;">
                                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @else
                                    <img class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-md" 
                                         src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                         alt="{{ Auth::user()->name }}"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Fallback -->
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-white shadow-md" style="display: none;">
                                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            @else
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-white shadow-md">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></div>
                        </div>
                        <div class="mr-3 flex-1">
                            <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-600 flex items-center mt-1">
                                @if(Auth::user()->is_admin)
                                    <i class="fas fa-crown text-yellow-500 ml-1"></i>
                                    مدير النظام
                                @elseif(Auth::user()->user_type === 'teacher')
                                    <i class="fas fa-chalkboard-teacher text-blue-500 ml-1"></i>
                                    معلم
                                @else
                                    <i class="fas fa-graduation-cap text-green-500 ml-1"></i>
                                    طالب
                                @endif
                            </div>
                            @if(!Auth::user()->is_admin)
                                <div class="text-xs mt-1">
                                    @if(Auth::user()->subscription_active)
                                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">مشترك نشط</span>
                                    @else
                                        <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">حساب مجاني</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Links -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <i class="fas fa-tachometer-alt ml-2"></i>
                    الرئيسية
                </x-responsive-nav-link>

                @if(Auth::user()->user_type === 'teacher' || Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')">
                        <i class="fas fa-clipboard-list ml-2"></i>
                        الاختبارات
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                        <i class="fas fa-chart-bar ml-2"></i>
                        النتائج
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        <i class="fas fa-chart-line ml-2"></i>
                        التقارير
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->user_type === 'student')
                    <x-responsive-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                        <i class="fas fa-trophy ml-2"></i>
                        إنجازاتي
                    </x-responsive-nav-link>
                @endif

                <!-- Admin Menu for Mobile -->
                @if(Auth::user()->is_admin)
                    <div class="border-t border-gray-200/50 pt-2 mt-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                            <i class="fas fa-shield-alt ml-2"></i>
                            إدارة النظام
                        </div>
                        
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            <i class="fas fa-chart-pie ml-2"></i>
                            لوحة الإدارة
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.users.index')">
                            <i class="fas fa-users ml-2"></i>
                            إدارة المستخدمين
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.subscription-plans.index')">
                            <i class="fas fa-credit-card ml-2"></i>
                            إدارة الاشتراكات
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.contact.index')">
                            <i class="fas fa-envelope ml-2"></i>
                            رسائل التواصل
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.subjects.index')">
                            <i class="fas fa-book ml-2"></i>
                            المواد الدراسية
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.logs.analyzer')">
                            <i class="fas fa-bug ml-2"></i>
                            محلل السجلات
                            <span class="mr-auto bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">جديد</span>
                        </x-responsive-nav-link>
                    </div>
                @endif

                <!-- Profile & Settings for Mobile -->
                <div class="border-t border-gray-200/50 pt-2 mt-2">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                        <i class="fas fa-user-cog ml-2"></i>
                        الحساب الشخصي
                    </div>

                    <x-responsive-nav-link :href="route('profile.dashboard')">
                        <i class="fas fa-user ml-2"></i>
                        الملف الشخصي
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('profile.edit')">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل الملف الشخصي
                    </x-responsive-nav-link>

                    @if(!Auth::user()->is_admin)
                        @if(Auth::user()->subscription_active)
                            <x-responsive-nav-link :href="route('subscription.manage')">
                                <i class="fas fa-crown ml-2"></i>
                                إدارة الاشتراك
                                <span class="mr-auto bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">نشط</span>
                            </x-responsive-nav-link>
                        @else
                            <x-responsive-nav-link :href="route('subscription.upgrade')">
                                <i class="fas fa-rocket ml-2"></i>
                                ترقية الحساب
                                <span class="mr-auto bg-purple-100 text-purple-600 text-xs px-2 py-1 rounded-full">ترقية</span>
                            </x-responsive-nav-link>
                        @endif
                    @endif

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt ml-2 text-red-500"></i>
                            <span class="text-red-700">تسجيل الخروج</span>
                        </x-responsive-nav-link>
                    </form>
                </div>

            @else
                <!-- Guest Mobile Menu -->
                <a href="/for-teachers" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition-colors">
                    <i class="fas fa-chalkboard-teacher ml-2"></i>
                    للمعلمين
                </a>

                <a href="/for-students" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition-colors">
                    <i class="fas fa-user-graduate ml-2"></i>
                    للطلاب
                </a>

                <a href="/about" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition-colors">
                    <i class="fas fa-info-circle ml-2"></i>
                    عن جُذور
                </a>

                <a href="/juzoor-model" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition-colors">
                    <i class="fas fa-seedling ml-2"></i>
                    نموذج جُذور
                </a>

                <div class="border-t border-gray-200/50 pt-4 mt-4">
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('login') }}" 
                           class="w-full text-center py-2 border border-purple-600 rounded-lg text-sm font-medium text-purple-600 hover:bg-purple-600 hover:text-white transition-all duration-200">
                            <i class="fas fa-sign-in-alt ml-2"></i>
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" 
                           class="w-full text-center py-2 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-blue-700 transition-all duration-200">
                            <i class="fas fa-user-plus ml-2"></i>
                            إنشاء حساب
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Spacer for fixed navigation -->
<div class="h-16"></div>