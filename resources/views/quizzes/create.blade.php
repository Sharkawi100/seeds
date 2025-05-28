@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-bold text-gray-800">إنشاء اختبار جديد</h1>
                <button type="button" onclick="window.location.href='{{ route('quizzes.index') }}'" 
                        class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Step Indicators -->
            <div class="relative">
                <div class="absolute top-5 right-0 left-0 h-0.5 bg-gray-200"></div>
                <div class="absolute top-5 right-0 h-0.5 bg-indigo-600 transition-all duration-500" id="progress-bar" style="width: 25%"></div>
                
                <div class="relative flex justify-between">
                    <div class="step-indicator active" data-step="1">
                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold transition-all">
                            1
                        </div>
                        <span class="absolute top-12 right-1/2 translate-x-1/2 text-xs text-gray-600 whitespace-nowrap">المعلومات الأساسية</span>
                    </div>
                    <div class="step-indicator" data-step="2">
                        <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center text-sm font-bold transition-all">
                            2
                        </div>
                        <span class="absolute top-12 right-1/2 translate-x-1/2 text-xs text-gray-600 whitespace-nowrap">نص القراءة</span>
                    </div>
                    <div class="step-indicator" data-step="3">
                        <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center text-sm font-bold transition-all">
                            3
                        </div>
                        <span class="absolute top-12 right-1/2 translate-x-1/2 text-xs text-gray-600 whitespace-nowrap">إعدادات الأسئلة</span>
                    </div>
                    <div class="step-indicator" data-step="4">
                        <div class="w-10 h-10 bg-gray-300 text-white rounded-full flex items-center justify-center text-sm font-bold transition-all">
                            4
                        </div>
                        <span class="absolute top-12 right-1/2 translate-x-1/2 text-xs text-gray-600 whitespace-nowrap">مراجعة وإنشاء</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <form id="quiz-form" action="{{ route('quizzes.store') }}" method="POST" class="bg-white rounded-2xl shadow-xl overflow-hidden">
            @csrf
            
            <!-- Step 1: Basic Information -->
            <div class="step-content" id="step-1">
                <div class="p-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">المعلومات الأساسية</h2>
                        <p class="text-gray-600">أدخل المعلومات الأساسية للاختبار</p>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Quiz Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                عنوان الاختبار <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="quiz-title"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="مثال: اختبار الوحدة الأولى - قواعد اللغة العربية"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">اختر عنواناً واضحاً يصف محتوى الاختبار</p>
                        </div>
                        
                        <!-- Subject and Grade -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Subject -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    المادة الدراسية <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="subject" 
                                            id="quiz-subject"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                            required>
                                        <option value="">اختر المادة</option>
                                        <option value="arabic">اللغة العربية</option>
                                        <option value="english">اللغة الإنجليزية</option>
                                        <option value="hebrew">اللغة العبرية</option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Grade Level -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    الصف الدراسي <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="grade_level" 
                                            id="quiz-grade"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                            required>
                                        <option value="">اختر الصف</option>
                                        @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">الصف {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Topic -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                موضوع الاختبار <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="topic" 
                                   id="quiz-topic"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                   placeholder="مثال: الأفعال الماضية، قواعد الإملاء، فهم المقروء..."
                                   required>
                            <p class="mt-1 text-sm text-gray-500">حدد الموضوع الذي سيغطيه الاختبار</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-8 py-4 bg-gray-50 flex justify-end">
                    <button type="button" onclick="nextStep()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        التالي
                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Step 2: Reading Passage -->
            <div class="step-content hidden" id="step-2">
                <div class="p-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">نص القراءة (اختياري)</h2>
                        <p class="text-gray-600">يمكنك إضافة نص قراءة لبناء الأسئلة عليه</p>
                    </div>
                    
                    <!-- Passage Toggle -->
                    <div class="mb-8">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   id="has-passage" 
                                   name="has_passage"
                                   class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 transition-all"
                                   onchange="togglePassageOptions()">
                            <span class="text-lg font-medium text-gray-700">أريد إضافة نص قراءة</span>
                        </label>
                    </div>
                    
                    <!-- Passage Options -->
                    <div id="passage-options" class="hidden space-y-6">
                        <!-- Passage Method Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button type="button" 
                                    onclick="selectPassageMethod('write')"
                                    class="passage-method-btn p-6 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-lg transition-all"
                                    data-method="write">
                                <div class="text-4xl mb-3">✍️</div>
                                <h3 class="font-bold text-gray-800 mb-1">كتابة يدوية</h3>
                                <p class="text-sm text-gray-600">اكتب النص بنفسك</p>
                            </button>
                            
                            <button type="button" 
                                    onclick="selectPassageMethod('ai')"
                                    class="passage-method-btn p-6 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-lg transition-all"
                                    data-method="ai">
                                <div class="text-4xl mb-3">🤖</div>
                                <h3 class="font-bold text-gray-800 mb-1">توليد بالذكاء الاصطناعي</h3>
                                <p class="text-sm text-gray-600">دع الذكاء الاصطناعي يولد النص</p>
                            </button>
                            
                            <button type="button" 
                                    onclick="selectPassageMethod('upload')"
                                    class="passage-method-btn p-6 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-lg transition-all"
                                    data-method="upload">
                                <div class="text-4xl mb-3">📄</div>
                                <h3 class="font-bold text-gray-800 mb-1">لصق نص جاهز</h3>
                                <p class="text-sm text-gray-600">الصق نصاً من مصدر آخر</p>
                            </button>
                        </div>
                        
                        <input type="hidden" name="passage_method" id="passage-method">
                        
                        <!-- Manual Writing -->
                        <div id="passage-write" class="hidden space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">عنوان النص</label>
                                <input type="text" 
                                       name="manual_passage_title"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="أدخل عنواناً مناسباً للنص">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نص القراءة</label>
                                <textarea name="manual_passage_content"
                                          rows="8"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                          placeholder="اكتب نص القراءة هنا..."></textarea>
                                <p class="mt-1 text-sm text-gray-500">اكتب نصاً مناسباً لمستوى الطلاب (150-300 كلمة)</p>
                            </div>
                        </div>
                        
                        <!-- AI Generation -->
                        <div id="passage-ai" class="hidden space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">موضوع النص (اختياري)</label>
                                <input type="text" 
                                       name="ai_passage_topic"
                                       id="ai-passage-topic"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="اترك فارغاً لاستخدام موضوع الاختبار">
                            </div>
                            
                            <button type="button" 
                                    onclick="generatePassage()"
                                    class="w-full py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 animate-spin hidden" id="generate-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>توليد النص</span>
                            </button>
                            
                            <!-- Generated Passage Preview -->
                            <div id="generated-passage" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                    <h4 class="font-bold text-gray-800 mb-2" id="generated-title"></h4>
                                    <div class="text-gray-700 whitespace-pre-wrap" id="generated-content"></div>
                                </div>
                                <div class="mt-4 flex gap-3">
                                    <button type="button" onclick="acceptGeneratedPassage()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        قبول النص
                                    </button>
                                    <button type="button" onclick="regeneratePassage()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                        إعادة التوليد
                                    </button>
                                    <button type="button" onclick="editGeneratedPassage()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        تعديل النص
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Edit Generated Passage -->
                            <div id="edit-generated" class="hidden space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">عنوان النص</label>
                                    <input type="text" 
                                           id="edit-passage-title"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نص القراءة</label>
                                    <textarea id="edit-passage-content"
                                              rows="8"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                                </div>
                                <button type="button" onclick="saveEditedPassage()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    حفظ التعديلات
                                </button>
                            </div>
                        </div>
                        
                        <!-- Upload/Paste -->
                        <div id="passage-upload" class="hidden space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">عنوان النص</label>
                                <input type="text" 
                                       name="upload_passage_title"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="أدخل عنواناً للنص">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الصق النص هنا</label>
                                <textarea name="upload_passage_content"
                                          rows="8"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                          placeholder="الصق نص القراءة هنا..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden fields for final passage -->
                    <input type="hidden" name="final_passage_title" id="final-passage-title">
                    <input type="hidden" name="final_passage_content" id="final-passage-content">
                </div>
                
                <div class="px-8 py-4 bg-gray-50 flex justify-between">
                    <button type="button" onclick="previousStep()" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        <svg class="inline-block w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        السابق
                    </button>
                    <button type="button" onclick="nextStep()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        التالي
                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Step 3: Question Settings -->
            <div class="step-content hidden" id="step-3">
                <div class="p-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">إعدادات الأسئلة</h2>
                        <p class="text-gray-600">حدد كيفية إنشاء الأسئلة</p>
                    </div>
                    
                    <!-- Question Creation Method -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-4">طريقة إنشاء الأسئلة</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button type="button"
                                    onclick="selectQuestionMethod('ai')"
                                    class="question-method-btn p-6 border-2 border-indigo-500 rounded-xl hover:shadow-lg transition-all bg-indigo-50"
                                    data-method="ai">
                                <div class="text-4xl mb-3">🤖</div>
                                <h3 class="font-bold text-gray-800 mb-1">توليد بالذكاء الاصطناعي</h3>
                                <p class="text-sm text-gray-600">دع الذكاء الاصطناعي يولد الأسئلة</p>
                            </button>
                            
                            <button type="button"
                                    onclick="selectQuestionMethod('manual')"
                                    class="question-method-btn p-6 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-lg transition-all"
                                    data-method="manual">
                                <div class="text-4xl mb-3">✍️</div>
                                <h3 class="font-bold text-gray-800 mb-1">إضافة يدوية</h3>
                                <p class="text-sm text-gray-600">أضف الأسئلة بنفسك</p>
                            </button>
                        </div>
                        <input type="hidden" name="creation_method" id="creation-method" value="ai">
                    </div>
                    
                    <!-- AI Settings -->
                    <div id="ai-settings" class="space-y-6">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">توزيع الأسئلة حسب نموذج جُذور</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Jawhar -->
                                <div class="bg-white rounded-lg p-4 border border-red-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">🎯</span>
                                            <h4 class="font-bold text-gray-800">جَوْهَر</h4>
                                        </div>
                                        <span class="text-sm text-gray-600">ما هو؟</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 1 (سطحي)</label>
                                            <input type="number" name="roots[jawhar][levels][0][count]" min="0" max="10" value="1" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="1" data-depth="1">
                                            <input type="hidden" name="roots[jawhar][levels][0][depth]" value="1">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 2 (متوسط)</label>
                                            <input type="number" name="roots[jawhar][levels][1][count]" min="0" max="10" value="1" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="2" data-depth="2">
                                            <input type="hidden" name="roots[jawhar][levels][1][depth]" value="2">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 3 (عميق)</label>
                                            <input type="number" name="roots[jawhar][levels][2][count]" min="0" max="10" value="0" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="3" data-depth="3">
                                            <input type="hidden" name="roots[jawhar][levels][2][depth]" value="3">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Zihn -->
                                <div class="bg-white rounded-lg p-4 border border-cyan-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">🧠</span>
                                            <h4 class="font-bold text-gray-800">ذِهْن</h4>
                                        </div>
                                        <span class="text-sm text-gray-600">كيف يعمل؟</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 1 (سطحي)</label>
                                            <input type="number" name="roots[zihn][levels][0][count]" min="0" max="10" value="1" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="1" data-depth="1">
                                            <input type="hidden" name="roots[zihn][levels][0][depth]" value="1">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 2 (متوسط)</label>
                                            <input type="number" name="roots[zihn][levels][1][count]" min="0" max="10" value="1" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="2" data-depth="2">
                                            <input type="hidden" name="roots[zihn][levels][1][depth]" value="2">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 3 (عميق)</label>
                                            <input type="number" name="roots[zihn][levels][2][count]" min="0" max="10" value="0" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="3" data-depth="3">
                                            <input type="hidden" name="roots[zihn][levels][2][depth]" value="3">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Waslat -->
                                <div class="bg-white rounded-lg p-4 border border-yellow-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">🔗</span>
                                            <h4 class="font-bold text-gray-800">وَصَلات</h4>
                                        </div>
                                        <span class="text-sm text-gray-600">كيف يرتبط؟</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 1 (سطحي)</label>
                                            <input type="number" name="roots[waslat][levels][0][count]" min="0" max="10" value="1" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="1" data-depth="1">
                                            <input type="hidden" name="roots[waslat][levels][0][depth]" value="1">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 2 (متوسط)</label>
                                            <input type="number" name="roots[waslat][levels][1][count]" min="0" max="10" value="1" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="2" data-depth="2">
                                            <input type="hidden" name="roots[waslat][levels][1][depth]" value="2">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 3 (عميق)</label>
                                            <input type="number" name="roots[waslat][levels][2][count]" min="0" max="10" value="0" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="3" data-depth="3">
                                            <input type="hidden" name="roots[waslat][levels][2][depth]" value="3">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Roaya -->
                                <div class="bg-white rounded-lg p-4 border border-purple-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">👁️</span>
                                            <h4 class="font-bold text-gray-800">رُؤْيَة</h4>
                                        </div>
                                        <span class="text-sm text-gray-600">كيف نستخدمه؟</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 1 (سطحي)</label>
                                            <input type="number" name="roots[roaya][levels][0][count]" min="0" max="10" value="1" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="1" data-depth="1">
                                            <input type="hidden" name="roots[roaya][levels][0][depth]" value="1">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 2 (متوسط)</label>
                                            <input type="number" name="roots[roaya][levels][1][count]" min="0" max="10" value="1" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="2" data-depth="2">
                                            <input type="hidden" name="roots[roaya][levels][1][depth]" value="2">
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm text-gray-600">مستوى 3 (عميق)</label>
                                            <input type="number" name="roots[roaya][levels][2][count]" min="0" max="10" value="0" 
                                                   class="w-16 px-2 py-1 border rounded text-center" data-level="3" data-depth="3">
                                            <input type="hidden" name="roots[roaya][levels][2][depth]" value="3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-600">إجمالي الأسئلة: <span id="total-questions" class="font-bold text-indigo-600">8</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-8 py-4 bg-gray-50 flex justify-between">
                    <button type="button" onclick="previousStep()" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        <svg class="inline-block w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        السابق
                    </button>
                    <button type="button" onclick="nextStep()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        التالي
                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Step 4: Review and Create -->
            <div class="step-content hidden" id="step-4">
                <div class="p-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">مراجعة وإنشاء</h2>
                        <p class="text-gray-600">راجع إعدادات الاختبار قبل الإنشاء</p>
                    </div>
                    
                    <!-- Review Summary -->
                    <div class="space-y-6">
                        <!-- Basic Info Review -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-bold text-gray-800 mb-4">المعلومات الأساسية</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm text-gray-600">عنوان الاختبار</dt>
                                    <dd class="font-medium text-gray-800" id="review-title">-</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">المادة</dt>
                                    <dd class="font-medium text-gray-800" id="review-subject">-</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">الصف</dt>
                                    <dd class="font-medium text-gray-800" id="review-grade">-</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">الموضوع</dt>
                                    <dd class="font-medium text-gray-800" id="review-topic">-</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <!-- Passage Review -->
                        <div id="review-passage-section" class="bg-blue-50 rounded-lg p-6 hidden">
                            <h3 class="font-bold text-gray-800 mb-4">نص القراءة</h3>
                            <div id="review-passage-content" class="text-gray-700"></div>
                        </div>
                        
                        <!-- Questions Review -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-bold text-gray-800 mb-4">إعدادات الأسئلة</h3>
                            <div id="review-questions" class="text-gray-700"></div>
                        </div>
                    </div>
                </div>
                
                <div class="px-8 py-4 bg-gray-50 flex justify-between">
                    <button type="button" onclick="previousStep()" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        <svg class="inline-block w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        السابق
                    </button>
                    <button type="submit" id="submit-btn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5 animate-spin hidden" id="submit-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>إنشاء الاختبار</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Step Management
