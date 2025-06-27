@extends('layouts.app')

@section('title', 'اكتمال الملف الشخصي')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">اكتمال الملف الشخصي</h2>
            
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">نسبة الاكتمال</span>
                    <span class="text-sm font-bold text-gray-900">{{ $completion_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" 
                         style="width: {{ $completion_percentage }}%"></div>
                </div>
            </div>

            @if($completion_percentage < 100)
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                    <h3 class="text-sm font-medium text-yellow-800 mb-2">لإكمال ملفك الشخصي:</h3>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        @if(empty($user->avatar))
                            <li>• إضافة صورة شخصية</li>
                        @endif
                        @if(empty($user->bio))
                            <li>• كتابة نبذة شخصية</li>
                        @endif
                        @if(empty($user->phone))
                            <li>• إضافة رقم الهاتف</li>
                        @endif
                        @if($user->user_type === 'teacher' && empty($user->school_name))
                            <li>• إضافة اسم المدرسة</li>
                        @endif
                        @if($user->user_type === 'student' && empty($user->grade_level))
                            <li>• تحديد المرحلة الدراسية</li>
                        @endif
                    </ul>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                    <p class="text-sm text-green-800">🎉 تهانينا! ملفك الشخصي مكتمل بنسبة 100%</p>
                </div>
            @endif

            <a href="{{ route('profile.edit') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                تعديل الملف الشخصي
            </a>
        </div>
    </div>
</div>
@endsection