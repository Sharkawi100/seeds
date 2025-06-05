@extends('layouts.guest')

@section('title', $action === 'login' ? 'اختر نوع الحساب - تسجيل الدخول' : 'اختر نوع الحساب - إنشاء حساب')

@push('styles')
<style>
    .role-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 2px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border-color: var(--hover-color);
    }
    
    .role-card.teacher:hover {
        --hover-color: #667eea;
    }
    
    .role-card.student:hover {
        --hover-color: #48bb78;
    }
    
    .icon-container {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .role-card:hover .icon-container {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                {{ $action === 'login' ? 'مرحباً بعودتك!' : 'انضم إلى جُذور' }}
            </h1>
            <p class="text-xl text-gray-600">
                {{ $action === 'login' ? 'اختر نوع حسابك لتسجيل الدخول' : 'اختر نوع الحساب الذي تريد إنشاءه' }}
            </p>
        </div>

        <!-- Role Selection Cards -->
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Teacher Card -->
            <a href="{{ route('teacher.' . $action) }}" class="block">
                <div class="role-card teacher rounded-2xl p-8 text-center">
                    <div class="icon-container bg-gradient-to-br from-purple-500 to-indigo-600">
                        <i class="fas fa-chalkboard-teacher text-4xl text-white"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">أنا معلم</h2>
                    
                    <ul class="text-right text-gray-600 space-y-2 mb-6">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            إنشاء اختبارات تفاعلية
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            متابعة تقدم الطلاب
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            تحليلات مفصلة
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            استخدام الذكاء الاصطناعي
                        </li>
                    </ul>
                    
                    <div class="inline-flex items-center text-purple-600 font-semibold">
                        <span>{{ $action === 'login' ? 'دخول المعلمين' : 'تسجيل كمعلم' }}</span>
                        <i class="fas fa-arrow-left mr-2"></i>
                    </div>
                </div>
            </a>

            <!-- Student Card -->
            <a href="{{ route('student.' . $action) }}" class="block">
                <div class="role-card student rounded-2xl p-8 text-center">
                    <div class="icon-container bg-gradient-to-br from-green-500 to-teal-600">
                        <i class="fas fa-user-graduate text-4xl text-white"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">أنا طالب</h2>
                    
                    <ul class="text-right text-gray-600 space-y-2 mb-6">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            الوصول للاختبارات
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            متابعة تقدمك الشخصي
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            شهادات الإنجاز
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 ml-2"></i>
                            تحديات ممتعة
                        </li>
                    </ul>
                    
                    <div class="inline-flex items-center text-green-600 font-semibold">
                        <span>{{ $action === 'login' ? 'دخول الطلاب' : 'تسجيل كطالب' }}</span>
                        <i class="fas fa-arrow-left mr-2"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Alternative Actions -->
        <div class="mt-12 text-center">
            <div class="text-gray-600">
                @if($action === 'login')
                    <span>ليس لديك حساب؟</span>
                    <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-700 font-semibold mr-2">
                        إنشاء حساب جديد
                    </a>
                @else
                    <span>لديك حساب بالفعل؟</span>
                    <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-semibold mr-2">
                        تسجيل الدخول
                    </a>
                @endif
            </div>
            
            <div class="mt-4">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للصفحة الرئيسية
                </a>
            </div>
        </div>
    </div>
</div>
@endsection