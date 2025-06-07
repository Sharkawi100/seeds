@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 ml-2 rtl:ml-0 rtl:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            الرئيسية
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 6 10">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 9l4-4-4-4"></path>
                            </svg>
                            <a href="{{ route('quizzes.index') }}" class="mr-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:mr-2">اختباراتي</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 6 10">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 9l4-4-4-4"></path>
                            </svg>
                            <span class="mr-1 text-sm font-medium text-gray-500 md:mr-2">{{ $quiz->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Quiz Header -->
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span>{{ __($quiz->subject) }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>الصف {{ $quiz->grade_level }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $quiz->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-2">
                        <a href="{{ route('quiz.take', $quiz) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            بدء الاختبار
                        </a>
                        
                        @if($quiz->has_submissions)
                            <div class="inline-flex items-center px-4 py-2 bg-orange-100 text-orange-700 font-medium rounded-lg">
                                <i class="fas fa-lock ml-2"></i>
                                الاختبار مقفل
                            </div>
                            <a href="{{ route('quizzes.duplicate', $quiz) }}" 
   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
    <i class="fas fa-copy ml-2"></i>
    نسخ الاختبار
</a>
                        @else
                            <a href="{{ route('quizzes.edit', $quiz) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition duration-150 ease-in-out">
                                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                تعديل
                            </a>
                        @endif
                    </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">عدد الأسئلة</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $quiz->questions->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">المحاولات</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $quiz->results->count() }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">متوسط النجاح</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $quiz->results->count() > 0 ? round($quiz->results->avg('total_score')) . '%' : '--' }}
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">آخر تحديث</p>
                            <p class="text-sm font-bold text-gray-900">{{ $quiz->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PIN Display Card -->
<div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl shadow-xl p-6 mb-6 text-white">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold mb-2">رمز الدخول السريع</h3>
            <p class="text-white/80">شارك هذا الرمز مع الطلاب للدخول المباشر</p>
        </div>
        <div class="mt-4 md:mt-0 text-center">
            <div class="bg-white/20 backdrop-blur rounded-xl p-6">
                <p class="text-4xl font-bold tracking-wider mb-3">{{ $quiz->pin_code }}</p>
                <div class="flex gap-2">
                    <button onclick="copyPIN('{{ $quiz->pin_code }}')"
                            class="btn btn-sm bg-white/20 hover:bg-white/30 border-0 text-white">
                        <i class="fas fa-copy"></i> نسخ
                    </button>
                    <button onclick="shareQuiz('{{ $quiz->pin_code }}', '{{ $quiz->title }}')"
                            class="btn btn-sm bg-white/20 hover:bg-white/30 border-0 text-white">
                        <i class="fas fa-share"></i> مشاركة
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 text-sm text-white/70">
        <i class="fas fa-info-circle"></i>
        رابط الدخول المباشر: {{ url('/') }}/?pin={{ $quiz->pin_code }}
    </div>
</div>
        </div>

        <!-- Educational Text / Passage -->
        @if($quiz->passage)
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        {{ $quiz->passage_title ?: 'النص التعليمي' }}
                    </h2>
                    <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm">
                        نص القراءة
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($quiz->passage)) !!}
                </div>
            </div>
        </div>
        @endif

        <!-- Questions Section -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    الأسئلة
                </h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($quiz->questions as $index => $question)
                @php
                    $rootColors = [
                        'jawhar' => 'bg-red-50 border-red-200',
                        'zihn' => 'bg-cyan-50 border-cyan-200',
                        'waslat' => 'bg-yellow-50 border-yellow-200',
                        'roaya' => 'bg-purple-50 border-purple-200'
                    ];
                    $rootIcons = [
                        'jawhar' => '🎯',
                        'zihn' => '🧠',
                        'waslat' => '🔗',
                        'roaya' => '👁️'
                    ];
                    $rootNames = [
                        'jawhar' => 'جَوهر',
                        'zihn' => 'ذِهن',
                        'waslat' => 'وَصلات',
                        'roaya' => 'رُؤية'
                    ];
                @endphp
                
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center font-bold text-gray-600">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $rootColors[$question->root_type] ?? 'bg-gray-100' }} border">
                                    <span class="ml-1">{{ $rootIcons[$question->root_type] ?? '❓' }}</span>
                                    {{ $rootNames[$question->root_type] ?? $question->root_type }}
                                </span>
                                <span class="text-xs text-gray-500">المستوى {{ $question->depth_level }}</span>
                            </div>
                            <p class="text-gray-900 mb-3">{!! $question->question !!}</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($question->options as $optionIndex => $option)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <span class="flex-shrink-0 w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-medium">
                                        {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] ?? $optionIndex + 1 }}
                                    </span>
                                    <span>{{ $option }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        إدارة الأسئلة
                    </a>
                    <span class="text-sm text-gray-600">
                        إجمالي {{ $quiz->questions->count() }} سؤال
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('quiz.take', $quiz) }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                بدء الاختبار
            </a>
            <a href="{{ route('quizzes.questions.create', $quiz) }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة أسئلة
            </a>
            <a href="{{ route('quizzes.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة للقائمة
            </a>
        </div>
    </div>
</div>
@push('scripts')
<script>
function copyPIN(pin) {
    navigator.clipboard.writeText(pin);
    alert('تم نسخ رمز الدخول');
}

function shareQuiz(pin, title) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: `رمز دخول الاختبار: ${pin}`,
            url: window.location.href
        });
    } else {
        copyPIN(pin);
    }
}
</script>
@endpush
@endsection

@push('scripts')
<script>
// Add any JavaScript functionality here if needed
</script>
@endpush