let currentStep = 1;
const totalSteps = 4;

function updateStepUI(step) {
    // Update progress bar
    const progress = (step / totalSteps) * 100;
    document.getElementById('progress-bar').style.width = progress + '%';
    
    // Update step indicators
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        const stepNum = index + 1;
        const indicatorDiv = indicator.querySelector('div');
        
        if (stepNum <= step) {
            indicatorDiv.classList.add('bg-indigo-600');
            indicatorDiv.classList.remove('bg-gray-300');
        } else {
            indicatorDiv.classList.remove('bg-indigo-600');
            indicatorDiv.classList.add('bg-gray-300');
        }
        
        indicator.classList.toggle('active', stepNum === step);
    });
    
    // Show/hide step content
    document.querySelectorAll('.step-content').forEach((content, index) => {
        content.classList.toggle('hidden', index + 1 !== step);
    });
}

function nextStep() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepUI(currentStep);
            
            // Update review if going to last step
            if (currentStep === 4) {
                updateReview();
            }
        }
    }
}

function previousStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepUI(currentStep);
    }
}

function validateCurrentStep() {
    switch(currentStep) {
        case 1:
            const title = document.getElementById('quiz-title').value;
            const subject = document.getElementById('quiz-subject').value;
            const grade = document.getElementById('quiz-grade').value;
            const topic = document.getElementById('quiz-topic').value;
            
            if (!title || !subject || !grade || !topic) {
                showNotification('الرجاء ملء جميع الحقول المطلوبة', 'error');
                return false;
            }
            return true;
            
        case 2:
            // Passage validation
            if (document.getElementById('has-passage').checked) {
                const method = document.getElementById('passage-method').value;
                if (!method) {
                    showNotification('الرجاء اختيار طريقة إضافة النص', 'error');
                    return false;
                }
                
                // Check if passage content exists based on method
                const passageData = getFinalPassageData();
                if (!passageData.content) {
                    showNotification('الرجاء إضافة نص القراءة', 'error');
                    return false;
                }
            }
            return true;
            
        case 3:
            // Question settings validation
            const totalQuestions = calculateTotalQuestions();
            if (totalQuestions === 0) {
                showNotification('الرجاء إضافة عدد أسئلة واحد على الأقل', 'error');
                return false;
            }
            return true;
            
        default:
            return true;
    }
}

