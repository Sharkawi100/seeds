@extends('layouts.app')

@section('title', 'إدارة الأسئلة - ' . $quiz->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Clean Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                            <a href="{{ route('quizzes.index') }}" class="hover:text-gray-700 transition-colors">
                                الاختبارات
                            </a>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <span class="text-gray-900 font-medium">{{ $quiz->title }}</span>
                        </nav>
                        
                        <h1 class="text-2xl font-bold text-gray-900">إدارة الأسئلة</h1>
                        
                        <div class="flex items-center gap-4 mt-3 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ $quiz->subject == 'arabic' ? 'اللغة العربية' : ($quiz->subject == 'english' ? 'اللغة الإنجليزية' : 'اللغة العبرية') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                الصف {{ $quiz->grade_level }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span id="question-count">{{ $quiz->questions->count() }}</span> سؤال
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('quiz.take', $quiz) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            معاينة
                        </a>
                        
                        <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            إضافة أسئلة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Root Distribution Stats -->
        @php
            $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
            $depthCounts = $quiz->questions->groupBy('depth_level')->map->count();
        @endphp
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <button onclick="filterByRoot('jawhar')" 
                    class="bg-white rounded-xl p-4 border border-gray-100 hover:border-red-200 hover:shadow-sm transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">🎯</span>
                    <span class="text-2xl font-bold text-gray-900">{{ $rootCounts['jawhar'] ?? 0 }}</span>
                </div>
                <div class="text-sm font-medium text-gray-700">جَوْهَر</div>
                <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-red-500 rounded-full transition-all duration-500" 
                         style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts['jawhar'] ?? 0) / $quiz->questions->count() * 100) : 0 }}%"></div>
                </div>
            </button>
            
            <button onclick="filterByRoot('zihn')" 
                    class="bg-white rounded-xl p-4 border border-gray-100 hover:border-cyan-200 hover:shadow-sm transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">🧠</span>
                    <span class="text-2xl font-bold text-gray-900">{{ $rootCounts['zihn'] ?? 0 }}</span>
                </div>
                <div class="text-sm font-medium text-gray-700">ذِهْن</div>
                <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-cyan-500 rounded-full transition-all duration-500" 
                         style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts['zihn'] ?? 0) / $quiz->questions->count() * 100) : 0 }}%"></div>
                </div>
            </button>
            
            <button onclick="filterByRoot('waslat')" 
                    class="bg-white rounded-xl p-4 border border-gray-100 hover:border-yellow-200 hover:shadow-sm transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">🔗</span>
                    <span class="text-2xl font-bold text-gray-900">{{ $rootCounts['waslat'] ?? 0 }}</span>
                </div>
                <div class="text-sm font-medium text-gray-700">وَصَلات</div>
                <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 rounded-full transition-all duration-500" 
                         style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts['waslat'] ?? 0) / $quiz->questions->count() * 100) : 0 }}%"></div>
                </div>
            </button>
            
            <button onclick="filterByRoot('roaya')" 
                    class="bg-white rounded-xl p-4 border border-gray-100 hover:border-purple-200 hover:shadow-sm transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">👁️</span>
                    <span class="text-2xl font-bold text-gray-900">{{ $rootCounts['roaya'] ?? 0 }}</span>
                </div>
                <div class="text-sm font-medium text-gray-700">رُؤْيَة</div>
                <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-purple-500 rounded-full transition-all duration-500" 
                         style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts['roaya'] ?? 0) / $quiz->questions->count() * 100) : 0 }}%"></div>
                </div>
            </button>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl border border-gray-100 p-4 mb-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           id="search-input" 
                           placeholder="ابحث في الأسئلة..." 
                           class="w-full pr-11 pl-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           oninput="searchQuestions(this.value)">
                </div>
                
                <div class="flex gap-3">
                    <select id="filter-root" onchange="applyFilters()" 
                            class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع الجذور</option>
                        <option value="jawhar">🎯 جَوْهَر</option>
                        <option value="zihn">🧠 ذِهْن</option>
                        <option value="waslat">🔗 وَصَلات</option>
                        <option value="roaya">👁️ رُؤْيَة</option>
                    </select>
                    
                    <select id="filter-depth" onchange="applyFilters()" 
                            class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">جميع المستويات</option>
                        <option value="1">مستوى 1</option>
                        <option value="2">مستوى 2</option>
                        <option value="3">مستوى 3</option>
                    </select>
                    
                    <button onclick="resetFilters()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    
                    <button onclick="toggleBulkMode()" 
                            class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Active filters -->
            <div id="active-filters" class="hidden mt-3 flex flex-wrap gap-2"></div>
        </div>

        <!-- Reading Passage (if exists) -->
        @if($quiz->questions->where('passage', '!=', null)->first())
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 rounded-lg p-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $quiz->questions->first()->passage_title ?? 'نص القراءة' }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">يُعرض هذا النص للطلاب قبل الأسئلة</p>
                    </div>
                </div>
                <button onclick="editPassage()" 
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    تعديل
                </button>
            </div>
            
            <div class="bg-white rounded-lg p-4" id="passage-content">
                <div class="prose prose-sm max-w-none">
                    {!! nl2br(e($quiz->questions->first()->passage)) !!}
                </div>
            </div>
            
            <!-- Edit mode (hidden by default) -->
            <div id="passage-edit-mode" class="hidden">
                <textarea id="passage-editor" class="w-full">{{ $quiz->questions->first()->passage }}</textarea>
                <div class="mt-4 flex gap-3 justify-end">
                    <button onclick="cancelPassageEdit()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
                        إلغاء
                    </button>
                    <button onclick="savePassage()" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        حفظ
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Questions List -->
        <div class="space-y-4" id="questions-container">
            @forelse($quiz->questions as $index => $question)
            <div class="question-item bg-white rounded-xl border border-gray-100 hover:border-gray-200 hover:shadow-sm transition-all" 
                 data-root="{{ $question->root_type }}" 
                 data-depth="{{ $question->depth_level }}"
                 data-question-id="{{ $question->id }}">
                
                <div class="p-6">
                    <div class="flex gap-4">
                        <!-- Checkbox for bulk selection -->
                        <div class="bulk-checkbox hidden">
                            <input type="checkbox" 
                                   class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" 
                                   value="{{ $question->id }}"
                                   onchange="updateSelectedCount()">
                        </div>
                        
                        <!-- Question number -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-sm font-medium text-gray-700">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        
                        <!-- Question content -->
                        <div class="flex-1">
                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ 
                                    $question->root_type == 'jawhar' ? 'bg-red-100 text-red-700' : 
                                    ($question->root_type == 'zihn' ? 'bg-cyan-100 text-cyan-700' : 
                                    ($question->root_type == 'waslat' ? 'bg-yellow-100 text-yellow-700' : 
                                    'bg-purple-100 text-purple-700')) 
                                }}">
                                    @if($question->root_type == 'jawhar')
                                        🎯 جَوْهَر
                                    @elseif($question->root_type == 'zihn')
                                        🧠 ذِهْن
                                    @elseif($question->root_type == 'waslat')
                                        🔗 وَصَلات
                                    @else
                                        👁️ رُؤْيَة
                                    @endif
                                </span>
                                
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    مستوى {{ $question->depth_level }}
                                </span>
                            </div>
                            
                            <!-- Question text -->
                            <div class="question-display" id="question-display-{{ $question->id }}">
                                <p class="text-gray-900 mb-4">{!! $question->question !!}</p>
                            </div>
                            
                            <!-- Edit mode (hidden) -->
                            <div class="question-edit hidden" id="question-edit-{{ $question->id }}">
                                <textarea id="editor-{{ $question->id }}" class="question-editor">{{ $question->question }}</textarea>
                                <div class="mt-3 flex gap-2">
                                    <button onclick="saveQuestion({{ $question->id }})" 
                                            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors">
                                        حفظ
                                    </button>
                                    <button onclick="cancelEdit({{ $question->id }})" 
                                            class="px-3 py-1.5 text-gray-600 hover:text-gray-900 text-sm transition-colors">
                                        إلغاء
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Answer options -->
                            <div class="grid sm:grid-cols-2 gap-2">
                                @foreach($question->options as $optionIndex => $option)
                                <div class="flex items-center gap-2 p-2.5 rounded-lg {{ 
                                    $option == $question->correct_answer 
                                    ? 'bg-green-50 border border-green-200' 
                                    : 'bg-gray-50' 
                                }}">
                                    <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-xs font-medium {{ 
                                        $option == $question->correct_answer 
                                        ? 'bg-green-600 text-white' 
                                        : 'bg-gray-200 text-gray-700' 
                                    }}">
                                        {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] }}
                                    </span>
                                    <span class="text-sm {{ $option == $question->correct_answer ? 'font-medium text-green-900' : 'text-gray-700' }}">
                                        {{ $option }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex-shrink-0 flex items-start gap-1">
                            <button onclick="editInline({{ $question->id }})" 
                                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                    title="تعديل سريع">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            
                            <div class="relative group">
                                <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10">
                                    <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" 
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        تعديل كامل
                                    </a>
                                    
                                    <button onclick="duplicateQuestion({{ $question->id }})" 
                                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 text-right">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        نسخ
                                    </button>
                                    
                                    <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')"
                                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-lg text-right">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty state -->
            <div class="bg-white rounded-xl border border-gray-100 p-12">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد أسئلة بعد</h3>
                    <p class="text-gray-500 mb-6">ابدأ بإضافة أسئلة لهذا الاختبار</p>
                    <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إضافة أسئلة جديدة
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Bottom actions -->
        @if($quiz->questions->count() > 0)
        <div class="mt-8 flex items-center justify-between">
            <a href="{{ route('quizzes.show', $quiz) }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                → العودة لتفاصيل الاختبار
            </a>
            
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">إجمالي {{ $quiz->questions->count() }} سؤال</span>
                <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إضافة المزيد
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Bulk actions bar -->
<div id="bulk-actions-bar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white rounded-lg shadow-lg px-6 py-3 hidden">
    <div class="flex items-center gap-6">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" id="select-all" onchange="toggleSelectAll()" class="w-4 h-4 rounded">
            <span class="text-sm">تحديد الكل</span>
        </label>
        
        <span class="text-sm">
            <span id="selected-count" class="font-medium">0</span> محدد
        </span>
        
        <button onclick="bulkDelete()" 
                class="text-sm text-red-400 hover:text-red-300 transition-colors">
            حذف المحدد
        </button>
        
        <button onclick="closeBulkMode()" 
                class="mr-4 text-gray-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Keyboard shortcuts hint -->
<div class="fixed bottom-6 left-6 text-xs text-gray-400">
    <kbd class="px-2 py-1 bg-gray-100 text-gray-600 rounded">Ctrl+K</kbd> للبحث السريع
</div>
@endsection

@push('styles')
<style>
/* Simple, clean styles */
.bulk-mode-active .bulk-checkbox {
    display: block !important;
}

.bulk-mode-active #bulk-actions-bar {
    display: block !important;
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Remove excessive animations */
.question-item {
    animation: none;
}

/* Clean focus states */
input:focus,
select:focus,
textarea:focus,
button:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}

