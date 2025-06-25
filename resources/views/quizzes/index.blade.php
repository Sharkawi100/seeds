@extends('layouts.app')

@section('title', 'اختباراتي - جُذور')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Header -->
        <div class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="space-y-3">
                    <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        اختباراتي
                    </h1>
                    <p class="text-xl text-gray-600">أنشئ وأدِر اختباراتك بنموذج جُذور الأربعة</p>
                </div>
                
                <!-- Enhanced Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="text-2xl font-bold text-gray-900">{{ $quizzes->count() }}</div>
                        <div class="text-sm text-gray-600 font-medium">إجمالي الاختبارات</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="text-2xl font-bold text-emerald-600">{{ $quizzes->where('is_active', true)->count() }}</div>
                        <div class="text-sm text-gray-600 font-medium">نشط</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="text-2xl font-bold text-blue-600">{{ $quizzes->sum(fn($q) => $q->questions->count()) }}</div>
                        <div class="text-sm text-gray-600 font-medium">الأسئلة</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="text-2xl font-bold text-orange-600">{{ $quizzes->sum(fn($q) => $q->results->count()) }}</div>
                        <div class="text-sm text-gray-600 font-medium">المحاولات</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty Quizzes Alert -->
        @if(isset($emptyQuizzesAlert) && $emptyQuizzesAlert)
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-3xl p-6 mb-8" 
             id="empty-quiz-alert" 
             role="alert" 
             aria-live="polite">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4">
                    <div class="bg-amber-100 rounded-full p-3" aria-hidden="true">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-amber-900 mb-1">تنبيه: اختبارات بدون أسئلة</h3>
                        <p class="text-amber-800 mb-4">{{ $emptyQuizzesAlert['message'] }}</p>
                        <div class="flex gap-3">
                            <button onclick="filterEmptyQuizzes()" 
                                    class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-xl font-medium transition-colors focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                {{ $emptyQuizzesAlert['action_text'] }}
                            </button>
                            <form action="{{ route('quizzes.delete-empty') }}" method="POST" class="inline-block" 
                                  onsubmit="return confirmDeleteEmpty()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-medium transition-colors focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    {{ $emptyQuizzesAlert['delete_text'] }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <button onclick="hideAlert()" 
                        class="text-amber-600 hover:text-amber-800 transition-colors p-1 rounded focus:ring-2 focus:ring-amber-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Enhanced Control Panel -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 mb-8 border border-gray-100 shadow-lg">
            <div class="flex flex-col gap-6">
                
                <!-- Search & Filters -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex flex-col sm:flex-row gap-4 flex-1">
                        <!-- Search -->
                        <div class="relative flex-1 min-w-0">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="search-input"
                                   placeholder="ابحث في الاختبارات..." 
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 text-right focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300"
                                   value="{{ request('search') }}">
                        </div>
                        
                        <!-- Subject Filter -->
                        <select id="subject-filter" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                            <option value="">جميع المواد</option>
                            <option value="arabic" {{ request('subject') == 'arabic' ? 'selected' : '' }}>اللغة العربية</option>
                            <option value="english" {{ request('subject') == 'english' ? 'selected' : '' }}>اللغة الإنجليزية</option>
                            <option value="hebrew" {{ request('subject') == 'hebrew' ? 'selected' : '' }}>اللغة العبرية</option>
                        </select>
                        
                        <!-- Grade Filter -->
                        <select id="grade-filter" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                            <option value="">جميع الصفوف</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('grade_level') == $i ? 'selected' : '' }}>
                                    الصف {{ $i }}
                                </option>
                            @endfor
                        </select>
                        
                        <!-- Enhanced Status Filter -->
                        <select id="status-filter" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="has_submissions" {{ request('status') == 'has_submissions' ? 'selected' : '' }}>تم استخدامه</option>
                            <option value="empty" {{ request('status') == 'empty' ? 'selected' : '' }}>بدون أسئلة</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <!-- View Toggle -->
                        <div class="flex bg-gray-100 rounded-2xl p-1">
                            <button id="grid-view-btn" 
                                    onclick="switchView('grid')" 
                                    class="view-toggle-btn active px-4 py-2 rounded-xl font-medium transition-all duration-300 flex items-center gap-2 focus:ring-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                شبكة
                            </button>
                            <button id="list-view-btn" 
                                    onclick="switchView('list')" 
                                    class="view-toggle-btn px-4 py-2 rounded-xl font-medium transition-all duration-300 flex items-center gap-2 focus:ring-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                قائمة
                            </button>
                        </div>
                        
                        <button onclick="clearFilters()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-medium transition-all duration-300 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            مسح الفلاتر
                        </button>
                        <a href="{{ route('quizzes.create') }}"
   class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600
          hover:from-indigo-600 hover:to-purple-700
          text-green-800 hover:text-green-900      /* ← هنا التغيير */
          rounded-2xl font-bold transition-all duration-300
          flex items-center gap-2 shadow-lg hover:shadow-xl
          border border-indigo-400/20"
   style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
    <svg class="w-5 h-5 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    <span class="drop-shadow-sm">اختبار جديد</span>
