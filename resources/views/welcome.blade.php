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
                <p class="text-2xl md:text-3xl text-gray-700 font-bold mt-4 animate-fade-in">نموذج تعليمي مبتكر</p>
            </div>

            <!-- Tagline with Typewriter Effect -->
            <div class="mb-12 animate-fade-in-up animation-delay-300">
                <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    حيث ينمو كل طالب بطريقته الخاصة
                </p>
                <p class="text-lg md:text-xl text-gray-500 mt-2">
                    أربعة جذور للمعرفة، إمكانيات لا محدودة للنمو
                </p>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto mb-12 animate-fade-in-up animation-delay-500">
                <!-- PIN Entry Card -->
                <div class="group relative bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 hover:shadow-3xl transition-all duration-300 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-blue-600/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform">
                            <i class="fas fa-key text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">دخول سريع بالرمز</h3>
                        <form action="{{ route('quiz.enter-pin') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="relative">
                                <input type="text" 
                                       name="pin" 
                                       id="pin-input"
                                       placeholder="DEMO01"
                                       maxlength="6"
                                       class="w-full px-6 py-4 text-2xl text-center font-mono uppercase border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all bg-gray-50/50"
                                       required>
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="fas fa-hashtag text-gray-400"></i>
                                </div>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 group">
                                <span>دخول الاختبار</span>
                                <i class="fas fa-arrow-left transform group-hover:-translate-x-1 transition-transform"></i>
                            </button>
                        </form>
                        <p class="text-sm text-gray-500 mt-4">احصل على رمز الاختبار من معلمك</p>
                    </div>
                </div>

                <!-- Registration Card -->
                <div class="group relative bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20 hover:shadow-3xl transition-all duration-300 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-600/10 to-teal-600/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-plus text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">للمعلمين والطلاب</h3>
                        <p class="text-gray-600 mb-6">أنشئ اختبارات ذكية وتابع تقدم طلابك</p>
                        <div class="space-y-3">
                            <a href="{{ route('register') }}" 
                               class="block w-full bg-gradient-to-r from-green-500 to-teal-500 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all text-center">
                                إنشاء حساب مجاني
                            </a>
                            <a href="{{ route('login') }}" 
                               class="block w-full bg-white text-gray-700 font-bold py-4 px-8 rounded-xl border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 transition-all text-center">
                                تسجيل الدخول
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demo Button -->
            <div class="animate-fade-in-up animation-delay-700">
                <a href="{{ route('quiz.demo') }}" 
                   class="inline-flex items-center gap-3 bg-white text-purple-600 font-bold py-4 px-8 rounded-xl border-2 border-purple-300 hover:bg-purple-50 hover:border-purple-400 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-play-circle text-2xl"></i>
                    <span>جرّب نموذج تجريبي</span>
                    <span class="bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded-full font-bold">مجاني</span>
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

