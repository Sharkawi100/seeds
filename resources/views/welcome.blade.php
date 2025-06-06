@extends('layouts.guest')

@section('title', 'منصة جُذور التعليمية')

@section('content')
<!-- Hero Section -->
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

            <!-- Tagline -->
            <div class="mb-12 animate-fade-in-up animation-delay-300">
                <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    نموذج تعليمي مبتكر يحول كل معلومة إلى شجرة معرفة
                </p>
            </div>

            <!-- PIN Entry -->
            <div class="max-w-xl mx-auto mb-12 animate-fade-in-up animation-delay-500">
                <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20">
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bolt text-xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">ابدأ الآن - دخول سريع</h3>
                    </div>
                    
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

            <!-- Quick Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-700">
                <a href="{{ route('quiz.demo') }}" 
                   class="inline-flex items-center gap-3 bg-white/90 text-purple-600 font-bold py-4 px-8 rounded-xl border-2 border-purple-300 hover:bg-purple-50 hover:border-purple-400 transition-all transform hover:scale-105 shadow-lg backdrop-blur">
                    <i class="fas fa-play-circle text-2xl"></i>
                    <span>جرّب اختبار تجريبي</span>
                </a>
                
                <a href="{{ route('juzoor.model') }}" 
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:scale-105 transition-all">
                    <i class="fas fa-book text-2xl"></i>
                    <span>اكتشف نموذج جُذور</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Quick Features -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-brain text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">نموذج جُذور الأربعة</h3>
                <p class="text-gray-600">تقييم شامل يغطي جميع جوانب المعرفة</p>
                <a href="{{ route('juzoor.model') }}" class="inline-block mt-3 text-purple-600 hover:text-purple-800 font-semibold">
                    اعرف المزيد <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-700 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-graduation-cap text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">للمعلمين والطلاب</h3>
                <p class="text-gray-600">أدوات متخصصة لكل دور تعليمي</p>
                <a href="{{ route('about') }}#roles" class="inline-block mt-3 text-green-600 hover:text-green-800 font-semibold">
                    اختر دورك <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-chart-line text-3xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">تتبع النمو</h3>
                <p class="text-gray-600">شاهد تطور المعرفة بصرياً</p>
                <a href="{{ route('juzoor.model') }}#growth" class="inline-block mt-3 text-blue-600 hover:text-blue-800 font-semibold">
                    شاهد النمو <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Simple Stats -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-4xl font-black text-purple-600 mb-2">
                    <span class="counter" data-target="{{ $stats['total_quizzes'] ?? 156 }}">0</span>+
                </div>
                <p class="text-gray-600">اختبار</p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-blue-600 mb-2">
                    <span class="counter" data-target="{{ $stats['total_attempts'] ?? 2341 }}">0</span>+
                </div>
                <p class="text-gray-600">محاولة</p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-green-600 mb-2">
                    <span class="counter" data-target="{{ $stats['active_schools'] ?? 12 }}">0</span>
                </div>
                <p class="text-gray-600">مدرسة</p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-black text-orange-600 mb-2">
                    <span class="counter" data-target="{{ $stats['total_questions'] ?? 1847 }}">0</span>+
                </div>
                <p class="text-gray-600">سؤال</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
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
            <a href="{{ route('about') }}" 
               class="bg-transparent border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-purple-600 transition-all">
                <i class="fas fa-info-circle ml-2"></i>
                تعرف علينا
            </a>
        </div>
    </div>
</section>

<!-- Simple Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-2xl font-bold mb-4">جُذور</h3>
                <p class="text-gray-400">منصة تعليمية مبتكرة</p>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">روابط سريعة</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white">عن جُذور</a></li>
                    <li><a href="{{ route('juzoor.model') }}" class="text-gray-400 hover:text-white">نموذج جُذور</a></li>
                    <li><a href="{{ route('contact.show') }}" class="text-gray-400 hover:text-white">تواصل معنا</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">للمستخدمين</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">إنشاء حساب</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">تسجيل دخول</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2024 جُذور. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>
@endsection

@push('styles')
<style>
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
</style>
@endpush

@push('scripts')
<script>
// PIN input formatting
document.querySelector('input[name="pin"]').addEventListener('input', function(e) {
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

// Intersection Observer
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            if (entry.target.classList.contains('counter')) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.counter').forEach(el => {
    observer.observe(el);
});
</script>
@endpush