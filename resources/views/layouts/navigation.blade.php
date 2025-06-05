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
               <!-- Logo with Role Indicator -->
               <div class="shrink-0 flex items-center">
                   <a href="{{ route('dashboard') }}" class="group flex items-center gap-2">
                       <h2 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent group-hover:from-purple-700 group-hover:to-purple-900 transition-all duration-300">
                           🌱 جُذور
                       </h2>
                       @auth
                           @if(Auth::user()->is_admin)
                               <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-semibold">إدارة</span>
                           @elseif(Auth::user()->user_type === 'teacher')
                               <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">معلم</span>
                           @else
                               <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">طالب</span>
                           @endif
                       @endauth
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
                       {{-- Admin Navigation --}}
                       @if(Auth::user()->is_admin)
                           <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                                       class="relative group">
                               <span class="flex items-center gap-2">
                                   <i class="fas fa-users"></i>
                                   المستخدمين
                               </span>
                           </x-nav-link>
                           
                           <x-nav-link :href="route('admin.quizzes.index')" :active="request()->routeIs('admin.quizzes.*')"
                                       class="relative group">
                               <span class="flex items-center gap-2">
                                   <i class="fas fa-clipboard-check"></i>
                                   جميع الاختبارات
                               </span>
                           </x-nav-link>
                           
                           @if(Route::has('admin.reports'))
                           <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')"
                                       class="relative group">
                               <span class="flex items-center gap-2">
                                   <i class="fas fa-chart-bar"></i>
                                   التقارير
                               </span>
                           </x-nav-link>
                           @endif
                       
                       {{-- Teacher Navigation --}}
                       @elseif(Auth::user()->user_type === 'teacher')
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
                           
                           <x-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')"
                                       class="relative group">
                               <span class="flex items-center gap-2">
                                   <i class="fas fa-chart-line"></i>
                                   نتائج الطلاب
                               </span>
                           </x-nav-link>
                           
                           <!-- Quick Create Button for Teachers -->
                           <div class="flex items-center">
                               <a href="{{ route('quizzes.create')" 
                                  class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center gap-2">
                                   <i class="fas fa-plus-circle"></i>
                                   إنشاء اختبار
                               </a>
                           </div>
                       
                       {{-- Student Navigation --}}
                       @else
                           <x-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')"
                                       class="relative group">
                               <span class="flex items-center gap-2">
                                   <i class="fas fa-chart-line"></i>
                                   نتائجي
                                   @if(Auth::user()->results()->count() > 0)
                                   <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">
                                       {{ Auth::user()->results()->count() }}
                                   </span>
                                   @endif
                               </span>
                           </x-nav-link>
                           
                           <!-- Quick Access for Students -->
                           <div class="flex items-center gap-2">
                               <a href="{{ route('home') }}#pin-section" 
                                  class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center gap-2">
                                   <i class="fas fa-keyboard"></i>
                                   أدخل رمز اختبار
                               </a>
                               
                               <a href="{{ route('quiz.demo') }}" 
                                  class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all duration-300 flex items-center gap-2">
                                   <i class="fas fa-play"></i>
                                   تجريبي
                               </a>
                           </div>
                       @endif
                       
                       {{-- Admin Settings Access (Show for admins regardless of other navigation) --}}
                       @if(Auth::user()->is_admin)
                           <div class="border-l border-gray-300 mx-4"></div>
                           <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                                       class="relative group">
                               <span class="flex items-center gap-2 text-red-600">
                                   <i class="fas fa-cog"></i>
                                   إعدادات النظام
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
                           <!-- Avatar with Role Color -->
                           <div class="w-8 h-8 rounded-full {{ Auth::user()->is_admin ? 'bg-gradient-to-br from-red-500 to-red-700' : (Auth::user()->user_type === 'teacher' ? 'bg-gradient-to-br from-blue-500 to-blue-700' : 'bg-gradient-to-br from-green-500 to-green-700') }} flex items-center justify-center text-white font-bold ml-2 group-hover:shadow-md transition-all duration-300">
                               {{ mb_substr(Auth::user()->name, 0, 1) }}
                           </div>
                           
                           <div class="text-right">
                               <div class="font-semibold">{{ Auth::user()->name }}</div>
                               <div class="text-xs text-gray-500">
                                   @if(Auth::user()->is_admin)
                                       <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full">مدير النظام</span>
                                   @elseif(Auth::user()->user_type === 'teacher')
                                       <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">معلم</span>
                                   @else
                                       <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">طالب</span>
                                   @endif
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
                               <div class="w-12 h-12 rounded-full {{ Auth::user()->is_admin ? 'bg-gradient-to-br from-red-500 to-red-700' : (Auth::user()->user_type === 'teacher' ? 'bg-gradient-to-br from-blue-500 to-blue-700' : 'bg-gradient-to-br from-green-500 to-green-700') }} flex items-center justify-center text-white font-bold text-lg ml-3">
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
                       
                       {{-- Role-specific dropdown items --}}
                       @if(Auth::user()->is_admin)
                           @if(Route::has('admin.settings'))
                           <x-dropdown-link :href="route('admin.settings')" class="flex items-center gap-2">
                               <i class="fas fa-sliders-h text-gray-500"></i>
                               إعدادات النظام
                           </x-dropdown-link>
                           @endif
                       @elseif(Auth::user()->user_type === 'teacher')
                           <x-dropdown-link :href="route('quizzes.index')" class="flex items-center gap-2">
                               <i class="fas fa-chart-line text-gray-500"></i>
                               إحصائياتي
                           </x-dropdown-link>
                           @if(Route::has('question.guide'))
                           <x-dropdown-link :href="route('question.guide')" class="flex items-center gap-2">
                               <i class="fas fa-book text-gray-500"></i>
                               دليل الأسئلة
                           </x-dropdown-link>
                           @endif
                       @else
                           <x-dropdown-link :href="route('results.index')" class="flex items-center gap-2">
                               <i class="fas fa-trophy text-gray-500"></i>
                               إنجازاتي
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

   <!-- Responsive Navigation Menu -->
   <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
       <div class="pt-2 pb-3 space-y-1">
           <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
               <i class="fas fa-home ml-2"></i>
               لوحة التحكم
           </x-responsive-nav-link>
           
           @auth
               {{-- Admin Mobile Navigation --}}
               @if(Auth::user()->is_admin)
                   <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                       <i class="fas fa-users ml-2"></i>
                       المستخدمين
                   </x-responsive-nav-link>
                   
                   <x-responsive-nav-link :href="route('admin.quizzes.index')" :active="request()->routeIs('admin.quizzes.*')">
                       <i class="fas fa-clipboard-check ml-2"></i>
                       جميع الاختبارات
                   </x-responsive-nav-link>
                   
                   @if(Route::has('admin.reports'))
                   <x-responsive-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">
                       <i class="fas fa-chart-bar ml-2"></i>
                       التقارير
                   </x-responsive-nav-link>
                   @endif
                   
                   <div class="border-t border-gray-200 my-2"></div>
                   
                   <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                                          class="text-red-600 font-semibold">
                       <i class="fas fa-cog ml-2"></i>
                       إعدادات النظام
                   </x-responsive-nav-link>
               
               {{-- Teacher Mobile Navigation --}}
               @elseif(Auth::user()->user_type === 'teacher')
                   <x-responsive-nav-link :href="route('quizzes.index')" :active="request()->routeIs('quizzes.*')">
                       <i class="fas fa-clipboard-list ml-2"></i>
                       اختباراتي
                       @if(Auth::user()->quizzes()->count() > 0)
                       <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full mr-2">
                           {{ Auth::user()->quizzes()->count() }}
                       </span>
                       @endif
                   </x-responsive-nav-link>
                   
                   <x-responsive-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                       <i class="fas fa-chart-line ml-2"></i>
                       نتائج الطلاب
                   </x-responsive-nav-link>
                   
                   <x-responsive-nav-link :href="route('quizzes.create')" :active="request()->routeIs('quizzes.create')"
                                          class="bg-purple-50 text-purple-700 font-semibold">
                       <i class="fas fa-plus-circle ml-2"></i>
                       إنشاء اختبار جديد
                   </x-responsive-nav-link>
               
               {{-- Student Mobile Navigation --}}
               @else
                   <x-responsive-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                       <i class="fas fa-chart-line ml-2"></i>
                       نتائجي
                       @if(Auth::user()->results()->count() > 0)
                       <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full mr-2">
                           {{ Auth::user()->results()->count() }}
                       </span>
                       @endif
                   </x-responsive-nav-link>
                   
                   <x-responsive-nav-link :href="route('home') . '#pin-section'" 
                                          class="bg-blue-50 text-blue-700 font-semibold">
                       <i class="fas fa-keyboard ml-2"></i>
                       أدخل رمز اختبار
                   </x-responsive-nav-link>
                   
                   <x-responsive-nav-link :href="route('quiz.demo')"
                                          class="bg-green-50 text-green-700 font-semibold">
                       <i class="fas fa-play ml-2"></i>
                       اختبار تجريبي
                   </x-responsive-nav-link>
               @endif
           @endauth
       </div>

       <!-- Responsive Settings Options -->
       @auth
       <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
           <div class="px-4 flex items-center">
               <div class="w-12 h-12 rounded-full {{ Auth::user()->is_admin ? 'bg-gradient-to-br from-red-500 to-red-700' : (Auth::user()->user_type === 'teacher' ? 'bg-gradient-to-br from-blue-500 to-blue-700' : 'bg-gradient-to-br from-green-500 to-green-700') }} flex items-center justify-center text-white font-bold text-lg ml-3">
                   {{ mb_substr(Auth::user()->name, 0, 1) }}
               </div>
               <div>
                   <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                   <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                   <div class="text-xs text-gray-500 mt-1">
                       @if(Auth::user()->is_admin)
                           <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full">مدير النظام</span>
                       @elseif(Auth::user()->user_type === 'teacher')
                           <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">معلم</span>
                       @else
                           <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">طالب</span>
                       @endif
                   </div>
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