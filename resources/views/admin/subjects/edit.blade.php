@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <nav class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                <a href="{{ route('admin.subjects.index') }}" class="hover:text-gray-700">إدارة المواد</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900">تعديل المادة الدراسية</span>
            </nav>
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 rounded-lg ml-4">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">تعديل المادة الدراسية</h1>
                    <p class="text-gray-600">تعديل معلومات المادة: <span class="font-semibold">{{ $subject->name }}</span></p>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">معلومات المادة الدراسية</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subject->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <svg class="w-1.5 h-1.5 ml-1.5" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3"/>
                            </svg>
                            {{ $subject->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Subject Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-900">
                            اسم المادة
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $subject->name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 ring-2 ring-red-200 @enderror" 
                                   placeholder="مثال: الرياضيات، العلوم، اللغة العربية"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>
                        @error('name')
                            <div class="flex items-center space-x-2 text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div class="space-y-2">
                        <label for="sort_order" class="block text-sm font-semibold text-gray-900">
                            ترتيب العرض
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', $subject->sort_order) }}" 
                                   min="0"
                                   max="999"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                   placeholder="0">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            الرقم الأصغر يظهر أولاً في القائمة
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.subjects.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            تحديث المادة
                        </button>
                    </div>
                </form>
            </div>

            <!-- Subject Info -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Subject Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">إحصائيات المادة</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">عدد الاختبارات:</span>
                            <span class="font-semibold text-gray-900">{{ $subject->quizzes()->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">تاريخ الإنشاء:</span>
                            <span class="font-semibold text-gray-900">{{ $subject->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">آخر تحديث:</span>
                            <span class="font-semibold text-gray-900">{{ $subject->updated_at->format('Y/m/d') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h4>
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('admin.subjects.toggle-status', $subject) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-yellow-300 rounded-lg text-yellow-700 bg-yellow-50 hover:bg-yellow-100 transition-colors duration-200">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                                {{ $subject->is_active ? 'إلغاء تفعيل المادة' : 'تفعيل المادة' }}
                            </button>
                        </form>

                        @if($subject->quizzes()->count() == 0)
                            <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    حذف المادة نهائياً
                                </button>
                            </form>
                        @else
                            <div class="flex items-center justify-center px-4 py-2 border border-gray-200 rounded-lg text-gray-500 bg-gray-50">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.768 0L3.046 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                لا يمكن حذف المادة (توجد اختبارات مرتبطة)
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection