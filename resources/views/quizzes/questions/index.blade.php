@extends('layouts.app')

@section('title', 'إدارة الأسئلة - ' . $quiz->title)

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Animated Header -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden mb-8 transform hover:scale-[1.01] transition-all duration-300">
            <div class="relative bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 p-8">
                <!-- Animated Background Shapes -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                </div>
                
                <div class="relative flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-black text-white mb-3 flex items-center gap-3">
                            <i class="fas fa-question-circle opacity-80"></i>
                            إدارة الأسئلة
                        </h1>
                        <h2 class="text-2xl text-white/90 font-medium mb-4">{{ $quiz->title }}</h2>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <span class="bg-white/20 backdrop-blur px-4 py-2 rounded-full flex items-center gap-2">
                                <i class="fas fa-book"></i>
                                {{ $quiz->subject == 'arabic' ? 'اللغة العربية' : ($quiz->subject == 'english' ? 'اللغة الإنجليزية' : 'اللغة العبرية') }}
                            </span>
                            <span class="bg-white/20 backdrop-blur px-4 py-2 rounded-full flex items-center gap-2">
                                <i class="fas fa-graduation-cap"></i>
                                الصف {{ $quiz->grade_level }}
                            </span>
                            <span class="bg-white/20 backdrop-blur px-4 py-2 rounded-full flex items-center gap-2">
                                <i class="fas fa-list-ol"></i>
                                <span id="question-count">{{ $quiz->questions->count() }}</span> سؤال
                            </span>
                            <span class="bg-white/20 backdrop-blur px-4 py-2 rounded-full flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                {{ $quiz->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-3">
                        <button onclick="toggleViewMode()" class="bg-white/20 hover:bg-white/30 backdrop-blur text-white px-5 py-2.5 rounded-xl transition-all duration-200 flex items-center gap-2 group" data-tooltip="تبديل طريقة العرض">
                            <i class="fas fa-th-large group-hover:rotate-180 transition-transform duration-300"></i>
                            <span class="hidden md:inline">عرض</span>
                        </button>
                        
                        <button onclick="toggleBulkMode()" class="bg-white/20 hover:bg-white/30 backdrop-blur text-white px-5 py-2.5 rounded-xl transition-all duration-200 flex items-center gap-2 group" data-tooltip="تحديد متعدد">
                            <i class="fas fa-check-square group-hover:scale-110 transition-transform"></i>
                            <span class="hidden md:inline">تحديد</span>
                        </button>
                        
                        <a href="{{ route('quiz.take', $quiz) }}" class="bg-white text-purple-600 hover:bg-purple-50 px-5 py-2.5 rounded-xl transition-all duration-200 flex items-center gap-2 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-play"></i>
                            <span class="hidden md:inline">معاينة</span>
                        </a>
                        
                        <a href="{{ route('quizzes.questions.create', $quiz) }}" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white px-5 py-2.5 rounded-xl transition-all duration-200 flex items-center gap-2 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus-circle"></i>
                            <span class="hidden md:inline">إضافة</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Interactive Stats Bar -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                        $depthCounts = $quiz->questions->groupBy('depth_level')->map->count();
                    @endphp
                    
                    <!-- Root Stats with Hover Effects -->
                    <div class="group cursor-pointer" onclick="filterByRoot('jawhar')">
                        <div class="bg-white rounded-2xl p-4 shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 border-2 border-transparent hover:border-red-300">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-4xl group-hover:scale-110 transition-transform">🎯</span>
                                <span class="text-3xl font-black text-red-600">{{ $rootCounts['jawhar'] ?? 0 }}</span>
                            </div>
                            <div class="text-sm font-medium text-gray-600">جَوْهَر</div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-400 to-red-600 rounded-full transition-all duration-500" 
                                     style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts['jawhar'] ?? 0) / $quiz->questions->count() * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="group cursor-pointer" onclick="filterByRoot('zihn')">
                        <div class="bg-white rounded-2xl p-4 shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 border-2 border-transparent hover:border-cyan-300">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-4xl group-hover:scale-110 transition-transform">🧠</span>
                                <span class="text-3xl font-black text-cyan-600">{{ $rootCounts['zihn'] ?? 0 }}</span>
                            </div>
                            <div class="text-sm font-medium text-gray-600">ذِهْن</div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-cyan-400 to-cyan-600 rounded-full transition-all duration-500" 
                                     style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts['zihn'] ?? 0) / $quiz->questions->count() * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="group cursor-pointer" onclick="filterByRoot('waslat')">
                        <div class="bg-white rounded-2xl p-4 shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 border-2 border-transparent hover:border-yellow-300">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-4xl group-hover:scale-110 transition-transform">🔗</span>
                                <span class="text-3xl font-black text-yellow-600">{{ $rootCounts['waslat'] ?? 0 }}</span>
                            </div>
                            <div class="text-sm font-medium text-gray-600">وَصَلات</div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full transition-all duration-500" 
                                     style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts['waslat'] ?? 0) / $quiz->questions->count() * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="group cursor-pointer" onclick="filterByRoot('roaya')">
                        <div class="bg-white rounded-2xl p-4 shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 border-2 border-transparent hover:border-purple-300">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-4xl group-hover:scale-110 transition-transform">👁️</span>
                                <span class="text-3xl font-black text-purple-600">{{ $rootCounts['roaya'] ?? 0 }}</span>
                            </div>
                            <div class="text-sm font-medium text-gray-600">رُؤْيَة</div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-400 to-purple-600 rounded-full transition-all duration-500" 
                                     style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts['roaya'] ?? 0) / $quiz->questions->count() * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Bulk Actions Bar -->
        <div id="bulk-actions-bar" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl shadow-2xl p-4 hidden opacity-0 scale-95 transition-all duration-300 z-50">
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" id="select-all" onchange="toggleSelectAll()" class="w-5 h-5 rounded">
                    <span class="font-medium">تحديد الكل</span>
                </label>
                
                <div class="h-8 w-px bg-white/30"></div>
                
                <span class="font-medium">
                    <span id="selected-count" class="font-black text-xl">0</span> محدد
                </span>
                
                <div class="h-8 w-px bg-white/30"></div>
                
                <div class="flex gap-3">
                    <button onclick="bulkEdit()" class="bg-white/20 hover:bg-white/30 backdrop-blur px-4 py-2 rounded-lg transition-all flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        تعديل
                    </button>
                    
                    <button onclick="bulkDelete()" class="bg-red-500/80 hover:bg-red-600 backdrop-blur px-4 py-2 rounded-lg transition-all flex items-center gap-2">
                        <i class="fas fa-trash"></i>
                        حذف
                    </button>
                </div>
                
                <button onclick="closeBulkMode()" class="mr-4 hover:bg-white/20 p-2 rounded-lg transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Advanced Search and Filter Bar -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search Input with Autocomplete -->
                <div class="flex-1 relative">
                    <input type="text" 
                           id="search-input" 
                           placeholder="🔍 ابحث في الأسئلة..." 
                           class="w-full px-5 py-3 pr-12 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:bg-white transition-all duration-200"
                           oninput="searchQuestions(this.value)">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <kbd class="text-xs">Ctrl+K</kbd>
                    </div>
                </div>
                
                <!-- Filter Chips -->
                <div class="flex flex-wrap gap-3">
                    <select id="filter-root" onchange="applyFilters()" class="px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 transition-all">
                        <option value="">🌱 جميع الجذور</option>
                        <option value="jawhar">🎯 جَوْهَر</option>
                        <option value="zihn">🧠 ذِهْن</option>
                        <option value="waslat">🔗 وَصَلات</option>
                        <option value="roaya">👁️ رُؤْيَة</option>
                    </select>
                    
                    <select id="filter-depth" onchange="applyFilters()" class="px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-purple-500 transition-all">
                        <option value="">📊 جميع المستويات</option>
                        <option value="1">🟡 مستوى 1</option>
                        <option value="2">🟠 مستوى 2</option>
                        <option value="3">🟢 مستوى 3</option>
                    </select>
                    
                    <button onclick="resetFilters()" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all flex items-center gap-2">
                        <i class="fas fa-undo"></i>
                        إعادة تعيين
                    </button>
                </div>
            </div>
            
            <!-- Active Filters Display -->
            <div id="active-filters" class="mt-4 flex flex-wrap gap-2 hidden">
                <!-- Dynamic filter chips will appear here -->
            </div>
        </div>

        <!-- Reading Passage Section (Enhanced) -->
        @if($quiz->questions->where('passage', '!=', null)->first())
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-8 mb-8 border-2 border-blue-200 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-300 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-300 rounded-full blur-3xl"></div>
            </div>
            
            <div class="relative">
                <div class="flex items-start gap-4 mb-6">
                    <div class="bg-white rounded-2xl p-4 shadow-lg">
                        <i class="fas fa-file-alt text-3xl text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-blue-900 mb-2">
                            {{ $quiz->questions->first()->passage_title ?? 'نص القراءة' }}
                        </h3>
                        <p class="text-blue-700">هذا النص سيظهر للطلاب قبل البدء في الإجابة على الأسئلة</p>
                    </div>
                    <button onclick="editPassage()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-edit"></i>
                        تعديل النص
                    </button>
                </div>
                
                <div class="bg-white/80 backdrop-blur rounded-2xl p-6 shadow-inner" id="passage-content">
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($quiz->questions->first()->passage)) !!}
                    </div>
                </div>
                
                <!-- Passage Edit Mode -->
                <div id="passage-edit-mode" class="hidden">
                    <textarea id="passage-editor" class="w-full">{{ $quiz->questions->first()->passage }}</textarea>
                    <div class="mt-4 flex gap-3 justify-end">
                        <button onclick="savePassage()" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
                        </button>
                        <button onclick="cancelPassageEdit()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold transition-all">
                            إلغاء
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Questions Container with View Modes -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl p-8">
            <!-- Header with View Toggle -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-list-ul text-purple-600"></i>
                    قائمة الأسئلة
                </h3>
                
                <!-- View Mode Buttons -->
                <div class="flex gap-2 bg-gray-100 rounded-lg p-1">
                    <button onclick="setViewMode('list')" class="view-mode-btn active px-4 py-2 rounded-lg transition-all" data-mode="list">
                        <i class="fas fa-list"></i>
                    </button>
                    <button onclick="setViewMode('grid')" class="view-mode-btn px-4 py-2 rounded-lg transition-all" data-mode="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button onclick="setViewMode('compact')" class="view-mode-btn px-4 py-2 rounded-lg transition-all" data-mode="compact">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Questions List/Grid -->
            <div id="questions-container" class="space-y-4" data-view="list">
                @foreach($quiz->questions as $index => $question)
                <div class="question-item bg-gradient-to-r from-gray-50 to-white rounded-2xl border-2 border-gray-200 hover:border-purple-300 p-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1" 
                     data-root="{{ $question->root_type }}" 
                     data-depth="{{ $question->depth_level }}"
                     data-question-id="{{ $question->id }}"
                     data-index="{{ $index }}">
                    
                    <div class="flex gap-4">
                        <!-- Checkbox for Bulk Selection -->
                        <div class="bulk-checkbox hidden opacity-0 transform scale-75 transition-all duration-200">
                            <input type="checkbox" 
                                   class="w-5 h-5 rounded text-purple-600 focus:ring-purple-500" 
                                   value="{{ $question->id }}"
                                   onchange="updateSelectedCount()">
                        </div>
                        
                        <!-- Question Number -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        
                        <!-- Question Content -->
                        <div class="flex-1">
                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="px-4 py-1.5 rounded-full text-sm font-bold shadow-sm transform hover:scale-105 transition-transform cursor-pointer"
                                      style="background: {{ 
                                          $question->root_type == 'jawhar' ? 'linear-gradient(135deg, #ff6b6b, #ff8e8e)' : 
                                          ($question->root_type == 'zihn' ? 'linear-gradient(135deg, #4ecdc4, #6ee7de)' : 
                                          ($question->root_type == 'waslat' ? 'linear-gradient(135deg, #f7b731, #faca5f)' : 
                                          'linear-gradient(135deg, #5f27cd, #7c3aed)')) 
                                      }}; color: white;"
                                      onclick="filterByRoot('{{ $question->root_type }}')">
                                    @if($question->root_type == 'jawhar')
                                        <i class="fas fa-bullseye ml-1"></i> جَوْهَر
                                    @elseif($question->root_type == 'zihn')
                                        <i class="fas fa-brain ml-1"></i> ذِهْن
                                    @elseif($question->root_type == 'waslat')
                                        <i class="fas fa-link ml-1"></i> وَصَلات
                                    @else
                                        <i class="fas fa-eye ml-1"></i> رُؤْيَة
                                    @endif
                                </span>
                                
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ 
                                    $question->depth_level == 1 ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : 
                                    ($question->depth_level == 2 ? 'bg-orange-100 text-orange-800 border border-orange-300' : 
                                    'bg-green-100 text-green-800 border border-green-300') 
                                }} shadow-sm">
                                    <i class="fas fa-layer-group ml-1"></i>
                                    مستوى {{ $question->depth_level }}
                                </span>
                                
                                @if($question->passage)
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300 shadow-sm">
                                    <i class="fas fa-file-alt ml-1"></i>
                                    يحتوي على نص
                                </span>
                                @endif
                            </div>
                            
                            <!-- Question Text -->
                            <div class="question-display" id="question-display-{{ $question->id }}">
                                <p class="text-lg font-medium text-gray-800 mb-4 leading-relaxed">{!! $question->question !!}</p>
                            </div>
                            
                            <!-- Question Edit Mode -->
                            <div class="question-edit hidden" id="question-edit-{{ $question->id }}">
                                <textarea id="editor-{{ $question->id }}" class="question-editor">{{ $question->question }}</textarea>
                                <div class="mt-3 flex gap-2">
                                    <button onclick="saveQuestion({{ $question->id }})" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all flex items-center gap-2">
                                        <i class="fas fa-save"></i>
                                        حفظ
                                    </button>
                                    <button onclick="cancelEdit({{ $question->id }})" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
                                        إلغاء
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Answer Options -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                @foreach($question->options as $optionIndex => $option)
                                <div class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 {{ 
                                    $option == $question->correct_answer 
                                    ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 shadow-sm' 
                                    : 'bg-gray-50 border-2 border-gray-200 hover:border-gray-300' 
                                }}">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shadow-sm {{ 
                                        $option == $question->correct_answer 
                                        ? 'bg-gradient-to-br from-green-500 to-emerald-500 text-white' 
                                        : 'bg-white border-2 border-gray-300 text-gray-700' 
                                    }}">
                                        {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] }}
                                    </span>
                                    <span class="flex-1 {{ $option == $question->correct_answer ? 'font-bold text-green-800' : 'text-gray-700' }}">
                                        {{ $option }}
                                    </span>
                                    @if($option == $question->correct_answer)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex-shrink-0 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button onclick="editInline({{ $question->id }})" 
                                    class="w-10 h-10 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-all duration-200 flex items-center justify-center group/btn"
                                    data-tooltip="تعديل سريع">
                                <i class="fas fa-magic group-hover/btn:rotate-12 transition-transform"></i>
                            </button>
                            
                            <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" 
                               class="w-10 h-10 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-all duration-200 flex items-center justify-center"
                               data-tooltip="تعديل كامل">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <button onclick="duplicateQuestion({{ $question->id }})" 
                                    class="w-10 h-10 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-all duration-200 flex items-center justify-center"
                                    data-tooltip="نسخ السؤال">
                                <i class="fas fa-copy"></i>
                            </button>
                            
                            <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-10 h-10 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-all duration-200 flex items-center justify-center"
                                        onclick="return confirmDelete()"
                                        data-tooltip="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if($quiz->questions->count() == 0)
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                    <i class="fas fa-inbox text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">لا توجد أسئلة بعد</h3>
                <p class="text-gray-500 mb-6">ابدأ بإضافة أسئلة لهذا الاختبار</p>
                <a href="{{ route('quizzes.questions.create', $quiz) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-xl font-bold hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-plus-circle"></i>
                    إضافة أسئلة جديدة
                </a>
            </div>
            @endif

            <!-- Bottom Actions and Stats -->
            <div class="flex flex-col lg:flex-row justify-between items-center mt-8 pt-8 border-t-2 border-gray-200 gap-6">
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('quizzes.show', $quiz) }}" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white px-6 py-3 rounded-xl font-bold transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-check-circle"></i>
                        انتهيت من التعديل
                    </a>
                    
                    <a href="{{ route('quiz.take', $quiz) }}" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-3 rounded-xl font-bold transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-play"></i>
                        تجربة الاختبار
                    </a>
                    
                    <a href="{{ route('quizzes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
                
                <!-- Juzoor Chart Preview -->
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 shadow-inner">
                    <h4 class="text-sm font-medium text-gray-600 mb-3 text-center">توزيع الجذور</h4>
                    <x-juzoor-chart :scores="[
                        'jawhar' => ($rootCounts['jawhar'] ?? 0) / max($quiz->questions->count(), 1) * 100,
                        'zihn' => ($rootCounts['zihn'] ?? 0) / max($quiz->questions->count(), 1) * 100,
                        'waslat' => ($rootCounts['waslat'] ?? 0) / max($quiz->questions->count(), 1) * 100,
                        'roaya' => ($rootCounts['roaya'] ?? 0) / max($quiz->questions->count(), 1) * 100
                    ]" size="small" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keyboard Shortcuts Modal -->
