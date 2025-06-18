@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@push('styles')
<style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .animated-shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
    }
    
    .shape-1 {
        width: 80px;
        height: 80px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .shape-2 {
        width: 120px;
        height: 120px;
        top: 70%;
        right: 10%;
        animation-delay: 2s;
    }
    
    .shape-3 {
        width: 60px;
        height: 60px;
        bottom: 10%;
        left: 30%;
        animation-delay: 4s;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(180deg); }
    }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
    
    .role-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .role-option {
        flex: 1;
        padding: 1.5rem;
        text-align: center;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    
    .role-option:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }
    
    .role-option.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .role-option i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .password-toggle {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6b7280;
        padding: 5px;
    }
    
    .password-toggle:hover {
        color: #667eea;
    }
    
    @media (max-width: 640px) {
        .role-selector {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <!-- Animated Background Shapes -->
    <div class="animated-shape shape-1"></div>
    <div class="animated-shape shape-2"></div>
    <div class="animated-shape shape-3"></div>
    
    
    
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-md">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-white mb-2">🌱 جُذور</h1>
                <p class="text-white/80 text-lg">منصة التعلم التفاعلي</p>
            </div>
            
            <!-- Login Form Container -->
            <div class="glass-effect rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">مرحباً بعودتك!</h2>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                
                <!-- Role Selector -->
                <div class="role-selector" x-data="{ role: 'student' }">
                    <div class="role-option" 
                         :class="{ 'selected': role === 'student' }"
                         @click="role = 'student'">
                        <i class="fas fa-user-graduate"></i>
                        <span class="font-semibold">أنا طالب</span>
                    </div>
                    <div class="role-option" 
                         :class="{ 'selected': role === 'teacher' }"
                         @click="role = 'teacher'">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="font-semibold">أنا معلم</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                    @csrf
                    
                    <!-- Hidden Role Field -->
                    <input type="hidden" name="login_role" x-bind:value="role">
                    
                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope ml-1"></i>
                            البريد الإلكتروني
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="أدخل بريدك الإلكتروني">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-6 relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock ml-1"></i>
                            كلمة المرور
                        </label>
                        <input id="password" 
                               :type="showPassword ? 'text' : 'password'"
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="أدخل كلمة المرور">
                        <button type="button" 
                                @click="showPassword = !showPassword"
                                class="password-toggle">
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500">
                            <span class="mr-2 text-sm text-gray-600">تذكرني</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="text-sm text-purple-600 hover:text-purple-700 transition">
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition duration-200 transform hover:scale-[1.02]">
                        <i class="fas fa-sign-in-alt ml-2"></i>
                        تسجيل الدخول
                    </button>
                    <!-- Social Login Buttons -->
<div class="mt-6 space-y-3">
    <a href="{{ route('social.login', 'google') }}" 
       class="flex items-center justify-center w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-300 transition duration-200 transform hover:scale-[1.02]">
        <svg class="w-5 h-5 ml-3" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        تسجيل الدخول بواسطة Google
    </a>
    
    
<!-- Update the divider section -->
<div class="my-6 flex items-center">
    <div class="flex-1 border-t border-gray-300"></div>
    <span class="px-4 text-sm text-gray-500">أو استخدم البريد الإلكتروني</span>
    <div class="flex-1 border-t border-gray-300"></div>
</div>
                </form>
                
                <!-- Divider -->
                <div class="my-6 flex items-center">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-sm text-gray-500">أو</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>
                
                <!-- Quick Access Options -->
                <div class="space-y-3">
                    <a href="{{ route('quiz.demo') }}" 
                       class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition">
                        <i class="fas fa-play-circle ml-2"></i>
                        جرب نموذج تجريبي
                    </a>
                    
                    <a href="{{ route('register') }}" 
                       class="block w-full text-center border-2 border-purple-600 text-purple-600 hover:bg-purple-50 font-medium py-3 px-4 rounded-lg transition">
                        <i class="fas fa-user-plus ml-2"></i>
                        إنشاء حساب جديد
                    </a>
                </div>
                
                <!-- PIN Access -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        لديك رمز اختبار؟ 
                        <a href="{{ route('home') }}#pin-section" class="text-purple-600 hover:text-purple-700 font-medium">
                            أدخله هنا
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Footer Links -->
            <div class="mt-8 text-center">
                <a href="{{ route('juzoor.model') }}" class="text-white/80 hover:text-white text-sm mx-3">
                    عن نموذج جُذور
                </a>
                <span class="text-white/60">•</span>
                <a href="{{ route('contact.show') }}" class="text-white/80 hover:text-white text-sm mx-3">
                    تواصل معنا
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endpush
@endsection