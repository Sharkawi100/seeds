@extends('layouts.app')

@section('title', 'دليل الطالب - كيف تستخدم منصة جُذور')

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .step-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(0);
    }
    
    .step-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .progress-step {
        transition: all 0.5s ease;
        cursor: pointer;
    }
    
    .progress-step.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        transform: scale(1.1);
    }
    
    .progress-step.completed {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .floating-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .pulse-glow {
        animation: pulse-glow 2s infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.3); }
        50% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.6); }
    }
    
    .slide-in {
        animation: slideIn 0.8s ease-out forwards;
        opacity: 0;
        transform: translateX(50px);
    }
    
    @keyframes slideIn {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(30px);
    }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .root-card {
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }
    
    .root-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .root-card:hover::before {
        left: 100%;
    }
    
    .image-placeholder {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        border: 2px dashed #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        transition: all 0.3s ease;
    }
    
    .image-placeholder:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        color: #667eea;
    }
    
    .tab-button {
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }
    
    .tab-button.active {
        border-bottom-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="hero-gradient relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-300/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 py-20">
            <div class="text-center">
                <div class="inline-block mb-8 floating-animation">
                    <div class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm">
                        <span class="text-5xl">🎓</span>
                    </div>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-black text-white mb-6 slide-in">
                    دليل الطالب
                </h1>
                <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto fade-in-up">
                    تعلم كيف تستخدم منصة جُذور خطوة بخطوة واكتشف كيف تنمو معرفتك في جميع الجوانب
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up">
                    <button onclick="scrollToSection('quick-start')" 
                            class="bg-white text-purple-600 px-8 py-4 rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all pulse-glow">
                        <i class="fas fa-rocket ml-2"></i>
                        ابدأ الآن
                    </button>
                    <button onclick="scrollToSection('juzoor-model')" 
                            class="bg-white/20 border-2 border-white text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-white hover:text-purple-600 transition-all">
                        <i class="fas fa-seedling ml-2"></i>
                        اكتشف نموذج جُذور
                    </button>
                </div>
                
                <!-- Progress Steps Preview -->
                <div class="flex justify-center gap-3 mt-12">
                    <div class="progress-step w-4 h-4 rounded-full bg-white/30" data-step="1"></div>
                    <div class="progress-step w-4 h-4 rounded-full bg-white/30" data-step="2"></div>
                    <div class="progress-step w-4 h-4 rounded-full bg-white/30" data-step="3"></div>
                    <div class="progress-step w-4 h-4 rounded-full bg-white/30" data-step="4"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Start Section -->
    <section id="quick-start" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                    🚀 ابدأ في 3 خطوات سريعة
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    احصل على أول نتيجة جُذور في أقل من 5 دقائق
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="step-card bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl p-8 text-center">
                    <div class="w-20 h-20 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">📝</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">1. أدخل رمز الاختبار</h3>
                    <p class="text-gray-700 mb-6">
                        احصل على الرمز من معلمك وأدخله في الصفحة الرئيسية
                    </p>
                    
                    <!-- Image Placeholder -->
                    <div class="image-placeholder h-48 rounded-xl mb-4">
                        <div class="text-center">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p class="font-medium">صورة: إدخال رمز الاختبار</p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4">
                        <p class="text-sm text-gray-600">
                            💡 <strong>نصيحة:</strong> الرمز يتكون من 6 أحرف أو أرقام
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="step-card bg-gradient-to-br from-green-50 to-emerald-100 rounded-3xl p-8 text-center">
                    <div class="w-20 h-20 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">📚</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">2. اقرأ النص وأجب</h3>
                    <p class="text-gray-700 mb-6">
                        اقرأ النص بعناية وأجب على أسئلة الجذور الأربعة
                    </p>
                    
                    <!-- Image Placeholder -->
                    <div class="image-placeholder h-48 rounded-xl mb-4">
                        <div class="text-center">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p class="font-medium">صورة: واجهة الاختبار</p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4">
                        <p class="text-sm text-gray-600">
                            💡 <strong>نصيحة:</strong> خذ وقتك في قراءة النص أولاً
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="step-card bg-gradient-to-br from-purple-50 to-violet-100 rounded-3xl p-8 text-center">
                    <div class="w-20 h-20 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">📊</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">3. شاهد نتائجك</h3>
                    <p class="text-gray-700 mb-6">
                        اكتشف قوتك في كل جذر واعرف كيف تحسن أداءك
                    </p>
                    
                    <!-- Image Placeholder -->
                    <div class="image-placeholder h-48 rounded-xl mb-4">
                        <div class="text-center">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p class="font-medium">صورة: شاشة النتائج</p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4">
                        <p class="text-sm text-gray-600">
                            💡 <strong>نصيحة:</strong> احفظ رابط النتائج لمراجعتها لاحقاً
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Demo Button -->
            <div class="text-center mt-12">
                <a href="{{ route('quiz.demo') }}" 
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                    <span class="text-2xl">🎮</span>
                    جرب الآن مع اختبار تجريبي
                </a>
            </div>
        </div>
    </section>

    <!-- جُذور Model Section -->
    <section id="juzoor-model" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                    🌱 ما هو نموذج جُذور؟
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                    نموذج تعليمي مبتكر يقيس 4 جوانب مختلفة من فهمك وتفكيرك
                </p>
            </div>

            <!-- The 4 Roots -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- جَوهر -->
                <div class="root-card bg-gradient-to-br from-red-500 to-pink-600 rounded-3xl p-8 text-white text-center">
                    <div class="text-6xl mb-4">🎯</div>
                    <h3 class="text-2xl font-bold mb-3">جَوهر</h3>
                    <p class="text-red-100 mb-4">ما هو الشيء؟</p>
                    <div class="bg-white/20 rounded-xl p-4">
                        <p class="text-sm">
                            فهم المعلومات الأساسية والتعريفات
                        </p>
                    </div>
                </div>

                <!-- ذِهن -->
                <div class="root-card bg-gradient-to-br from-cyan-500 to-blue-600 rounded-3xl p-8 text-white text-center">
                    <div class="text-6xl mb-4">🧠</div>
                    <h3 class="text-2xl font-bold mb-3">ذِهن</h3>
                    <p class="text-cyan-100 mb-4">كيف يعمل؟</p>
                    <div class="bg-white/20 rounded-xl p-4">
                        <p class="text-sm">
                            تحليل العمليات والأسباب
                        </p>
                    </div>
                </div>

                <!-- وَصلات -->
                <div class="root-card bg-gradient-to-br from-yellow-500 to-orange-600 rounded-3xl p-8 text-white text-center">
                    <div class="text-6xl mb-4">🔗</div>
                    <h3 class="text-2xl font-bold mb-3">وَصلات</h3>
                    <p class="text-yellow-100 mb-4">كيف يرتبط؟</p>
                    <div class="bg-white/20 rounded-xl p-4">
                        <p class="text-sm">
                            ربط المعلومات والعلاقات
                        </p>
                    </div>
                </div>

                <!-- رُؤية -->
                <div class="root-card bg-gradient-to-br from-purple-500 to-indigo-600 rounded-3xl p-8 text-white text-center">
                    <div class="text-6xl mb-4">👁️</div>
                    <h3 class="text-2xl font-bold mb-3">رُؤية</h3>
                    <p class="text-purple-100 mb-4">كيف نستخدمه؟</p>
                    <div class="bg-white/20 rounded-xl p-4">
                        <p class="text-sm">
                            التطبيق والإبداع
                        </p>
                    </div>
                </div>
            </div>

            <!-- Interactive Chart Demo -->
            <div class="bg-white rounded-3xl p-8 shadow-2xl">
                <h3 class="text-2xl font-bold text-center text-gray-900 mb-8">
                    هكذا تبدو نتائجك 📊
                </h3>
                
                <!-- Image Placeholder for Juzoor Chart -->
                <div class="image-placeholder h-64 rounded-2xl mb-6">
                    <div class="text-center">
                        <i class="fas fa-chart-radar text-5xl mb-3"></i>
                        <p class="font-bold text-lg">رسم بياني لنموذج جُذور</p>
                        <p class="text-sm">يوضح أداءك في الجذور الأربعة</p>
                    </div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6">
                        <h4 class="font-bold text-green-800 mb-3">📈 نقاط القوة</h4>
                        <p class="text-green-700">الجذور التي تتفوق فيها تظهر بألوان زاهية</p>
                    </div>
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6">
                        <h4 class="font-bold text-orange-800 mb-3">📊 مجالات التحسين</h4>
                        <p class="text-orange-700">الجذور التي تحتاج تطوير تظهر بألوان أفتح</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Guide Section -->
    <section id="detailed-guide" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                    📖 الدليل التفصيلي
                </h2>
                <p class="text-xl text-gray-600">
                    كل ما تحتاج معرفته لتحقيق أفضل النتائج
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex flex-wrap justify-center gap-2 mb-12">
                <button class="tab-button active px-6 py-3 rounded-xl font-bold text-gray-700" 
                        onclick="showTab('access')">
                    🚪 الدخول للاختبار
                </button>
                <button class="tab-button px-6 py-3 rounded-xl font-bold text-gray-700" 
                        onclick="showTab('taking')">
                    ✍️ أخذ الاختبار
                </button>
                <button class="tab-button px-6 py-3 rounded-xl font-bold text-gray-700" 
                        onclick="showTab('results')">
                    📊 فهم النتائج
                </button>
                <button class="tab-button px-6 py-3 rounded-xl font-bold text-gray-700" 
                        onclick="showTab('tips')">
                    💡 نصائح ذهبية
                </button>
            </div>

            <!-- Tab Content -->
            <div id="access-tab" class="tab-content">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-6">🚪 طرق الدخول للاختبار</h3>
                        
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                                <h4 class="text-xl font-bold text-blue-900 mb-3">
                                    <span class="text-2xl">📱</span> الدخول بالرمز (الأسرع)
                                </h4>
                                <p class="text-blue-800 mb-4">
                                    احصل على رمز مكون من 6 أحرف أو أرقام من معلمك
                                </p>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">
                                        ✅ لا يحتاج تسجيل<br>
                                        ✅ دخول فوري<br>
                                        ✅ مناسب للضيوف
                                    </p>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                                <h4 class="text-xl font-bold text-green-900 mb-3">
                                    <span class="text-2xl">👤</span> الدخول بالحساب
                                </h4>
                                <p class="text-green-800 mb-4">
                                    سجل حساب لحفظ جميع نتائجك ومتابعة تقدمك
                                </p>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-sm text-gray-600">
                                        ✅ حفظ النتائج دائماً<br>
                                        ✅ متابعة التقدم<br>
                                        ✅ إحصائيات شخصية
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="image-placeholder h-96 rounded-2xl">
                        <div class="text-center">
                            <i class="fas fa-mobile-alt text-6xl mb-4"></i>
                            <p class="font-bold text-xl">لقطة شاشة</p>
                            <p class="text-lg">طرق الدخول المختلفة</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="taking-tab" class="tab-content hidden">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="image-placeholder h-96 rounded-2xl">
                        <div class="text-center">
                            <i class="fas fa-laptop text-6xl mb-4"></i>
                            <p class="font-bold text-xl">فيديو تفاعلي</p>
                            <p class="text-lg">كيفية أخذ الاختبار</p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-6">✍️ خطوات أخذ الاختبار</h3>
                        
                        <div class="space-y-4">
                            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">1</div>
                                <div>
                                    <h4 class="font-bold text-gray-900">اقرأ النص بعناية</h4>
                                    <p class="text-gray-600">خذ وقتك لفهم النص قبل الانتقال للأسئلة</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">2</div>
                                <div>
                                    <h4 class="font-bold text-gray-900">تذكر الجذور الأربعة</h4>
                                    <p class="text-gray-600">كل سؤال يقيس جذر معين، انتبه للألوان</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">3</div>
                                <div>
                                    <h4 class="font-bold text-gray-900">راجع إجاباتك</h4>
                                    <p class="text-gray-600">تأكد من إجاباتك قبل الإرسال النهائي</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl">
                            <h4 class="font-bold text-orange-900 mb-3">⚡ ميزات مساعدة</h4>
                            <ul class="space-y-2 text-orange-800">
                                <li>• يمكنك العودة للنص في أي وقت</li>
                                <li>• استخدم الأسهم للتنقل بين الأسئلة</li>
                                <li>• النقاط الملونة تدل على تقدمك</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div id="results-tab" class="tab-content hidden">
                <div class="text-center">
                    <h3 class="text-3xl font-bold text-gray-900 mb-8">📊 كيف تفهم نتائجك</h3>
                    
                    <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8">
                            <h4 class="text-2xl font-bold text-green-900 mb-4">🎯 النتيجة الإجمالية</h4>
                            <div class="image-placeholder h-32 rounded-xl mb-4">
                                <div class="text-center">
                                    <span class="text-4xl">85%</span>
                                    <p class="text-sm">مثال النتيجة</p>
                                </div>
                            </div>
                            <p class="text-green-800">تخبرك كم سؤال أجبت عليه بشكل صحيح</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl p-8">
                            <h4 class="text-2xl font-bold text-purple-900 mb-4">🌱 نتائج الجذور</h4>
                            <div class="image-placeholder h-32 rounded-xl mb-4">
                                <div class="text-center">
                                    <i class="fas fa-chart-pie text-3xl mb-2"></i>
                                    <p class="text-sm">رسم الجذور</p>
                                </div>
                            </div>
                            <p class="text-purple-800">تُظهر قوتك في كل نوع من أنواع التفكير</p>
                        </div>
                    </div>
                    
                    <div class="mt-12 max-w-4xl mx-auto">
                        <h4 class="text-2xl font-bold text-gray-900 mb-6">🎨 فهم الألوان</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-red-100 rounded-xl">
                                <div class="text-3xl mb-2">🎯</div>
                                <p class="font-bold text-red-800">جَوهر</p>
                                <p class="text-sm text-red-600">أحمر</p>
                            </div>
                            <div class="text-center p-4 bg-cyan-100 rounded-xl">
                                <div class="text-3xl mb-2">🧠</div>
                                <p class="font-bold text-cyan-800">ذِهن</p>
                                <p class="text-sm text-cyan-600">سماوي</p>
                            </div>
                            <div class="text-center p-4 bg-yellow-100 rounded-xl">
                                <div class="text-3xl mb-2">🔗</div>
                                <p class="font-bold text-yellow-800">وَصلات</p>
                                <p class="text-sm text-yellow-600">أصفر</p>
                            </div>
                            <div class="text-center p-4 bg-purple-100 rounded-xl">
                                <div class="text-3xl mb-2">👁️</div>
                                <p class="font-bold text-purple-800">رُؤية</p>
                                <p class="text-sm text-purple-600">بنفسجي</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tips-tab" class="tab-content hidden">
                <div class="max-w-4xl mx-auto">
                    <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">💡 نصائح ذهبية للنجاح</h3>
                    
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                            <h4 class="text-xl font-bold text-blue-900 mb-3">
                                <span class="text-2xl">🎯</span> قبل البدء
                            </h4>
                            <ul class="space-y-2 text-blue-800">
                                <li>• تأكد من اتصال الإنترنت المستقر</li>
                                <li>• اختر مكان هادئ ومريح</li>
                                <li>• جهز ورقة وقلم للملاحظات</li>
                                <li>• تأكد من شحن جهازك إذا كان محمول</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                            <h4 class="text-xl font-bold text-green-900 mb-3">
                                <span class="text-2xl">📚</span> أثناء القراءة
                            </h4>
                            <ul class="space-y-2 text-green-800">
                                <li>• اقرأ النص مرتين: مرة للفهم العام ومرة للتفاصيل</li>
                                <li>• ركز على الأفكار الرئيسية والكلمات المفتاحية</li>
                                <li>• لا تتعجل، خذ وقتك الكافي</li>
                                <li>• يمكنك العودة للنص في أي وقت</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6">
                            <h4 class="text-xl font-bold text-orange-900 mb-3">
                                <span class="text-2xl">✍️</span> عند الإجابة
                            </h4>
                            <ul class="space-y-2 text-orange-800">
                                <li>• اقرأ السؤال بعناية وحدد الجذر المطلوب</li>
                                <li>• احذر من الخيارات المشابهة</li>
                                <li>• إذا لم تكن متأكداً، استخدم الاستبعاد</li>
                                <li>• راجع إجاباتك قبل الإرسال</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6">
                            <h4 class="text-xl font-bold text-purple-900 mb-3">
                                <span class="text-2xl">🚀</span> لتحسين الأداء
                            </h4>
                            <ul class="space-y-2 text-purple-800">
                                <li>• تدرب على اختبارات مختلفة</li>
                                <li>• راجع نتائجك السابقة وحلل نقاط الضعف</li>
                                <li>• اطلب من معلمك شرح الأسئلة الصعبة</li>
                                <li>• طور مهارات القراءة والتحليل</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gradient-to-br from-purple-50 to-indigo-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                    ❓ أسئلة شائعة
                </h2>
                <p class="text-xl text-gray-600">
                    إجابات على أكثر الأسئلة تكراراً
                </p>
            </div>

            <div class="space-y-4">
                <div class="faq-item bg-white rounded-2xl shadow-md">
                    <button class="faq-toggle w-full p-6 text-right flex justify-between items-center" onclick="toggleFAQ(this)">
                        <span class="text-lg font-bold text-gray-900">ماذا لو نسيت رمز الاختبار؟</span>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="faq-content hidden p-6 pt-0">
                        <p class="text-gray-700">
                            اطلب من معلمك الرمز مرة أخرى، أو ابحث عن الرمز في رسائل الواتساب أو البريد الإلكتروني.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white rounded-2xl shadow-md">
                    <button class="faq-toggle w-full p-6 text-right flex justify-between items-center" onclick="toggleFAQ(this)">
                        <span class="text-lg font-bold text-gray-900">هل يمكنني إعادة الاختبار؟</span>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="faq-content hidden p-6 pt-0">
                        <p class="text-gray-700">
                            نعم! يمكنك إعادة الاختبار عدة مرات. كل مرة ستحصل على تجربة تعلم جديدة.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white rounded-2xl shadow-md">
                    <button class="faq-toggle w-full p-6 text-right flex justify-between items-center" onclick="toggleFAQ(this)">
                        <span class="text-lg font-bold text-gray-900">كيف أحسن نتائجي في الجذور الضعيفة؟</span>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="faq-content hidden p-6 pt-0">
                        <p class="text-gray-700">
                            راجع أسئلة الجذر الضعيف، اطلب شرح من معلمك، وتدرب على أنواع الأسئلة المختلفة لهذا الجذر.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white rounded-2xl shadow-md">
                    <button class="faq-toggle w-full p-6 text-right flex justify-between items-center" onclick="toggleFAQ(this)">
                        <span class="text-lg font-bold text-gray-900">هل النتائج محفوظة دائماً؟</span>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="faq-content hidden p-6 pt-0">
                        <p class="text-gray-700">
                            إذا سجلت حساب، نعم. إذا دخلت كضيف، النتائج محفوظة لمدة أسبوع فقط.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-purple-600 to-pink-600">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                جاهز لتبدأ رحلة التعلم؟ 🚀
            </h2>
            <p class="text-xl text-white/90 mb-8">
                اختبر معرفتك الآن واكتشف قدراتك في جميع الجذور
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('quiz.demo') }}" 
                   class="bg-white text-purple-600 px-8 py-4 rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                    <i class="fas fa-play ml-2"></i>
                    جرب الاختبار التجريبي
                </a>
                
                <a href="{{ route('register') }}" 
                   class="bg-purple-700 border-2 border-white text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-purple-800 transition-all">
                    <i class="fas fa-user-plus ml-2"></i>
                    أنشئ حساب مجاني
                </a>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
// Smooth scrolling
function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Tab functionality
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

// FAQ toggle
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Progress tracking
function updateProgress() {
    const steps = document.querySelectorAll('.progress-step');
    const scrolled = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = (scrolled / docHeight) * 100;
    
    steps.forEach((step, index) => {
        if (progress > (index + 1) * 25) {
            step.classList.add('completed');
        } else if (progress > index * 25) {
            step.classList.add('active');
        } else {
            step.classList.remove('active', 'completed');
        }
    });
}

// Animation on scroll
function animateOnScroll() {
    const elements = document.querySelectorAll('.step-card, .root-card');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
}

// Event listeners
window.addEventListener('scroll', () => {
    updateProgress();
    animateOnScroll();
});

// Initialize animations
document.addEventListener('DOMContentLoaded', () => {
    animateOnScroll();
});
</script>
@endpush
@endsection