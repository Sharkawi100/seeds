@extends('layouts.guest')

@section('title', 'منصة جُذور التعليمية - نموذج تعليمي مبتكر')

@section('content')
<!-- Hero Section with Animated Background -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 opacity-90"></div>
        <div class="absolute inset-0">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Logo Animation -->
            <div class="mb-8 animate-fade-in-down">
                <div class="inline-block relative">
                    <h1 class="text-7xl md:text-9xl font-black relative">
                        <span class="absolute inset-0 text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 blur-lg">جُذور</span>
                        <span class="relative text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600">جُذور</span>
                    </h1>
                    <div class="absolute -top-6 -right-6 animate-float">
                        <span class="text-4xl">🌱</span>
                    </div>
                </div>
                <p class="text-2xl md:text-3xl text-gray-700 font-bold mt-4 animate-fade-in">تعلّم ينمو معك</p>
            </div>

            <!-- Simplified Tagline -->
            <div class="mb-12 animate-fade-in-up animation-delay-300">
                <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    نموذج تعليمي مبتكر يحول كل معلومة إلى شجرة معرفة
                </p>
                <a href="{{ route('juzoor.model') }}" class="inline-flex items-center gap-2 mt-4 text-purple-600 hover:text-purple-700 font-bold text-lg group">
                    <span>ما هو جُذور؟</span>
                    <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform"></i>
                </a>
            </div>

            <!-- Enhanced PIN Entry -->
            <div class="max-w-xl mx-auto mb-12 animate-fade-in-up animation-delay-500">
                <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bolt text-xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">ابدأ الآن - دخول سريع</h3>
                    </div>
                    
                    <div class="max-w-md mx-auto">
                        <form action="{{ route('quiz.enter-pin') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    أدخل رمز الاختبار
                                </label>
                                <input type="text" 
                                       name="pin" 
                                       class="w-full px-4 py-3 text-center text-2xl font-bold tracking-wider uppercase border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100"
                                       placeholder="ABC123"
                                       maxlength="6"
                                       required
                                       autofocus>
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-xl font-bold hover:shadow-lg transform hover:scale-105 transition">
                                دخول الاختبار
                            </button>
                        </form>
                    </div>
                    
                    <div class="flex items-center gap-4 mt-6">
                        <div class="flex-1 h-px bg-gray-300"></div>
                        <span class="text-gray-500 text-sm">أو</span>
                        <div class="flex-1 h-px bg-gray-300"></div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mt-6">
                        <a href="{{ route('register') }}" 
                           class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl transition-all text-center">
                            إنشاء حساب
                        </a>
                        <a href="{{ route('login') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all text-center">
                            تسجيل دخول
                        </a>
                    </div>
                </div>
            </div>

            <!-- Demo Button -->
            <div class="animate-fade-in-up animation-delay-700">
                <a href="{{ route('quiz.demo') }}" 
                   class="inline-flex items-center gap-3 bg-white/90 text-purple-600 font-bold py-4 px-8 rounded-xl border-2 border-purple-300 hover:bg-purple-50 hover:border-purple-400 transition-all transform hover:scale-105 shadow-lg backdrop-blur">
                    <i class="fas fa-play-circle text-2xl"></i>
                    <span>جرّب اختبار تجريبي</span>
                    <span class="bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded-full font-bold animate-pulse">مجاني</span>
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="flex flex-col items-center text-gray-400">
                <span class="text-sm mb-2">اكتشف المزيد</span>
                <i class="fas fa-chevron-down text-2xl"></i>
            </div>
        </div>
    </div>
</section>