<!-- Features Section with Interactive Cards -->
<section class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-purple-600 font-bold text-sm uppercase tracking-wider">نموذج جُذور</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-800 mt-3 mb-4">
                تعلّم بطريقة طبيعية وشاملة
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                كما تنمو الشجرة من بذرة صغيرة، ينمو فهمك من خلال أربعة جذور متكاملة
            </p>
        </div>

        <!-- Interactive Root Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            @php
            $roots = [
                [
                    'key' => 'jawhar',
                    'name' => 'جَوهر',
                    'emoji' => '🎯',
                    'question' => 'ما هو؟',
                    'desc' => 'فهم الماهية والتعريف',
                    'skills' => ['التعريف الدقيق', 'تحديد المكونات', 'التمييز بين المفاهيم'],
                    'gradient' => 'from-red-500 to-orange-500',
                    'bg' => 'from-red-50 to-orange-50',
                    'shadow' => 'shadow-red-200'
                ],
                [
                    'key' => 'zihn',
                    'name' => 'ذِهن',
                    'emoji' => '🧠',
                    'question' => 'كيف يعمل؟',
                    'desc' => 'تحليل الآليات والعمليات',
                    'skills' => ['التحليل العميق', 'فهم الأسباب', 'شرح العمليات'],
                    'gradient' => 'from-teal-500 to-cyan-500',
                    'bg' => 'from-teal-50 to-cyan-50',
                    'shadow' => 'shadow-teal-200'
                ],
                [
                    'key' => 'waslat',
                    'name' => 'وَصلات',
                    'emoji' => '🔗',
                    'question' => 'كيف يرتبط؟',
                    'desc' => 'اكتشاف العلاقات والروابط',
                    'skills' => ['الربط بين المفاهيم', 'التكامل المعرفي', 'رؤية الصورة الكبرى'],
                    'gradient' => 'from-yellow-500 to-orange-500',
                    'bg' => 'from-yellow-50 to-orange-50',
                    'shadow' => 'shadow-yellow-200'
                ],
                [
                    'key' => 'roaya',
                    'name' => 'رُؤية',
                    'emoji' => '👁️',
                    'question' => 'كيف نستخدمه؟',
                    'desc' => 'التطبيق والإبداع',
                    'skills' => ['حل المشكلات', 'الابتكار', 'التطبيق العملي'],
                    'gradient' => 'from-purple-500 to-indigo-500',
                    'bg' => 'from-purple-50 to-indigo-50',
                    'shadow' => 'shadow-purple-200'
                ]
            ];
            @endphp

            @foreach($roots as $root)
            <div class="group relative" data-root="{{ $root['key'] }}">
                <!-- Card -->
                <div class="relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 cursor-pointer h-full">
                    <!-- Gradient Background -->
                    <div class="absolute inset-0 bg-gradient-to-br {{ $root['bg'] }} rounded-2xl opacity-50"></div>
                    
                    <!-- Content -->
                    <div class="relative z-10">
                        <!-- Icon -->
                        <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform">{{ $root['emoji'] }}</div>
                        
                        <!-- Title -->
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $root['name'] }}</h3>
                        <p class="text-lg font-semibold text-transparent bg-clip-text bg-gradient-to-r {{ $root['gradient'] }} mb-3">
                            {{ $root['question'] }}
                        </p>
                        
                        <!-- Description -->
                        <p class="text-gray-600 mb-4">{{ $root['desc'] }}</p>
                        
                        <!-- Skills -->
                        <ul class="space-y-2">
                            @foreach($root['skills'] as $skill)
                            <li class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-check-circle text-green-500 ml-2"></i>
                                {{ $skill }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Central Concept -->
        <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-3xl p-8 md:p-12 text-center">
            <h3 class="text-3xl font-bold text-gray-800 mb-6">الفكرة المركزية</h3>
            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-seedling text-3xl text-green-600"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">كل معلومة بذرة</h4>
                    <p class="text-gray-600">يمكن أن تنمو في اتجاهات متعددة</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-network-wired text-3xl text-blue-600"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">التعلم الحقيقي</h4>
                    <p class="text-gray-600">يحدث عندما تتشابك الجذور</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-chart-line text-3xl text-purple-600"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">لا يوجد فشل</h4>
                    <p class="text-gray-600">فقط مستويات مختلفة من النمو</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Live Statistics Section -->
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
            <h3 class="text-2xl font-bold text-gray-800 mb-6">نمو الطلاب هذا الأسبوع</h3>
            <div class="space-y-6">
                @foreach(['jawhar' => ['name' => 'جَوهر', 'color' => 'red', 'growth' => 75],
                         'zihn' => ['name' => 'ذِهن', 'color' => 'teal', 'growth' => 82],
                         'waslat' => ['name' => 'وَصلات', 'color' => 'yellow', 'growth' => 68],
                         'roaya' => ['name' => 'رُؤية', 'color' => 'purple', 'growth' => 60]] as $key => $root)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-{{ $root['color'] }}-600">{{ $root['name'] }}</span>
                        <span class="text-sm text-gray-500">{{ $root['growth'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="progress-bar bg-gradient-to-r from-{{ $root['color'] }}-400 to-{{ $root['color'] }}-600 h-4 rounded-full transition-all duration-1000" 
                             style="width: 0%" 
                             data-width="{{ $root['growth'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- For Educators Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-purple-600 font-bold text-sm uppercase tracking-wider">للمعلمين</span>
            <h2 class="text-4xl font-black text-gray-800 mt-3 mb-4">أدوات قوية لتعليم أفضل</h2>
            <p class="text-xl text-gray-600">كل ما تحتاجه لتطبيق نموذج جُذور في صفك</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $features = [
                ['icon' => 'fa-robot', 'title' => 'توليد ذكي للأسئلة', 'desc' => 'استخدم Claude AI لإنشاء أسئلة متوازنة', 'color' => 'purple'],
                ['icon' => 'fa-chart-line', 'title' => 'تحليلات تفصيلية', 'desc' => 'تابع نمو طلابك في كل جذر', 'color' => 'blue'],
                ['icon' => 'fa-clock', 'title' => 'نتائج فورية', 'desc' => 'تقارير مرئية لحظية', 'color' => 'green'],
                ['icon' => 'fa-language', 'title' => 'متعدد اللغات', 'desc' => 'يدعم العربية والإنجليزية والعبرية', 'color' => 'yellow'],
                ['icon' => 'fa-shield-alt', 'title' => 'آمن وخاص', 'desc' => 'حماية كاملة لبيانات الطلاب', 'color' => 'red'],
                ['icon' => 'fa-mobile-alt', 'title' => 'متوافق مع الأجهزة', 'desc' => 'يعمل على جميع الأجهزة', 'color' => 'indigo']
            ];
            @endphp

            @foreach($features as $feature)
            <div class="group">
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 h-full">
                    <div class="w-14 h-14 bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 rounded-xl flex items-center justify-center mb-4 transform group-hover:scale-110 transition-transform">
                        <i class="fas {{ $feature['icon'] }} text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600">{{ $feature['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTA -->
        <div class="mt-16 text-center">
            <div class="inline-block bg-gradient-to-r from-purple-600 to-blue-600 p-1 rounded-3xl">
                <div class="bg-white rounded-3xl px-8 py-12">
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">ابدأ رحلتك التعليمية اليوم</h3>
                    <p class="text-xl text-gray-600 mb-8">انضم لمئات المعلمين الذين يستخدمون جُذور</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" 
                           class="bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <i class="fas fa-user-plus ml-2"></i>
                            إنشاء حساب معلم
                        </a>
                        <a href="#demo" 
                           class="bg-gray-100 text-gray-700 font-bold py-4 px-8 rounded-xl hover:bg-gray-200 transition-all">
                            <i class="fas fa-play ml-2"></i>
                            شاهد عرض توضيحي
                        </a>
                    </div>
                </div>
            </div>
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
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition">تواصل معنا</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">للمعلمين</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition">إنشاء حساب</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">دليل الاستخدام</a></li>
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

/* Hover effects */
.shadow-3xl {
    box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
}

/* PIN input styling */
#pin-input {
    letter-spacing: 0.5em;
}

#pin-input:focus {
    box-shadow: 0 0 0 4px rgba(147, 51, 234, 0.1);
}

/* Smooth scroll */
html {
    scroll-behavior: smooth;
}
</style>
@endpush

@push('scripts')
<script>
// PIN input formatting
document.getElementById('pin-input')?.addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
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
</script>
@endpush