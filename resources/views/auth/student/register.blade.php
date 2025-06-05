@extends('layouts.guest')

@section('title', 'تسجيل طالب جديد')

@push('styles')
<style>
    .student-register-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }
    
    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="student-register-container flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="form-container rounded-2xl p-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">تسجيل طالب جديد</h2>
            
            <form method="POST" action="{{ route('student.register') }}">
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
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                
                <!-- Grade Level -->
                <div class="mb-4">
                    <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                        المرحلة الدراسية
                    </label>
                    <select id="grade_level" 
                            name="grade_level" 
                            required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">اختر المرحلة</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>
                                الصف {{ $i }}
                            </option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->get('grade_level')" class="mt-2" />
                </div>
                
                <!-- Parent Email (Optional) -->
                <div class="mb-4">
                    <label for="parent_email" class="block text-sm font-medium text-gray-700 mb-2">
                        بريد ولي الأمر <span class="text-gray-500">(اختياري)</span>
                    </label>
                    <input id="parent_email" 
                           type="email" 
                           name="parent_email" 
                           value="{{ old('parent_email') }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('parent_email')" class="mt-2" />
                </div>
                
                <!-- Student School ID (Optional) -->
                <div class="mb-4">
                    <label for="student_school_id" class="block text-sm font-medium text-gray-700 mb-2">
                        رقم الطالب في المدرسة <span class="text-gray-500">(اختياري)</span>
                    </label>
                    <input id="student_school_id" 
                           type="text" 
                           name="student_school_id" 
                           value="{{ old('student_school_id') }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <x-input-error :messages="$errors->get('student_school_id')" class="mt-2" />
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
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                
                <!-- Submit -->
                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white font-bold py-3 px-4 rounded-lg hover:from-green-700 hover:to-green-800 transition">
                    إنشاء حساب طالب
                </button>
            </form>
            
            <!-- Links -->
            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">
                    لديك حساب بالفعل؟
                    <a href="{{ route('student.login') }}" class="text-green-600 hover:text-green-700 font-semibold">
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