<!-- Quick Access Buttons -->
<section class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('juzoor.model') }}" class="group bg-white hover:bg-purple-50 px-8 py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center gap-3 border-2 border-purple-200 hover:border-purple-400">
                <span class="text-2xl">📚</span>
                <span class="font-bold text-gray-800 group-hover:text-purple-600">نموذج جُذور التعليمي</span>
                <i class="fas fa-arrow-left text-purple-600 transform group-hover:-translate-x-1 transition-transform"></i>
            </a>
            
            <a href="{{ route('question.guide') }}" class="group bg-white hover:bg-green-50 px-8 py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center gap-3 border-2 border-green-200 hover:border-green-400">
                <span class="text-2xl">📝</span>
                <span class="font-bold text-gray-800 group-hover:text-green-600">دليل إنشاء الأسئلة</span>
                <i class="fas fa-arrow-left text-green-600 transform group-hover:-translate-x-1 transition-transform"></i>
            </a>
            
            <a href="#teachers-section" class="group bg-white hover:bg-blue-50 px-8 py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center gap-3 border-2 border-blue-200 hover:border-blue-400">
                <span class="text-2xl">👨‍🏫</span>
                <span class="font-bold text-gray-800 group-hover:text-blue-600">للمعلمين</span>
                <i class="fas fa-arrow-down text-blue-600"></i>
            </a>
        </div>
    </div>
</section>

