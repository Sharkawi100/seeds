<<<<<<< HEAD
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
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
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
                            </x-nav-link>

                            <!-- Results -->
                            <x-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                                <i class="fas fa-chart-bar ml-2"></i>
                                النتائج
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
                                <i class="fas fa-cog ml-2"></i>
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
                                 class="absolute left-0 mt-2 w-64 rounded-xl shadow-xl bg-white/95 backdrop-blur-lg ring-1 ring-black ring-opacity-5 border border-gray-200/50 overflow-hidden">
                                
                                <!-- Admin Dashboard -->
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-chart-pie text-purple-500 w-5 ml-3"></i>
                                    لوحة الإدارة
                                </a>

                                <div class="border-t border-gray-200/50"></div>

                                <!-- User Management -->
                                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-users text-blue-500 w-5 ml-3"></i>
                                    إدارة المستخدمين
                                </a>

                                <!-- Subject Management -->
                                <a href="{{ route('admin.subjects.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                                    <i class="fas fa-book text-green-500 w-5 ml-3"></i>
                                    المواد الدراسية
                                </a>

                                <!-- Quiz Management -->
                                <a href="{{ route('admin.quizzes.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-clipboard-check text-indigo-500 w-5 ml-3"></i>
                                    إدارة الاختبارات
                                </a>

                                <div class="border-t border-gray-200/50"></div>

                                <!-- AI Management -->
                                <a href="{{ route('admin.ai.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-robot text-purple-500 w-5 ml-3"></i>
                                    إدارة الذكاء الاصطناعي
                                </a>

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
                        <x-nav-link :href="route('for.teachers')" :active="request()->routeIs('for.teachers')">
                            <i class="fas fa-chalkboard-teacher ml-2"></i>
                            للمعلمين
                        </x-nav-link>

                        <x-nav-link :href="route('for.students')" :active="request()->routeIs('for.students')">
                            <i class="fas fa-user-graduate ml-2"></i>
                            للطلاب
                        </x-nav-link>

                        <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                            <i class="fas fa-info-circle ml-2"></i>
                            عن جُذور
                        </x-nav-link>

                        <x-nav-link :href="route('juzoor.model')" :active="request()->routeIs('juzoor.*')">
                            <i class="fas fa-seedling ml-2"></i>
                            نموذج جُذور
                        </x-nav-link>
=======
?>
<nav x-data="{ open: false, profileOpen: false, currentPath: '{{ request()->path() }}' }" 
     class="bg-white/90 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 transition-all duration-300">
    
    <!-- Progress Indicator for Active Quiz -->
    @if(session('active_quiz_progress'))
    <div class="absolute top-0 left-0 right-0 h-1 bg-gray-200">
        <div class="h-full bg-gradient-to-r from-purple-600 to-blue-600 transition-all duration-500" 
             style="width: {{ session('active_quiz_progress') }}%"></div>
    </div>
    @endif
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group">
                        <h2 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent group-hover:from-purple-700 group-hover:to-purple-900 transition-all duration-300">
                            🌱 جُذور
                        </h2>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                class="relative group">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-home"></i>
                            لوحة التحكم
                        </span>
                        @if(request()->routeIs('dashboard'))
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-purple-600 to-blue-600"></span>
                        @endif
                    </x-nav-link>
                    
                    @auth
                    <x-nav-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')"
                                class="relative group">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-clipboard-list"></i>
                            اختباراتي
                            @if(Auth::user()->quizzes()->count() > 0)
                            <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">
                                {{ Auth::user()->quizzes()->count() }}
                            </span>
                            @endif
                        </span>
                    </x-nav-link>
                    
                    <!-- Quick Create Button for Teachers -->
                    @if(Auth::user()->user_type === 'teacher')
                    <div class="flex items-center">
                        <a href="{{ route('quizzes.create')" 
                           class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center gap-2">
                            <i class="fas fa-plus-circle"></i>
                            إنشاء اختبار سريع
                        </a>
                    </div>
                    @endif
                    
                    @if(Auth::user()->is_admin)
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                                class="relative group">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-cog"></i>
                            لوحة الإدارة
                        </span>
                    </x-nav-link>
                    @endif