<div id="shortcuts-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50" onclick="closeShortcutsModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform scale-95 opacity-0 transition-all duration-300" onclick="event.stopPropagation()">
            <h3 class="text-2xl font-bold mb-6 text-center gradient-text">اختصارات لوحة المفاتيح</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">البحث السريع</span>
                    <kbd>Ctrl + K</kbd>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">تحديد الكل</span>
                    <kbd>Ctrl + A</kbd>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">حذف المحدد</span>
                    <kbd>Delete</kbd>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">إضافة سؤال</span>
                    <kbd>Ctrl + N</kbd>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">معاينة الاختبار</span>
                    <kbd>Ctrl + P</kbd>
                </div>
            </div>
            
            <button onclick="closeShortcutsModal()" class="mt-6 w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-xl font-bold hover:shadow-xl transition-all">
                فهمت
            </button>
        </div>
    </div>
</div>

<!-- Floating Help Button -->
<button onclick="openShortcutsModal()" class="fixed bottom-8 left-8 w-14 h-14 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all duration-200 flex items-center justify-center group z-40">
    <i class="fas fa-keyboard"></i>
    <span class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-black text-white text-sm px-3 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
        اختصارات لوحة المفاتيح
    </span>
</button>
@endsection

@push('styles')
<style>
    /* View Mode Styles */
    .view-mode-btn {
        color: #6b7280;
    }
    
    .view-mode-btn.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    /* Grid View */
    #questions-container[data-view="grid"] {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 1.5rem;
    }
    
    #questions-container[data-view="grid"] .question-item {
        height: 100%;
    }
    
    /* Compact View */
    #questions-container[data-view="compact"] .question-item {
        padding: 1rem;
    }
    
    #questions-container[data-view="compact"] .question-display p {
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    
    #questions-container[data-view="compact"] .grid {
        display: none;
    }
    
    /* Bulk Mode Active */
    .bulk-mode-active .bulk-checkbox {
        display: block !important;
        opacity: 1 !important;
        transform: scale(1) !important;
    }
    
    .bulk-mode-active #bulk-actions-bar {
        display: block !important;
        opacity: 1 !important;
        transform: scale(1) !important;
    }
    
    /* Search Highlight */
    .search-highlight {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        padding: 2px 4px;
        border-radius: 4px;
        font-weight: 600;
    }
    
    /* Question Hover Effects */
    .question-item {
        position: relative;
        overflow: hidden;
    }
    
    .question-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }
    
    .question-item:hover::before {
        left: 100%;
    }
    
    /* Smooth Transitions */
    .question-item,
    .question-item * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endpush