<!-- Model Overview Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-purple-600 font-bold text-sm uppercase tracking-wider animate-fade-in">نموذج جُذور</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-800 mt-3 mb-4 animate-fade-in-up">
                أربعة جذور للمعرفة الشاملة
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto animate-fade-in-up animation-delay-200">
                انقر على البذرة لترى كيف تنمو المعرفة في أربعة اتجاهات
            </p>
        </div>

        <!-- Interactive Seed and Roots Visualization -->
        <div class="relative max-w-5xl mx-auto mb-16">
            <div id="roots-container" class="relative h-[600px] flex items-center justify-center">
                <!-- Central Seed -->
                <div id="central-seed" class="absolute z-30 cursor-pointer transform hover:scale-110 transition-all duration-300" onclick="toggleRoots()">
                    <div class="w-40 h-40 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex flex-col items-center justify-center shadow-2xl animate-pulse-slow">
                        <span class="text-6xl mb-2">🌱</span>
                        <span class="text-white font-bold text-sm">انقر هنا</span>
                    </div>
                </div>

                <!-- Root Lines (Hidden by default) -->
                <svg class="absolute inset-0 w-full h-full z-10 pointer-events-none">
                    <!-- Top Line (Jawhar) -->
                    <line id="line-jawhar" x1="50%" y1="50%" x2="50%" y2="15%" stroke="url(#gradient-red)" stroke-width="3" stroke-dasharray="5,5" opacity="0" class="root-line"/>
                    
                    <!-- Right Line (Zihn) -->
                    <line id="line-zihn" x1="50%" y1="50%" x2="85%" y2="50%" stroke="url(#gradient-teal)" stroke-width="3" stroke-dasharray="5,5" opacity="0" class="root-line"/>
                    
                    <!-- Bottom Line (Waslat) -->
                    <line id="line-waslat" x1="50%" y1="50%" x2="50%" y2="85%" stroke="url(#gradient-yellow)" stroke-width="3" stroke-dasharray="5,5" opacity="0" class="root-line"/>
                    
                    <!-- Left Line (Roaya) -->
                    <line id="line-roaya" x1="50%" y1="50%" x2="15%" y2="50%" stroke="url(#gradient-purple)" stroke-width="3" stroke-dasharray="5,5" opacity="0" class="root-line"/>
                    
                    <!-- Gradients -->
                    <defs>
                        <linearGradient id="gradient-red" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ef4444;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#dc2626;stop-opacity:1" />
                        </linearGradient>
                        <linearGradient id="gradient-teal" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#14b8a6;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#0d9488;stop-opacity:1" />
                        </linearGradient>
                        <linearGradient id="gradient-yellow" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#eab308;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#ca8a04;stop-opacity:1" />
                        </linearGradient>
                        <linearGradient id="gradient-purple" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#a855f7;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#9333ea;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>

                <!-- Root Cards (Hidden by default) -->
                <!-- Jawhar (Top) -->
                <div id="root-jawhar" class="root-card absolute top-0 left-1/2 transform -translate-x-1/2 opacity-0 scale-0 transition-all duration-500 z-20">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-72 border-3 border-red-200 hover:border-red-400 cursor-pointer hover:scale-105 transition-all">
                        <div class="flex items-start gap-3">
                            <span class="text-4xl">🎯</span>
                            <div>
                                <h3 class="text-xl font-bold text-red-600">جَوهر</h3>
                                <p class="text-sm font-semibold text-gray-700">ما هو؟</p>
                                <p class="text-xs text-gray-500 mt-1">التعريف والماهية</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Zihn (Right) -->
                <div id="root-zihn" class="root-card absolute right-0 top-1/2 transform -translate-y-1/2 opacity-0 scale-0 transition-all duration-500 z-20">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-72 border-3 border-teal-200 hover:border-teal-400 cursor-pointer hover:scale-105 transition-all">
                        <div class="flex items-start gap-3">
                            <span class="text-4xl">🧠</span>
                            <div>
                                <h3 class="text-xl font-bold text-teal-600">ذِهن</h3>
                                <p class="text-sm font-semibold text-gray-700">كيف يعمل؟</p>
                                <p class="text-xs text-gray-500 mt-1">التحليل والفهم</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Waslat (Bottom) -->
                <div id="root-waslat" class="root-card absolute bottom-0 left-1/2 transform -translate-x-1/2 opacity-0 scale-0 transition-all duration-500 z-20">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-72 border-3 border-yellow-200 hover:border-yellow-400 cursor-pointer hover:scale-105 transition-all">
                        <div class="flex items-start gap-3">
                            <span class="text-4xl">🔗</span>
                            <div>
                                <h3 class="text-xl font-bold text-yellow-600">وَصلات</h3>
                                <p class="text-sm font-semibold text-gray-700">كيف يرتبط؟</p>
                                <p class="text-xs text-gray-500 mt-1">العلاقات والروابط</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roaya (Left) -->
                <div id="root-roaya" class="root-card absolute left-0 top-1/2 transform -translate-y-1/2 opacity-0 scale-0 transition-all duration-500 z-20">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-72 border-3 border-purple-200 hover:border-purple-400 cursor-pointer hover:scale-105 transition-all">
                        <div class="flex items-start gap-3">
                            <span class="text-4xl">👁️</span>
                            <div>
                                <h3 class="text-xl font-bold text-purple-600">رُؤية</h3>
                                <p class="text-sm font-semibold text-gray-700">كيف نستخدمه؟</p>
                                <p class="text-xs text-gray-500 mt-1">التطبيق والإبداع</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Model Details Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
            <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                <div class="text-center mb-4">
                    <span class="text-4xl">🎯</span>
                    <h3 class="text-xl font-bold text-red-600 mt-2">جَوهر</h3>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>التعريف الدقيق</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>تحديد المكونات</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>التمييز بين المفاهيم</span>
                    </li>
                </ul>
            </div>

            <div class="bg-gradient-to-br from-teal-50 to-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                <div class="text-center mb-4">
                    <span class="text-4xl">🧠</span>
                    <h3 class="text-xl font-bold text-teal-600 mt-2">ذِهن</h3>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>التحليل العميق</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>فهم الأسباب</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>شرح العمليات</span>
                    </li>
                </ul>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                <div class="text-center mb-4">
                    <span class="text-4xl">🔗</span>
                    <h3 class="text-xl font-bold text-yellow-600 mt-2">وَصلات</h3>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>الربط بين المفاهيم</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>التكامل المعرفي</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>رؤية الصورة الكبرى</span>
                    </li>
                </ul>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all">
                <div class="text-center mb-4">
                    <span class="text-4xl">👁️</span>
                    <h3 class="text-xl font-bold text-purple-600 mt-2">رُؤية</h3>
                </div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>حل المشكلات</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>الابتكار</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>التطبيق العملي</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Central Concept -->
        <div class="mt-16 bg-gradient-to-r from-purple-100 to-blue-100 rounded-3xl p-8 md:p-12 text-center max-w-5xl mx-auto">
            <h3 class="text-3xl font-bold text-gray-800 mb-6">الفكرة المركزية</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg transform hover:scale-110 transition-transform">
                        <i class="fas fa-seedling text-3xl text-green-600"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">كل معلومة بذرة</h4>
                    <p class="text-gray-600">يمكن أن تنمو في اتجاهات متعددة</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg transform hover:scale-110 transition-transform">
                        <i class="fas fa-network-wired text-3xl text-blue-600"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">التعلم الحقيقي</h4>
                    <p class="text-gray-600">يحدث عندما تتشابك الجذور</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg transform hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-3xl text-purple-600"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">لا يوجد فشل</h4>
                    <p class="text-gray-600">فقط مستويات مختلفة من النمو</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-12">
            <a href="{{ route('juzoor.model') }}" 
               class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                <span>اكتشف التفاصيل الكاملة للنموذج</span>
                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

