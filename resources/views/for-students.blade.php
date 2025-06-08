@extends('layouts.guest')

@section('title', 'للطلاب - منصة جُذور التعليمية')

@push('styles')
<style>
    * {
        font-family: 'Tajawal', sans-serif !important;
    }
    
    .hero-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #feca57 100%);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }
    
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .fun-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .fun-card::before {
        content: '';
        position: absolute;
        top: -100%;
        left: -100%;
        width: 300%;
        height: 300%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: all 0.6s;
        transform: rotate(45deg);
    }
    
    .fun-card:hover::before {
        top: -50%;
        left: -50%;
    }
    
    .fun-card:hover {
        transform: translateY(-10px) rotate(-1deg);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
    
    .bounce {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-30px); }
        60% { transform: translateY(-15px); }
    }
    
    .wiggle {
        animation: wiggle 2s ease-in-out infinite;
    }
    
    @keyframes wiggle {
        0%, 100% { transform: rotate(-3deg); }
        50% { transform: rotate(3deg); }
    }
    
    .pulse-grow {
        animation: pulse-grow 2s ease-in-out infinite;
    }
    
    @keyframes pulse-grow {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .emoji-rain {
        position: absolute;
        font-size: 2rem;
        animation: fall linear infinite;
    }
    
    @keyframes fall {
        to { transform: translateY(100vh) rotate(360deg); }
    }
    
    /* Ensure button text contrast */
    .btn-white {
        background-color: white !important;
        color: #553c9a !important; /* purple-800 */
    }
    
    .btn-yellow {
        background-color: #f6e05e !important; /* yellow-400 */
        color: #553c9a !important; /* purple-800 */
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden hero-bg text-white min-h-screen">
    <!-- Emoji Rain -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <span class="emoji-rain" style="left: 10%; animation-duration: 8s; animation-delay: 0s;">🌟</span>
        <span class="emoji-rain" style="left: 30%; animation-duration: 10s; animation-delay: 2s;">🚀</span>
        <span class="emoji-rain" style="left: 50%; animation-duration: 9s; animation-delay: 1s;">💡</span>
        <span class="emoji-rain" style="left: 70%; animation-duration: 11s; animation-delay: 3s;">🎯</span>
        <span class="emoji-rain" style="left: 90%; animation-duration: 7s; animation-delay: 2.5s;">🏆</span>
    </div>
    
    <div class="relative z-10 container mx-auto px-4 py-32 pt-64">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black mb-6 bounce mt-16">
                مرحباً يا أبطال! 🎉
            </h1>
            <p class="text-xl md:text-2xl mb-8">
                اكتشف قدراتك الخارقة في التعلم مع <span class="font-bold">جُذور</span>
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('student.register') }}" 
                   class="bg-white text-purple-800 px-8 py-4 rounded-full font-bold text-lg hover:bg-yellow-300 hover:text-purple-900 transform hover:scale-110 transition shadow-2xl wiggle">
                    <i class="fas fa-user-astronaut ml-2 text-purple-800"></i>
                    <span class="text-purple-800">انضم للمغامرة!</span>
                </a>
                <a href="{{ route('home') }}#pin-section" 
                   class="bg-yellow-400 text-purple-800 px-8 py-4 rounded-full font-bold text-lg hover:bg-yellow-300 hover:text-purple-900 transform hover:scale-110 transition shadow-2xl">
                    <i class="fas fa-key ml-2 text-purple-800"></i>
                    <span class="text-purple-800">لديك رمز PIN؟</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- What is Juzoor -->
