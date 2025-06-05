@extends('layouts.guest')

@section('title', 'تسجيل معلم جديد')

@push('styles')
<style>
    .teacher-register-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .approval-notice {
        background: #fef3c7;
        border: 1px solid #f59e0b;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="teacher-register-container flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="form-container rounded-2xl p-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">تسجيل معلم جديد</h2>
            
            <!-- Approval Notice -->
            <div class="approval-notice">
                <p class="text-sm text-amber-800">
                    <i class="fas fa-info-circle ml-2"></i>
                    يتطلب حساب المعلم موافقة الإدارة قبل إمكانية إنشاء الاختبارات
                </p>
            </div>
            
            <form method="POST" action="{{ route('teacher.register') }}">
                @csrf
                
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        الاسم الكامل
                    </label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                
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
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                
                <!-- School Name -->
                <div class="mb-4">
                    <label for="school_name" class="block text-sm font-medium text-gray-700 mb-2">
                        اسم المدرسة
                    </label>
                    <input id="school_name" 
                           type="text" 
                           name="school_name" 
                           value="{{ old('school_name') }}" 
                           required
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                </div>
                
                <!-- Subjects Taught -->
                <div class="mb-4">
                    <label for="subjects_taught" class="block text-sm font-medium text-gray-700 mb-2">
                        المواد التي تدرسها
                    </label>
                    <input id="subjects_taught" 
                           type="text" 
                           name="subjects_taught" 
                           value="{{ old('subjects_taught') }}" 
                           placeholder="مثال: اللغة العربية، الرياضيات"
                           required
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('subjects_taught')" class="mt-2" />
                </div>
                
                <!-- Experience Years -->
                <div class="mb-4">
                    <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">
                        سنوات الخبرة
                    </label>
                    <input id="experience_years" 
                           type="number" 
                           name="experience_years" 
                           value="{{ old('experience_years') }}" 
                           min="0" 
                           max="50"
                           required
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('experience_years')" class="mt-2" />
                </div>
                
                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        كلمة المرور
                    </label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        تأكيد كلمة المرور
                    </label>
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <!-- Submit -->
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition">
                    إنشاء حساب معلم
                </button>
            </form>
            
            <!-- Links -->
            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">
                    لديك حساب بالفعل؟
                    <a href="{{ route('teacher.login') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
                        تسجيل الدخول
                    </a>
                </p>
                <p class="mt-2 text-gray-600">
                    <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700">
                        العودة لاختيار النوع
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection