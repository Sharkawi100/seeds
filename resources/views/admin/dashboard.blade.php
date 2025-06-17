@extends('layouts.app')

@section('title', 'لوحة تحكم المدير')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap');
    
    /* Force English numerals in all elements */
    .metric-card, .animate-counter, .chart-text {
        font-variant-numeric: tabular-nums;
        direction: ltr;
        unicode-bidi: embed;
    }
    
    /* Ensure numbers display as English numerals */
    .counter-number {
        font-family: 'Arial', monospace;
        direction: ltr;
        display: inline-block;
    }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .metric-card {
            transition: all 0.3s ease;
        }
        
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .root-jawhar { background: linear-gradient(135deg, #ff6b6b, #ff8e8e); }
        .root-zihn { background: linear-gradient(135deg, #4ecdc4, #6ee7de); }
        .root-waslat { background: linear-gradient(135deg, #f7b731, #faca5f); }
        .root-roaya { background: linear-gradient(135deg, #5f27cd, #7c3aed); }
        
        .animate-counter {
            animation: countUp 2s ease-out;
        }
        
        @keyframes countUp {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        .pulse-ring {
            animation: pulse-ring 2s infinite;
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.33); }
            80%, 100% { opacity: 0; }
        }
        
        .activity-item {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
@endpush

@section('content')
{{-- Teacher Perspective Option --}}
<div class="mb-6">
    <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-4 flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-2 ml-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">تجربة منظور المعلم</h3>
                <p class="text-gray-600 text-sm">اختبر كيف يرى المعلمون المنصة وتفاعل مع الأدوات التعليمية</p>
            </div>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('switch.teacher') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    عرض كمعلم
                </button>
            </form>
        </div>
    </div>
</div>
@php
    // Helper function to format numbers with English numerals
    function formatNumber($number) {
        // Convert any Arabic-Hindi numerals to English numerals
        $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        $formatted = number_format($number, 0, '.', ',');
        $formatted = str_replace($persianDigits, $englishDigits, $formatted);
        $formatted = str_replace($arabicDigits, $englishDigits, $formatted);
        
        return $formatted;
    }
    
    // Helper function to format percentages with English numerals
    function formatPercentage($number) {
        $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        $formatted = $number . '%';
        $formatted = str_replace($persianDigits, $englishDigits, $formatted);
        $formatted = str_replace($arabicDigits, $englishDigits, $formatted);
        
        return $formatted;
    }
@endphp

<!-- Header -->
    <header class="gradient-bg shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-4xl">🌱</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-white">لوحة تحكم المدير</h1>
                        <p class="text-white/80">منصة جُذور التعليمية</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-home ml-2"></i>الرئيسية
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-users ml-2"></i>المستخدمين
                        </a>
                        <a href="{{ route('admin.quizzes.index') }}" class="text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-clipboard-list ml-2"></i>الاختبارات
                        </a>
                        <a href="{{ route('admin.reports') }}" class="text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-chart-bar ml-2"></i>التقارير
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-colors">
                            <i class="fas fa-bell text-white"></i>
                            @if($user_breakdown['pending_approval'] > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-xs text-white flex items-center justify-center counter-number">
                                {{ $user_breakdown['pending_approval'] }}
                            </span>
                            @endif
                        </button>
                    </div>
                    
                    <!-- User Info -->
                    <div class="text-left text-white">
                        <p class="text-sm opacity-80">مرحباً بك</p>
                        <p class="font-bold">{{ Auth::user()->name }}</p>
                    </div>
                    
                    <!-- User Avatar with Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-colors">
                            <i class="fas fa-user text-white text-xl"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" 
                             class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-1 scale-100">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-edit ml-2"></i>تعديل الملف الشخصي
                            </a>
                            <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog ml-2"></i>الإعدادات
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-right px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt ml-2"></i>تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Notifications/Alerts Bar -->
        @if($user_breakdown['pending_approval'] > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    <div>
                        <p class="font-bold text-yellow-800">يوجد <span class="counter-number">{{ formatNumber($user_breakdown['pending_approval']) }}</span> معلم في انتظار الموافقة</p>
                        <p class="text-yellow-700 text-sm">راجع طلبات الانضمام واتخذ الإجراء المناسب</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}?status=pending" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    مراجعة الطلبات
                </a>
            </div>
        </div>
        @endif

        @if($system_health['uptime'] < 99)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                    <div>
                        <p class="font-bold text-red-800">انتباه: انخفاض في أداء النظام</p>
                        <p class="text-red-700 text-sm">معدل التشغيل: {{ $system_health['uptime'] }}%</p>
                    </div>
                </div>
                <a href="{{ route('admin.settings') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    فحص النظام
                </a>
            </div>
        </div>
        @endif
        <!-- Key Metrics Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <a href="{{ route('admin.users.index') }}" class="metric-card glass-card rounded-2xl p-6 border-r-4 border-blue-500 hover:shadow-lg transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">إجمالي المستخدمين</p>
                        <p class="text-3xl font-black text-gray-900 animate-counter counter-number">{{ formatNumber($metrics['total_users']) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-{{ $growth_rates['users'] >= 0 ? 'green' : 'red' }}-600 text-sm font-bold">
                                {{ $growth_rates['users'] >= 0 ? '+' : '' }}{{ $growth_rates['users'] }}%
                            </span>
                            <span class="text-gray-500 text-xs">هذا الشهر</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center relative">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                        @if($user_breakdown['total_active'] > 0)
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full pulse-ring"></div>
                        @endif
                    </div>
                </div>
            </a>

            <!-- Total Quizzes -->
            <a href="{{ route('admin.quizzes.index') }}" class="metric-card glass-card rounded-2xl p-6 border-r-4 border-purple-500 hover:shadow-lg transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">إجمالي الاختبارات</p>
                        <p class="text-3xl font-black text-gray-900 animate-counter counter-number">{{ formatNumber($metrics['total_quizzes']) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-{{ $growth_rates['quizzes'] >= 0 ? 'green' : 'red' }}-600 text-sm font-bold">
                                {{ $growth_rates['quizzes'] >= 0 ? '+' : '' }}{{ $growth_rates['quizzes'] }}%
                            </span>
                            <span class="text-gray-500 text-xs">هذا الأسبوع</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </a>

            <!-- Total Results -->
            <a href="{{ route('admin.reports') }}" class="metric-card glass-card rounded-2xl p-6 border-r-4 border-green-500 hover:shadow-lg transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">إجمالي النتائج</p>
                        <p class="text-3xl font-black text-gray-900 animate-counter counter-number">{{ formatNumber($metrics['total_results']) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-{{ $growth_rates['results'] >= 0 ? 'green' : 'red' }}-600 text-sm font-bold">
                                {{ $growth_rates['results'] >= 0 ? '+' : '' }}{{ $growth_rates['results'] }}%
                            </span>
                            <span class="text-gray-500 text-xs">هذا الشهر</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                    </div>
                </div>
            </a>

            <!-- Demo vs Real Usage -->
            <a href="{{ route('admin.reports') }}?filter=demo" class="metric-card glass-card rounded-2xl p-6 border-r-4 border-orange-500 hover:shadow-lg transition-all cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">تجريبي / حقيقي</p>
                        <p class="text-3xl font-black text-gray-900 animate-counter counter-number">{{ formatNumber($demo_stats['total_attempts']) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-gray-600 text-sm counter-number">{{ formatPercentage($demo_stats['real_percentage']) }} حقيقي</span>
                            <span class="text-orange-600 text-sm counter-number">{{ formatPercentage($demo_stats['demo_percentage']) }} تجريبي</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-flask text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Roots Performance Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Roots Distribution Chart -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">أداء الجذور الأربعة</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.reports') }}?period=week" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg text-sm font-medium hover:bg-purple-200 transition-colors">الأسبوع</a>
                        <a href="{{ route('admin.reports') }}?period=month" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">الشهر</a>
                    </div>
                </div>
                <div class="relative h-64">
                    <canvas id="rootsChart"></canvas>
                </div>
                
                <!-- Root Legend -->
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="flex items-center gap-3 p-3 root-jawhar rounded-xl text-white">
                        <span class="text-2xl">🎯</span>
                        <div>
                            <p class="font-bold">جَوهر</p>
                            <p class="text-sm opacity-90">متوسط الأداء: <span class="counter-number">{{ formatPercentage($roots_performance['jawhar']['average']) }}</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 root-zihn rounded-xl text-white">
                        <span class="text-2xl">🧠</span>
                        <div>
                            <p class="font-bold">ذِهن</p>
                            <p class="text-sm opacity-90">متوسط الأداء: <span class="counter-number">{{ formatPercentage($roots_performance['zihn']['average']) }}</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 root-waslat rounded-xl text-white">
                        <span class="text-2xl">🔗</span>
                        <div>
                            <p class="font-bold">وَصلات</p>
                            <p class="text-sm opacity-90">متوسط الأداء: <span class="counter-number">{{ formatPercentage($roots_performance['waslat']['average']) }}</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 root-roaya rounded-xl text-white">
                        <span class="text-2xl">👁️</span>
                        <div>
                            <p class="font-bold">رُؤية</p>
                            <p class="text-sm opacity-90">متوسط الأداء: <span class="counter-number">{{ formatPercentage($roots_performance['roaya']['average']) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Trends -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">اتجاهات الاستخدام</h3>
                    <a href="{{ route('admin.reports') }}" class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-green-200 transition-colors">
                        <i class="fas fa-arrow-up ml-1"></i>
                        عرض التفاصيل
                    </a>
                </div>
                <div class="relative h-64">
                    <canvas id="usageChart"></canvas>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="text-center p-3 bg-blue-50 rounded-xl">
                        <p class="text-2xl font-bold text-blue-600">{{ $usage_trends['total_week'] }}</p>
                        <p class="text-sm text-gray-600">اختبارات الأسبوع</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-xl">
                        <p class="text-2xl font-bold text-green-600">{{ $quiz_performance['completion_rate'] }}%</p>
                        <p class="text-sm text-gray-600">معدل الإكمال</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-xl">
                        <p class="text-2xl font-bold text-purple-600">{{ $system_health['uptime'] }}</p>
                        <p class="text-sm text-gray-600">معدل التشغيل</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- User Management Summary -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users-cog text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">إدارة المستخدمين</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <span class="text-gray-700">معلمين نشطين</span>
                        </div>
                        <span class="font-bold text-gray-900 counter-number">{{ formatNumber($user_breakdown['active_teachers']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <span class="text-gray-700">طلاب مسجلين</span>
                        </div>
                        <span class="font-bold text-gray-900 counter-number">{{ formatNumber($user_breakdown['registered_students']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                            <span class="text-gray-700">في انتظار الموافقة</span>
                        </div>
                        <span class="font-bold text-gray-900 counter-number">{{ formatNumber($user_breakdown['pending_approval']) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                            <span class="text-gray-700">مدراء</span>
                        </div>
                        <span class="font-bold text-gray-900 counter-number">{{ formatNumber($user_breakdown['admins']) }}</span>
                    </div>
                </div>
                
                <button class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors"
                        onclick="window.location.href='{{ route('admin.users.index') }}'">
                    إدارة المستخدمين
                </button>
            </div>

            <!-- Quiz Performance -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">أداء الاختبارات</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="p-4 border-r-4 border-green-500 bg-green-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">معدل النجاح العام</span>
                            <span class="text-2xl font-bold text-green-600">{{ $quiz_performance['success_rate'] }}%</span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">متوسط الوقت</span>
                            <span class="font-bold">{{ $quiz_performance['average_time'] }} دقيقة</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">أسئلة لكل اختبار</span>
                            <span class="font-bold">{{ $quiz_performance['average_questions'] }} سؤال</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">معدل الإكمال</span>
                            <span class="font-bold text-green-600">{{ $quiz_performance['completion_rate'] }}%</span>
                        </div>
                    </div>
                </div>
                
                @php
                    $worstRoot = collect($roots_performance)->sortBy('average')->keys()->first();
                    $rootNames = [
                        'jawhar' => 'الجوهر',
                        'zihn' => 'الذهن', 
                        'waslat' => 'الوصلات',
                        'roaya' => 'الرؤية'
                    ];
                @endphp
                
                <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-lightbulb text-yellow-600"></i>
                        <span class="text-yellow-800 text-sm font-medium">نصيحة</span>
                    </div>
                    <p class="text-yellow-700 text-sm mt-1">
                        جذر "{{ $rootNames[$worstRoot] ?? 'الرؤية' }}" يحتاج تحسين - فكر في ورش تدريبية
                    </p>
                </div>
                
                <button class="w-full mt-4 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-medium transition-colors"
                        onclick="window.location.href='{{ route('admin.reports') }}'">
                    تحليل مفصل
                </button>
            </div>

            <!-- System Health -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-server text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">حالة النظام</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-gray-700">الخدمة</span>
                        </div>
                        <span class="text-green-600 font-bold">{{ $system_health['database_status'] === 'healthy' ? 'متاحة' : 'مشكلة' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-gray-700">قاعدة البيانات</span>
                        </div>
                        <span class="text-green-600 font-bold">{{ $system_health['database_status'] === 'healthy' ? 'طبيعية' : 'مشكلة' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-gray-700">الذكاء الاصطناعي</span>
                        </div>
                        <span class="text-green-600 font-bold">{{ $system_health['ai_service_status'] === 'connected' ? 'متصل' : 'منقطع' }}</span>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $system_health['uptime'] }}%</p>
                        <p class="text-blue-700 text-sm">معدل التشغيل</p>
                    </div>
                </div>
                
                <button class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors"
                        onclick="window.location.href='{{ route('admin.settings') }}'">
                    تفاصيل النظام
                </button>
            </div>
        </div>

        <!-- Recent Activity & Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Activity -->
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">النشاط الأخير</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">عرض الكل</a>
                </div>
                
                <div class="space-y-4">
                    @forelse($recent_activity as $activity)
                    <div class="activity-item flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                            <i class="fas {{ $activity['icon'] }} text-{{ $activity['color'] }}-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $activity['title'] }}</p>
                            <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $activity['time'] }}</span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-history text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">لا يوجد نشاط حديث</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">إجراءات سريعة</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.users.create') }}" class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 text-center block">
                        <i class="fas fa-user-plus text-2xl mb-2"></i>
                        <p class="font-medium">إضافة مستخدم</p>
                    </a>
                    
                    <a href="{{ route('quizzes.create') }}" class="p-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl text-white hover:from-purple-600 hover:to-purple-700 transition-all transform hover:scale-105 text-center block">
                        <i class="fas fa-plus text-2xl mb-2"></i>
                        <p class="font-medium">إنشاء اختبار</p>
                    </a>
                    
                    <a href="{{ route('admin.reports') }}" class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-xl text-white hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 text-center block">
                        <i class="fas fa-file-export text-2xl mb-2"></i>
                        <p class="font-medium">تصدير تقرير</p>
                    </a>
                    
                    <a href="{{ route('admin.settings') }}" class="p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl text-white hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 text-center block">
                        <i class="fas fa-cog text-2xl mb-2"></i>
                        <p class="font-medium">الإعدادات</p>
                    </a>
                </div>
                
                <!-- AI Insights Box -->
                <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-robot text-indigo-600"></i>
                        </div>
                        <h4 class="font-bold text-indigo-900">رؤى الذكاء الاصطناعي</h4>
                    </div>
                    <p class="text-indigo-800 text-sm">
                        💡 الطلاب يواجهون صعوبة في أسئلة "الرؤية" - اقترح ورش عملية أكثر
                    </p>
                    <a href="{{ route('admin.reports') }}" class="mt-3 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        عرض التوصيات الكاملة →
                    </a>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        // Roots Performance Chart
        const rootsCtx = document.getElementById('rootsChart').getContext('2d');
        new Chart(rootsCtx, {
            type: 'doughnut',
            data: {
                labels: ['جَوهر', 'ذِهن', 'وَصلات', 'رُؤية'],
                datasets: [{
                    data: [
                        {{ $roots_performance['jawhar']['average'] }},
                        {{ $roots_performance['zihn']['average'] }}, 
                        {{ $roots_performance['waslat']['average'] }},
                        {{ $roots_performance['roaya']['average'] }}
                    ],
                    backgroundColor: [
                        '#ff6b6b',
                        '#4ecdc4', 
                        '#f7b731',
                        '#5f27cd'
                    ],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Usage Trends Chart
        const usageCtx = document.getElementById('usageChart').getContext('2d');
        new Chart(usageCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($usage_trends['labels']) !!},
                datasets: [{
                    label: 'الاختبارات المكتملة',
                    data: {!! json_encode($usage_trends['data']) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Counter Animation with English numerals
        function animateCounters() {
            const counters = document.querySelectorAll('.animate-counter');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/,/g, ''));
                let start = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    start += increment;
                    // Format with English numerals and commas
                    counter.textContent = Math.floor(start).toLocaleString('en-US');
                    if (start >= target) {
                        counter.textContent = target.toLocaleString('en-US');
                        clearInterval(timer);
                    }
                }, 30);
            });
        }

        // Start animations when page loads
        window.addEventListener('load', () => {
            setTimeout(animateCounters, 500);
        });
    </script>
@endpush
@endsection