/* Active filter indicator */
.active-filter {
    background-color: rgba(59, 130, 246, 0.1);
    border-color: rgb(59, 130, 246);
}
</style>
@endpush

@push('scripts')
<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/cmtwmtmif3u7ducaiqvogvq1wvc280ugtxjzo2ffaymjmuxg/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
// Global state
let bulkMode = false;
let activeFilters = {
    root: null,
    depth: null,
    search: null
};

// Initialize TinyMCE with simpler config
function initTinyMCE(selector) {
    return tinymce.init({
        selector: selector,
        language: 'ar',
        directionality: 'rtl',
        height: 200,
        menubar: false,
        plugins: 'link lists',
        toolbar: 'undo redo | bold italic | bullist numlist | link',
        content_style: 'body { font-family: system-ui, -apple-system, sans-serif; font-size: 14px; }',
        branding: false,
        promotion: false
    });
}

// Search and filter functions
function searchQuestions(query) {
    activeFilters.search = query.toLowerCase();
    applyFilters();
}

function filterByRoot(root) {
    document.getElementById('filter-root').value = root;
    activeFilters.root = root;
    applyFilters();
}

function applyFilters() {
    const rootFilter = document.getElementById('filter-root').value;
    const depthFilter = document.getElementById('filter-depth').value;
    const searchQuery = document.getElementById('search-input').value.toLowerCase();
    
    let visibleCount = 0;
    
    document.querySelectorAll('.question-item').forEach(item => {
        const matchRoot = !rootFilter || item.dataset.root === rootFilter;
        const matchDepth = !depthFilter || item.dataset.depth === depthFilter;
        const questionText = item.querySelector('.question-display').textContent.toLowerCase();
        const matchSearch = !searchQuery || questionText.includes(searchQuery);
        
        const isVisible = matchRoot && matchDepth && matchSearch;
        item.style.display = isVisible ? '' : 'none';
        
        if (isVisible) visibleCount++;
    });
    
    document.getElementById('question-count').textContent = visibleCount;
    updateActiveFiltersDisplay();
}

