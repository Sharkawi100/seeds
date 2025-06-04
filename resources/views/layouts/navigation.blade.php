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
                    @endauth
                </div>
            </div>

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
                </div>
                @endauth
            </div>

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
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
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