@push('scripts')
<!-- TinyMCE with your API key -->
<script src="https://cdn.tiny.cloud/1/cmtwmtmif3u7ducaiqvogvq1wvc280ugtxjzo2ffaymjmuxg/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
// Global Variables
let bulkMode = false;
let viewMode = 'list';
let activeFilters = {
    root: null,
    depth: null,
    search: null
};

// Initialize TinyMCE
function initTinyMCE(selector, options = {}) {
    const defaultOptions = {
        selector: selector,
        language: 'ar',
        directionality: 'rtl',
        height: 300,
        menubar: false,
        plugins: 'lists link charmap preview searchreplace autolink directionality code fullscreen table emoticons',
        toolbar: 'undo redo | bold italic underline strikethrough | bullist numlist | link | alignleft aligncenter alignright alignjustify | removeformat | preview code emoticons',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
        branding: false,
        promotion: false,
        entity_encoding: 'raw',
        setup: function(editor) {
            editor.on('init', function() {
                editor.focus();
            });
        }
    };
    
    return tinymce.init({ ...defaultOptions, ...options });
}

// View Mode Functions
function toggleViewMode() {
    const modes = ['list', 'grid', 'compact'];
    const currentIndex = modes.indexOf(viewMode);
    const nextIndex = (currentIndex + 1) % modes.length;
    setViewMode(modes[nextIndex]);
}

