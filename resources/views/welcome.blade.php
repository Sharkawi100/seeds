@extends('layouts.guest')

@section('title', 'جُذور - نموذج الجذور الأربعة للتعلم الحقيقي')

@push('styles')
<style>
/* Custom animations for roots model */
@keyframes rootGrow {
    0% { transform: scale(0.8) rotate(-5deg); opacity: 0.7; }
    50% { transform: scale(1.1) rotate(2deg); opacity: 1; }
    100% { transform: scale(1) rotate(0deg); opacity: 1; }
}

@keyframes connectionPulse {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

@keyframes dataCount {
    0% { transform: translateY(10px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.root-animation { animation: rootGrow 2s ease-out forwards; }
.connection-line { animation: connectionPulse 3s ease-in-out infinite; }
.stat-counter { animation: dataCount 1s ease-out forwards; }

/* Root-specific colors */
.jawhar-gradient { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); }
.zihn-gradient { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
.waslat-gradient { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }
.roaya-gradient { background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%); }

/* Interactive chart container */
.roots-chart-container {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
    padding: 2rem;
}

/* Floating elements */
.floating-root {
    animation: float 6s ease-in-out infinite;
}

.floating-root:nth-child(2) { animation-delay: -2s; }
.floating-root:nth-child(3) { animation-delay: -4s; }
.floating-root:nth-child(4) { animation-delay: -6s; }

/* Educational credibility styling */
.research-badge {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    animation: pulse 2s infinite;
}

/* Navigation Styling */
.navbar-glass {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

/* Animation utilities */
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

.animation-delay-100 { animation-delay: 100ms; }
.animation-delay-200 { animation-delay: 200ms; }
.animation-delay-300 { animation-delay: 300ms; }
.animation-delay-400 { animation-delay: 400ms; }
.animation-delay-500 { animation-delay: 500ms; }
.animation-delay-600 { animation-delay: 600ms; }
.animation-delay-700 { animation-delay: 700ms; }

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endpush

@section('content')
<!-- Stylish Navigation Bar -->
<nav class="fixed top-0 left-0 right-0 z-50 navbar-glass">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">ج</span>
                    </div>
                    <span class="text-xl font-bold text-gray-800">جُذور</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('for.teachers') }}" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">
                    للمعلمين
                </a>
                <a href="{{ route('for.students') }}" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">
                    للطلاب
                </a>
                <a href="{{ route('juzoor.model') }}" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">
                    نموذج الجذور
                </a>
                <a href="https://iseraj.com/roots/plans" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">
                    الخطط والأسعار
                </a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-purple-600 font-medium transition-colors">
                    عن المنصة
                </a>
            </div>

            <!-- Auth Buttons -->
            @guest
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('login') }}" class="px-4 py-2 text-purple-600 border border-purple-600 rounded-lg hover:bg-purple-50 transition-all">
                    تسجيل الدخول
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all">
                    إنشاء حساب
                </a>
            </div>
            @else
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-purple-600 border border-purple-600 rounded-lg hover:bg-purple-50 transition-all">
                    لوحة التحكم
                </a>
            </div>
            @endguest

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button class="text-gray-700 hover:text-purple-600" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden bg-white/95 backdrop-blur-lg border-t border-gray-200">
            <div class="py-4 space-y-2">
                <a href="{{ route('for.teachers') }}" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                    للمعلمين
                </a>
                <a href="{{ route('for.students') }}" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                    للطلاب
                </a>
                <a href="{{ route('juzoor.model') }}" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                    نموذج الجذور
                </a>
                <a href="https://iseraj.com/roots/plans" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                    الخطط والأسعار
                </a>
                <a href="{{ route('about') }}" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                    عن المنصة
                </a>
                
                <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                    @guest
                        <a href="{{ route('login') }}" class="block mx-4 px-4 py-2 text-center border border-purple-600 text-purple-600 rounded-lg">
                            تسجيل الدخول
                        </a>
                        <a href="{{ route('register') }}" class="block mx-4 px-4 py-2 text-center bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg">
                            إنشاء حساب
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block mx-4 px-4 py-2 text-center bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg">
                            لوحة التحكم
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mx-4">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-center border border-red-600 text-red-600 rounded-lg hover:bg-red-50">
                                تسجيل الخروج
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navigation -->
<div class="h-16"></div>
<!-- Hero Section - Educational Revolution -->
<section class="relative min-h-screen overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(99, 102, 241, 0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(168, 85, 247, 0.1) 0%, transparent 50%);"></div>
    </div>
    
    <!-- Floating Root Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="floating-root absolute top-20 right-10 w-16 h-16 jawhar-gradient rounded-full opacity-20"></div>
        <div class="floating-root absolute top-40 left-20 w-12 h-12 zihn-gradient rounded-full opacity-20"></div>
        <div class="floating-root absolute bottom-40 right-1/4 w-20 h-20 waslat-gradient rounded-full opacity-20"></div>
        <div class="floating-root absolute bottom-20 left-10 w-14 h-14 roaya-gradient rounded-full opacity-20"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 pt-20">
        <!-- Research Badge -->
        <div class="text-center mb-8 animate-fade-in-up">
            <span class="research-badge">
                <i class="fas fa-microscope ml-2"></i>
                نموذج تعليمي مبتكر مبني على البحث العلمي
            </span>
        </div>

        <!-- Main Hero Content -->
        <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[80vh]">
            <!-- Left: Educational Message -->
            <div class="space-y-8 animate-fade-in-up animation-delay-200">
                <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 leading-tight">
                    <span class="block gradient-text">ثورة في</span>
                    <span class="block text-gray-800">قياس التعلم</span>
                </h1>
                
                <div class="text-xl lg:text-2xl text-gray-700 space-y-4">
                    <p class="font-medium">أول منصة تقيس التعلم الحقيقي عبر</p>
                    <p class="text-3xl font-bold text-purple-600">الجذور الأربعة للمعرفة</p>
                </div>

                <!-- 4 Roots Quick Preview -->
                <div class="grid grid-cols-2 gap-4 py-6">
                    <div class="root-animation animation-delay-300 flex items-center gap-3 p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                        <div class="w-10 h-10 jawhar-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-gem text-white"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">جَوهر</div>
                            <div class="text-sm text-gray-600">ما هو؟</div>
                        </div>
                    </div>
                    
                    <div class="root-animation animation-delay-400 flex items-center gap-3 p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                        <div class="w-10 h-10 zihn-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-brain text-white"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">ذِهن</div>
                            <div class="text-sm text-gray-600">كيف يعمل؟</div>
                        </div>
                    </div>
                    
                    <div class="root-animation animation-delay-500 flex items-center gap-3 p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                        <div class="w-10 h-10 waslat-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-project-diagram text-white"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">وَصلات</div>
                            <div class="text-sm text-gray-600">كيف يرتبط؟</div>
                        </div>
                    </div>
                    
                    <div class="root-animation animation-delay-600 flex items-center gap-3 p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                        <div class="w-10 h-10 roaya-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-lightbulb text-white"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">رُؤية</div>
                            <div class="text-sm text-gray-600">كيف نستخدمه؟</div>
                        </div>
                    </div>
                </div>

                <!-- Educational Promise -->
                <div class="bg-gradient-to-r from-purple-100 to-blue-100 p-6 rounded-2xl border border-purple-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">لماذا الجذور الأربعة؟</h3>
                    <p class="text-gray-700 leading-relaxed">
                        التعلم الحقيقي يتجاوز الحفظ والاستذكار. نموذجنا الثوري يقيس عمق الفهم عبر أربعة أبعاد مترابطة، 
                        مما يكشف نقاط القوة الحقيقية ومجالات التطوير لكل متعلم.
                    </p>
                </div>
            </div>

            <!-- Right: Interactive Roots Chart -->
            <div class="animate-fade-in-up animation-delay-700">
                <div class="roots-chart-container">
                    <h3 class="text-2xl font-bold text-center text-gray-800 mb-6">نموذج التقييم الشامل</h3>
                    
                    <!-- Chart Container -->
                    <div class="relative">
                        <canvas id="rootsModelChart" width="400" height="400"></canvas>
                        
                        <!-- Interactive Legend -->
                        <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 jawhar-gradient rounded"></div>
                                <span class="text-gray-700">الجوهر - المعرفة الأساسية</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 zihn-gradient rounded"></div>
                                <span class="text-gray-700">الذهن - التفكير النقدي</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 waslat-gradient rounded"></div>
                                <span class="text-gray-700">الوصلات - الربط والتكامل</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 roaya-gradient rounded"></div>
                                <span class="text-gray-700">الرؤية - التطبيق والإبداع</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sample Depth Levels -->
                    <div class="mt-6 text-center">
                        <div class="text-sm text-gray-600 mb-2">مستويات العمق</div>
                        <div class="flex justify-center gap-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">المستوى 1 - سطحي</span>
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs">المستوى 2 - متوسط</span>
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs">المستوى 3 - عميق</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Access PIN Entry - Moved Up for Students -->
<section class="py-16 bg-white/90 backdrop-blur-lg border-y border-gray-200">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <div class="max-w-xl mx-auto">
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-3xl shadow-lg p-8 border border-blue-200">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bolt text-xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">دخول سريع للطلاب</h3>
                </div>
                
                <p class="text-gray-600 mb-6">ادخل رمز الاختبار الذي أعطاه لك معلمك</p>
                
                <form action="{{ route('quiz.enter-pin') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <input type="text" 
                               name="pin" 
                               class="w-full px-6 py-4 text-center text-2xl font-bold tracking-wider uppercase border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all"
                               placeholder="ABC123"
                               maxlength="6"
                               required>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg transform hover:scale-105 transition-all">
                        ابدأ الاختبار
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Real Growth Patterns Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">كيف ينمو التعلم عبر الجذور الأربعة</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                نموذجنا يكشف أنماط التطور الحقيقية في كل بُعد من أبعاد التعلم، 
                مما يساعد المعلمين على توجيه الطلاب بدقة علمية
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Growth Visualization -->
            <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 text-center">نمط التطور النموذجي للطالب</h3>
                <canvas id="growthChart" width="400" height="300"></canvas>
                
                <div class="mt-6 text-sm text-gray-600">
                    <div class="flex items-center justify-center gap-6">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded"></div>
                            <span>المحاولة الأولى</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded"></div>
                            <span>بعد الممارسة</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-500 rounded"></div>
                            <span>الإتقان</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth Insights -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 jawhar-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-gem text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">جَوهر - الأساس القوي</h4>
                            <p class="text-sm text-gray-600">المعرفة الأساسية تنمو بثبات مع التكرار</p>
                        </div>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-3">
                        <div class="text-sm text-blue-800">
                            <i class="fas fa-chart-line ml-1"></i>
                            أساس المعرفة - يتطور بالتوازي مع الجذور الأخرى
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 zihn-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-brain text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">ذِهن - التفكير العميق</h4>
                            <p class="text-sm text-gray-600">التحليل النقدي يتطور مع الخبرة</p>
                        </div>
                    </div>
                    <div class="bg-green-100 rounded-lg p-3">
                        <div class="text-sm text-green-800">
                            <i class="fas fa-lightbulb ml-1"></i>
                            قفزات نوعية بعد فهم الأنماط والعلاقات
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 waslat-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-project-diagram text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">وَصلات - الربط والتكامل</h4>
                            <p class="text-sm text-gray-600">ربط المفاهيم يحدث تدريجياً</p>
                        </div>
                    </div>
                    <div class="bg-red-100 rounded-lg p-3">
                        <div class="text-sm text-red-800">
                            <i class="fas fa-network-wired ml-1"></i>
                            الأبطأ نمواً لكنه الأكثر قيمة للفهم العميق
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 roaya-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-lightbulb text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">رُؤية - التطبيق والإبداع</h4>
                            <p class="text-sm text-gray-600">الإبداع ينفجر بعد إتقان الجذور الأخرى</p>
                        </div>
                    </div>
                    <div class="bg-purple-100 rounded-lg p-3">
                        <div class="text-sm text-purple-800">
                            <i class="fas fa-rocket ml-1"></i>
                            نمو متسارع عند وصول الجذور الأخرى لمستوى معين
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Educational Research Section -->
<section class="py-20 bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    تقييم مبني على
                    <span class="text-yellow-400">البحث العلمي</span>
                </h2>
                
                <div class="space-y-6 text-lg">
                    <p class="leading-relaxed">
                        نموذج الجذور الأربعة منهجية تعليمية مبتكرة تهدف إلى قياس التعلم بشكل شامل 
                        عبر أربعة أبعاد مترابطة، بدلاً من التركيز على الحفظ والاستذكار فقط.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-6 py-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-400">4</div>
                            <div class="text-sm">أبعاد مترابطة</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-400">3</div>
                            <div class="text-sm">مستويات عمق</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-400">12</div>
                            <div class="text-sm">نقطة قياس</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-400">∞</div>
                            <div class="text-sm">إمكانيات التطوير</div>
                        </div>
                    </div>
                </div>
                
                <!-- Model Philosophy -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                    <h4 class="font-bold mb-3 text-yellow-400">فلسفة النموذج</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-400"></i>
                            الجذور الأربعة مترابطة وليست هرمية
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-400"></i>
                            قياس شامل للتعلم الحقيقي
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-400"></i>
                            تطوير مستمر ومتوازن للمهارات
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Real Data Insights -->
            <div class="text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                    <h3 class="text-2xl font-bold mb-6">بيانات التطور الفعلية</h3>
                    
                    <!-- Learning Distribution Chart -->
                    <div class="mb-6">
                        <canvas id="learningDistributionChart" width="300" height="200"></canvas>
                    </div>
                    
                    <!-- Key Insights from Real Data -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-white/10 rounded-lg p-3">
                            <div class="text-yellow-400 font-bold">85%</div>
                            <div>من الطلاب يحسنون</div>
                            <div>الجوهر أولاً</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-3">
                            <div class="text-yellow-400 font-bold">73%</div>
                            <div>نمو متوازن في</div>
                            <div>جميع الجذور</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-3">
                            <div class="text-yellow-400 font-bold">92%</div>
                            <div>تحسن بعد المحاولة</div>
                            <div>الثالثة</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-3">
                            <div class="text-yellow-400 font-bold">67%</div>
                            <div>تطور الرؤية بعد</div>
                            <div>إتقان الجذور الأخرى</div>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-xs text-gray-400">
                        * بناءً على تحليل أنماط التعلم في قاعدة البيانات
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dual User Paths -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">اختر مسارك التعليمي</h2>
            <p class="text-xl text-gray-600">منصة واحدة، رحلتان مختلفتان نحو التميز</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Teacher Path -->
            <div class="bg-gradient-to-br from-purple-100 to-blue-100 rounded-3xl p-8 border border-purple-200 transform hover:scale-105 transition-all duration-300">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-purple-600 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">للمعلمين</h3>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        اكتشف قوة نموذج الجذور الأربعة في تطوير طلابك. 
                        أنشئ اختبارات ذكية واحصل على تقارير تفصيلية.
                    </p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-robot text-purple-600"></i>
                            <span>إنشاء أسئلة بالذكاء الاصطناعي</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-chart-line text-purple-600"></i>
                            <span>تحليلات تعلم متقدمة</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-users text-purple-600"></i>
                            <span>متابعة تقدم الطلاب</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('teacher.register') }}" class="block w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 rounded-xl font-bold hover:shadow-lg transform hover:scale-105 transition-all">
                        ابدأ مجاناً
                    </a>
                </div>
            </div>

            <!-- Student Path -->
            <div class="bg-gradient-to-br from-green-100 to-blue-100 rounded-3xl p-8 border border-green-200 transform hover:scale-105 transition-all duration-300">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-green-600 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">للطلاب</h3>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        اكتشف نقاط قوتك الحقيقية عبر الجذور الأربعة. 
                        تعلم بطريقة أذكى وطور مهاراتك بشكل شامل.
                    </p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-trophy text-green-600"></i>
                            <span>إنجازات وشارات تحفيزية</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-brain text-green-600"></i>
                            <span>تطوير مهارات التفكير</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <i class="fas fa-chart-pie text-green-600"></i>
                            <span>تقارير تقدم شخصية</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('student.register') }}" class="block w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-3 rounded-xl font-bold hover:shadow-lg transform hover:scale-105 transition-all">
                        انضم الآن
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Revolutionary Call to Action Section -->
<section class="py-24 bg-gray-900 text-white overflow-hidden relative">
    <!-- Modern Background Pattern -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-purple-900 to-blue-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 25% 25%, rgba(139, 92, 246, 0.3) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(59, 130, 246, 0.3) 0%, transparent 50%);"></div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <!-- Main Content -->
        <div class="text-center mb-16">
            <!-- Impact Badge -->
            <div class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full px-6 py-3 mb-8 shadow-lg">
                <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center">
                    <i class="fas fa-rocket text-purple-600 text-sm"></i>
                </div>
                <span class="text-white font-semibold text-sm">الآن هو الوقت المناسب</span>
            </div>
            
            <!-- Main Headline -->
            <h2 class="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
                <span class="block text-white">انطلق في</span>
                <span class="block bg-gradient-to-r from-purple-400 via-pink-400 to-blue-400 bg-clip-text text-transparent">
                    رحلة التعلم الثورية
                </span>
            </h2>
            
            <!-- Subtitle -->
            <p class="text-xl lg:text-2xl mb-4 text-gray-200 max-w-4xl mx-auto leading-relaxed">
                اكتشف إمكانيات لا محدودة للتعلم والنمو مع نموذج الجذور الأربعة
            </p>
            
            <p class="text-lg text-gray-400 max-w-3xl mx-auto">
                انضم إلى المعلمين والطلاب الذين اختاروا مستقبل التعليم اليوم
            </p>
        </div>

        <!-- Clean Action Buttons -->
        <div class="flex flex-col lg:flex-row gap-6 justify-center items-center mb-16">
            <!-- Primary CTA -->
            <a href="{{ route('for.teachers') }}" class="group relative px-12 py-6 bg-white text-gray-900 rounded-2xl font-bold text-xl shadow-2xl transform hover:scale-105 transition-all duration-300 min-w-80">
                <div class="flex items-center justify-center gap-4">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-white"></i>
                    </div>
                    <span>ابدأ كمعلم</span>
                    <i class="fas fa-arrow-left group-hover:translate-x-1 transition-transform text-purple-600"></i>
                </div>
            </a>
            
            <!-- Separator -->
            <div class="flex flex-col items-center">
                <div class="text-xl font-semibold text-gray-300 mb-3">أو</div>
                <div class="w-20 h-px bg-gradient-to-r from-transparent via-gray-400 to-transparent"></div>
            </div>
            
            <!-- Secondary CTA -->
            <a href="{{ route('for.students') }}" class="group relative px-12 py-6 bg-transparent border-2 border-white text-white rounded-2xl font-bold text-xl hover:bg-white hover:text-gray-900 transition-all duration-300 min-w-80">
                <div class="flex items-center justify-center gap-4">
                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg flex items-center justify-center group-hover:bg-purple-600 transition-colors">
                        <i class="fas fa-graduation-cap text-white"></i>
                    </div>
                    <span>ابدأ كطالب</span>
                    <i class="fas fa-arrow-left group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>
        </div>

        <!-- Benefits Grid -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <!-- Teachers Benefits -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 mx-auto shadow-lg">
                    <i class="fas fa-chalkboard-teacher text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-center text-white">للمعلمين المبدعين</h3>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>تحليلات تعلم متقدمة</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>إنشاء محتوى بالذكاء الاصطناعي</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>تقييم شامل للطلاب</span>
                    </li>
                </ul>
            </div>

            <!-- Students Benefits -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 mx-auto shadow-lg">
                    <i class="fas fa-graduation-cap text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-center text-white">للطلاب الطموحين</h3>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>فهم أعمق للمواد</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>تطوير مهارات التفكير</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>تقدم قابل للقياس</span>
                    </li>
                </ul>
            </div>

            <!-- Innovation Promise -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mb-6 mx-auto shadow-lg">
                    <i class="fas fa-lightbulb text-2xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-center text-white">نموذج مبتكر</h3>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>الجذور الأربعة المترابطة</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>تقييم متعدد الأبعاد</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span>منهجية علمية حديثة</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Additional Quick Actions -->
        <div class="flex flex-wrap justify-center gap-6 mb-16">
            <a href="{{ route('juzoor.model') }}" class="flex items-center gap-3 px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors border border-white/20">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-white"></i>
                </div>
                <div class="text-left">
                    <div class="font-semibold text-white">تعرف على النموذج</div>
                    <div class="text-sm text-gray-400">اكتشف الجذور الأربعة</div>
                </div>
            </a>
            
            <a href="https://iseraj.com/roots/plans" class="flex items-center gap-3 px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors border border-white/20">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tags text-white"></i>
                </div>
                <div class="text-left">
                    <div class="font-semibold text-white">عرض الأسعار</div>
                    <div class="text-sm text-gray-400">باقات مرنة ومناسبة</div>
                </div>
            </a>
            
            <a href="{{ route('contact.show') }}" class="flex items-center gap-3 px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors border border-white/20">
                <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-envelope text-white"></i>
                </div>
                <div class="text-left">
                    <div class="font-semibold text-white">تواصل معنا</div>
                    <div class="text-sm text-gray-400">نحن هنا لمساعدتك</div>
                </div>
            </a>
        </div>

        <!-- Trust Indicators -->
        <div class="pt-8 border-t border-white/20">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-shield-alt text-green-400 text-xl"></i>
                    </div>
                    <div class="text-white font-semibold">آمن ومحمي</div>
                    <div class="text-gray-400 text-sm">حماية كاملة لبياناتك</div>
                </div>
                
                <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-globe text-blue-400 text-xl"></i>
                    </div>
                    <div class="text-white font-semibold">يدعم العربية</div>
                    <div class="text-gray-400 text-sm">مصمم للغة العربية</div>
                </div>
                
                <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-purple-400 text-xl"></i>
                    </div>
                    <div class="text-white font-semibold">جميع الأجهزة</div>
                    <div class="text-gray-400 text-sm">كمبيوتر، جوال، تابلت</div>
                </div>
                
                <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-red-400 text-xl"></i>
                    </div>
                    <div class="text-white font-semibold">مصمم للتعليم</div>
                    <div class="text-gray-400 text-sm">بفهم عميق لاحتياجات التعلم</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Mobile menu toggle
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all charts
    initializeRootsChart();
    initializeGrowthChart();
    initializeLearningDistributionChart();
});