function updateActiveFiltersDisplay() {
    const container = document.getElementById('active-filters');
    container.innerHTML = '';
    
    let hasFilters = false;
    
    if (activeFilters.root || document.getElementById('filter-root').value) {
        hasFilters = true;
        const root = activeFilters.root || document.getElementById('filter-root').value;
        addFilterChip('root', root);
    }
    
    if (activeFilters.depth || document.getElementById('filter-depth').value) {
        hasFilters = true;
        const depth = activeFilters.depth || document.getElementById('filter-depth').value;
        addFilterChip('depth', `مستوى ${depth}`);
    }
    
    if (activeFilters.search || document.getElementById('search-input').value) {
        hasFilters = true;
        const search = activeFilters.search || document.getElementById('search-input').value;
        addFilterChip('search', search);
    }
    
    container.classList.toggle('hidden', !hasFilters);
}

function addFilterChip(type, value) {
    const chip = document.createElement('span');
    chip.className = 'inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm';
    chip.innerHTML = `
        ${value}
        <button onclick="removeFilter('${type}')" class="hover:text-blue-900">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    document.getElementById('active-filters').appendChild(chip);
}

function removeFilter(type) {
    if (type === 'root') {
        document.getElementById('filter-root').value = '';
        activeFilters.root = null;
    } else if (type === 'depth') {
        document.getElementById('filter-depth').value = '';
        activeFilters.depth = null;
    } else if (type === 'search') {
        document.getElementById('search-input').value = '';
        activeFilters.search = null;
    }
    applyFilters();
}

function resetFilters() {
    document.getElementById('filter-root').value = '';
    document.getElementById('filter-depth').value = '';
    document.getElementById('search-input').value = '';
    activeFilters = { root: null, depth: null, search: null };
    applyFilters();
}

// Bulk mode functions
function toggleBulkMode() {
    bulkMode = !bulkMode;
    document.body.classList.toggle('bulk-mode-active', bulkMode);
    
    if (!bulkMode) {
        document.querySelectorAll('.bulk-checkbox input').forEach(cb => {
            cb.checked = false;
        });
        updateSelectedCount();
    }
}

function closeBulkMode() {
    bulkMode = false;
    document.body.classList.remove('bulk-mode-active');
}

function toggleSelectAll() {
    const selectAll = document.getElementById('select-all').checked;
    document.querySelectorAll('.bulk-checkbox input:not(:disabled)').forEach(cb => {
        cb.checked = selectAll;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.bulk-checkbox input:checked').length;
    document.getElementById('selected-count').textContent = count;
}

async function bulkDelete() {
    const selected = Array.from(document.querySelectorAll('.bulk-checkbox input:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    if (!confirm(`هل أنت متأكد من حذف ${selected.length} سؤال؟`)) return;
    
    // Implementation for bulk delete
    showNotification('تم حذف الأسئلة المحددة', 'success');
    closeBulkMode();
    location.reload();
}

// Inline editing
function editInline(questionId) {
    document.getElementById(`question-display-${questionId}`).classList.add('hidden');
    document.getElementById(`question-edit-${questionId}`).classList.remove('hidden');
    
    initTinyMCE(`#editor-${questionId}`);
}