<section class="py-20 bg-gradient-to-b from-purple-50 to-pink-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">ما هي جُذور؟ 🌱</h2>
            <p class="text-xl text-gray-600">طريقة ممتعة وذكية لاكتشاف مواهبك!</p>
        </div>
        
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-32 h-32 bg-yellow-300 rounded-full -translate-x-16 -translate-y-16 opacity-50"></div>
                <div class="absolute bottom-0 right-0 w-48 h-48 bg-pink-300 rounded-full translate-x-24 translate-y-24 opacity-50"></div>
                
                <div class="relative z-10 grid md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h3 class="text-3xl font-bold mb-6">تخيل أن عقلك شجرة! 🌳</h3>
                        <p class="text-lg text-gray-700 mb-4">
                            مثل الشجرة التي لها جذور مختلفة، عقلك أيضاً له أربعة جذور رائعة:
                        </p>
                        <ul class="space-y-3 text-lg">
                            <li class="flex items-center">
                                <span class="text-2xl ml-3">🎯</span>
                                <span><strong>جَوهر:</strong> قوة الفهم والمعرفة</span>
                            </li>
                            <li class="flex items-center">
                                <span class="text-2xl ml-3">🧠</span>
                                <span><strong>ذِهن:</strong> قوة التفكير والذكاء</span>
                            </li>
                            <li class="flex items-center">
                                <span class="text-2xl ml-3">🔗</span>
                                <span><strong>وَصلات:</strong> قوة الربط والإبداع</span>
                            </li>
                            <li class="flex items-center">
                                <span class="text-2xl ml-3">👁️</span>
                                <span><strong>رُؤية:</strong> قوة التطبيق والابتكار</span>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center">
                        <div class="relative inline-block pulse-grow">
                            <div class="w-64 h-64 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                <span class="text-8xl">🧠</span>
                            </div>
                            <div class="absolute top-0 left-0 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center text-2xl">
                                ✨
                            </div>
                            <div class="absolute bottom-0 right-0 w-20 h-20 bg-green-400 rounded-full flex items-center justify-center text-3xl">
                                💪
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features for Students -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">لماذا ستحب جُذور؟ 💖</h2>
            <p class="text-xl text-gray-600">اكتشف المميزات الرائعة!</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Fun Quizzes -->
            <div class="fun-card bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">🎮</div>
                <h3 class="text-2xl font-bold mb-3">اختبارات ممتعة</h3>
                <p class="text-gray-700">
                    ليست مثل الامتحانات المملة! اختباراتنا مثل الألعاب، ممتعة وتحفز التفكير
                </p>
            </div>
            
            <!-- Instant Results -->
            <div class="fun-card bg-gradient-to-br from-green-100 to-green-200 rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">⚡</div>
                <h3 class="text-2xl font-bold mb-3">نتائج فورية</h3>
                <p class="text-gray-700">
                    بمجرد الانتهاء، شاهد نتيجتك مباشرة مع رسومات رائعة تُظهر قوتك في كل جذر
                </p>
            </div>
            
            <!-- No Stress -->
            <div class="fun-card bg-gradient-to-br from-purple-100 to-purple-200 rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">😌</div>
                <h3 class="text-2xl font-bold mb-3">بدون ضغط</h3>
                <p class="text-gray-700">
                    لا توجد درجات نجاح أو رسوب، فقط اكتشف نقاط قوتك واعمل على تحسينها
                </p>
            </div>
            
            <!-- Track Progress -->
            <div class="fun-card bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">📈</div>
                <h3 class="text-2xl font-bold mb-3">تابع تقدمك</h3>
                <p class="text-gray-700">
                    شاهد كيف تتحسن مع الوقت واحصل على شارات وإنجازات رائعة
                </p>
            </div>
            
            <!-- Learn Languages -->
            <div class="fun-card bg-gradient-to-br from-pink-100 to-pink-200 rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">🌍</div>
                <h3 class="text-2xl font-bold mb-3">تعلم بلغات متعددة</h3>
                <p class="text-gray-700">
                    اختبارات بالعربية والإنجليزية والعبرية، اختر اللغة التي تحبها
                </p>
            </div>
            
            <!-- Easy Access -->
            <div class="fun-card bg-gradient-to-br from-orange-100 to-orange-200 rounded-3xl p-8 text-center">
                <div class="text-6xl mb-4">🔑</div>
                <h3 class="text-2xl font-bold mb-3">دخول سهل</h3>
                <p class="text-gray-700">
                    فقط أدخل رمز PIN من معلمك وابدأ الاختبار فوراً، لا حاجة لكلمات مرور معقدة
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How to Start -->
<section class="py-20 bg-gradient-to-b from-blue-50 to-purple-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">كيف تبدأ؟ 🚀</h2>
            <p class="text-xl text-gray-600">ثلاث خطوات سهلة جداً!</p>
        </div>
        
        <div class="max-w-5xl mx-auto">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="relative inline-block mb-6">
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white">
                            <span class="text-5xl font-bold">1</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center bounce">
                            <span class="text-2xl">📱</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">احصل على الرمز</h3>
                    <p class="text-gray-600 text-lg">
                        اطلب من معلمك رمز PIN الخاص بالاختبار
                    </p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center">
                    <div class="relative inline-block mb-6">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white">
                            <span class="text-5xl font-bold">2</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center bounce" style="animation-delay: 0.5s;">
                            <span class="text-2xl">⌨️</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">أدخل الرمز</h3>
                    <p class="text-gray-600 text-lg">
                        اكتب الرمز في الصفحة الرئيسية واضغط دخول
                    </p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center">
                    <div class="relative inline-block mb-6">
                        <div class="w-32 h-32 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white">
                            <span class="text-5xl font-bold">3</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center bounce" style="animation-delay: 1s;">
                            <span class="text-2xl">🎯</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">ابدأ المغامرة!</h3>
                    <p class="text-gray-600 text-lg">
                        أجب على الأسئلة واكتشف قواك الخارقة
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Achievements Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">اجمع الإنجازات! 🏆</h2>
            <p class="text-xl text-gray-600">كل اختبار تحدي جديد وإنجاز ينتظرك</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
            <!-- Achievement 1 -->
            <div class="text-center wiggle">
                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-2xl mb-3">
                    <span class="text-5xl">⭐</span>
                </div>
                <h4 class="font-bold">البداية الموفقة</h4>
                <p class="text-sm text-gray-600">أكمل أول اختبار</p>
            </div>
            
            <!-- Achievement 2 -->
            <div class="text-center wiggle" style="animation-delay: 0.2s;">
                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center shadow-2xl mb-3">
                    <span class="text-5xl">🚀</span>
                </div>
                <h4 class="font-bold">المثابر</h4>
                <p class="text-sm text-gray-600">أكمل 5 اختبارات</p>
            </div>
            
            <!-- Achievement 3 -->
            <div class="text-center wiggle" style="animation-delay: 0.4s;">
                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-2xl mb-3">
                    <span class="text-5xl">💎</span>
                </div>
                <h4 class="font-bold">المتميز</h4>
                <p class="text-sm text-gray-600">احصل على 90%+</p>
            </div>
            
            <!-- Achievement 4 -->
            <div class="text-center wiggle" style="animation-delay: 0.6s;">
                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-pink-400 to-rose-500 rounded-full flex items-center justify-center shadow-2xl mb-3">
                    <span class="text-5xl">👑</span>
                </div>
                <h4 class="font-bold">سيد الجذور</h4>
                <p class="text-sm text-gray-600">تفوق في كل الجذور</p>
            </div>
        </div>
    </div>
