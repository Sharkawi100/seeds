@extends('layouts.app')

@section('title', 'تحقق من بريدك الإلكتروني - جُذور')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-800 flex items-center justify-center p-4 relative overflow-hidden" dir="rtl">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-yellow-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-40 w-80 h-80 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="max-w-lg w-full relative z-10">
        <!-- Success Alert -->
        @if (session('status') == 'verification-link-sent')
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl p-4 mb-6 animate-slide-down shadow-2xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div class="mr-3">
                        <p class="text-sm font-semibold">
                            ✨ تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني!
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center backdrop-blur-lg border border-white/20 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600"></div>
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-purple-200 to-blue-200 rounded-full opacity-20"></div>
            <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-br from-pink-200 to-purple-200 rounded-full opacity-20"></div>

            <!-- Logo/Branding -->
            <div class="mb-8 relative z-10">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg relative">
                    <i class="fas fa-envelope-open text-white text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation text-white text-xs"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-3">
                    تحقق من بريدك الإلكتروني
                </h1>
                <div class="flex items-center justify-center space-x-2 space-x-reverse">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">جُ</span>
                    </div>
                    <p class="text-gray-700 font-semibold">منصة جُذور التعليمية</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="mb-8 relative z-10">
                <!-- Welcome Message -->
                <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-2xl p-6 mb-6 border-2 border-purple-200">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-blue-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-sparkles text-white text-xl"></i>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        🎉 مرحباً بك في منصة جُذور!
                    </h2>
                    <div class="bg-white rounded-lg p-4 mb-4 shadow-sm">
                        <p class="text-gray-700 leading-relaxed mb-3">
                            لقد أرسلنا رسالة تحقق إلى:
                        </p>
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg p-3 font-mono text-sm break-all">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm font-medium">
                        📧 انقر على الرابط في الرسالة لتفعيل حسابك والاستمتاع بجميع ميزات المنصة
                    </p>
                </div>

                <!-- Step-by-Step Instructions -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl p-6 mb-6 shadow-xl">
                    <h3 class="font-bold text-lg mb-4 flex items-center justify-center">
                        <i class="fas fa-list-check ml-2"></i>
                        خطوات التفعيل
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 space-x-reverse">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-white font-bold text-sm">1</span>
                            </div>
                            <div>
                                <p class="font-semibold">افتح بريدك الإلكتروني</p>
                                <p class="text-blue-100 text-sm">تحقق من صندوق الوارد والرسائل غير المرغوب فيها</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 space-x-reverse">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-white font-bold text-sm">2</span>
                            </div>
                            <div>
                                <p class="font-semibold">ابحث عن رسالة من جُذور</p>
                                <p class="text-blue-100 text-sm">العنوان: "Verify Email Address"</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 space-x-reverse">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-white font-bold text-sm">3</span>
                            </div>
                            <div>
                                <p class="font-semibold">انقر على زر التفعيل</p>
                                <p class="text-blue-100 text-sm">سيتم تحويلك مباشرة للمنصة</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Tips -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-200 rounded-xl p-4">
                    <div class="flex items-center justify-center mb-3">
                        <i class="fas fa-lightbulb text-amber-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-amber-800 mb-2 text-center">💡 نصائح مفيدة</h4>
                    <ul class="text-sm text-amber-700 space-y-1 text-right">
                        <li>• قد تستغرق الرسالة حتى 5 دقائق للوصول</li>
                        <li>• تحقق من مجلد "الترويج" في Gmail</li>
                        <li>• أضف noreply@iseraj.com لجهات الاتصال الآمنة</li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4 relative z-10">
                <!-- Resend Button -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 hover:from-purple-700 hover:via-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-purple-300 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        <span class="relative flex items-center justify-center">
                            <i class="fas fa-paper-plane ml-2 text-lg"></i>
                            <span class="text-lg">إعادة إرسال رسالة التحقق</span>
                        </span>
                    </button>
                </form>

                <!-- Email Client Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="mailto:" 
                       class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:shadow-lg text-sm flex items-center justify-center">
                        <i class="fas fa-envelope-open ml-2"></i>
                        فتح البريد
                    </a>
                    <button onclick="refreshPage()" 
                            class="bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:shadow-lg text-sm flex items-center justify-center">
                        <i class="fas fa-refresh ml-2"></i>
                        تحديث الصفحة
                    </button>
                </div>

                <!-- Quick Access Links -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-600 mb-3 font-medium">روابط سريعة:</p>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <a href="https://gmail.com" target="_blank" 
                           class="bg-red-500 text-white p-2 rounded-lg text-center hover:bg-red-600 transition-colors">
                            <i class="fab fa-google mb-1 block"></i>
                            Gmail
                        </a>
                        <a href="https://outlook.live.com" target="_blank" 
                           class="bg-blue-600 text-white p-2 rounded-lg text-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-microsoft mb-1 block"></i>
                            Outlook
                        </a>
                        <a href="https://mail.yahoo.com" target="_blank" 
                           class="bg-purple-600 text-white p-2 rounded-lg text-center hover:bg-purple-700 transition-colors">
                            <i class="fab fa-yahoo mb-1 block"></i>
                            Yahoo
                        </a>
                    </div>
                </div>

                <!-- Alternative Actions -->
                <div class="pt-6 border-t-2 border-gray-100">
                    <p class="text-sm text-gray-600 mb-4 font-medium">تحتاج مساعدة؟</p>
                    <div class="flex space-x-3 space-x-reverse">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex-1 bg-white border-2 border-purple-300 text-purple-600 hover:bg-purple-50 hover:border-purple-400 font-semibold py-3 px-4 rounded-lg transition-all duration-300 text-center text-sm">
                            <i class="fas fa-edit ml-1"></i>
                            تغيير البريد
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-white border-2 border-gray-300 text-gray-600 hover:bg-gray-50 hover:border-gray-400 font-semibold py-3 px-4 rounded-lg transition-all duration-300 text-sm">
                                <i class="fas fa-sign-out-alt ml-1"></i>
                                تسجيل خروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-8">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 text-white shadow-xl">
                <div class="flex items-center justify-center mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-blue-400 rounded-lg flex items-center justify-center mr-3">
                        <span class="text-white font-bold">جُ</span>
                    </div>
                    <div>
                        <p class="font-bold text-lg">منصة جُذور</p>
                        <p class="text-sm text-purple-200">التعليم الذكي باستخدام الذكاء الاصطناعي</p>
                    </div>
                </div>
                <div class="border-t border-white/20 pt-3">
                    <p class="text-xs text-purple-200">
                        © 2025 جميع الحقوق محفوظة لمنصة جُذور | صُنع بـ ❤️ في السعودية
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Verification Success Modal -->
@if (request()->has('verified') && request('verified') == 1)
<div id="successModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50" dir="rtl">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 text-center animate-success-bounce shadow-2xl relative overflow-hidden">
        <!-- Confetti Animation -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="confetti"></div>
            <div class="confetti"></div>
            <div class="confetti"></div>
            <div class="confetti"></div>
            <div class="confetti"></div>
        </div>
        
        <!-- Success Content -->
        <div class="relative z-10">
            <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg animate-pulse">
                <i class="fas fa-check text-white text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-3">🎉 تم التحقق بنجاح!</h3>
            <p class="text-gray-600 mb-6 leading-relaxed">
                مبروك! تم تفعيل حسابك بنجاح في منصة جُذور.<br>
                يمكنك الآن الاستفادة من جميع الميزات المتاحة.
            </p>
            <div class="space-y-3">
                <button onclick="closeSuccessModal()" 
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-rocket ml-2"></i>
                    البدء في استخدام المنصة
                </button>
                <p class="text-xs text-gray-500">سيتم التحويل تلقائياً بعد 10 ثواني...</p>
            </div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');
    
    body {
        font-family: 'Cairo', sans-serif;
    }

    /* Animation Keyframes */
    @keyframes slide-down {
        from { 
            opacity: 0; 
            transform: translateY(-20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    @keyframes success-bounce {
        0% { 
            opacity: 0; 
            transform: scale(0.3) rotate(-10deg); 
        }
        50% { 
            opacity: 1; 
            transform: scale(1.05) rotate(2deg); 
        }
        100% { 
            opacity: 1; 
            transform: scale(1) rotate(0deg); 
        }
    }

    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }

    @keyframes confetti-fall {
        0% { 
            transform: translateY(-100vh) rotate(0deg); 
            opacity: 1; 
        }
        100% { 
            transform: translateY(100vh) rotate(360deg); 
            opacity: 0; 
        }
    }

    /* Animation Classes */
    .animate-slide-down {
        animation: slide-down 0.6s ease-out;
    }

    .animate-success-bounce {
        animation: success-bounce 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
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

    /* Confetti Animation */
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #ffeaa7);
        animation: confetti-fall 3s linear infinite;
    }

    .confetti:nth-child(1) {
        left: 10%;
        animation-delay: 0s;
        background: #ff6b6b;
    }

    .confetti:nth-child(2) {
        left: 20%;
        animation-delay: 0.5s;
        background: #4ecdc4;
    }

    .confetti:nth-child(3) {
        left: 30%;
        animation-delay: 1s;
        background: #45b7d1;
    }

    .confetti:nth-child(4) {
        left: 40%;
        animation-delay: 1.5s;
        background: #96ceb4;
    }

    .confetti:nth-child(5) {
        left: 50%;
        animation-delay: 2s;
        background: #ffeaa7;
    }

    /* Improved RTL Support */
    [dir="rtl"] .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
        --tw-space-x-reverse: 1;
        margin-right: calc(0.75rem * var(--tw-space-x-reverse));
        margin-left: calc(0.75rem * calc(1 - var(--tw-space-x-reverse)));
    }

    /* Enhanced Gradient Text */
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
    }

    /* Improved Button Hover Effects */
    .group:hover .group-hover\:translate-x-full {
        transform: translateX(100%);
    }

    /* Better Backdrop Blur */
    .backdrop-blur-lg {
        backdrop-filter: blur(16px);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #5a67d8 0%, #6b46c1 100%);
    }