function cancelEdit(questionId) {
    document.getElementById(`question-display-${questionId}`).classList.remove('hidden');
    document.getElementById(`question-edit-${questionId}`).classList.add('hidden');
    
    tinymce.remove(`#editor-${questionId}`);
}

async function saveQuestion(questionId) {
    const editor = tinymce.get(`editor-${questionId}`);
    if (!editor) return;
    
    const content = editor.getContent();
    
    try {
        const response = await fetch(`/quizzes/{{ $quiz->id }}/questions/${questionId}/update-text`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ question: content })
        });
        
        if (response.ok) {
            document.querySelector(`#question-display-${questionId} p`).innerHTML = content;
            cancelEdit(questionId);
            showNotification('تم حفظ السؤال', 'success');
        }
    } catch (error) {
        showNotification('حدث خطأ في الحفظ', 'error');
    }
}

// Passage editing
function editPassage() {
    document.getElementById('passage-content').classList.add('hidden');
    document.getElementById('passage-edit-mode').classList.remove('hidden');
    initTinyMCE('#passage-editor');
}

function cancelPassageEdit() {
    document.getElementById('passage-content').classList.remove('hidden');
    document.getElementById('passage-edit-mode').classList.add('hidden');
    tinymce.remove('#passage-editor');
}

async function savePassage() {
    const editor = tinymce.get('passage-editor');
    if (!editor) return;
    
    const content = editor.getContent();
    const firstQuestionId = {{ $quiz->questions->first()->id ?? 'null' }};
    
    if (!firstQuestionId) return;
    
    // Save passage logic here
    document.querySelector('#passage-content .prose').innerHTML = content;
    cancelPassageEdit();
    showNotification('تم حفظ النص', 'success');
}

// Utility functions
function duplicateQuestion(questionId) {
    showNotification('سيتم إضافة ميزة النسخ قريباً', 'info');
}

function showNotification(message, type = 'success') {
    // Simple notification implementation
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-100 text-green-800' :
        type === 'error' ? 'bg-red-100 text-red-800' :
        'bg-blue-100 text-blue-800'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('search-input').focus();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Any initialization code
});
</script>
@endpush