</a>

                    </div>
                </div>

                <!-- Selection Actions -->
                <div class="flex items-center gap-3" id="selection-actions" style="display: none;">
                    <span class="text-sm text-gray-600 font-medium">المحدد: <span id="selected-count">0</span></span>
                    <button onclick="deleteSelected()" 
                            class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-xl font-medium transition-colors flex items-center gap-2 focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        حذف المحدد
                    </button>
                    <button onclick="clearSelection()" 
                            class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-xl font-medium transition-colors focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        إلغاء التحديد
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        @if($quizzes->count() > 0)
            <!-- Grid View (Enhanced Original Design) -->
            <div id="quizzes-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($quizzes as $quiz)
                <div class="quiz-card bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-200 group h-full flex flex-col"
                     data-quiz-id="{{ $quiz->id }}"
                     data-subject="{{ $quiz->subject ?? '' }}" 
                     data-grade="{{ $quiz->grade_level }}" 
                     data-status="{{ $quiz->is_active ? 'active' : 'inactive' }}{{ $quiz->has_submissions ? ' has_submissions' : '' }}{{ $quiz->total_questions == 0 ? ' empty' : '' }}"
                     data-has-submissions="{{ $quiz->has_submissions ? 'true' : 'false' }}"
                     data-title="{{ strtolower($quiz->title) }}">
                    
                    <!-- Enhanced Quiz Header -->
                    @php
                        $gradientStyles = [
                            'background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #000000 100%);',
                            'background: linear-gradient(135deg, #1e3a8a 0%, #312e81 50%, #581c87 100%);',
                            'background: linear-gradient(135deg, #14532d 0%, #064e3b 50%, #134e4a 100%);',
                            'background: linear-gradient(135deg, #7f1d1d 0%, #881337 50%, #831843 100%);',
                            'background: linear-gradient(135deg, #581c87 0%, #6b21a8 50%, #86198f 100%);',
                            'background: linear-gradient(135deg, #78350f 0%, #9a3412 50%, #7f1d1d 100%);'
                        ];
                        $gradientClasses = [
                            'bg-gradient-to-br from-gray-800 via-gray-900 to-black',
                            'bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900',
                            'bg-gradient-to-br from-green-900 via-emerald-900 to-teal-900',
                            'bg-gradient-to-br from-red-900 via-rose-900 to-pink-900',
                            'bg-gradient-to-br from-purple-900 via-violet-900 to-fuchsia-900',
                            'bg-gradient-to-br from-amber-900 via-orange-900 to-red-900'
                        ];
                        $gradientIndex = abs($quiz->id) % count($gradientStyles);
                        $selectedGradient = $gradientClasses[$gradientIndex];
                        $selectedStyle = $gradientStyles[$gradientIndex];
                    @endphp
                    <div class="relative {{ $selectedGradient }} p-6 text-white min-h-[160px] flex flex-col justify-between overflow-hidden"
                         style="{{ $selectedStyle }} background-size: cover; background-attachment: local;">
                        
                        <!-- Background Effects -->
                        <div class="absolute inset-0" style="background: rgba(0,0,0,0.2);"></div>
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                        <div class="absolute top-4 left-4 w-16 h-16 bg-white/5 rounded-full blur-xl"></div>
                        
                        <!-- Content Layer -->
                        <div class="relative z-10">
                            <!-- Top Section with Selection, Status & PIN -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <!-- Selection Checkbox -->
                                    <input type="checkbox" 
                                           class="quiz-selector w-5 h-5 text-indigo-600 border-2 border-white/50 rounded bg-white/20 focus:ring-indigo-500" 
                                           data-quiz-id="{{ $quiz->id }}" 
                                           data-has-submissions="{{ $quiz->has_submissions ? 'true' : 'false' }}"
                                           onchange="updateSelection()">
                                    
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $quiz->is_active ? 'bg-green-400 text-green-900' : 'bg-gray-400 text-gray-900' }} shadow-lg backdrop-blur-sm border border-white/20">
                                        <div class="w-2 h-2 rounded-full {{ $quiz->is_active ? 'bg-green-700' : 'bg-gray-700' }} ml-2 animate-pulse"></div>
                                        {{ $quiz->is_active ? 'نشط' : 'معطل' }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <!-- PIN -->
                                    <div class="text-lg font-mono font-black text-white bg-white/20 backdrop-blur-sm px-3 py-1 rounded-lg border border-white/30 shadow-lg hover:bg-white/30 transition-all duration-300">
                                        {{ $quiz->pin ?? '---' }}
                                    </div>
                                    
                                    <!-- 3-dots Menu -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" 
                                                class="p-2 text-white/80 hover:text-white hover:bg-white/20 transition-colors rounded-full backdrop-blur-sm">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                        
                                        <div x-show="open" 
                                             @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute left-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                                            
                                            @if($quiz->total_questions == 0)
                                            <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                                               class="block px-4 py-2 text-sm text-blue-700 hover:bg-blue-50 transition-colors">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    إضافة أسئلة
                                                </span>
                                            </a>
                                            @endif
                                            
                                            @if(!$quiz->has_submissions)
                                            <a href="{{ route('quizzes.edit', $quiz) }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    تعديل
                                                </span>
                                            </a>
                                            @endif
                                            
                                            <button onclick="copyQuizPin('{{ $quiz->pin }}')" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    نسخ الرمز ({{ $quiz->pin }})
                                                </span>
                                            </button>
                                            
                                            <div class="border-t border-gray-100 my-1"></div>
                                            
                                            @if(!$quiz->has_submissions && $quiz->total_questions == 0)
                                            <button onclick="deleteQuiz({{ $quiz->id }}, '{{ addslashes($quiz->title) }}')" 
                                                    class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    حذف الاختبار
                                                </span>
                                            </button>
                                            @elseif($quiz->has_submissions)
                                            <div class="px-4 py-2 text-sm text-gray-500" title="لا يمكن حذف الاختبار لأنه يحتوي على إجابات طلاب">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                    محمي (به إجابات)
                                                </span>
                                            </div>
                                            @else
                                            <div class="px-4 py-2 text-sm text-gray-500" title="لا يمكن حذف الاختبار لأنه يحتوي على {{ $quiz->total_questions }} {{ $quiz->total_questions == 1 ? 'سؤال' : 'أسئلة' }}">
                                                <span class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    محمي (به أسئلة)
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quiz Title -->
                            <div>
                                <h3 class="text-xl font-bold leading-tight mb-4 text-white line-clamp-2 min-h-[3rem] flex items-center drop-shadow-sm">
                                    {{ $quiz->title }}
                                </h3>
                                <div class="flex items-center justify-between text-white/90 text-sm">
                                    <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-full border border-white/20 shadow-sm hover:bg-white/30 transition-all duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span class="font-bold">{{ $quiz->subject == 'arabic' ? 'اللغة العربية' : ($quiz->subject == 'english' ? 'اللغة الإنجليزية' : 'اللغة العبرية') }}</span>
                                    </span>
                                    <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-full border border-white/20 shadow-sm hover:bg-white/30 transition-all duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="font-bold">الصف {{ $quiz->grade_level }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Subtle Pattern Overlay -->
                        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="7" cy="7" r="1"/><circle cx="27" cy="7" r="1"/><circle cx="47" cy="7" r="1"/><circle cx="7" cy="27" r="1"/><circle cx="27" cy="27" r="1"/><circle cx="47" cy="27" r="1"/><circle cx="7" cy="47" r="1"/><circle cx="27" cy="47" r="1"/><circle cx="47" cy="47" r="1"/></g></g></svg>');"></div>
                    </div>
                    
                    <!-- Quiz Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Statistics -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-2xl p-4 text-center border border-blue-100">
                                <div class="text-3xl font-black {{ $quiz->total_questions == 0 ? 'text-red-600' : 'text-blue-700' }} mb-1">{{ $quiz->total_questions }}</div>
                                <div class="text-sm {{ $quiz->total_questions == 0 ? 'text-red-600' : 'text-blue-600' }} font-bold">سؤال</div>
                            </div>
                            <div class="bg-green-50 rounded-2xl p-4 text-center border border-green-100">
                                <div class="text-3xl font-black text-green-700 mb-1">{{ $quiz->results->count() }}</div>
                                <div class="text-sm text-green-600 font-bold">محاولة</div>
                            </div>
                        </div>
                        
                        <!-- Status Badges -->
                        @if($quiz->total_questions == 0 || !$quiz->is_active || $quiz->has_submissions)
                        <div class="flex gap-2 flex-wrap mb-4">
                            @if($quiz->total_questions == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    بدون أسئلة
                                </span>
                            @endif
                            
                            @if(!$quiz->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    غير نشط
                                </span>
                            @endif
                            
                            @if($quiz->has_submissions)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    تم استخدامه
                                </span>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Roots Distribution -->
                        @if($quiz->total_questions > 0)
                        <div class="mb-6">
                            <div class="text-sm font-bold text-gray-800 mb-4 text-center">توزيع الجذور الأربعة</div>
                            <div class="grid grid-cols-4 gap-3">
                                @php
                                    $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                                    $rootColors = [
                                        'jawhar' => ['name' => 'جَوهر', 'bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                                        'zihn' => ['name' => 'ذِهن', 'bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200'],
                                        'waslat' => ['name' => 'وَصلات', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
                                        'roaya' => ['name' => 'رُؤية', 'bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-200']
                                    ];
                                @endphp
                                @foreach($rootColors as $rootType => $rootData)
                                    @php $count = $rootCounts[$rootType] ?? 0; @endphp
                                    <div class="text-center">
                                        <div class="w-12 h-12 mx-auto rounded-xl {{ $rootData['bg'] }} {{ $rootData['border'] }} border flex items-center justify-center mb-2 shadow-sm">
                                            <span class="{{ $rootData['text'] }} font-black text-lg">{{ $count }}</span>
                                        </div>
                                        <div class="text-xs {{ $rootData['text'] }} font-bold leading-tight">{{ $rootData['name'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Meta Information -->
                        <div class="text-sm text-gray-600 mb-6 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-gray-700">تاريخ الإنشاء</span>
                                <span class="font-mono bg-white px-2 py-1 rounded-md border">{{ $quiz->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($quiz->has_submissions)
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-700">آخر نشاط</span>
                                <span class="text-green-600 font-bold">{{ $quiz->results->sortByDesc('created_at')->first()?->created_at?->diffForHumans() ?? 'لا يوجد' }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <div class="grid grid-cols-3 gap-3">
                                <a href="{{ route('quizzes.show', $quiz) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-2xl font-bold text-center transition-all duration-300 flex items-center justify-center gap-2 border border-gray-200 shadow-sm hover:shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    عرض
                                </a>
                                
                                @if($quiz->total_questions > 0)
                                <a href="{{ route('quizzes.edit', $quiz) }}" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-2xl font-bold text-center transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    تعديل
                                </a>
                                @else
                                <a href="{{ route('quizzes.questions.create', $quiz) }}" class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-2xl font-bold text-center transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    إضافة أسئلة
                                </a>
                                @endif
                                
                                <a href="{{ route('results.quiz', $quiz) }}" 
                                   class="bg-green-100 hover:bg-green-200 text-green-700 py-3 px-4 rounded-2xl font-bold text-center transition-all duration-300 flex items-center justify-center gap-2 border border-green-200 shadow-sm hover:shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    النتائج
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- List View -->
            <div id="list-view" style="display: none;" class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-lg overflow-hidden border border-gray-100">
                <!-- List Header -->
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <input type="checkbox" 
                                   id="select-all-list" 
                                   class="w-5 h-5 text-indigo-600 border-2 border-gray-300 rounded focus:ring-indigo-500" 
                                   onchange="toggleSelectAll()">
                            <label for="select-all-list" class="text-sm font-medium text-gray-700 cursor-pointer">تحديد الكل</label>
                        </div>
                        <span class="text-sm text-gray-500" id="list-count">{{ $quizzes->count() }} اختبار</span>
                    </div>
                </div>

                <!-- List Content -->
                <div class="divide-y divide-gray-100">
                    @foreach($quizzes as $quiz)
                    <div class="quiz-list-item p-6 hover:bg-gray-50 transition-colors duration-200"
                         data-quiz-id="{{ $quiz->id }}"
                         data-title="{{ strtolower($quiz->title) }}"
                         data-subject="{{ $quiz->subject ?? '' }}" 
                         data-grade="{{ $quiz->grade_level }}" 
                         data-status="{{ $quiz->is_active ? 'active' : 'inactive' }}{{ $quiz->has_submissions ? ' has_submissions' : '' }}{{ $quiz->total_questions == 0 ? ' empty' : '' }}"
                         data-has-submissions="{{ $quiz->has_submissions ? 'true' : 'false' }}">
                        
                        <div class="flex items-center justify-between">
                            <!-- Left Section -->
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <!-- Selection Checkbox -->
                                <input type="checkbox" 
                                       class="quiz-selector w-5 h-5 text-indigo-600 border-2 border-gray-300 rounded focus:ring-indigo-500" 
                                       data-quiz-id="{{ $quiz->id }}" 
                                       data-has-submissions="{{ $quiz->has_submissions ? 'true' : 'false' }}"
                                       onchange="updateSelection()">
                                
                                <!-- Quiz Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $quiz->title }}</h3>
                                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $quiz->pin }}</span>
                                        
                                        <!-- Status Badges -->
                                        <div class="flex gap-2">
                                            @if($quiz->total_questions == 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">بدون أسئلة</span>
                                            @endif
                                            @if(!$quiz->is_active)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">غير نشط</span>
                                            @endif
                                            @if($quiz->has_submissions)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">تم استخدامه</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Meta Information -->
                                    <div class="flex items-center gap-6 text-sm text-gray-500">
                                        <span>{{ $quiz->subject == 'arabic' ? 'اللغة العربية' : ($quiz->subject == 'english' ? 'اللغة الإنجليزية' : 'اللغة العبرية') }}</span>
                                        <span>الصف {{ $quiz->grade_level }}</span>
                                        <span class="{{ $quiz->total_questions == 0 ? 'text-red-600 font-medium' : 'text-green-600 font-medium' }}">{{ $quiz->total_questions }} {{ $quiz->total_questions == 1 ? 'سؤال' : 'أسئلة' }}</span>
                                        @if($quiz->results->count() > 0)
                                        <span class="text-blue-600">{{ $quiz->results->count() }} محاولة</span>
                                        @endif
                                        <span class="text-gray-400">{{ $quiz->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Section: Actions -->
                            <div class="flex items-center gap-3">
                                <a href="{{ route('quizzes.show', $quiz) }}" 
                                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                    عرض
                                </a>
                                
                                @if($quiz->total_questions > 0)
                                <a href="{{ route('quizzes.edit', $quiz) }}" 
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    تعديل
                                </a>
                                @else
                                <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    إضافة أسئلة
                                </a>
                                @endif
                                
                                <!-- 3-dots Menu -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" 
                                         class="absolute left-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                                        <button onclick="copyQuizPin('{{ $quiz->pin }}')" 
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            نسخ الرمز
                                        </button>
                                        @if(!$quiz->has_submissions)
                                        <button onclick="deleteQuiz({{ $quiz->id }}, '{{ addslashes($quiz->title) }}')" 
                                                class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                            حذف الاختبار
                                        </button>
                                        @else
                                        <div class="px-4 py-2 text-sm text-gray-500">محمي (به إجابات)</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- No Results Message -->
            <div id="no-results" style="display: none;" class="text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">لا توجد اختبارات</h3>
                <p class="text-gray-500">لم يتم العثور على اختبارات تطابق معايير البحث.</p>
            </div>
        @else
            <!-- Enhanced Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">لا توجد اختبارات بعد</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">ابدأ رحلة التعليم الرقمي بإنشاء أول اختبار بنموذج جُذور الأربعة</p>
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-2xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    إنشاء اختبار جديد
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Hidden Forms for Operations -->
<form id="bulk-delete-form" action="{{ route('quizzes.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="quiz_ids" id="selected-quiz-ids">
</form>

<form id="single-delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
// Application State
let currentView = 'grid';

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    updateQuizVisibility();
    
    // Load saved view preference
    const savedView = localStorage.getItem('quiz-view-preference') || 'grid';
    switchView(savedView);
    
    // Add initial animation
    animateQuizCards();
});

// Initialize filters
function initializeFilters() {
    const searchInput = document.getElementById('search-input');
    const subjectFilter = document.getElementById('subject-filter');
    const gradeFilter = document.getElementById('grade-filter');
    const statusFilter = document.getElementById('status-filter');
    
    // Add event listeners
    searchInput?.addEventListener('input', updateQuizVisibility);
    subjectFilter?.addEventListener('change', updateQuizVisibility);
    gradeFilter?.addEventListener('change', updateQuizVisibility);
    statusFilter?.addEventListener('change', updateQuizVisibility);
}

// View Management
function switchView(view) {
    currentView = view;
    
    const gridView = document.getElementById('quizzes-grid');
    const listView = document.getElementById('list-view');
    const gridBtn = document.getElementById('grid-view-btn');
    const listBtn = document.getElementById('list-view-btn');
    
    if (view === 'grid') {
        gridView.style.display = 'grid';
        listView.style.display = 'none';
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
    } else {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
    }
    
    // Save preference
    localStorage.setItem('quiz-view-preference', view);
    
    // Update visibility after view switch
    updateQuizVisibility();
    updateSelection();
}

// Filter Management
function updateQuizVisibility() {
    const searchTerm = document.getElementById('search-input')?.value.toLowerCase() || '';
    const selectedSubject = document.getElementById('subject-filter')?.value || '';
    const selectedGrade = document.getElementById('grade-filter')?.value || '';
    const selectedStatus = document.getElementById('status-filter')?.value || '';
    
    let visibleCount = 0;
    
    // Update both grid cards and list items
    const allItems = [
        ...document.querySelectorAll('.quiz-card'),
        ...document.querySelectorAll('.quiz-list-item')
    ];
    
    allItems.forEach(item => {
        const title = item.dataset.title || '';
        const subject = item.dataset.subject || '';
        const grade = item.dataset.grade || '';
        const status = item.dataset.status || '';
        
        let shouldShow = true;
        
        // Search filter
        if (searchTerm && !title.includes(searchTerm)) {
            shouldShow = false;
        }
        
        // Subject filter
        if (selectedSubject && subject !== selectedSubject) {
            shouldShow = false;
        }
        
        // Grade filter
        if (selectedGrade && grade !== selectedGrade) {
            shouldShow = false;
        }
        
        // Status filter
        if (selectedStatus) {
            if (selectedStatus === 'empty' && !status.includes('empty')) {
                shouldShow = false;
            } else if (selectedStatus !== 'empty' && !status.includes(selectedStatus)) {
                shouldShow = false;
            }
        }
        
        // Apply visibility with animation
        if (shouldShow) {
            item.style.display = item.classList.contains('quiz-card') ? 'block' : 'block';
            visibleCount++;
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 10);
        } else {
            item.style.opacity = '0';
            item.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                item.style.display = 'none';
            }, 300);
        }
    });
    
    // Update list count
    const listCount = document.getElementById('list-count');
    if (listCount) {
        listCount.textContent = `${visibleCount} اختبار`;
    }
    
    // Show/hide no results message
    const noResults = document.getElementById('no-results');
    if (noResults) {
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

// Selection Management
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('select-all-list');
    const isChecked = selectAllCheckbox?.checked || false;
    
    document.querySelectorAll('.quiz-selector').forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    
    updateSelection();
}

function updateSelection() {
    const checkboxes = document.querySelectorAll('.quiz-selector:checked');
    const count = checkboxes.length;
    const selectionActions = document.getElementById('selection-actions');
    const selectedCount = document.getElementById('selected-count');
    const selectAllList = document.getElementById('select-all-list');
    
    // Update selection actions visibility
    if (selectionActions) {
        selectionActions.style.display = count > 0 ? 'flex' : 'none';
    }
    
    if (selectedCount) {
        selectedCount.textContent = count;
    }
    
    // Update select all checkbox state
    const totalCheckboxes = document.querySelectorAll('.quiz-selector').length;
    if (selectAllList) {
        selectAllList.indeterminate = count > 0 && count < totalCheckboxes;
        selectAllList.checked = count === totalCheckboxes && count > 0;
    }
}

function clearSelection() {
    document.querySelectorAll('.quiz-selector').forEach(cb => {
        cb.checked = false;
    });
    updateSelection();
}

// Delete Functions
function deleteSelected() {
    const selectedCheckboxes = document.querySelectorAll('.quiz-selector:checked');
    
    if (selectedCheckboxes.length === 0) {
        alert('يرجى تحديد اختبارات للحذف.');
        return;
    }
    
    // Check if any selected quiz has submissions
    const hasSubmissions = Array.from(selectedCheckboxes).some(cb => 
        cb.dataset.hasSubmissions === 'true'
    );
    
    if (hasSubmissions) {
        alert('لا يمكن حذف الاختبارات التي تحتوي على إجابات طلاب.');
        return;
    }
    
    const count = selectedCheckboxes.length;
    if (confirm(`هل أنت متأكد من حذف ${count} اختبار؟ هذا الإجراء لا يمكن التراجع عنه.`)) {
        const quizIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.quizId);
        document.getElementById('selected-quiz-ids').value = JSON.stringify(quizIds);
        document.getElementById('bulk-delete-form').submit();
    }
}

function deleteQuiz(quizId, quizTitle) {
    const safeTitle = quizTitle.substring(0, 50);
    
    if (confirm(`هل أنت متأكد من حذف اختبار "${safeTitle}"؟ هذا الإجراء لا يمكن التراجع عنه.`)) {
        const form = document.getElementById('single-delete-form');
        form.action = `/quizzes/${quizId}`;
        form.submit();
    }
}

// Quick filter functions
function filterEmptyQuizzes() {
    const statusFilter = document.getElementById('status-filter');
    if (statusFilter) {
        statusFilter.value = 'empty';
        updateQuizVisibility();
    }
}

function clearFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('subject-filter').value = '';
    document.getElementById('grade-filter').value = '';
    document.getElementById('status-filter').value = '';
    updateQuizVisibility();
}

// Alert Management
function hideAlert() {
    const alert = document.getElementById('empty-quiz-alert');
    if (alert) {
        alert.style.transition = 'all 0.3s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            alert.style.display = 'none';
        }, 300);
    }
}

function confirmDeleteEmpty() {
    return confirm('هل أنت متأكد من حذف جميع الاختبارات الفارغة؟ هذا الإجراء لا يمكن التراجع عنه.');
}

// Utility Functions
function copyQuizPin(pin) {
    if (!pin) return;
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(pin).then(() => {
            showNotification(`تم نسخ الرمز: ${pin}`);
        }).catch(() => {
            alert(`رمز الاختبار: ${pin}`);
        });
    } else {
        alert(`رمز الاختبار: ${pin}`);
    }
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50';
    notification.innerHTML = `<strong>${message}</strong>`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// Add initial animation to quiz cards
function animateQuizCards() {
    const quizCards = document.querySelectorAll('.quiz-card');
    
    quizCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
        
        // Add hover effects
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}
</script>
@endpush

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Quiz Card Animations */
.quiz-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.quiz-card:hover {
    transform: translateY(-4px);
}

/* View Toggle Buttons */
.view-toggle-btn {
    color: #6b7280;
    background-color: transparent;
}

.view-toggle-btn.active {
    color: #4f46e5;
    background-color: #ffffff;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.view-toggle-btn:hover:not(.active) {
    color: #374151;
    background-color: rgba(255, 255, 255, 0.5);
}

/* List View Styles */
.quiz-list-item {
    transition: all 0.2s ease;
}

.quiz-list-item:hover {
    background-color: #f9fafb;
    transform: translateX(2px);
}

/* Selection checkbox improvements */
input[type="checkbox"]:indeterminate {
    background-color: #4f46e5;
    border-color: #4f46e5;
    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M4 8h8'/%3e%3c/svg%3e");
}

/* Mobile Responsive Adjustments */
@media (max-width: 768px) {
    .quiz-list-item .flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .quiz-list-item .flex .flex-1 {
        width: 100%;
    }
    
    .view-toggle-btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .view-toggle-btn svg {
        width: 1rem;
        height: 1rem;
    }
}

/* Custom scrollbar for modern look */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Loading animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}
</style>
@endpush