// Passage Handling
function togglePassageOptions() {
    const hasPassage = document.getElementById('has-passage').checked;
    const passageOptions = document.getElementById('passage-options');
    
    if (hasPassage) {
        passageOptions.classList.remove('hidden');
    } else {
        passageOptions.classList.add('hidden');
        // Clear passage data
        document.getElementById('passage-method').value = '';
        document.getElementById('final-passage-title').value = '';
        document.getElementById('final-passage-content').value = '';
    }
}

function selectPassageMethod(method) {
    // Update UI
    document.querySelectorAll('.passage-method-btn').forEach(btn => {
        const isActive = btn.dataset.method === method;
        btn.classList.toggle('border-indigo-500', isActive);
        btn.classList.toggle('border-gray-200', !isActive);
        btn.classList.toggle('bg-indigo-50', isActive);
        btn.classList.toggle('bg-white', !isActive);
    });
    
    // Set method
    document.getElementById('passage-method').value = method;
    
    // Show appropriate section
    ['write', 'ai', 'upload'].forEach(m => {
        const section = document.getElementById(`passage-${m}`);
        section.classList.toggle('hidden', m !== method);
    });
}

// AI Passage Generation
async function generatePassage() {
    const topic = document.getElementById('ai-passage-topic').value || document.getElementById('quiz-topic').value;
    const subject = document.getElementById('quiz-subject').value;
    const gradeLevel = document.getElementById('quiz-grade').value;
    
    // Show spinner
    document.getElementById('generate-spinner').classList.remove('hidden');
    
    try {
        const response = await fetch('/api/generate-passage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                topic: topic,
                subject: subject,
                grade_level: gradeLevel
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Display generated passage
            document.getElementById('generated-title').textContent = data.title;
            document.getElementById('generated-content').textContent = data.content;
            document.getElementById('generated-passage').classList.remove('hidden');
            
            // Store temporarily
            window.generatedPassage = {
                title: data.title,
                content: data.content
            };
        } else {
            showNotification(data.message || 'فشل توليد النص', 'error');
        }
    } catch (error) {
        showNotification('حدث خطأ في الاتصال', 'error');
    } finally {
        document.getElementById('generate-spinner').classList.add('hidden');
    }
}

