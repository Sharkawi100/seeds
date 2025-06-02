```blade
@extends('layouts.app')

@section('title', 'عن جُذور - منصة التقييم التفاعلي')

@section('content')
<!-- Hero Section with Modern Gradient -->
<section class="relative min-h-[600px] flex items-center overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-40 right-20 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <div class="mb-8 animate-fade-in-down">
                <h1 class="text-5xl md:text-7xl font-black text-white mb-6">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-300 to-pink-300">
                        منصة السراج
                    </span>
                </h1>
                <p class="text-2xl md:text-3xl text-white font-light">
                    للامتحانات والموارد التعليمية
                </p>
            </div>
            
            <p class="text-lg md:text-xl text-gray-100 max-w-3xl mx-auto mb-10 leading-relaxed animate-fade-in-up">
                نوفر بيئة تعليمية متكاملة تجمع بين الامتحانات الورقية التقليدية والاختبارات التفاعلية الحديثة
                لتحقيق أفضل نتائج التعلم
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-300">
                <a href="https://iseraj.com" target="_blank" 
                   class="group relative inline-flex items-center gap-3 bg-white text-indigo-900 font-bold py-5 px-10 rounded-2xl overflow-hidden transition-all transform hover:scale-105 hover:shadow-2xl">
                    <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <i class="fas fa-external-link-alt relative z-10 group-hover:text-white transition-colors"></i>
                    <span class="relative z-10 group-hover:text-white transition-colors">زيارة منصة السراج</span>
                </a>
                <a href="{{ route('juzoor.model') }}" 
                   class="inline-flex items-center gap-3 bg-transparent border-2 border-white text-white font-bold py-5 px-10 rounded-2xl hover:bg-white/20 hover:border-white transition-all backdrop-blur-sm">
                    <i class="fas fa-seedling"></i>
                    <span>اكتشف نموذج جُذور</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-2xl text-white"></i>
    </div>
</section>

<!-- Platform Ecosystem -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">منظومة تعليمية متكاملة</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">
                منصتان متكاملتان تعملان معاً لتوفير تجربة تعليمية شاملة تلبي جميع احتياجات المعلمين والطلاب
            </p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Iseraj Platform -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl transform rotate-1 group-hover:rotate-2 transition-transform opacity-10"></div>
                <div class="relative bg-white rounded-3xl shadow-xl p-8 hover:shadow-2xl transition-all border border-blue-200">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-pdf text-white text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900">موقع السراج</h3>
                            <p class="text-gray-700">منصة الامتحانات الورقية</p>
                        </div>
                    </div>
                    
                    <p class="text-gray-800 mb-6 leading-relaxed">
                        المنصة الرئيسية لإدارة وتحميل الامتحانات التقليدية، حيث يمكن للمعلمين مشاركة امتحاناتهم
                        بصيغ PDF و DOCX مع مجتمع تعليمي نشط.
                    </p>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-download text-blue-700"></i>
                            </div>
                            <span class="text-gray-800">تحميل امتحانات جاهزة للطباعة</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-folder-open text-indigo-700"></i>
                            </div>
                            <span class="text-gray-800">مكتبة شاملة منظمة حسب المواد والمستويات</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-star text-purple-700"></i>
                            </div>
                            <span class="text-gray-800">نظام تقييم ومكافآت للمساهمين</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Juzoor Platform -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-800 rounded-3xl transform -rotate-1 group-hover:-rotate-2 transition-transform opacity-10"></div>
                <div class="relative bg-white rounded-3xl shadow-xl p-8 hover:shadow-2xl transition-all border-2 border-purple-300">
                    <div class="absolute -top-4 -right-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                        جديد!
                    </div>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="text-white text-4xl">🌱</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900">جُذور</h3>
                            <p class="text-gray-700">منصة الاختبارات التفاعلية</p>
                        </div>
                    </div>
                    
                    <p class="text-gray-800 mb-6 leading-relaxed">
                        الذراع التقني المتقدم لمنصة السراج، متخصص في الاختبارات الإلكترونية التفاعلية
                        باستخدام نموذج جُذور التربوي المبتكر والذكاء الاصطناعي.
                    </p>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-laptop text-purple-700"></i>
                            </div>
                            <span class="text-gray-800">اختبارات إلكترونية مع تصحيح فوري</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-brain text-pink-700"></i>
                            </div>
                            <span class="text-gray-800">تقييم شامل بنموذج جُذور الأربعة</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-robot text-rose-700"></i>
                            </div>
                            <span class="text-gray-800">توليد أسئلة ذكية بـ Claude AI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">لماذا جُذور؟</h2>
            <p class="text-xl text-gray-700">ميزات تجعل التعلم أكثر فعالية ومتعة</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $features = [
                [
                    'icon' => 'fa-magic',
                    'title' => 'ذكاء اصطناعي متقدم',
                    'description' => 'استخدام Claude AI لتوليد أسئلة ذكية ومتنوعة تغطي جميع جوانب المعرفة',
                    'gradient' => 'from-purple-600 to-purple-800',
                    'bg' => 'from-purple-100 to-purple-200'
                ],
                [
                    'icon' => 'fa-chart-line',
                    'title' => 'تحليلات تفصيلية',
                    'description' => 'تقارير شاملة توضح نقاط القوة والضعف في كل جذر من جذور المعرفة',
                    'gradient' => 'from-blue-600 to-blue-800',
                    'bg' => 'from-blue-100 to-blue-200'
                ],
                [
                    'icon' => 'fa-users',
                    'title' => 'مجتمع تعليمي نشط',
                    'description' => 'تواصل مباشر بين المعلمين والطلاب لتبادل الخبرات والمعرفة',
                    'gradient' => 'from-emerald-600 to-emerald-800',
                    'bg' => 'from-emerald-100 to-emerald-200'
                ],
                [
                    'icon' => 'fa-mobile-alt',
                    'title' => 'متوافق مع جميع الأجهزة',
                    'description' => 'تصميم متجاوب يعمل بسلاسة على الهواتف والأجهزة اللوحية والحواسيب',
                    'gradient' => 'from-rose-600 to-rose-800',
                    'bg' => 'from-rose-100 to-rose-200'
                ],
                [
                    'icon' => 'fa-shield-alt',
                    'title' => 'آمن وموثوق',
                    'description' => 'حماية كاملة لبيانات المستخدمين مع نسخ احتياطية دورية',
                    'gradient' => 'from-indigo-600 to-indigo-800',
                    'bg' => 'from-indigo-100 to-indigo-200'
                ],
                [
                    'icon' => 'fa-language',
                    'title' => 'دعم متعدد اللغات',
                    'description' => 'يدعم العربية والإنجليزية والعبرية لتنوع أكبر في المحتوى',
                    'gradient' => 'from-amber-600 to-amber-800',
                    'bg' => 'from-amber-100 to-amber-200'
                ]
            ];
            @endphp
            
            @foreach($features as $feature)
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r {{ $feature['gradient'] }} rounded-3xl blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative bg-gradient-to-br {{ $feature['bg'] }} rounded-3xl p-8 shadow-md hover:shadow-xl transition-all transform hover:-translate-y-2 border border-gray-200">
                    <div class="w-16 h-16 bg-gradient-to-br {{ $feature['gradient'] }} rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas {{ $feature['icon'] }} text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $feature['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">المنصة بالأرقام</h2>
            <p class="text-xl text-gray-700">إنجازات مجتمعنا التعليمي المتنامي</p>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="relative inline-block">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-800 mb-2">
                        <span class="counter" data-target="6539">0</span>+
                    </div>
                </div>
                <p class="text-gray-800 font-bold text-lg">مستخدم نشط</p>
            </div>
            
            <div class="text-center group">
                <div class="relative inline-block">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-800 mb-2">
                        <span class="counter" data-target="9207">0</span>+
                    </div>
                </div>
                <p class="text-gray-800 font-bold text-lg">تحميل</p>
            </div>
            
            <div class="text-center group">
                <div class="relative inline-block">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-purple-800 mb-2">
                        <span class="counter" data-target="90053">0</span>+
                    </div>
                </div>
                <p class="text-gray-800 font-bold text-lg">زيارة</p>
            </div>
            
            <div class="text-center group">
                <div class="relative inline-block">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-400 to-amber-600 rounded-full blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-amber-800 mb-2">
                        <span class="counter" data-target="1200">0</span>+
                    </div>
                </div>
                <p class="text-gray-800 font-bold text-lg">اختبار تفاعلي</p>
            </div>
        </div>
    </div>
</section>

<!-- Developer Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">رؤية تربوية متميزة</h2>
            <p class="text-xl text-gray-700">خبرة تعليمية عميقة تقود الابتكار</p>
        </div>
        
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-br from-slate-800 via-purple-800 to-slate-800 p-1">
                <div class="bg-white rounded-3xl p-8 md:p-12">
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <!-- Circular Image -->
                        <div class="flex-shrink-0">
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur-xl opacity-50 group-hover:opacity-70 transition-opacity"></div>
                                <div class="relative w-48 h-48 rounded-full overflow-hidden border-4 border-white shadow-2xl">
                                    <img src="https://iseraj.com/assets/images/ashraf.jpg" 
                                         alt="أشرف شرقاوي" 
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white p-3 rounded-full shadow-lg">
                                    <i class="fas fa-award text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 text-center md:text-right">
                            <h3 class="text-3xl font-black text-gray-900 mb-2">أشرف شرقاوي</h3>
                            <p class="text-xl text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 font-bold mb-6">
                                مؤسس منصة السراج التعليمية
                            </p>
                            
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 bg-gradient-to-r from-blue-100 to-purple-100 p-4 rounded-2xl">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                        <i class="fas fa-graduation-cap text-white"></i>
                                    </div>
                                    <div class="text-right">
                                        <h4 class="font-bold text-gray-900 mb-1">خبرة تربوية عميقة</h4>
                                        <p class="text-gray-700 text-sm">أكثر من 15 عاماً في مجال التعليم والتطوير التربوي</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4 bg-gradient-to-r from-purple-100 to-indigo-100 p-4 rounded-2xl">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                        <i class="fas fa-lightbulb text-white"></i>
                                    </div>
                                    <div class="text-right">
                                        <h4 class="font-bold text-gray-900 mb-1">رائد الابتكار التعليمي</h4>
                                        <p class="text-gray-700 text-sm">مبتكر نموذج جُذور التربوي لتقييم شامل ومتوازن</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4 bg-gradient-to-r from-indigo-100 to-blue-100 p-4 rounded-2xl">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                        <i class="fas fa-code text-white"></i>
                                    </div>
                                    <div class="text-right">
                                        <h4 class="font-bold text-gray-900 mb-1">دمج التقنية بالتعليم</h4>
                                        <p class="text-gray-700 text-sm">تطوير حلول تقنية متقدمة لتحسين العملية التعليمية</p>
                                    </div>
                                </div>
                            </div>
                            
                            <blockquote class="mt-6 border-r-4 border-gradient-to-b from-blue-500 to-purple-500 pr-4 italic text-gray-700 bg-gray-100 p-4 rounded-xl">
                                "التعليم ليس مجرد نقل معلومات، بل بناء عقول قادرة على التفكير والإبداع والابتكار"
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-900/30 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-900/30 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-black text-white mb-6">كن جزءاً من التغيير</h2>
        <p class="text-xl text-gray-100 mb-10 leading-relaxed">
            انضم لآلاف المعلمين والطلاب الذين يستخدمون منصة السراج وجُذور
            لتحسين تجربة التعلم وتحقيق نتائج أفضل
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="group relative inline-flex items-center gap-3 bg-white text-gray-900 font-bold py-5 px-10 rounded-2xl overflow-hidden transition-all transform hover:scale-105 hover:shadow-2xl">
                <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-purple-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <i class="fas fa-rocket relative z-10 group-hover:text-white transition-colors"></i>
                <span class="relative z-10 group-hover:text-white transition-colors">ابدأ رحلتك الآن</span>
            </a>
            
            <a href="https://iseraj.com" target="_blank" 
               class="inline-flex items-center gap-3 bg-gray-800 border-2 border-gray-600 text-gray-100 font-bold py-5 px-10 rounded-2xl hover:bg-gray-700 hover:border-gray-500 transition-all">
                <i class="fas fa-globe"></i>
                <span>استكشف منصة السراج</span>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Counter animation
function animateCounter(element) {
    const target = parseInt(element.dataset.target);
    const duration = 2500;
    const step = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.round(current).toLocaleString('en-US');
    }, 16);
}

// Intersection Observer for animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            if (entry.target.classList.contains('counter')) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
            
            // Add animation classes
            if (entry.target.classList.contains('animate-on-scroll')) {
                entry.target.classList.add('animate-fade-in-up');
            }
        }
    });
}, { threshold: 0.5 });

// Observe elements
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.counter').forEach(counter => {
        observer.observe(counter);
    });
    
    document.querySelectorAll('.animate-on-scroll').forEach(element => {
        observer.observe(element);
    });
});
</script>
@endpush

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
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-down {
    animation: fade-in-down 0.8s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animation-delay-300 {
    animation-delay: 300ms;
}

/* Smooth transitions */
* {
    transition-property: transform, box-shadow, opacity;
    transition-duration: 300ms;
    transition-timing-function: ease-out;
}

/* Counter styles */
.counter {
    display: inline-block;
    min-width: 120px;
    font-variant-numeric: tabular-nums;
}

/* Hover effects */
.group:hover .group-hover\:opacity-40 {
    opacity: 0.4;
}

/* Gradient text animation */
@keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.bg-clip-text {
    background-size: 200% 200%;
    animation: gradient-shift 3s ease infinite;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #6366f1, #8b5cf6);
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #4f46e5, #7c3aed);
}

/* Border gradient */
.border-gradient-to-b {
    border-image: linear-gradient(to bottom, #3b82f6, #a855f7) 1;
}
</style>
@endpush
```