@extends('layouts.guest')

@section('title', 'عن جُذور - منصة التقييم التفاعلي')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[400px] flex items-center overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <div class="absolute inset-0 bg-black opacity-10"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-300 to-pink-300">
                    منصة جُذور
                </span>
            </h1>
            <p class="text-2xl md:text-3xl text-white font-light">
                للامتحانات والموارد التعليمية
            </p>
        </div>
    </div>
</section>

<!-- Platform Overview -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">منظومة تعليمية متكاملة</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">
                منصة متكاملة توفر تجربة تعليمية شاملة تلبي جميع احتياجات المعلمين والطلاب
            </p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Iseraj Platform -->
            <div class="group relative">
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
                    <p class="text-gray-800 mb-4">
                        المنصة الرئيسية لإدارة وتحميل الامتحانات التقليدية
                    </p>
                    <a href="https://iseraj.com" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
                        زيارة الموقع <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            
            <!-- Juzoor Platform -->
            <div class="group relative">
                <div class="relative bg-white rounded-3xl shadow-xl p-8 hover:shadow-2xl transition-all border-2 border-purple-300">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="text-white text-4xl">🌱</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900">جُذور</h3>
                            <p class="text-gray-700">منصة الاختبارات التفاعلية</p>
                        </div>
                    </div>
                    <p class="text-gray-800 mb-4">
                        الذراع التقني المتقدم متخصص في الاختبارات الإلكترونية التفاعلية
                    </p>
                    <a href="{{ route('juzoor.model') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-semibold">
                        اكتشف النموذج <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Role-Based Sections -->
<section id="roles" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">اختر دورك</h2>
            <p class="text-xl text-gray-700">حلول مخصصة لكل مستخدم</p>
        </div>

        <!-- Role Selection -->
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-16">
            <button onclick="showRole('teacher')" id="teacher-btn" 
                    class="role-btn bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all border-2 border-transparent hover:border-blue-500 focus:border-blue-500 focus:outline-none">
                <div class="text-5xl mb-4">👨‍🏫</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">أنا معلم</h3>
                <p class="text-gray-600">أريد إنشاء اختبارات وتتبع أداء الطلاب</p>
            </button>

            <button onclick="showRole('student')" id="student-btn"
                    class="role-btn bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all border-2 border-transparent hover:border-green-500 focus:border-green-500 focus:outline-none">
                <div class="text-5xl mb-4">🎓</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">أنا طالب</h3>
                <p class="text-gray-600">أريد إجراء اختبارات وتتبع تقدمي</p>
            </button>
        </div>

        <!-- Teacher Section -->
        <div id="teacher-section" class="role-section hidden">
            <div class="bg-gradient-to-br from-blue-50 to-white rounded-3xl shadow-xl p-8 md:p-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">أدوات قوية للمعلمين</h3>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-robot text-2xl text-blue-600"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">توليد ذكي</h4>
                        <p class="text-gray-600">استخدم AI لإنشاء أسئلة متوازنة</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">تحليلات مفصلة</h4>
                        <p class="text-gray-600">تابع نمو طلابك في كل جذر</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-share-alt text-2xl text-blue-600"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">مشاركة سهلة</h4>
                        <p class="text-gray-600">شارك برمز PIN بسيط</p>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('register') }}" 
                       class="inline-block bg-blue-600 text-white font-bold py-4 px-10 rounded-xl hover:bg-blue-700 transition-all">
                        ابدأ كمعلم
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Section -->
        <div id="student-section" class="role-section hidden">
            <div class="bg-gradient-to-br from-green-50 to-white rounded-3xl shadow-xl p-8 md:p-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">تعلم بطريقة ممتعة</h3>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-gamepad text-2xl text-green-600"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">تعلم تفاعلي</h4>
                        <p class="text-gray-600">أسئلة متنوعة وممتعة</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-2xl text-green-600"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">تقدم واضح</h4>
                        <p class="text-gray-600">شاهد نموك في كل جذر</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-trophy text-2xl text-green-600"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">إنجازات</h4>
                        <p class="text-gray-600">احتفل بتقدمك</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 mb-8">
                    <h4 class="text-xl font-bold mb-4 text-center">جرب الآن برمز PIN</h4>
                    <form action="{{ route('quiz.enter-pin') }}" method="POST" class="max-w-md mx-auto">
                        @csrf
                        <input type="text" name="pin" placeholder="أدخل رمز الاختبار" 
                               class="w-full px-4 py-3 text-center text-xl font-bold border-2 border-gray-300 rounded-lg focus:border-green-500 mb-4"
                               maxlength="6" required>
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition-all">
                            ابدأ الاختبار
                        </button>
                    </form>
                </div>

                <div class="text-center">
                    <a href="{{ route('register') }}" 
                       class="inline-block bg-gray-200 text-gray-700 font-bold py-4 px-10 rounded-xl hover:bg-gray-300 transition-all">
                        إنشاء حساب طالب
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Developer Section -->
<section class="py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">رؤية تربوية متميزة</h2>
        </div>
        
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-br from-slate-800 via-purple-800 to-slate-800 p-1">
                <div class="bg-white rounded-3xl p-8 md:p-12">
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-shrink-0">
                            <div class="relative w-48 h-48 rounded-full overflow-hidden border-4 border-white shadow-2xl">
                                <img src="https://iseraj.com/assets/images/ashraf.jpg" 
                                     alt="أشرف شرقاوي" 
                                     class="w-full h-full object-cover">
                            </div>
                        </div>
                        
                        <div class="flex-1 text-center md:text-right">
                            <h3 class="text-3xl font-black text-gray-900 mb-2">أشرف شرقاوي</h3>
                            <p class="text-xl text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 font-bold mb-6">
                                مؤسس منصة السراج التعليمية
                            </p>
                            
                            <blockquote class="border-r-4 border-gradient-to-b from-blue-500 to-purple-500 pr-4 italic text-gray-700 bg-gray-100 p-4 rounded-xl">
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
<section class="py-20 bg-gradient-to-br from-purple-600 to-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6">كن جزءاً من التغيير</h2>
        <p class="text-xl mb-8 opacity-90">
            انضم لآلاف المعلمين والطلاب الذين يستخدمون منصة جُذور
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-white text-purple-600 font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition-all">
                <i class="fas fa-rocket ml-2"></i>
                ابدأ رحلتك الآن
            </a>
            
            <a href="https://iseraj.com" target="_blank" 
               class="bg-transparent border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-purple-600 transition-all">
                <i class="fas fa-globe ml-2"></i>
                استكشف منصة السراج
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function showRole(role) {
    // Hide all sections
    document.querySelectorAll('.role-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Remove active state from buttons
    document.querySelectorAll('.role-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'border-green-500');
    });
    
    // Show selected section
    document.getElementById(`${role}-section`).classList.remove('hidden');
    
    // Highlight selected button
    const btn = document.getElementById(`${role}-btn`);
    btn.classList.add(role === 'teacher' ? 'border-blue-500' : 'border-green-500');
    
    // Smooth scroll to section
    document.getElementById(`${role}-section`).scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Auto-show teacher section if hash is #roles
if (window.location.hash === '#roles') {
    setTimeout(() => showRole('teacher'), 500);
}
</script>
@endpush