</style>
@endpush

@push('scripts')
<script>
    // Page Load Animations
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success alert
        const successAlert = document.querySelector('.bg-gradient-to-r.from-green-500');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-10px)';
                setTimeout(() => successAlert.remove(), 300);
            }, 6000);
        }
    });

    // Success Modal Functions
    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        if (modal) {
            modal.style.transition = 'all 0.5s ease';
            modal.style.opacity = '0';
            modal.style.transform = 'scale(0.9)';
            setTimeout(() => {
                window.location.href = '{{ route('dashboard') }}';
            }, 500);
        }
    }

    // Auto-close success modal with countdown
    @if (request()->has('verified') && request('verified') == 1)
    let countdown = 10;
    const countdownInterval = setInterval(() => {
        countdown--;
        const countdownText = document.querySelector('#successModal p:last-child');
        if (countdownText) {
            countdownText.textContent = `سيتم التحويل تلقائياً بعد ${countdown} ثواني...`;
        }
        if (countdown <= 0) {
            clearInterval(countdownInterval);
            closeSuccessModal();
        }
    }, 1000);
    @endif

    // Form Submission Handler
    const resendForm = document.querySelector('form[action="{{ route('verification.send') }}"]');
    if (resendForm) {
        resendForm.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const originalHTML = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = `
                <span class="relative flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin ml-2 text-lg"></i>
                    <span class="text-lg">جاري الإرسال...</span>
                </span>
            `;
            
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = originalHTML;
            }, 3000);
        });
    }

    // Utility Functions
    function refreshPage() {
        window.location.reload();
    }

    // Email Client Detection
    function detectEmailDomain() {
        const email = '{{ auth()->user()->email }}';
        const domain = email.split('@')[1].toLowerCase();
        
        const emailServices = {
            'gmail.com': 'https://gmail.com',
            'yahoo.com': 'https://mail.yahoo.com',
            'hotmail.com': 'https://outlook.live.com',
            'outlook.com': 'https://outlook.live.com',
            'icloud.com': 'https://www.icloud.com/mail'
        };
        
        return emailServices[domain] || 'https://gmail.com';
    }

    // Email Links Handler
    const emailLinks = document.querySelectorAll('a[href="mailto:"]');
    emailLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const mailtoLink = `mailto:{{ auth()->user()->email }}`;
            window.location.href = mailtoLink;
            
            setTimeout(() => {
                window.open(detectEmailDomain(), '_blank');
            }, 1000);
        });
    });

    // Keyboard Navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('successModal');
            if (modal) {
                closeSuccessModal();
            }
        }
    });
</script>
@endpush

@endsection