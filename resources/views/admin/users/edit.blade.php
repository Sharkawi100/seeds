{{-- File: resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">تعديل المستخدم</h2>
                
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    {{-- Basic Information --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">المعلومات الأساسية</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" 
                                       required>
                                @error('email')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    {{-- Password Change --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">تغيير كلمة المرور</h3>
                        <p class="text-sm text-gray-600 mb-4">اتركه فارغاً لعدم التغيير</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور الجديدة</label>
                                <input type="password" name="password" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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
                                    <option value="student" {{ old('user_type', $user->user_type) == 'student' ? 'selected' : '' }}>طالب</option>
                                    <option value="teacher" {{ old('user_type', $user->user_type) == 'teacher' ? 'selected' : '' }}>معلم</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_admin" value="1" 
                                           class="form-checkbox h-5 w-5 text-indigo-600" 
                                           {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <span class="mr-3 text-gray-700 font-bold">مدير النظام</span>
                                </label>
                                @if($user->id === auth()->id())
                                    <span class="text-xs text-gray-500 mr-2">(لا يمكن تغييرها)</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" 
                                           class="form-checkbox h-5 w-5 text-green-600" 
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <span class="mr-3 text-gray-700 font-bold">حساب نشط</span>
                                </label>
                                @if($user->id === auth()->id())
                                    <span class="text-xs text-gray-500 mr-2">(لا يمكن تغييرها)</span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Deactivation Reason (shown when deactivating) --}}
                        <div id="deactivation_reason_container" class="mt-4" style="display: none;">
                            <label class="block text-gray-700 text-sm font-bold mb-2">سبب التعطيل</label>
                            <textarea name="deactivation_reason" rows="3" 
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                      placeholder="اختياري: اكتب سبب تعطيل الحساب...">{{ old('deactivation_reason') }}</textarea>
                        </div>
                    </div>
                    
                    {{-- Additional Fields --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">معلومات إضافية</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- School Name (for teachers) --}}
                            <div id="school_name_container" style="{{ old('user_type', $user->user_type) === 'teacher' ? '' : 'display: none;' }}">
                                <label class="block text-gray-700 text-sm font-bold mb-2">اسم المدرسة</label>
                                <input type="text" name="school_name" value="{{ old('school_name', $user->school_name) }}" 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            
                            {{-- Grade Level (for students) --}}
                            <div id="grade_level_container" style="{{ old('user_type', $user->user_type) === 'student' ? '' : 'display: none;' }}">
                                <label class="block text-gray-700 text-sm font-bold mb-2">المرحلة الدراسية</label>
                                <select name="grade_level" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">اختر المرحلة</option>
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ old('grade_level', $user->grade_level) == $i ? 'selected' : '' }}>
                                            الصف {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Social Login Management --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">حسابات التواصل الاجتماعي</h3>
                        
                        <div class="space-y-3">
                            {{-- Google Account --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div class="flex items-center gap-3">
                                    <i class="fab fa-google text-2xl text-red-600"></i>
                                    <div>
                                        <p class="font-medium">Google</p>
                                        @if($user->google_id)
                                            <p class="text-xs text-gray-500">ID: {{ substr($user->google_id, 0, 10) }}...</p>
                                        @endif
                                    </div>
                                </div>
                                @if($user->google_id)
                                    <button type="button" 
                                            class="bg-red-500 hover:bg-red-700 text-white text-sm px-3 py-1 rounded"
                                            onclick="disconnectSocial('google', {{ $user->id }})">
                                        <i class="fas fa-unlink ml-1"></i>
                                        فصل الحساب
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm">غير متصل</span>
                                @endif
                            </div>
                            
                            {{-- Facebook Account --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div class="flex items-center gap-3">
                                    <i class="fab fa-facebook text-2xl text-blue-600"></i>
                                    <div>
                                        <p class="font-medium">Facebook</p>
                                        @if($user->facebook_id)
                                            <p class="text-xs text-gray-500">ID: {{ substr($user->facebook_id, 0, 10) }}...</p>
                                        @endif
                                    </div>
                                </div>
                                @if($user->facebook_id)
                                    <button type="button" 
                                            class="bg-red-500 hover:bg-red-700 text-white text-sm px-3 py-1 rounded"
                                            onclick="disconnectSocial('facebook', {{ $user->id }})">
                                        <i class="fas fa-unlink ml-1"></i>
                                        فصل الحساب
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm">غير متصل</span>
                                @endif
                            </div>
                            
                            {{-- Auth Provider Info --}}
                            <div class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-info-circle ml-1"></i>
                                طريقة التسجيل الأساسية: 
                                <span class="font-medium">
                                    {{ $user->auth_provider == 'google' ? 'Google' : ($user->auth_provider == 'facebook' ? 'Facebook' : 'البريد الإلكتروني') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Current Status Info --}}
                    @if(!$user->is_active)
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded">
                            <h4 class="text-red-800 font-bold mb-2">الحساب معطل حالياً</h4>
                            @if($user->deactivated_at)
                                <p class="text-sm text-red-700">
                                    معطل منذ: {{ $user->deactivated_at->format('Y/m/d H:i') }}
                                    @if($user->deactivatedBy)
                                        بواسطة {{ $user->deactivatedBy->name }}
                                    @endif
                                </p>
                            @endif
                            @if($user->deactivation_reason)
                                <p class="text-sm text-red-700 mt-1">السبب: {{ $user->deactivation_reason }}</p>
                            @endif
                        </div>
                    @endif
                    
                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-between">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            حفظ التغييرات
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

// Show deactivation reason when unchecking active status
document.querySelector('input[name="is_active"]').addEventListener('change', function() {
    const reasonContainer = document.getElementById('deactivation_reason_container');
    const currentlyActive = {{ $user->is_active ? 'true' : 'false' }};
    
    if (!this.checked && currentlyActive) {
        reasonContainer.style.display = 'block';
    } else {
        reasonContainer.style.display = 'none';
    }
});

// Disconnect social account
async function disconnectSocial(provider, userId) {
    if (!confirm(`هل أنت متأكد من فصل حساب ${provider}؟`)) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/users/${userId}/disconnect-social`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ provider: provider })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'حدث خطأ');
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
    }
}
</script>
@endpush
@endsection