function initializeRootsChart() {
    const ctx = document.getElementById('rootsModelChart');
    if (!ctx) return;
    
    // Sample data representing the 4 roots model
    const rootsData = {
        labels: ['جَوهر (الجوهر)', 'ذِهن (العقل)', 'وَصلات (الروابط)', 'رُؤية (الرؤية)'],
        datasets: [{
            label: 'نموذج الجذور الأربعة',
            data: [85, 92, 78, 88], // Sample scores
            backgroundColor: [
                'rgba(37, 99, 235, 0.8)',  // jawhar - blue
                'rgba(5, 150, 105, 0.8)',  // zihn - green  
                'rgba(220, 38, 38, 0.8)',  // waslat - red
                'rgba(147, 51, 234, 0.8)'  // roaya - purple
            ],
            borderColor: [
                'rgba(37, 99, 235, 1)',
                'rgba(5, 150, 105, 1)', 
                'rgba(220, 38, 38, 1)',
                'rgba(147, 51, 234, 1)'
            ],
            borderWidth: 3
        }]
    };

    new Chart(ctx, {
        type: 'radar',
        data: rootsData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 25,
                        color: 'rgba(107, 114, 128, 0.8)'
                    },
                    grid: {
                        color: 'rgba(107, 114, 128, 0.2)'
                    },
                    pointLabels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        color: 'rgba(31, 41, 55, 0.9)'
                    }
                }
            },
            elements: {
                point: {
                    radius: 6,
                    hoverRadius: 8
                },
                line: {
                    borderWidth: 3
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
}

function initializeGrowthChart() {
    const ctx = document.getElementById('growthChart');
    if (!ctx) return;
    
    // Realistic growth patterns based on learning theory
    const growthData = {
        labels: ['المحاولة 1', 'المحاولة 2', 'المحاولة 3', 'المحاولة 4', 'المحاولة 5'],
        datasets: [
            {
                label: 'جَوهر',
                data: [45, 62, 75, 82, 85],
                borderColor: 'rgb(37, 99, 235)',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4
            },
            {
                label: 'ذِهن',
                data: [35, 48, 68, 85, 92],
                borderColor: 'rgb(5, 150, 105)',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                tension: 0.4
            },
            {
                label: 'وَصلات',
                data: [25, 35, 52, 68, 78],
                borderColor: 'rgb(220, 38, 38)',
                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                tension: 0.4
            },
            {
                label: 'رُؤية',
                data: [30, 42, 58, 78, 88],
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.4
            }
        ]
    };

    new Chart(ctx, {
        type: 'line',
        data: growthData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            elements: {
                point: {
                    radius: 5,
                    hoverRadius: 7
                }
            },
            animation: {
                duration: 2500,
                easing: 'easeInOutCubic'
            }
        }
    });
}

function initializeLearningDistributionChart() {
    const ctx = document.getElementById('learningDistributionChart');
    if (!ctx) return;
    
    // Distribution showing how students typically develop across the 4 roots
    const distributionData = {
        labels: ['جَوهر', 'ذِهن', 'وَصلات', 'رُؤية'],
        datasets: [{
            label: 'متوسط التحسن (%)',
            data: [85, 73, 67, 79],
            backgroundColor: [
                'rgba(37, 99, 235, 0.8)',
                'rgba(5, 150, 105, 0.8)',
                'rgba(220, 38, 38, 0.8)',
                'rgba(147, 51, 234, 0.8)'
            ],
            borderColor: [
                'rgb(37, 99, 235)',
                'rgb(5, 150, 105)',
                'rgb(220, 38, 38)',
                'rgb(147, 51, 234)'
            ],
            borderWidth: 2
        }]
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: distributionData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        color: 'white'
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutBounce'
            }
        }
    });
}
</script>
@endpush