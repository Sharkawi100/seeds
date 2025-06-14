@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <nav class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                <a href="{{ route('admin.quizzes.index') }}" class="hover:text-gray-700">إدارة الاختبارات</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900">{{ Str::limit($quiz->title, 50) }}</span>
            </nav>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Quiz Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8 text-white">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-6 lg:mb-0">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $quiz->is_active ? 'bg-green-400 text-green-900' : 'bg-red-400 text-red-900' }}">
                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                {{ $quiz->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        <h1 class="text-4xl font-bold mb-3">{{ $quiz->title }}</h1>
                        <div class="flex flex-wrap items-center gap-6 text-white/80">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                @if($quiz->subject)
                                    {{ $quiz->subject->name }}
                                @else
                                    غير محدد
                                @endif
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                الصف {{ $quiz->grade_level }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $quiz->user->name }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $quiz->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('quizzes.edit', $quiz) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-xl backdrop-blur-sm transition-all duration-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            تعديل الاختبار
                        </a>
                        <a href="{{ route('quiz.take', $quiz) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-white text-indigo-600 hover:bg-gray-50 font-semibold rounded-xl transition-all duration-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-8 4h8M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            معاينة الاختبار
                        </a>
                        <a href="{{ route('admin.quizzes.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-500/20 hover:bg-gray-500/30 text-white font-medium rounded-xl backdrop-blur-sm transition-all duration-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            رجوع
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg ml-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الأسئلة</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $quiz->questions->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg ml-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">المحاولات</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $quiz->results->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg ml-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">متوسط النتيجة</p>
                        <p class="text-3xl font-bold text-gray-900">
                            @if($quiz->results->count() > 0)
                                {{ number_format($quiz->results->avg('total_score'), 1) }}%
                            @else
                                --
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-amber-100 rounded-lg ml-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">رمز الدخول</p>
                        <p class="text-2xl font-mono font-bold text-gray-900 tracking-wider">{{ $quiz->pin }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Questions Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 text-blue-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                أسئلة الاختبار
                            </h3>
                            <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                إدارة الأسئلة
                            </a>
                        </div>
                    </div>
                    
                    @php
                        $rootColors = [
                            'jawhar' => ['bg' => 'bg-red-50', 'border' => 'border-red-300', 'text' => 'text-red-700', 'icon' => '🎯', 'name' => 'جَوهر'],
                            'zihn' => ['bg' => 'bg-cyan-50', 'border' => 'border-cyan-300', 'text' => 'text-cyan-700', 'icon' => '🧠', 'name' => 'ذِهن'],
                            'waslat' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-300', 'text' => 'text-yellow-700', 'icon' => '🔗', 'name' => 'وَصلات'],
                            'roaya' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-300', 'text' => 'text-purple-700', 'icon' => '👁️', 'name' => 'رُؤية']
                        ];
                    @endphp
                    
                    @if($quiz->questions->count() > 0)
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @foreach($quiz->questions->take(10) as $index => $question)
                                <div class="p-6 {{ $rootColors[$question->root_type]['bg'] }} border-r-4 {{ $rootColors[$question->root_type]['border'] }}">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <span class="text-lg font-bold text-gray-400">{{ $index + 1 }}</span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xl">{{ $rootColors[$question->root_type]['icon'] }}</span>
                                                <span class="font-bold {{ $rootColors[$question->root_type]['text'] }}">
                                                    {{ $rootColors[$question->root_type]['name'] }}
                                                </span>
                                                <span class="px-2 py-1 bg-white rounded-full text-xs font-medium {{ $rootColors[$question->root_type]['text'] }}">
                                                    مستوى {{ $question->depth_level }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-800 font-medium mb-3">{{ Str::limit($question->question, 150) }}</p>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($question->options as $optionIndex => $option)
                                            <div class="flex items-center gap-2 p-2 rounded-lg text-sm {{ $option == $question->correct_answer ? 'bg-green-100 text-green-800' : 'bg-white text-gray-700' }}">
                                                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $option == $question->correct_answer ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                    {{ ['أ', 'ب', 'ج', 'د'][$optionIndex] }}
                                                </span>
                                                <span class="truncate">{{ Str::limit($option, 30) }}</span>
                                                @if($option == $question->correct_answer)
                                                    <svg class="w-4 h-4 text-green-600 mr-auto" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($quiz->questions->count() > 10)
                            <div class="px-6 py-4 bg-gray-50 border-t text-center">
                                <p class="text-sm text-gray-600">
                                    عرض {{ min(10, $quiz->questions->count()) }} من أصل {{ $quiz->questions->count() }} سؤال
                                </p>
                                <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    عرض جميع الأسئلة
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="p-12 text-center">
                            <div class="p-4 bg-gray-100 rounded-full inline-block mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">لا توجد أسئلة بعد</h4>
                            <p class="text-gray-600 mb-4">ابدأ بإضافة أسئلة لهذا الاختبار</p>
                            <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                إضافة أسئلة
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quiz Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">تفاصيل الاختبار</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        @if($quiz->description)
                            <div>
                                <h4 class="text-sm font-medium text-gray-600 mb-2">الوصف</h4>
                                <p class="text-gray-900">{{ $quiz->description }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 mb-2">تاريخ الإنشاء</h4>
                            <p class="text-gray-900">{{ $quiz->created_at->format('d/m/Y - H:i') }}</p>
                        </div>
                        
                        @if($quiz->time_limit)
                            <div>
                                <h4 class="text-sm font-medium text-gray-600 mb-2">المدة الزمنية</h4>
                                <p class="text-gray-900">{{ $quiz->time_limit }} دقيقة</p>
                            </div>
                        @endif
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 mb-2">درجة النجاح</h4>
                            <p class="text-gray-900">{{ $quiz->passing_score }}%</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 mb-2">إظهار النتائج</h4>
                            <p class="text-gray-900">{{ $quiz->show_results ? 'نعم' : 'لا' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Juzoor Analysis Card -->
                @if($quiz->questions->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">تحليل جُذور التعلم</h3>
                        </div>
                        
                        <div class="p-6">
                            @php
                                $rootCounts = [
                                    'jawhar' => $quiz->questions->where('root_type', 'jawhar')->count(),
                                    'zihn' => $quiz->questions->where('root_type', 'zihn')->count(),
                                    'waslat' => $quiz->questions->where('root_type', 'waslat')->count(),
                                    'roaya' => $quiz->questions->where('root_type', 'roaya')->count(),
                                ];
                                $totalQuestions = array_sum($rootCounts);
                                
                                $roots = [
                                    'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'red'],
                                    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'cyan'],
                                    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'yellow'],
                                    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'purple']
                                ];
                            @endphp
                            
                            <div class="space-y-4">
                                @foreach($roots as $key => $root)
                                    @php
                                        $count = $rootCounts[$key];
                                        $percentage = $totalQuestions > 0 ? ($count / $totalQuestions) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="text-xl ml-3">{{ $root['icon'] }}</span>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $root['name'] }}</div>
                                                <div class="text-sm text-gray-500">{{ $count }} سؤال</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">{{ number_format($percentage, 1) }}%</div>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-{{ $root['color'] }}-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">إجراءات سريعة</h3>
                    </div>
                    
                    <div class="p-6 space-y-3">
                        <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            إدارة الأسئلة
                        </a>
                        
                        @if($quiz->results->count() > 0)
                            <a href="{{ route('results.quiz', $quiz) }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                عرض النتائج
                            </a>
                        @endif
                        
                        <button onclick="toggleStatus({{ $quiz->id }})" 
                                class="w-full flex items-center justify-center px-4 py-3 {{ $quiz->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                            </svg>
                            {{ $quiz->is_active ? 'تعطيل الاختبار' : 'تفعيل الاختبار' }}
                        </button>
                        
                        @if(!$quiz->has_submissions)
                            <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاختبار؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    حذف الاختبار
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function toggleStatus(quizId) {
    try {
        const response = await fetch(`/admin/quizzes/${quizId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            window.location.reload();
        } else {
            alert('حدث خطأ أثناء تغيير حالة الاختبار');
        }
    } catch (error) {
        alert('حدث خطأ أثناء تغيير حالة الاختبار');
    }
}
</script>
@endsection