function acceptGeneratedPassage() {
    if (window.generatedPassage) {
        document.getElementById('final-passage-title').value = window.generatedPassage.title;
        document.getElementById('final-passage-content').value = window.generatedPassage.content;
        showNotification('تم قبول النص', 'success');
    }
}

function regeneratePassage() {
    generatePassage();
}

function editGeneratedPassage() {
    if (window.generatedPassage) {
        document.getElementById('edit-passage-title').value = window.generatedPassage.title;
        document.getElementById('edit-passage-content').value = window.generatedPassage.content;
        document.getElementById('generated-passage').classList.add('hidden');
        document.getElementById('edit-generated').classList.remove('hidden');
    }
}

function saveEditedPassage() {
    const title = document.getElementById('edit-passage-title').value;
    const content = document.getElementById('edit-passage-content').value;
    
    window.generatedPassage = { title, content };
    document.getElementById('final-passage-title').value = title;
    document.getElementById('final-passage-content').value = content;
    
    // Update preview
    document.getElementById('generated-title').textContent = title;
    document.getElementById('generated-content').textContent = content;
    
    document.getElementById('edit-generated').classList.add('hidden');
    document.getElementById('generated-passage').classList.remove('hidden');
    
    showNotification('تم حفظ التعديلات', 'success');
}

