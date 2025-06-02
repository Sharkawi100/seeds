@extends('layouts.guest')

@section('title', 'إنشاء حساب جديد')

@push('styles')
<style>
    /* Reuse login styles */
    .register-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }
    
    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .step.active {
        background: white;
        color: #667eea;
        transform: scale(1.1);
    }
    
    .step.completed {
        background: #10b981;
    }
    
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -60px;
        width: 60px;
        height: 2px;
        background: rgba(255, 255, 255, 0.3);
    }
    
    .form-step {
        display: none;
    }
    
    .form-step.active {
        display: block;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .school-input {
        transition: all 0.3s ease;
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
            <div class="glass-effect rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">إنشاء حساب جديد</h2>
                
                <!-- Social Registration -->
                <div class="space-y-3 mb-6">
                    <a href="{{ route('social.login', 'google') }}" 
                       class="flex items-center justify-center w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-300 transition">
                        <svg class="w-5 h-5 ml-3" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        التسجيل بواسطة Google
                    </a>
                    
                    <a href="{{ route('social.login', 'facebook') }}" 
                       class="flex items-center justify-center w-full bg-[#1877F2] hover:bg-[#166FE5] text-white font-medium py-3 px-4 rounded-lg transition">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        التسجيل بواسطة Facebook
                    </a>
                </div>
                
                <!-- Divider -->
                <div class="flex items-center mb-6">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-sm text-gray-500">أو التسجيل بالبريد الإلكتروني</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>
            <!-- Registration Form Container -->
            <div class="glass-effect rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">إنشاء حساب جديد</h2>
                
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" id="step-1">1</div>
                    <div class="step" id="step-2">2</div>
                    <div class="step" id="step-3">3</div>
                </div>
                
                <form method="POST" action="{{ route('register') }}" x-data="registrationForm()">
                    @csrf
                    
                    <!-- Step 1: Basic Info -->
                    <div class="form-step active" id="form-step-1">
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
                        
                        <button type="button" 
                                @click="nextStep()"
                                class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition duration-200">
                            التالي
                            <i class="fas fa-arrow-left mr-2"></i>
                        </button>
                    </div>
                    
                    <!-- Step 2: Password -->
                    <div class="form-step" id="form-step-2">
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
                                       @input="checkPasswordStrength()"
                                       class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       placeholder="أدخل كلمة مرور قوية">
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        class="password-toggle">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="flex gap-1 mb-1">
                                    <div class="h-2 flex-1 rounded" :class="passwordStrength >= 1 ? 'bg-red-500' : 'bg-gray-300'"></div>
                                    <div class="h-2 flex-1 rounded" :class="passwordStrength >= 2 ? 'bg-yellow-500' : 'bg-gray-300'"></div>
                                    <div class="h-2 flex-1 rounded" :class="passwordStrength >= 3 ? 'bg-green-500' : 'bg-gray-300'"></div>
                                </div>
                                <p class="text-xs text-gray-600" x-text="passwordMessage"></p>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <div class="mb-6">
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
                        
                        <div class="flex gap-3">
                            <button type="button" 
                                    @click="previousStep()"
                                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-4 rounded-lg transition">
                                <i class="fas fa-arrow-right ml-2"></i>
                                السابق
                            </button>
                            <button type="button" 
                                    @click="nextStep()"
                                    class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition">
                                التالي
                                <i class="fas fa-arrow-left mr-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Role & Additional Info -->
                    <div class="form-step" id="form-step-3">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-tag ml-1"></i>
                                نوع الحساب
                            </label>
                            <div class="role-selector">
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
                        
                        <!-- Teacher-specific fields -->
                        <div x-show="formData.user_type === 'teacher'" x-transition>
                            <div class="mb-4">
                                <label for="school_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-school ml-1"></i>
                                    اسم المدرسة
                                </label>
                                <input id="school_name" 
                                       type="text" 
                                       name="school_name"
                                       x-model="formData.school_name"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       placeholder="أدخل اسم مدرستك">
                            </div>
                        </div>
                        
                        <!-- Student-specific fields -->
                        <div x-show="formData.user_type === 'student'" x-transition>
                            <div class="mb-4">
                                <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-graduation-cap ml-1"></i>
                                    المرحلة الدراسية
                                </label>
                                <select id="grade_level" 
                                        name="grade_level"
                                        x-model="formData.grade_level"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option value="">اختر المرحلة</option>
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">الصف {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        
                        <!-- Terms & Conditions -->
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input type="checkbox" 
                                       name="terms" 
                                       x-model="formData.terms"
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
                        
                        <div class="flex gap-3">
                            <button type="button" 
                                    @click="previousStep()"
                                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-4 rounded-lg transition">
                                <i class="fas fa-arrow-right ml-2"></i>
                                السابق
                            </button>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold py-3 px-4 rounded-lg hover:from-green-700 hover:to-green-800 transition">
                                <i class="fas fa-check ml-2"></i>
                                إنشاء الحساب
                            </button>
                        </div>
                    </div>
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
        currentStep: 1,
        passwordStrength: 0,
        passwordMessage: 'أدخل كلمة مرور قوية',
        formData: {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            user_type: 'student',
            school_name: '',
            grade_level: '',
            terms: false
        },
        
        nextStep() {
            if (this.validateStep()) {
                if (this.currentStep < 3) {
                    this.currentStep++;
                    this.updateStepIndicator();
                }
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                this.updateStepIndicator();
            }
        },
        
        validateStep() {
            switch(this.currentStep) {
                case 1:
                    return this.formData.name && this.formData.email;
                case 2:
                    return this.formData.password && this.formData.password === this.formData.password_confirmation;
                case 3:
                    return this.formData.terms;
            }
            return true;
        },
        
        updateStepIndicator() {
            // Update steps
            for (let i = 1; i <= 3; i++) {
                const step = document.getElementById(`step-${i}`);
                const formStep = document.getElementById(`form-step-${i}`);
                
                step.classList.remove('active', 'completed');
                formStep.classList.remove('active');
                
                if (i < this.currentStep) {
                    step.classList.add('completed');
                } else if (i === this.currentStep) {
                    step.classList.add('active');
                    formStep.classList.add('active');
                }
            }
        },
        
        checkPasswordStrength() {
            const password = this.formData.password;
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) strength++;
            
            this.passwordStrength = strength;
            
            const messages = [
                'كلمة المرور ضعيفة',
                'كلمة المرور مقبولة',
                'كلمة المرور جيدة',
                'كلمة المرور قوية'
            ];
            
            this.passwordMessage = messages[strength];
        }
    }
}
</script>
@endpush
@endsection