</section>

<!-- Tips Section -->
<section class="py-20 bg-gradient-to-br from-yellow-50 to-orange-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">نصائح للنجاح! 💡</h2>
                <p class="text-xl text-gray-600">اتبع هذه النصائح لتحقق أفضل النتائج</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                    <span class="text-3xl">📚</span>
                    <div>
                        <h4 class="font-bold mb-2">اقرأ بتمعن</h4>
                        <p class="text-gray-600">خذ وقتك في قراءة السؤال جيداً قبل الإجابة</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                    <span class="text-3xl">🧘</span>
                    <div>
                        <h4 class="font-bold mb-2">كن هادئاً</h4>
                        <p class="text-gray-600">لا تتوتر، هذا ليس امتحاناً بل فرصة للتعلم</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                    <span class="text-3xl">💭</span>
                    <div>
                        <h4 class="font-bold mb-2">فكر بعمق</h4>
                        <p class="text-gray-600">بعض الأسئلة تحتاج تفكير، استمتع بالتحدي!</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-lg flex items-start gap-4">
                    <span class="text-3xl">🎯</span>
                    <div>
                        <h4 class="font-bold mb-2">ركز على التعلم</h4>
                        <p class="text-gray-600">الهدف ليس الدرجة بل اكتشاف نقاط قوتك</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-purple-900 via-pink-900 to-orange-900 text-white relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-96 h-96 bg-purple-500 rounded-full filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-500 rounded-full filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-5xl font-bold mb-6 bounce">مستعد للمغامرة؟ 🎉</h2>
            <p class="text-2xl mb-8">ابدأ رحلتك مع جُذور واكتشف قواك الخارقة!</p>
            
            <div class="flex flex-wrap gap-6 justify-center mb-12">
                <a href="{{ route('home') }}#pin-section" 
                   class="bg-yellow-400 text-purple-800 px-10 py-5 rounded-full font-bold text-xl hover:bg-yellow-300 hover:text-purple-900 transform hover:scale-110 transition shadow-2xl inline-flex items-center">
                    <i class="fas fa-play-circle ml-3 text-2xl text-purple-800"></i>
                    <span class="text-purple-800">لديك رمز PIN؟ ابدأ الآن!</span>
                </a>
            </div>
            
            <div class="bg-white/10 backdrop-blur rounded-2xl p-8 max-w-md mx-auto">
                <h3 class="text-2xl font-bold mb-4">أو سجل حساباً مجانياً</h3>
                <p class="mb-6">احفظ نتائجك وتابع تقدمك مع الوقت</p>
                <a href="{{ route('student.register') }}" 
                   class="bg-white text-purple-800 px-6 py-3 rounded-full font-bold hover:bg-gray-100 hover:text-purple-900 transition inline-flex items-center">
                    <i class="fas fa-user-plus ml-2 text-purple-800"></i>
                    <span class="text-purple-800">إنشاء حساب طالب</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Fun Footer -->