<!-- For Teachers Section (Enhanced) -->
<section id="teachers-section" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-green-600 font-bold text-sm uppercase tracking-wider">للمعلمين</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-800 mt-3 mb-4">أدوات قوية لتعليم أفضل</h2>
            <p class="text-xl text-gray-600">كل ما تحتاجه لتطبيق نموذج جُذور في صفك</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @php
            $features = [
                ['icon' => 'fa-robot', 'title' => 'توليد ذكي للأسئلة', 'desc' => 'استخدم Claude AI لإنشاء أسئلة متوازنة تغطي جميع الجذور الأربعة', 'color' => 'purple'],
                ['icon' => 'fa-chart-line', 'title' => 'تحليلات تفصيلية', 'desc' => 'تابع نمو طلابك في كل جذر وحدد نقاط القوة والضعف', 'color' => 'blue'],
                ['icon' => 'fa-clock', 'title' => 'نتائج فورية', 'desc' => 'تقارير مرئية لحظية مع تحليل شامل للأداء', 'color' => 'green'],
                ['icon' => 'fa-language', 'title' => 'متعدد اللغات', 'desc' => 'يدعم العربية والإنجليزية والعبرية لتنوع أكبر', 'color' => 'yellow'],
                ['icon' => 'fa-shield-alt', 'title' => 'آمن وخاص', 'desc' => 'حماية كاملة لبيانات الطلاب وخصوصيتهم', 'color' => 'red'],
                ['icon' => 'fa-mobile-alt', 'title' => 'متوافق مع الأجهزة', 'desc' => 'يعمل بسلاسة على جميع الأجهزة والشاشات', 'color' => 'indigo']
            ];
            @endphp

            @foreach($features as $feature)
            <div class="group">
                <div class="bg-gray-50 rounded-2xl p-6 hover:bg-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 h-full">
                    <div class="w-14 h-14 bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 rounded-xl flex items-center justify-center mb-4 transform group-hover:scale-110 transition-transform">
                        <i class="fas {{ $feature['icon'] }} text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600">{{ $feature['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Teacher Journey -->
        <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-3xl p-8 md:p-12">
            <h3 class="text-3xl font-bold text-gray-800 mb-8 text-center">رحلتك مع جُذور</h3>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-2xl font-bold text-green-600">1</span>
                    </div>
                    <h4 class="font-bold mb-2">سجل حسابك</h4>
                    <p class="text-sm text-gray-600">إنشاء حساب مجاني في دقائق</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-2xl font-bold text-green-600">2</span>
                    </div>
                    <h4 class="font-bold mb-2">أنشئ اختباراتك</h4>
                    <p class="text-sm text-gray-600">بالذكاء الاصطناعي أو يدوياً</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-2xl font-bold text-green-600">3</span>
                    </div>
                    <h4 class="font-bold mb-2">شارك مع طلابك</h4>
                    <p class="text-sm text-gray-600">برمز PIN بسيط</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-2xl font-bold text-green-600">4</span>
                    </div>
                    <h4 class="font-bold mb-2">تابع النمو</h4>
                    <p class="text-sm text-gray-600">بتقارير شاملة ومرئية</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="mt-12 text-center">
            <a href="{{ route('register') }}" 
               class="inline-block bg-gradient-to-r from-green-500 to-teal-500 text-white font-bold py-4 px-10 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all text-lg">
                <i class="fas fa-user-plus ml-2"></i>
                ابدأ رحلتك التعليمية اليوم
            </a>
        </div>
    </div>
</section>

<!-- For Students Section (New) -->
<section id="students-section" class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-bold text-sm uppercase tracking-wider">للطلاب</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-800 mt-3 mb-4">تعلّم بطريقة ممتعة وذكية</h2>
            <p class="text-xl text-gray-600">اكتشف طريقتك الخاصة في التعلم مع جُذور</p>
        </div>

        <!-- Fun Root Visualization for Students -->
        <div class="relative max-w-3xl mx-auto mb-16">
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-3xl shadow-2xl p-8 md:p-12">
                <h3 class="text-2xl font-bold text-center mb-8">كيف تنمو معرفتك مع جُذور؟</h3>
                
                <!-- Smooth Interactive Tree Visual -->
                <div class="relative h-[500px]" id="student-tree-container">
                    <!-- Background circles for depth -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="absolute w-96 h-96 bg-purple-100 rounded-full opacity-20 animate-pulse-slow"></div>
                        <div class="absolute w-72 h-72 bg-blue-100 rounded-full opacity-30 animate-pulse-slow animation-delay-1000"></div>
                        <div class="absolute w-48 h-48 bg-green-100 rounded-full opacity-40 animate-pulse-slow animation-delay-2000"></div>
                    </div>
                    
                    <!-- Central Seed -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                        <div id="student-seed" class="w-32 h-32 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex flex-col items-center justify-center shadow-2xl cursor-pointer hover:scale-110 transition-all" onclick="animateStudentGrowth()">
                            <span class="text-5xl">🌱</span>
                            <span class="text-white text-xs font-bold mt-1">ابدأ النمو</span>
                        </div>
                    </div>
                    
                    <!-- Growth Stages (Hidden initially) -->
                    <div id="growth-stage-1" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 scale-0 transition-all duration-1000">
                        <!-- Sprouting roots -->
                        <div class="relative w-64 h-64">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-8">
                                <div class="bg-red-100 rounded-full p-3 shadow-lg">
                                    <span class="text-2xl">🎯</span>
                                </div>
                            </div>
                            <div class="absolute top-1/2 right-0 translate-x-8 -translate-y-1/2">
                                <div class="bg-teal-100 rounded-full p-3 shadow-lg">
                                    <span class="text-2xl">🧠</span>
                                </div>
                            </div>
                            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-8">
                                <div class="bg-yellow-100 rounded-full p-3 shadow-lg">
                                    <span class="text-2xl">🔗</span>
                                </div>
                            </div>
                            <div class="absolute top-1/2 left-0 -translate-x-8 -translate-y-1/2">
                                <div class="bg-purple-100 rounded-full p-3 shadow-lg">
                                    <span class="text-2xl">👁️</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="growth-stage-2" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 scale-0 transition-all duration-1000">
                        <!-- Growing tree -->
                        <div class="relative">
                            <span class="text-8xl">🌿</span>
                        </div>
                    </div>
                    
                    <div id="growth-stage-3" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 scale-0 transition-all duration-1000">
                        <!-- Full tree -->
                        <div class="relative">
                            <span class="text-9xl">🌳</span>
                        </div>
                    </div>
                    
                    <!-- Progress indicators -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-3">
                        <div id="progress-1" class="w-3 h-3 bg-gray-300 rounded-full transition-all"></div>
                        <div id="progress-2" class="w-3 h-3 bg-gray-300 rounded-full transition-all"></div>
                        <div id="progress-3" class="w-3 h-3 bg-gray-300 rounded-full transition-all"></div>
                        <div id="progress-4" class="w-3 h-3 bg-gray-300 rounded-full transition-all"></div>
                    </div>
                </div>
                
                <div class="text-center mt-8">
                    <p class="text-gray-600 font-semibold">كل سؤال تجيب عليه ينمي جذراً مختلفاً من معرفتك!</p>
                    <p class="text-sm text-gray-500 mt-2">مع الوقت، تتحول البذرة الصغيرة إلى شجرة معرفة قوية 🌳</p>
                </div>
            </div>
        </div>

        <!-- How to Use Guide -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <div class="bg-blue-50 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-4">1️⃣</div>
                <h4 class="font-bold text-lg mb-2">احصل على الرمز</h4>
                <p class="text-gray-600 text-sm">اطلب من معلمك رمز PIN للاختبار</p>
            </div>
            
            <div class="bg-blue-50 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-4">2️⃣</div>
                <h4 class="font-bold text-lg mb-2">أدخل الرمز</h4>
                <p class="text-gray-600 text-sm">اكتبه في المربع أعلاه وابدأ</p>
            </div>
            
            <div class="bg-blue-50 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-4">3️⃣</div>
                <h4 class="font-bold text-lg mb-2">أجب بذكاء</h4>
                <p class="text-gray-600 text-sm">فكر جيداً واختر أفضل إجابة</p>
            </div>
            
            <div class="bg-blue-50 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-4">4️⃣</div>
                <h4 class="font-bold text-lg mb-2">شاهد نموك</h4>
                <p class="text-gray-600 text-sm">اكتشف نقاط قوتك في التعلم</p>
            </div>
        </div>

        <!-- Fun PIN Entry for Students -->
        <div class="max-w-lg mx-auto">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-3xl p-1">
                <div class="bg-white rounded-3xl p-8">
                    <h3 class="text-2xl font-bold text-center mb-6">جاهز للتحدي؟ 🚀</h3>
                    
                    <form action="{{ route('quiz.enter-pin') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="text" 
                               name="pin" 
                               placeholder="أدخل رمز الاختبار هنا"
                               maxlength="6"
                               class="w-full px-6 py-4 text-2xl text-center font-mono uppercase border-2 border-blue-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all"
                               required>
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 group">
                            <span>هيا بنا!</span>
                            <i class="fas fa-gamepad transform group-hover:rotate-12 transition-transform"></i>
                        </button>
                    </form>
                    
                    <p class="text-center text-gray-500 text-sm mt-4">
                        💡 نصيحة: اسأل معلمك عن رمز الاختبار
                    </p>
                </div>
            </div>
        </div>

        <!-- Why Students Love Juzoor -->
        <div class="mt-16 grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="text-5xl mb-4">🎮</div>
                <h4 class="text-xl font-bold mb-2">مثل اللعبة</h4>
                <p class="text-gray-600">أسئلة متنوعة وممتعة تجعل التعلم مغامرة</p>
            </div>
            
            <div class="text-center">
                <div class="text-5xl mb-4">📊</div>
                <h4 class="text-xl font-bold mb-2">تقدم واضح</h4>
                <p class="text-gray-600">شاهد كيف تنمو معرفتك في كل جذر</p>
            </div>
            
            <div class="text-center">
                <div class="text-5xl mb-4">🏆</div>
                <h4 class="text-xl font-bold mb-2">لا يوجد فشل</h4>
                <p class="text-gray-600">كل محاولة هي فرصة للنمو والتحسن</p>
            </div>
        </div>
    </div>
</section>

<!-- Live Statistics Section (Updated) -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-purple-600 font-bold text-sm uppercase tracking-wider">إحصائيات حية</span>
            <h2 class="text-4xl font-black text-gray-800 mt-3 mb-4">منصة نشطة ومتنامية</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
            <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl">
                <div class="text-4xl font-black text-purple-600 mb-2">
                    <span class="counter" data-target="{{ $stats['total_quizzes'] ?? 156 }}">0</span>+
                </div>
                <p class="text-gray-600">اختبار منشور</p>
            </div>
            <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl">
                <div class="text-4xl font-black text-blue-600 mb-2">
                    <span class="counter" data-target="{{ $stats['total_attempts'] ?? 2341 }}">0</span>+
                </div>
                <p class="text-gray-600">محاولة هذا الشهر</p>
            </div>
            <div class="text-center p-6 bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl">
                <div class="text-4xl font-black text-green-600 mb-2">
                    <span class="counter" data-target="{{ $stats['active_schools'] ?? 12 }}">0</span>
                </div>
                <p class="text-gray-600">مدرسة نشطة</p>
            </div>
            <div class="text-center p-6 bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl">
                <div class="text-4xl font-black text-orange-600 mb-2">
                    <span class="counter" data-target="{{ $stats['total_questions'] ?? 1847 }}">0</span>+
                </div>
                <p class="text-gray-600">سؤال تفاعلي</p>
            </div>
        </div>

        <!-- Growth Chart -->
        <div class="bg-gray-50 rounded-3xl p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">متوسط نمو الطلاب هذا الأسبوع</h3>
            <div class="space-y-6">
                @foreach($growthStats as $key => $root)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">
                                {{ ['jawhar' => '🎯', 'zihn' => '🧠', 'waslat' => '🔗', 'roaya' => '👁️'][$key] }}
                            </span>
                            <span class="font-bold text-gray-700">
                                {{ ['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'][$key] }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-bold text-gray-800">{{ $root['percentage'] }}%</span>
                            <span class="text-sm text-green-600 mr-2">↑{{ $root['growth'] }}%</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="progress-bar bg-gradient-to-r {{ 
                            $key == 'jawhar' ? 'from-red-400 to-red-600' : 
                            ($key == 'zihn' ? 'from-teal-400 to-teal-600' : 
                            ($key == 'waslat' ? 'from-yellow-400 to-yellow-600' : 'from-purple-400 to-purple-600')) 
                        }} h-4 rounded-full transition-all duration-1000" 
                             style="width: 0%" 
                             data-width="{{ $root['percentage'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="py-20 bg-gradient-to-br from-purple-600 to-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6">جاهز لتحويل التعلم إلى رحلة نمو؟</h2>
        <p class="text-xl mb-8 opacity-90">انضم لآلاف المعلمين والطلاب الذين يستخدمون جُذور</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-white text-purple-600 font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-rocket ml-2"></i>
                ابدأ مجاناً
            </a>
            <a href="{{ route('juzoor.model') }}" 
               class="bg-transparent border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-purple-600 transition-all">
                <i class="fas fa-book ml-2"></i>
                تعرف على النموذج
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-2xl font-bold mb-4">جُذور</h3>
                <p class="text-gray-400">منصة تعليمية مبتكرة تُحول التعلم إلى رحلة نمو شخصية</p>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">روابط سريعة</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition">عن جُذور</a></li>
                    <li><a href="{{ route('juzoor.model') }}" class="text-gray-400 hover:text-white transition">نموذج جُذور</a></li>
                    <li><a href="{{ route('contact.show') }}" class="text-gray-400 hover:text-white transition">تواصل معنا</a></li>            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">للمستخدمين</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition">إنشاء حساب</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">تسجيل دخول</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">الأسئلة الشائعة</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">تابعنا</h4>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2024 جُذور. جميع الحقوق محفوظة. صُنع بـ ❤️ للتعليم العربي</p>
        </div>
    </div>
</footer>
@endsection

@push('styles')
<style>
/* Animations */
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

@keyframes fade-in-down {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-fade-in-down {
    animation: fade-in-down 0.8s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animation-delay-300 {
    animation-delay: 300ms;
}

.animation-delay-500 {
    animation-delay: 500ms;
}

.animation-delay-700 {
    animation-delay: 700ms;
}

/* Custom styles for RTL */
[dir="rtl"] .group:hover .group-hover\:-translate-x-1 {
    transform: translateX(0.25rem);
}

/* Progress bar animation */
.progress-bar {
    transition: width 1.5s ease-out;
}

/* PIN input styling */
#pin-input {
    letter-spacing: 0.3em;
}

#pin-input:focus {
    box-shadow: 0 0 0 4px rgba(147, 51, 234, 0.1);
}

/* Smooth scroll */
html {
    scroll-behavior: smooth;
}

/* Hover effects for root cards */
.group:hover .shadow-xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
</style>
@endpush

@push('scripts')
<script>
// PIN input formatting
document.querySelectorAll('input[name="pin"]').forEach(input => {
    input.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
});

// Counter animation
function animateCounter(element) {
    const target = parseInt(element.dataset.target);
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.round(current).toLocaleString('ar-SA');
    }, 16);
}

// Intersection Observer for animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Animate counters
            if (entry.target.classList.contains('counter')) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
            
            // Animate progress bars
            if (entry.target.classList.contains('progress-bar')) {
                setTimeout(() => {
                    entry.target.style.width = entry.target.dataset.width;
                }, 200);
                observer.unobserve(entry.target);
            }
        }
    });
}, { threshold: 0.5 });

// Observe elements
document.querySelectorAll('.counter, .progress-bar').forEach(el => {
    observer.observe(el);
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Interactive root hover effects
document.querySelectorAll('.group').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.querySelector('[class*="text-5xl"]')?.classList.add('animate-pulse');
    });
    
    card.addEventListener('mouseleave', function() {
        this.querySelector('[class*="text-5xl"]')?.classList.remove('animate-pulse');
    });
});
</script>
@endpush