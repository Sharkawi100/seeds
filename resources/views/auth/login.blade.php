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
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .animated-shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
        pointer-events: none;
    }
    
    .shape-1 {
        width: 100px;
        height: 100px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .shape-2 {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 15%;
        animation-delay: 2s;
    }
    
    .shape-3 {
        width: 80px;
        height: 80px;
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .password-toggle {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0.25rem;
    }
    
    .password-toggle:hover {
        color: #8b5cf6;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <!-- Animated Background Shapes -->
    <div class="animated-shape shape-1"></div>
    <div class="animated-shape shape-2"></div>
    <div class="animated-shape shape-3"></div>
    
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-white mb-2">🌱 جُذور</h1>
                <p class="text-white/80 text-lg">مرحباً بعودتك</p>
            </div>
            
            <!-- Login Form Container -->
            <div class="glass-effect rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">تسجيل الدخول</h2>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                
                <!-- Login Form -->
                <div x-data="{ showPassword: false }">
                    <!-- Email Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- Email -->
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
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="example@email.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock ml-1"></i>
                                كلمة المرور
                            </label>
                            <div class="relative">
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'"
                                       name="password" 
                                       required
                                       class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       placeholder="أدخل كلمة المرور">
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        class="password-toggle">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500">
                                <span class="mr-2 text-sm text-gray-600">تذكرني</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-purple-600 hover:text-purple-700">
                                    نسيت كلمة المرور؟
                                </a>
                            @endif
                        </div>
                        
                        <!-- Submit -->
                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition duration-200">
                            <i class="fas fa-sign-in-alt ml-2"></i>
                            تسجيل الدخول
                        </button>
                    </form>
                </div>
                
                <!-- Registration Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ليس لديك حساب؟ 
                        <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                            سجل حساب جديد
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Simple script for any future enhancements
console.log('جُذور - تسجيل الدخول');
</script>
@endpush
@endsection