function setViewMode(mode) {
    viewMode = mode;
    const container = document.getElementById('questions-container');
    container.setAttribute('data-view', mode);
    
    document.querySelectorAll('.view-mode-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-mode') === mode);
    });
    
    // Save preference
    localStorage.setItem('questionsViewMode', mode);
}

// Bulk Selection Functions
function toggleBulkMode() {
    bulkMode = !bulkMode;
    document.body.classList.toggle('bulk-mode-active', bulkMode);
    
    if (!bulkMode) {
        // Clear all selections when exiting bulk mode
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

// Search and Filter Functions
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
    
    activeFilters.root = rootFilter || null;
    activeFilters.depth = depthFilter || null;
    activeFilters.search = searchQuery || null;
    
    let visibleCount = 0;
    
    document.querySelectorAll('.question-item').forEach(item => {
        const matchRoot = !rootFilter || item.dataset.root === rootFilter;
        const matchDepth = !depthFilter || item.dataset.depth === depthFilter;
        const questionText = item.querySelector('.question-display').textContent.toLowerCase();
        const matchSearch = !searchQuery || questionText.includes(searchQuery);
        
        const isVisible = matchRoot && matchDepth && matchSearch;
        item.style.display = isVisible ? '' : 'none';
        
        if (isVisible) {
            visibleCount++;
            
            // Highlight search terms
            if (searchQuery) {
                highlightSearchTerms(item, searchQuery);
            } else {
                removeHighlights(item);
            }
        }
    });
    
    // Update question count
    document.getElementById('question-count').textContent = visibleCount;
    
    // Show/hide active filters
    updateActiveFiltersDisplay();
}

function highlightSearchTerms(element, query) {
    const questionText = element.querySelector('.question-display p');
    const originalText = questionText.textContent;
    const regex = new RegExp(`(${query})`, 'gi');
    questionText.innerHTML = originalText.replace(regex, '<span class="search-highlight">$1</span>');
}

function removeHighlights(element) {
    const questionText = element.querySelector('.question-display p');
    questionText.innerHTML = questionText.textContent;
}

function updateActiveFiltersDisplay() {
    const container = document.getElementById('active-filters');
    container.innerHTML = '';
    
    let hasFilters = false;
    
    if (activeFilters.root) {
        hasFilters = true;
        addFilterChip('root', activeFilters.root);
    }
    
    if (activeFilters.depth) {
        hasFilters = true;
        addFilterChip('depth', `مستوى ${activeFilters.depth}`);
    }
    
    if (activeFilters.search) {
        hasFilters = true;
        addFilterChip('search', activeFilters.search);
    }
    
    container.classList.toggle('hidden', !hasFilters);
}

function addFilterChip(type, value) {
    const chip = document.createElement('span');
    chip.className = 'inline-flex items-center gap-2 px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium';
    chip.innerHTML = `
        ${value}
        <button onclick="removeFilter('${type}')" class="hover:text-purple-900">
            <i class="fas fa-times"></i>
        </button>
    `;
    document.getElementById('active-filters').appendChild(chip);
}

function removeFilter(type) {
    activeFilters[type] = null;
    
    if (type === 'root') {
        document.getElementById('filter-root').value = '';
    } else if (type === 'depth') {
        document.getElementById('filter-depth').value = '';
    } else if (type === 'search') {
        document.getElementById('search-input').value = '';
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

// Question Editing Functions
function editInline(questionId) {
    // Close all other editors
    document.querySelectorAll('.question-edit').forEach(edit => {
        const id = edit.id.replace('question-edit-', '');
        if (id !== questionId.toString()) {
            cancelEdit(id);
        }
    });
    
    const displayEl = document.getElementById(`question-display-${questionId}`);
    const editEl = document.getElementById(`question-edit-${questionId}`);
    
    displayEl.classList.add('hidden');
    editEl.classList.remove('hidden');
    
    // Initialize TinyMCE for this specific editor
    initTinyMCE(`#editor-${questionId}`, {
        height: 250,
        setup: function(editor) {
            editor.on('init', function() {
                editor.focus();
            });
        }
    });
}

function cancelEdit(questionId) {
    const displayEl = document.getElementById(`question-display-${questionId}`);
    const editEl = document.getElementById(`question-edit-${questionId}`);
    
    if (displayEl && editEl) {
        displayEl.classList.remove('hidden');
        editEl.classList.add('hidden');
        
        // Remove TinyMCE instance
        tinymce.remove(`#editor-${questionId}`);
    }
}

async function saveQuestion(questionId) {
    const editor = tinymce.get(`editor-${questionId}`);
    if (!editor) return;
    
    const content = editor.getContent();
    const saveBtn = event.target;
    
    // Show loading state
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري الحفظ...';
    
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
            // Update display content
            document.querySelector(`#question-display-${questionId} p`).innerHTML = content;
            
            // Close editor
            cancelEdit(questionId);
            
            // Show success notification
            showNotification('تم حفظ السؤال بنجاح', 'success');
        } else {
            throw new Error('Failed to save');
        }
    } catch (error) {
        console.error('Save error:', error);
        showNotification('حدث خطأ في الحفظ', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save ml-2"></i> حفظ';
    }
}

// Passage Editing Functions
function editPassage() {
    document.getElementById('passage-content').classList.add('hidden');
    document.getElementById('passage-edit-mode').classList.remove('hidden');
    
    initTinyMCE('#passage-editor', {
        height: 400,
        plugins: 'lists link charmap preview searchreplace autolink directionality code fullscreen table emoticons image media',
        toolbar: 'undo redo | bold italic underline strikethrough | bullist numlist | link image media | alignleft aligncenter alignright alignjustify | removeformat | preview code fullscreen'
    });
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
    
    try {
        const response = await fetch(`/quizzes/{{ $quiz->id }}/questions/${firstQuestionId}/update-passage`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ passage: content })
        });
        
        if (response.ok) {
            document.querySelector('#passage-content .prose').innerHTML = content;
            cancelPassageEdit();
            showNotification('تم حفظ النص بنجاح', 'success');
        } else {
            throw new Error('Failed to save');
        }
    } catch (error) {
        console.error('Save passage error:', error);
        showNotification('حدث خطأ في حفظ النص', 'error');
    }
}

