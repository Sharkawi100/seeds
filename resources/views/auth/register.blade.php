@extends('layouts.guest')

@section('title', 'إنشاء حساب جديد')

@push('styles')
<style>
    .register-container {
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
    
    .role-option {
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        background: white;
    }
    
    .role-option:hover {
        border-color: #8b5cf6;
        background: #f3f4f6;
    }
    
    .role-option.selected {
        border-color: #8b5cf6;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }
    
    .role-option i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
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
<div class="register-container">
    <!-- Animated Background Shapes -->
    <div class="animated-shape shape-1"></div>
    <div class="animated-shape shape-2"></div>
    <div class="animated-shape shape-3"></div>
    
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-white mb-2">🌱 جُذور</h1>
                <p class="text-white/80 text-lg">انضم إلى رحلة التعلم</p>
            </div>
            
            <!-- Registration Form Container -->
            <div class="glass-effect rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">إنشاء حساب جديد</h2>
                
                <form method="POST" action="{{ route('register') }}" x-data="registrationForm()">
                    @csrf
                    
                    <!-- User Type Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-user-tag ml-1"></i>
                            نوع الحساب
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="role-option" 
                                 :class="{ 'selected': formData.user_type === 'student' }"
                                 @click="formData.user_type = 'student'">
                                <i class="fas fa-user-graduate"></i>
                                <span class="font-semibold">طالب</span>
                            </div>
                            <div class="role-option" 
                                 :class="{ 'selected': formData.user_type === 'teacher' }"
                                 @click="formData.user_type = 'teacher'">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span class="font-semibold">معلم</span>
                            </div>
                        </div>
                        <input type="hidden" name="user_type" x-model="formData.user_type">
                    </div>
                    
                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user ml-1"></i>
                            الاسم الكامل
                        </label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus
                               x-model="formData.name"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="أدخل اسمك الكامل">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    
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
                               x-model="formData.email"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="example@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-4" x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock ml-1"></i>
                            كلمة المرور
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   :type="showPassword ? 'text' : 'password'"
                                   name="password" 
                                   required
                                   x-model="formData.password"
                                   class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="أدخل كلمة مرور قوية">
                            <button type="button" 
                                    @click="showPassword = !showPassword"
                                    class="password-toggle">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    
                    <!-- Password Confirmation -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-check-double ml-1"></i>
                            تأكيد كلمة المرور
                        </label>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required
                               x-model="formData.password_confirmation"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="أعد إدخال كلمة المرور">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    
                    <!-- Teacher-specific fields -->
                    <div x-show="formData.user_type === 'teacher'" x-transition class="mb-4">
                        <label for="school_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-school ml-1"></i>
                            اسم المدرسة
                        </label>
                        <input id="school_name" 
                               type="text" 
                               name="school_name"
                               value="{{ old('school_name') }}"
                               x-model="formData.school_name"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="أدخل اسم مدرستك">
                        <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                    </div>
                    
                    <!-- Student-specific fields -->
                    <div x-show="formData.user_type === 'student'" x-transition class="mb-4">
                        <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-graduation-cap ml-1"></i>
                            المرحلة الدراسية
                        </label>
                        <select id="grade_level" 
                                name="grade_level"
                                x-model="formData.grade_level"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <option value="">اختر المرحلة</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>الصف {{ $i }}</option>
                            @endfor
                        </select>
                        <x-input-error :messages="$errors->get('grade_level')" class="mt-2" />
                    </div>
                    
                    <!-- Terms & Conditions -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" 
                                   name="terms" 
                                   required
                                   class="mt-1 rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500">
                            <span class="mr-2 text-sm text-gray-600">
                                أوافق على 
                                <a href="#" class="text-purple-600 hover:text-purple-700">الشروط والأحكام</a>
                                و
                                <a href="#" class="text-purple-600 hover:text-purple-700">سياسة الخصوصية</a>
                            </span>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition duration-200">
                        <i class="fas fa-user-plus ml-2"></i>
                        إنشاء الحساب
                    </button>
                </form>
                
                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        لديك حساب بالفعل؟ 
                        <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                            سجل دخولك هنا
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function registrationForm() {
    return {
        formData: {
            user_type: 'student',
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            school_name: '',
            grade_level: ''
        }
    }
}
</script>
@endpush
@endsection