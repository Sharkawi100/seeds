{{-- File: resources/views/admin/users/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">إضافة مستخدم جديد</h2>
                
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    
                    {{-- Basic Information --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">المعلومات الأساسية</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
                                <input type="text" name="name" value="{{ old('name') }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email') }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" 
                                       required>
                                @error('email')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    {{-- Password --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">كلمة المرور</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور</label>
                                <input type="password" name="password" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror"
                                       required>
                                @error('password')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Role and Status --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">الصلاحيات والحالة</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">نوع المستخدم</label>
                                <select name="user_type" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        id="user_type_select">
                                    <option value="student" {{ old('user_type') == 'student' ? 'selected' : '' }}>طالب</option>
                                    <option value="teacher" {{ old('user_type') == 'teacher' ? 'selected' : '' }}>معلم</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_admin" value="1" 
                                           class="form-checkbox h-5 w-5 text-indigo-600" 
                                           {{ old('is_admin') ? 'checked' : '' }}>
                                    <span class="mr-3 text-gray-700 font-bold">مدير النظام</span>
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" 
                                           class="form-checkbox h-5 w-5 text-green-600" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <span class="mr-3 text-gray-700 font-bold">حساب نشط</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Additional Fields --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">معلومات إضافية</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- School Name (for teachers) --}}
                            <div id="school_name_container" style="{{ old('user_type', 'student') === 'teacher' ? '' : 'display: none;' }}">
                                <label class="block text-gray-700 text-sm font-bold mb-2">اسم المدرسة</label>
                                <input type="text" name="school_name" value="{{ old('school_name') }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            
                            {{-- Grade Level (for students) --}}
                            <div id="grade_level_container" style="{{ old('user_type', 'student') === 'student' ? '' : 'display: none;' }}">
                                <label class="block text-gray-700 text-sm font-bold mb-2">المرحلة الدراسية</label>
                                <select name="grade_level" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">اختر المرحلة</option>
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>
                                            الصف {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-between">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            إضافة المستخدم
                        </button>
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-gray-600 hover:text-gray-900">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle additional fields based on user type
document.getElementById('user_type_select').addEventListener('change', function() {
    const schoolContainer = document.getElementById('school_name_container');
    const gradeContainer = document.getElementById('grade_level_container');
    
    if (this.value === 'teacher') {
        schoolContainer.style.display = 'block';
        gradeContainer.style.display = 'none';
    } else if (this.value === 'student') {
        schoolContainer.style.display = 'none';
        gradeContainer.style.display = 'block';
    }
});
</script>
@endpush
@endsection