// Bulk Operations
async function bulkDelete() {
    const selected = Array.from(document.querySelectorAll('.bulk-checkbox input:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    if (!confirm(`هل أنت متأكد من حذف ${selected.length} سؤال؟`)) return;
    
    try {
        const response = await fetch(`/quizzes/{{ $quiz->id }}/questions/bulk-delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: selected })
        });
        
        if (response.ok) {
            const result = await response.json();
            showNotification(result.message || 'تم حذف الأسئلة بنجاح', 'success');
            
            // Remove deleted items with animation
            selected.forEach(id => {
                const item = document.querySelector(`[data-question-id="${id}"]`);
                if (item) {
                    item.style.transform = 'translateX(-100%)';
                    item.style.opacity = '0';
                    setTimeout(() => item.remove(), 300);
                }
            });
            
            // Update counts
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error('Failed to delete');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showNotification('حدث خطأ في حذف الأسئلة', 'error');
    }
}

function bulkEdit() {
    const selected = Array.from(document.querySelectorAll('.bulk-checkbox input:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        showNotification('الرجاء تحديد أسئلة للتعديل', 'warning');
        return;
    }
    
    // Open bulk edit modal (to be implemented)
    showNotification('ميزة التعديل الجماعي قيد التطوير', 'info');
}

// Utility Functions
function showNotification(message, type = 'success') {
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-pink-500',
        warning: 'from-yellow-500 to-orange-500',
        info: 'from-blue-500 to-cyan-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = `toast show bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3`;
    notification.innerHTML = `
        <i class="fas ${icons[type]} text-2xl"></i>
        <p class="font-medium">${message}</p>
        <button onclick="this.parentElement.remove()" class="mr-auto hover:bg-white/20 p-1 rounded">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

function confirmDelete() {
    return confirm('هل أنت متأكد من حذف هذا السؤال؟ لا يمكن التراجع عن هذا الإجراء.');
}

function duplicateQuestion(questionId) {
    // To be implemented
    showNotification('ميزة نسخ السؤال قيد التطوير', 'info');
}

// Keyboard Shortcuts
function openShortcutsModal() {
    const modal = document.getElementById('shortcuts-modal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').style.transform = 'scale(1)';
        modal.querySelector('.bg-white').style.opacity = '1';
    }, 10);
}

function closeShortcutsModal() {
    const modal = document.getElementById('shortcuts-modal');
    modal.querySelector('.bg-white').style.transform = 'scale(0.95)';
    modal.querySelector('.bg-white').style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Keyboard Event Handlers
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('search-input').focus();
    }
    
    // Ctrl/Cmd + A for select all (in bulk mode)
    if ((e.ctrlKey || e.metaKey) && e.key === 'a' && bulkMode) {
        e.preventDefault();
        document.getElementById('select-all').click();
    }
    
    // Delete for bulk delete
    if (e.key === 'Delete' && bulkMode) {
        const selected = document.querySelectorAll('.bulk-checkbox input:checked').length;
        if (selected > 0) {
            bulkDelete();
        }
    }
    
    // Ctrl/Cmd + N for new question
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        window.location.href = '{{ route("quizzes.questions.create", $quiz) }}';
    }
    
    // Ctrl/Cmd + P for preview
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        window.location.href = '{{ route("quiz.take", $quiz) }}';
    }
    
    // Escape to close modals/editors
    if (e.key === 'Escape') {
        // Close shortcuts modal
        if (!document.getElementById('shortcuts-modal').classList.contains('hidden')) {
            closeShortcutsModal();
        }
        
        // Close any open editors
        document.querySelectorAll('.question-edit:not(.hidden)').forEach(edit => {
            const id = edit.id.replace('question-edit-', '');
            cancelEdit(id);
        });
        
        // Exit bulk mode
        if (bulkMode) {
            closeBulkMode();
        }
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Restore view mode preference
    const savedViewMode = localStorage.getItem('questionsViewMode');
    if (savedViewMode) {
        setViewMode(savedViewMode);
    }
    
    // Initialize tooltips (you can use a library like Tippy.js for better tooltips)
    document.querySelectorAll('[data-tooltip]').forEach(el => {
        el.classList.add('tooltip');
    });
    
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});

// Clean up on page unload
window.addEventListener('beforeunload', function() {
    tinymce.remove();
});
</script>
@endpush