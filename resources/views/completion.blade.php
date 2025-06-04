@extends('layouts.app')

@section('title', 'إكمال الملف الشخصي')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg shadow-xl p-8 mb-8">
            <h1 class="text-3xl font-bold text-white mb-4">
                <i class="fas fa-tasks ml-2"></i>
                أكمل ملفك الشخصي
            </h1>
            <div class="bg-white/20 backdrop-blur rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-white text-lg">نسبة الإكمال</span>
                    <span class="text-white text-2xl font-bold">{{ $completion ?? 0 }}%</span>
                </div>
                <div class="w-full bg-white/30 rounded-full h-4">
                    <div class="bg-white rounded-full h-4 transition-all duration-500" 
                         style="width: {{ $completion ?? 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Incomplete Fields -->
        @if(count($incompleteFields ?? []) > 0)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">
                <i class="fas fa-exclamation-circle text-yellow-500 ml-2"></i>
                الحقول المطلوبة لإكمال ملفك
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($incompleteFields as $field)
                <div class="flex items-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <i class="fas fa-circle text-yellow-400 text-xs ml-3"></i>
                    <span class="text-gray-700">{{ $field }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Benefits of Completing Profile -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">
                <i class="fas fa-gift text-purple-600 ml-2"></i>
                فوائد إكمال ملفك الشخصي
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <div class="bg-purple-100 rounded-full p-3 ml-4">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">تتبع أفضل للتقدم</h3>
                        <p class="text-gray-600 text-sm">احصل على تحليلات مفصلة لأدائك في الجذور الأربعة</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-green-100 rounded-full p-3 ml-4">
                        <i class="fas fa-trophy text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">الإنجازات والشارات</h3>
                        <p class="text-gray-600 text-sm">اكسب شارات خاصة عند إكمال ملفك الشخصي</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-blue-100 rounded-full p-3 ml-4">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">التواصل مع المعلمين</h3>
                        <p class="text-gray-600 text-sm">سهّل على معلميك متابعة تقدمك</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-red-100 rounded-full p-3 ml-4">
                        <i class="fas fa-certificate text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">شهادات مخصصة</h3>
                        <p class="text-gray-600 text-sm">احصل على شهادات باسمك الكامل وصورتك</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">
                <i class="fas fa-rocket text-indigo-600 ml-2"></i>
                إجراءات سريعة
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                    <div class="flex items-center">
                        <i class="fas fa-edit text-indigo-600 text-xl ml-3"></i>
                        <div>
                            <p class="font-semibold text-gray-800">تحديث المعلومات الأساسية</p>
                            <p class="text-sm text-gray-600">الاسم، البريد الإلكتروني، الهاتف</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-left text-gray-400"></i>
                </a>
                
                <a href="{{ route('profile.dashboard') }}#avatar" 
                   class="flex items-center justify-between p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    <div class="flex items-center">
                        <i class="fas fa-camera text-purple-600 text-xl ml-3"></i>
                        <div>
                            <p class="font-semibold text-gray-800">إضافة صورة شخصية</p>
                            <p class="text-sm text-gray-600">اجعل ملفك أكثر احترافية</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-left text-gray-400"></i>
                </a>
            </div>
        </div>
        
        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('profile.dashboard') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة إلى الملف الشخصي
            </a>
        </div>
    </div>
</div>
@endsection