>>>>>>> b7d65b0 (Your next changes)
                    @endauth
                </div>
            </div>

<<<<<<< HEAD
            <!-- Right Side -->
            <div class="flex items-center">
                @auth
                    <!-- Notifications (placeholder) -->
                    <button class="hidden md:flex items-center justify-center w-10 h-10 rounded-lg text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200 mr-3 relative">
                        <i class="fas fa-bell"></i>
                        <!-- Notification badge -->
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center max-w-xs bg-white/50 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 hover:bg-white/80">
                            <div class="flex items-center">
                                @if(Auth::user()->avatar)
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold border-2 border-gray-200">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="mr-3 text-right hidden lg:block">
                                    <div class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if(Auth::user()->is_admin)
                                            مدير النظام
                                        @elseif(Auth::user()->user_type === 'teacher')
                                            معلم
                                        @else
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
                             class="absolute left-0 mt-2 w-64 rounded-xl shadow-xl bg-white/95 backdrop-blur-lg ring-1 ring-black ring-opacity-5 border border-gray-200/50 overflow-hidden">
                            
                            <!-- Profile Header -->
                            <div class="px-4 py-3 border-b border-gray-200/50 bg-gradient-to-r from-purple-50 to-blue-50">
                                <div class="flex items-center">
                                    @if(Auth::user()->avatar)
                                        <img class="h-12 w-12 rounded-full object-cover" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="mr-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-600">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Links -->
                            <a href="{{ route('profile.dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                                <i class="fas fa-user text-purple-500 w-5 ml-3"></i>
                                الملف الشخصي
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <i class="fas fa-edit text-blue-500 w-5 ml-3"></i>
                                تعديل الملف الشخصي
                            </a>

                            <a href="{{ route('profile.dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                                <i class="fas fa-cog text-green-500 w-5 ml-3"></i>
                                إعدادات الحساب
                            </a>

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
                @else
                    <!-- Guest Buttons -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-purple-600 rounded-lg text-sm font-medium text-purple-600 hover:bg-purple-600 hover:text-white transition-all duration-200">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
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
=======
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:bg-gray-50 transition-all duration-300 group">
                            <!-- Avatar Placeholder -->
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center text-white font-bold ml-2 group-hover:shadow-md transition-all duration-300">
                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                            </div>
                            
                            <div class="text-right">
                                <div class="font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">
                                    @switch(Auth::user()->user_type)
                                        @case('admin')
                                            <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full">مدير</span>
                                            @break
                                        @case('teacher')
                                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">معلم</span>
                                            @break
                                        @default
                                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">طالب</span>
                                    @endswitch
                                </div>
                            </div>
                            
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 transition-transform duration-300" 
                                     :class="{'rotate-180': profileOpen}"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Profile Header in Dropdown -->
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center text-white font-bold text-lg ml-3">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                                    <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-gray-500"></i>
                            الملف الشخصي
                        </x-dropdown-link>
                        
                        @if(Auth::user()->user_type === 'teacher')
                        <x-dropdown-link :href="route('quizzes.index')" class="flex items-center gap-2">
                            <i class="fas fa-chart-line text-gray-500"></i>
                            إحصائياتي
                        </x-dropdown-link>
                        @endif

                        <div class="border-t border-gray-200"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="flex items-center gap-2 text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt"></i>
                                تسجيل الخروج
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-purple-600 transition-colors duration-300">
                        تسجيل الدخول
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm bg-gradient-to-r from-purple-600 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-300">
                            التسجيل
                        </a>
                    @endif
>>>>>>> b7d65b0 (Your next changes)
                </div>
                @endauth
            </div>
