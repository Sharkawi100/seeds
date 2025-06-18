@extends('layouts.guest')

@section('title', 'دخول الطلاب')

@push('styles')
<style>
    .student-login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }
    
    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .student-badge {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 25px rgba(72, 187, 120, 0.3);
    }
    
    .login-method {
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .login-method.active {
        border-color: #48bb78;
        background: #f0fff4;
    }
</style>
@endpush

@section('content')
<div class="student-login-container flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="form-container rounded-2xl p-8">
            <!-- Student Badge -->
            <div class="student-badge">
                <i class="fas fa-user-graduate text-3xl text-white"></i>
            </div>
            
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">دخول الطلاب</h2>
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Login Method Selection -->
            <div class="mb-6" x-data="{ method: 'email' }">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="login-method" :class="{ 'active': method === 'email' }" @click="method = 'email'">
                        <div class="text-center">
                            <i class="fas fa-envelope text-2xl text-gray-600 mb-2"></i>
                            <p class="font-semibold">البريد الإلكتروني</p>
                        </div>
                    </div>
                    <div class="login-method" :class="{ 'active': method === 'pin' }" @click="method = 'pin'">
                        <div class="text-center">
                            <i class="fas fa-id-card text-2xl text-gray-600 mb-2"></i>
                            <p class="font-semibold">رمز المدرسة</p>
                        </div>
                    </div>
                </div>
                
                <!-- Email Login Form -->
                <form x-show="method === 'email'" method="POST" action="{{ route('student.login') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            البريد الإلكتروني
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            كلمة المرور
                        </label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    
                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600">
                            <span class="mr-2 text-sm text-gray-600">تذكرني</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-700">
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>
                    
                    <!-- Submit -->
                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white font-bold py-3 px-4 rounded-lg hover:from-green-700 hover:to-green-800 transition">
                        تسجيل الدخول
                    </button>
                </form>
                
                <!-- PIN Login Form -->
                <form x-show="method === 'pin'" method="POST" action="{{ route('student.pin-login') }}" style="display: none;">
                    @csrf
                    
                    <!-- School Code -->
                    <div class="mb-4">
                        <label for="school_code" class="block text-sm font-medium text-gray-700 mb-2">
                            رمز المدرسة
                        </label>
                        <input id="school_code" 
                               type="text" 
                               name="school_code" 
                               value="{{ old('school_code') }}" 
                               maxlength="6"
                               required
                               class="w-full px-4 py-3 text-center text-2xl font-mono uppercase rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('school_code')" class="mt-2" />
                    </div>
                    
                    <!-- Student ID -->
                    <div class="mb-6">
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم الطالب
                        </label>
                        <input id="student_id" 
                               type="text" 
                               name="student_id" 
                               value="{{ old('student_id') }}" 
                               required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                    </div>
                    
                    <!-- Submit -->
                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white font-bold py-3 px-4 rounded-lg hover:from-green-700 hover:to-green-800 transition">
                        تسجيل الدخول
                    </button>
                </form>
            </div>
            <!-- Social Login -->
<div class="my-6 flex items-center">
    <div class="flex-1 border-t border-gray-300"></div>
    <span class="px-4 text-sm text-gray-500">أو</span>
    <div class="flex-1 border-t border-gray-300"></div>
</div>

<div class="space-y-3">
    <a href="{{ route('social.login', 'google') }}" 
       class="flex items-center justify-center w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-300 transition">
        <svg class="w-5 h-5 ml-3" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        تسجيل الدخول بواسطة Google
    </a>
    
    
</div>
            <!-- Links -->
            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">
                    لست طالباً؟
                    <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold">
                        اختر نوع آخر
                    </a>
                </p>
                <p class="mt-2 text-gray-600">
                    ليس لديك حساب؟
                    <a href="{{ route('student.register') }}" class="text-green-600 hover:text-green-700 font-semibold">
                        سجل كطالب جديد
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection