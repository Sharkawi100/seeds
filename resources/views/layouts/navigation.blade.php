<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <h2 class="text-xl font-bold text-blue-600">🌱 جُذور</h2>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        لوحة التحكم
                    </x-nav-link>
                    
                    @auth
                    <x-nav-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')">
                        اختباراتي
                    </x-nav-link>
                    
                    <x-nav-link :href="route('quizzes.create')" :active="request()->routeIs('quizzes.create')">
                        إنشاء اختبار
                    </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
<x-dropdown align="right" width="64">
    <x-slot name="trigger">
        <button class="inline-flex items-center px-3 py-2 ...">
            <div class="flex items-center">
                @if(Auth::user()->avatar)
    @if(str_starts_with(Auth::user()->avatar, 'http'))
        <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-8 h-8 rounded-full ml-2">
    @else
        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full ml-2">
    @endif
@else
    <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center ml-2">
        <span class="text-white text-sm font-bold">
            {{ substr(Auth::user()->name, 0, 1) }}
        </span>
    </div>
@endif
                <div class="text-right">
                    <div>{{ Auth::user()->name }}</div>
                    @if(Auth::user()->profile_completion < 100)
                        <div class="text-xs text-gray-500">
                            الملف الشخصي {{ Auth::user()->profile_completion }}% مكتمل
                        </div>
                    @endif
                </div>
            </div>
        </button>
    </x-slot>

    <x-slot name="content">
        <x-dropdown-link :href="route('profile.dashboard')">
            <i class="fas fa-user ml-2"></i> ملفي الشخصي
        </x-dropdown-link>
        
        @if(Auth::user()->profile_completion < 100)
            <x-dropdown-link :href="route('profile.completion')">
                <i class="fas fa-tasks ml-2 text-yellow-500"></i> 
                أكمل ملفك الشخصي
            </x-dropdown-link>
        @endif
        
        <x-dropdown-link :href="route('profile.edit')">
            <i class="fas fa-cog ml-2"></i> الإعدادات
        </x-dropdown-link>

        <hr class="my-1">

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
            </x-dropdown-link>
        </form>
    </x-slot>
</x-dropdown>

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

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                لوحة التحكم
            </x-responsive-nav-link>
            
            @if(Auth::check() && Auth::user()->is_admin)
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                لوحة الإدارة
            </x-nav-link>
        @endif
            @auth
            <x-responsive-nav-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')">
                اختباراتي
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('quizzes.create')" :active="request()->routeIs('quizzes.create')">
                إنشاء اختبار
            </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    الملف الشخصي
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        تسجيل الخروج
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>