<<<<<<< HEAD
        </div>
    </div>

    <!-- Mobile Menu -->
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
                <!-- User Info -->
                <div class="px-3 py-3 border-b border-gray-200/50 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg mb-2">
                    <div class="flex items-center">
                        @if(Auth::user()->avatar)
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                        @else
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="mr-3">
                            <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-600">
                                @if(Auth::user()->is_admin)
                                    مدير النظام
                                @elseif(Auth::user()->user_type === 'teacher')
                                    معلم
                                @else
                                    طالب
                                @endif
                            </div>
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
=======

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Breadcrumb Navigation -->
    @if(!request()->routeIs('dashboard'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 text-sm">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-purple-600 transition-colors duration-300">
                        <i class="fas fa-home ml-1"></i>
                        الرئيسية
                    </a>
                </li>
                
                @if(request()->routeIs('quizzes.*'))
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-left mx-2 text-gray-400"></i>
                        <a href="{{ route('quizzes.index') }}" class="text-gray-500 hover:text-purple-600 transition-colors duration-300">
                            اختباراتي
                        </a>
                    </div>
                </li>
                @endif
                
                @if(request()->routeIs('quizzes.show') && isset($quiz))
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-left mx-2 text-gray-400"></i>
                        <span class="text-gray-700 font-medium">{{ $quiz->title }}</span>
                    </div>
                </li>
                @endif
            </ol>
        </nav>
    </div>
    @endif

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fas fa-home ml-2"></i>
                لوحة التحكم
            </x-responsive-nav-link>
            
            @auth
            <x-responsive-nav-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')">
                <i class="fas fa-clipboard-list ml-2"></i>
                اختباراتي
            </x-responsive-nav-link>
            
            @if(Auth::user()->user_type === 'teacher')
            <x-responsive-nav-link :href="route('quizzes.create')" :active="request()->routeIs('quizzes.create')"
                                   class="bg-purple-50 text-purple-700 font-semibold">
                <i class="fas fa-plus-circle ml-2"></i>
                إنشاء اختبار سريع
            </x-responsive-nav-link>
            @endif
            
            @if(Auth::user()->is_admin)
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                <i class="fas fa-cog ml-2"></i>
                لوحة الإدارة
            </x-responsive-nav-link>
            @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <div class="px-4 flex items-center">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center text-white font-bold text-lg ml-3">
                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user-circle ml-2"></i>
                    الملف الشخصي
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-red-600">
                        <i class="fas fa-sign-out-alt ml-2"></i>
                        تسجيل الخروج
>>>>>>> b7d65b0 (Your next changes)
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                        <i class="fas fa-chart-bar ml-2"></i>
                        النتائج
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
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">إدارة النظام</div>
                        
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            <i class="fas fa-chart-pie ml-2"></i>
                            لوحة الإدارة
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.users.index')">
                            <i class="fas fa-users ml-2"></i>
                            إدارة المستخدمين
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

                <!-- Profile & Settings -->
                <div class="border-t border-gray-200/50 pt-2 mt-2">
                    <x-responsive-nav-link :href="route('profile.dashboard')">
                        <i class="fas fa-user ml-2"></i>
                        الملف الشخصي
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('profile.edit')">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل الملف الشخصي
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('profile.dashboard')">
                        <i class="fas fa-cog ml-2"></i>
                        إعدادات الحساب
                    </x-responsive-nav-link>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt ml-2"></i>
                            تسجيل الخروج
                        </x-responsive-nav-link>
                    </form>
                </div>

            @else
                <!-- Guest Mobile Menu -->
                <x-responsive-nav-link :href="route('for.teachers')">
                    <i class="fas fa-chalkboard-teacher ml-2"></i>
                    للمعلمين
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('for.students')">
                    <i class="fas fa-user-graduate ml-2"></i>
                    للطلاب
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('about')">
                    <i class="fas fa-info-circle ml-2"></i>
                    عن جُذور
                </x-responsive-nav-link>

                <div class="border-t border-gray-200/50 pt-4 mt-4">
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('login') }}" class="w-full text-center py-2 border border-purple-600 rounded-lg text-sm font-medium text-purple-600 hover:bg-purple-600 hover:text-white transition-all duration-200">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="w-full text-center py-2 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-blue-700 transition-all duration-200">
                            إنشاء حساب
                        </a>
                    </div>
                </div>
            @endauth
        </div>
<<<<<<< HEAD
    </div>
</nav>

<!-- Spacer for fixed navigation -->
<div class="h-16"></div>
=======
        @else
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="space-y-1 px-4">
                <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-purple-600">
                    تسجيل الدخول
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block py-2 text-purple-600 font-semibold">
                        التسجيل
                    </a>
                @endif
            </div>
        </div>
        @endauth
    </div>
</nav>

<?php
>>>>>>> b7d65b0 (Your next changes)