<footer class="bg-gray-900 text-gray-300 py-12 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 text-8xl">🌟</div>
        <div class="absolute top-20 right-20 text-6xl">🚀</div>
        <div class="absolute bottom-10 left-1/2 text-7xl">💡</div>
        <div class="absolute bottom-20 right-10 text-9xl">🎯</div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-8">
            <h3 class="text-3xl font-bold text-white mb-2">جُذور</h3>
            <p class="text-lg">رحلة التعلم الممتعة تبدأ من هنا! 🌱</p>
        </div>
        
        <div class="flex flex-wrap justify-center gap-6 mb-8">
            <a href="{{ route('about') }}" class="hover:text-white transition">عن جُذور</a>
            <a href="{{ route('juzoor.model') }}" class="hover:text-white transition">نموذج التعلم</a>
            <a href="{{ route('for.teachers') }}" class="hover:text-white transition">للمعلمين</a>
            <a href="{{ route('contact.show') }}" class="hover:text-white transition">تواصل معنا</a>
        </div>
        
        <div class="flex justify-center gap-4 mb-8">
            <a href="#" class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition transform hover:rotate-12">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition transform hover:rotate-12">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition transform hover:rotate-12">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition transform hover:rotate-12">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
        
        <p class="text-center text-sm text-gray-400">
            © 2024 جُذور. صُنع بـ ❤️ لأبطال المستقبل
        </p>
    </div>
</footer>
@endsection

@push('scripts')
<script>
// Fun cursor effect
document.addEventListener('mousemove', (e) => {
    const cards = document.querySelectorAll('.fun-card');
    const x = e.clientX / window.innerWidth;
    const y = e.clientY / window.innerHeight;
    
    cards.forEach(card => {
        const rect = card.getBoundingClientRect();
        const cardX = rect.left + rect.width / 2;
        const cardY = rect.top + rect.height / 2;
        const angleX = (e.clientY - cardY) * 0.01;
        const angleY = (e.clientX - cardX) * -0.01;
        
        card.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg)`;
    });
});

// Confetti effect on hover
document.querySelectorAll('.wiggle').forEach(element => {
    element.addEventListener('mouseenter', function() {
        confetti({
            particleCount: 30,
            spread: 70,
            origin: { 
                x: (this.getBoundingClientRect().left + this.getBoundingClientRect().width / 2) / window.innerWidth,
                y: (this.getBoundingClientRect().top + this.getBoundingClientRect().height / 2) / window.innerHeight
            }
        });
    });
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>

<!-- Confetti Library -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
@endpush