// Question Method Selection
function selectQuestionMethod(method) {
    document.querySelectorAll('.question-method-btn').forEach(btn => {
        const isActive = btn.dataset.method === method;
        btn.classList.toggle('border-indigo-500', isActive);
        btn.classList.toggle('border-gray-200', !isActive);
        btn.classList.toggle('bg-indigo-50', isActive);
        btn.classList.toggle('bg-white', !isActive);
    });
    
    document.getElementById('creation-method').value = method;
    document.getElementById('ai-settings').classList.toggle('hidden', method !== 'ai');
}

// Calculate Total Questions
function calculateTotalQuestions() {
    let total = 0;
    document.querySelectorAll('input[type="number"][name*="count"]').forEach(input => {
        total += parseInt(input.value) || 0;
    });
    document.getElementById('total-questions').textContent = total;
    return total;
}

// Get Final Passage Data
function getFinalPassageData() {
    const method = document.getElementById('passage-method').value;
    let title = '';
    let content = '';
    
    if (document.getElementById('final-passage-title').value) {
        // Already processed
        return {
            title: document.getElementById('final-passage-title').value,
            content: document.getElementById('final-passage-content').value
        };
    }
    
    switch(method) {
        case 'write':
            title = document.querySelector('[name="manual_passage_title"]').value;
            content = document.querySelector('[name="manual_passage_content"]').value;
            break;
        case 'ai':
            // Should already be in final fields
            title = document.getElementById('final-passage-title').value;
            content = document.getElementById('final-passage-content').value;
            break;
        case 'upload':
            title = document.querySelector('[name="upload_passage_title"]').value;
            content = document.querySelector('[name="upload_passage_content"]').value;
            break;
    }
    
    // Store in final fields
    document.getElementById('final-passage-title').value = title;
    document.getElementById('final-passage-content').value = content;
    
    return { title, content };
}

