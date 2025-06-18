@extends('layouts.guest')

@section('title', 'للمعلمين - منصة جُذور التعليمية')

@push('styles')
<style>
    * {
        font-family: 'Tajawal', sans-serif !important;
    }
    
    .hero-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .feature-card {
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .floating {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Ensure button text contrast */
    .btn-primary {
        background-color: white !important;
        color: #553c9a !important; /* purple-800 */
    }
    
    .btn-primary:hover {
        background-color: #f7fafc !important;
        color: #44337a !important; /* purple-900 */
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 text-white min-h-screen">
    <div class="hero-pattern absolute inset-0"></div>
    <div class="relative z-10 container mx-auto px-4 py-32 pt-64">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black mb-6 animate-fade-in mt-16">
                مرحباً بك في <span class="gradient-text">جُذور</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-200">
                منصة تعليمية مبتكرة تُمكّن المعلمين من تقييم الطلاب بشكل شامل وممتع
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('teacher.register') }}" 
                   class="bg-white text-purple-800 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 hover:text-purple-900 transform hover:scale-105 transition shadow-2xl">
                    <i class="fas fa-user-plus ml-2 text-purple-800"></i>
                    <span class="text-purple-800">ابدأ الآن مجاناً</span>
                </a>
                <a href="{{ route('teacher.login') }}" 
                   class="border-2 border-white bg-transparent text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-purple-900 transition">
                    <i class="fas fa-sign-in-alt ml-2"></i>
                    <span>تسجيل الدخول</span>
                </a>
            </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 floating">
            <div class="w-20 h-20 bg-yellow-400 rounded-full opacity-20"></div>
        </div>
        <div class="absolute bottom-20 right-10 floating" style="animation-delay: 3s;">
            <div class="w-32 h-32 bg-pink-400 rounded-full opacity-20"></div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">لماذا جُذور للمعلمين؟</h2>
            <p class="text-xl text-gray-600">أدوات قوية لتحسين تجربة التعليم والتقييم</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- AI Quiz Generation -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-robot text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">توليد الاختبارات بالذكاء الاصطناعي</h3>
                <p class="text-gray-600 mb-4">
                    وفّر وقتك مع تقنية Claude AI التي تنشئ اختبارات متوازنة تلقائياً حسب نموذج الجذور الأربعة
                </p>
                <ul class="text-gray-700 space-y-2">
                    <li><i class="fas fa-check text-green-500 ml-2"></i>إنشاء فوري للأسئلة</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>توازن تلقائي بين الجذور</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>تخصيص حسب المستوى</li>
                </ul>
            </div>
            
            <!-- Comprehensive Analytics -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">تحليلات شاملة للأداء</h3>
                <p class="text-gray-600 mb-4">
                    احصل على رؤى عميقة حول تقدم طلابك في كل جذر من جذور المعرفة الأربعة
                </p>
                <ul class="text-gray-700 space-y-2">
                    <li><i class="fas fa-check text-green-500 ml-2"></i>تقارير مفصلة لكل طالب</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>رسوم بيانية تفاعلية</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>تتبع التقدم بمرور الوقت</li>
                </ul>
            </div>
            
            <!-- Multi-language Support -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-language text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">دعم متعدد اللغات</h3>
                <p class="text-gray-600 mb-4">
                    صمم اختبارات باللغة العربية، الإنجليزية، أو العبرية مع دعم كامل للاتجاه RTL
                </p>
                <ul class="text-gray-700 space-y-2">
                    <li><i class="fas fa-check text-green-500 ml-2"></i>ثلاث لغات مدعومة</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>واجهة متوافقة RTL</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>محتوى مناسب ثقافياً</li>
                </ul>
            </div>
            
            <!-- Easy Classroom Management -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-users-cog text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">إدارة سهلة للفصل</h3>
                <p class="text-gray-600 mb-4">
                    شارك الاختبارات مع طلابك بسهولة عبر رموز PIN فريدة دون الحاجة لتسجيل دخولهم
                </p>
                <ul class="text-gray-700 space-y-2">
                    <li><i class="fas fa-check text-green-500 ml-2"></i>رموز PIN سهلة</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>وصول فوري للطلاب</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>نتائج لحظية</li>
                </ul>
            </div>
            
            <!-- Question Bank -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-database text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">بنك أسئلة متنوع</h3>
                <p class="text-gray-600 mb-4">
                    أنشئ وأعد استخدام الأسئلة مع إمكانية التعديل والتخصيص حسب احتياجاتك
                </p>
                <ul class="text-gray-700 space-y-2">
                    <li><i class="fas fa-check text-green-500 ml-2"></i>حفظ وإعادة استخدام</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>تصنيف حسب الجذور</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>استيراد وتصدير</li>
                </ul>
            </div>
            
            <!-- Progress Tracking -->
            <div class="feature-card bg-white rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">تتبع تقدم الطلاب</h3>
                <p class="text-gray-600 mb-4">
                    راقب نمو طلابك في كل جذر من جذور المعرفة وحدد نقاط القوة والضعف
                </p>
                <ul class="text-gray-700 space-y-2">
                    <li><i class="fas fa-check text-green-500 ml-2"></i>لوحة معلومات شاملة</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>تقارير فردية وجماعية</li>
                    <li><i class="fas fa-check text-green-500 ml-2"></i>توصيات تحسين مخصصة</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">كيف تعمل منصة جُذور؟</h2>
            <p class="text-xl text-gray-600">ثلاث خطوات بسيطة لتحويل تجربة التقييم</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold">
                    1
                </div>
                <h3 class="text-2xl font-bold mb-4">أنشئ اختباراً</h3>
                <p class="text-gray-600">
                    اختر بين الإنشاء اليدوي أو استخدم الذكاء الاصطناعي لتوليد أسئلة متوازنة تلقائياً
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold">
                    2
                </div>
                <h3 class="text-2xl font-bold mb-4">شارك مع طلابك</h3>
                <p class="text-gray-600">
                    احصل على رمز PIN فريد وشاركه مع طلابك ليتمكنوا من الدخول فوراً
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold">
                    3
                </div>
                <h3 class="text-2xl font-bold mb-4">حلل النتائج</h3>
                <p class="text-gray-600">
                    احصل على تحليل فوري ومفصل لأداء كل طالب في الجذور الأربعة
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Juzoor Model -->
<section class="py-20 bg-gradient-to-br from-purple-50 to-blue-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">نموذج جُذور التعليمي</h2>
                <p class="text-xl text-gray-600">تقييم شامل يغطي جميع جوانب التعلم</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Root 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl border-r-8 border-red-500">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center ml-4">
                            <span class="text-3xl">🎯</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-red-600">جَوهر</h3>
                            <p class="text-gray-600">Essence - الجوهر</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        يركز على فهم المعاني والتعريفات الأساسية. يجيب على سؤال "ما هو؟" ويساعد الطلاب على بناء أساس قوي من المعرفة.
                    </p>
                </div>
                
                <!-- Root 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl border-r-8 border-cyan-500">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center ml-4">
                            <span class="text-3xl">🧠</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-cyan-600">ذِهن</h3>
                            <p class="text-gray-600">Mind - العقل</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        يطور مهارات التحليل والتفكير النقدي. يجيب على سؤال "كيف يعمل؟" ويعمق الفهم من خلال التحليل المنطقي.
                    </p>
                </div>
                
                <!-- Root 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl border-r-8 border-yellow-500">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center ml-4">
                            <span class="text-3xl">🔗</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-yellow-600">وَصلات</h3>
                            <p class="text-gray-600">Connections - الروابط</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        يبني الروابط بين المفاهيم المختلفة. يجيب على سؤال "كيف يرتبط؟" ويساعد على رؤية الصورة الكاملة.
                    </p>
                </div>
                
                <!-- Root 4 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl border-r-8 border-purple-500">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center ml-4">
                            <span class="text-3xl">👁️</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-purple-600">رُؤية</h3>
                            <p class="text-gray-600">Vision - الرؤية</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        يحفز الإبداع والتطبيق العملي. يجيب على سؤال "كيف نستخدمه؟" ويشجع على الابتكار والتفكير خارج الصندوق.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">ماذا يقول المعلمون؟</h2>
            <p class="text-xl text-gray-600">تجارب حقيقية من معلمين يستخدمون جُذور</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold ml-3">
                        س
                    </div>
                    <div>
                        <p class="font-bold">سارة أحمد</p>
                        <p class="text-sm text-gray-600">معلمة لغة عربية</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">
                    "جُذور غيّرت طريقة تقييمي للطلاب. أصبح بإمكاني رؤية نقاط القوة والضعف بشكل أوضح وأكثر دقة."
                </p>
                <div class="flex text-yellow-400 mt-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold ml-3">
                        م
                    </div>
                    <div>
                        <p class="font-bold">محمد الخطيب</p>
                        <p class="text-sm text-gray-600">معلم لغة إنجليزية</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">
                    "توفير الوقت مع الذكاء الاصطناعي مذهل! أصبح بإمكاني إنشاء اختبارات متوازنة في دقائق بدلاً من ساعات."
                </p>
                <div class="flex text-yellow-400 mt-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold ml-3">
                        ر
                    </div>
                    <div>
                        <p class="font-bold">ريم السيد</p>
                        <p class="text-sm text-gray-600">منسقة تعليمية</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">
                    "التقارير التحليلية ساعدتني في تطوير خطط تعليمية مخصصة لكل طالب. نتائج مذهلة!"
                </p>
                <div class="flex text-yellow-400 mt-4">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-purple-900 to-blue-900 text-white relative overflow-hidden">
    <div class="hero-pattern absolute inset-0 opacity-10"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-4xl font-bold mb-6">ابدأ رحلتك مع جُذور اليوم</h2>
            <p class="text-xl mb-8 text-gray-200">
                انضم لآلاف المعلمين الذين يحدثون ثورة في التقييم التعليمي
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('teacher.register') }}" 
                   class="bg-white text-purple-800 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 hover:text-purple-900 transform hover:scale-105 transition shadow-2xl">
                    <i class="fas fa-rocket ml-2 text-purple-800"></i>
                    <span class="text-purple-800">ابدأ تجربتك المجانية</span>
                </a>
                <a href="{{ route('juzoor.model') }}" 
                   class="border-2 border-white bg-transparent text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-purple-900 transition">
                    <i class="fas fa-book ml-2"></i>
                    <span>اعرف المزيد عن النموذج</span>
                </a>
            </div>
            
            <div class="mt-12 flex flex-wrap justify-center gap-8 text-gray-200">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 text-xl ml-2"></i>
                    لا حاجة لبطاقة ائتمان
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 text-xl ml-2"></i>
                    دعم فني مجاني
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 text-xl ml-2"></i>
                    تدريب شامل
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-2xl font-bold text-white mb-4">جُذور</h3>
                <p class="text-gray-400">
                    منصة تعليمية مبتكرة تُحول التعلم إلى رحلة نمو شخصية
                </p>
            </div>
            
            <div>
                <h4 class="text-lg font-bold text-white mb-4">روابط سريعة</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">عن جُذور</a></li>
                    <li><a href="{{ route('juzoor.model') }}" class="hover:text-white transition">النموذج التعليمي</a></li>
                    <li><a href="{{ route('for.students') }}" class="hover:text-white transition">للطلاب</a></li>
                    <li><a href="{{ route('contact.show') }}" class="hover:text-white transition">تواصل معنا</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-bold text-white mb-4">المساعدة</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('question.guide') }}" class="hover:text-white transition">دليل الأسئلة</a></li>
                    <li><a href="#" class="hover:text-white transition">الأسئلة الشائعة</a></li>
                    <li><a href="#" class="hover:text-white transition">دروس تعليمية</a></li>
                    <li><a href="#" class="hover:text-white transition">الدعم الفني</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-bold text-white mb-4">تابعنا</h4>
                <div class="flex gap-4 mb-6">
                    
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
                <p class="text-sm text-gray-400">
                    © 2024 جُذور. جميع الحقوق محفوظة.
                </p>
            </div>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
<script>
// Add smooth scroll behavior
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Add animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in-up');
        }
    });
}, observerOptions);

document.querySelectorAll('.feature-card').forEach(el => {
    observer.observe(el);
});
</script>
@endpush