// Update Review
function updateReview() {
    // Basic info
    document.getElementById('review-title').textContent = document.getElementById('quiz-title').value;
    document.getElementById('review-subject').textContent = document.getElementById('quiz-subject').selectedOptions[0].textContent;
    document.getElementById('review-grade').textContent = document.getElementById('quiz-grade').selectedOptions[0].textContent;
    document.getElementById('review-topic').textContent = document.getElementById('quiz-topic').value;
    
    // Passage
    if (document.getElementById('has-passage').checked) {
        const passageData = getFinalPassageData();
        if (passageData.content) {
            document.getElementById('review-passage-section').classList.remove('hidden');
            document.getElementById('review-passage-content').innerHTML = `
                <h4 class="font-bold mb-2">${passageData.title || 'بدون عنوان'}</h4>
                <p class="whitespace-pre-wrap">${passageData.content}</p>
            `;
        }
    } else {
        document.getElementById('review-passage-section').classList.add('hidden');
    }
    
    // Questions
    const method = document.getElementById('creation-method').value;
    if (method === 'ai') {
        const total = calculateTotalQuestions();
        document.getElementById('review-questions').innerHTML = `
            <p>طريقة الإنشاء: <strong>توليد بالذكاء الاصطناعي</strong></p>
            <p>عدد الأسئلة: <strong>${total} سؤال</strong></p>
        `;
    } else {
        document.getElementById('review-questions').innerHTML = `
            <p>طريقة الإنشاء: <strong>إضافة يدوية</strong></p>
            <p>سيتم إضافة الأسئلة في الخطوة التالية</p>
        `;
    }
}

// Form Submission
document.getElementById('quiz-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Show loading
    document.getElementById('submit-spinner').classList.remove('hidden');
    document.getElementById('submit-btn').disabled = true;
    
    // Get final passage data
    getFinalPassageData();
    
    // Submit form
    this.submit();
});

// Notifications
function showNotification(message, type = 'success') {
    // Simple notification implementation
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
        type === 'error' ? 'bg-red-600' : 'bg-green-600'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for question count inputs
    document.querySelectorAll('input[type="number"][name*="count"]').forEach(input => {
        input.addEventListener('input', calculateTotalQuestions);
    });
    
    calculateTotalQuestions();
});
</script>
@endpush

@push('styles')
<style>
/* Smooth transitions */
.step-content {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Focus styles */
input:focus,
select:focus,
textarea:focus {
    outline: none;
    ring: 2;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endpush
@endsection