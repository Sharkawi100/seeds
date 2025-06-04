@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">إنشاء اختبار جديد</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">ابدأ رحلة تعليمية جديدة مع نموذج جُذور للتقييم الشامل</p>
        </div>

        <!-- Progress Indicator -->
        <div class="mb-12">
            <div class="flex items-center justify-center space-x-8 rtl:space-x-reverse">
                <div class="flex items-center">
                    <div class="step-indicator active" data-step="1">
                        <div class="step-circle">
                            <span class="step-number">1</span>
                            <svg class="step-check w-5 h-5 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="step-label">المعلومات الأساسية</span>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-indicator" data-step="2">
                        <div class="step-circle">
                            <span class="step-number">2</span>
                            <svg class="step-check w-5 h-5 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="step-label">النص التعليمي</span>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-indicator" data-step="3">
                        <div class="step-circle">
                            <span class="step-number">3</span>
                            <svg class="step-check w-5 h-5 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="step-label">إعدادات الأسئلة</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Quiz ID for wizard steps -->
        <input type="hidden" id="quiz-id" value="">

        <!-- Step 1: Basic Information -->
        <div id="step-1" class="step-content">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 transition-all duration-700">
                <div class="space-y-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">المعلومات الأساسية</h2>
                        <p class="text-gray-600">ابدأ بتحديد معلومات الاختبار الأساسية</p>
                    </div>

                    <form id="step-1-form" class="space-y-6">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Quiz Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-semibold text-gray-900 mb-3">
                                    عنوان الاختبار <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           required
                                           class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 bg-white/50 backdrop-blur-sm"
                                           placeholder="مثال: اختبار الفهم القرائي - الفصل الأول">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject_id" class="block text-sm font-semibold text-gray-900 mb-3">
                                    المادة الدراسية <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="subject_id" 
                                            name="subject_id" 
                                            required
                                            class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 bg-white/50 backdrop-blur-sm appearance-none">
                                        <option value="">اختر المادة</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Grade Level -->
                            <div>
                                <label for="grade_level" class="block text-sm font-semibold text-gray-900 mb-3">
                                    الصف الدراسي <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="grade_level" 
                                            name="grade_level" 
                                            required
                                            class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 bg-white/50 backdrop-blur-sm appearance-none">
                                        <option value="">اختر الصف</option>
                                        @for($i = 1; $i <= 9; $i++)
                                            <option value="{{ $i }}">الصف {{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Topic -->
                            <div class="md:col-span-2">
                                <label for="topic" class="block text-sm font-semibold text-gray-900 mb-3">
                                    موضوع الاختبار <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           id="topic" 
                                           name="topic" 
                                           required
                                           class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 bg-white/50 backdrop-blur-sm"
                                           placeholder="مثال: الفصول الأربعة، الحضارة الإسلامية، النظام الشمسي">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">حدد الموضوع الذي سيتناوله النص والأسئلة</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" 
                                    class="group px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3 rtl:space-x-reverse">
                                <span>التالي</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Step 2: Text Generation -->
        <div id="step-2" class="step-content hidden">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 transition-all duration-700">
                <div class="space-y-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">النص التعليمي</h2>
                        <p class="text-gray-600">اختر مصدر النص أو قم بإنشائه باستخدام الذكاء الاصطناعي</p>
                    </div>

                    <!-- Text Source Selection -->
                    <div class="grid md:grid-cols-3 gap-4 mb-8">
                        <div class="text-source-card cursor-pointer p-6 border-2 border-gray-200 rounded-2xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300"
                             data-source="ai" onclick="setTextSource('ai')">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">توليد بالذكاء الاصطناعي</h3>
                                <p class="text-sm text-gray-600">اتركه للذكاء الاصطناعي ليبدع نصاً مناسباً</p>
                            </div>
                        </div>

                        <div class="text-source-card cursor-pointer p-6 border-2 border-gray-200 rounded-2xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300"
                             data-source="manual" onclick="setTextSource('manual')">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">كتابة يدوية</h3>
                                <p class="text-sm text-gray-600">اكتب أو الصق النص الخاص بك</p>
                            </div>
                        </div>

                        <div class="text-source-card cursor-pointer p-6 border-2 border-gray-200 rounded-2xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300"
                             data-source="none" onclick="setTextSource('none')">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-gray-500 to-gray-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 12M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-2">بدون نص</h3>
                                <p class="text-sm text-gray-600">أسئلة مباشرة بدون نص قراءة</p>
                            </div>
                        </div>
                    </div>

                    <!-- AI Text Options -->
                    <div id="ai-text-options" class="hidden space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">نوع النص</label>
                                <select id="text_type" 
                                        name="text_type"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300">
                                    <option value="story">📖 قصة</option>
                                    <option value="article">📰 مقال</option>
                                    <option value="dialogue">💬 حوار</option>
                                    <option value="description">📝 نص وصفي</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">طول النص</label>
                                <select id="text_length" 
                                        name="text_length"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300">
                                    <option value="short">قصير (50-100 كلمة)</option>
                                    <option value="medium" selected>متوسط (150-250 كلمة)</option>
                                    <option value="long">طويل (300-500 كلمة)</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="button" 
                                onclick="generateText()"
                                id="generate-text-btn"
                                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-4 rounded-2xl font-semibold text-lg transition-all duration-300 flex items-center justify-center space-x-3 rtl:space-x-reverse shadow-lg hover:shadow-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <span>توليد النص</span>
                        </button>
                    </div>

                    <!-- Text Editor -->
                    <div id="text-editor-container" class="hidden">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="block text-sm font-semibold text-gray-900">النص التعليمي</label>
                                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    <span id="word-count">0</span> كلمة
                                </span>
                            </div>
                            <textarea id="educational_text"
                                      name="educational_text"
                                      class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 bg-white/50 backdrop-blur-sm"
                                      rows="12"
                                      placeholder="اكتب أو الصق النص التعليمي هنا..."
                                      oninput="updateWordCount()"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-between pt-6">
                        <button type="button" 
                                onclick="previousStep()"
                                class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-2xl font-semibold text-lg transition-all duration-300 flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                            </svg>
                            <span>السابق</span>
                        </button>
                        <button type="button" 
                                onclick="nextStep()"
                                id="step-2-next"
                                class="group px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3 rtl:space-x-reverse">
                            <span>التالي</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Question Settings -->
        <div id="step-3" class="step-content hidden">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 transition-all duration-700">
                <div class="space-y-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">إعدادات الأسئلة</h2>
                        <p class="text-gray-600">حدد توزيع الأسئلة حسب نموذج الجُذور الأربعة</p>
                    </div>

                    <!-- Juzoor (4 Roots) Configuration -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Jawhar (جَوهر) -->
                        <div class="root-card border-2 border-red-200 rounded-2xl p-6 hover:border-red-400 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-2xl">🎯</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">جَوهر</h3>
                                    <p class="text-sm text-gray-600">ما هو؟ - التعريفات والفهم الأساسي</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">سطحي</label>
                                    <input type="number" id="jawhar-1" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">متوسط</label>
                                    <input type="number" id="jawhar-2" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">عميق</label>
                                    <input type="number" id="jawhar-3" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="text-sm font-medium text-gray-700">المجموع: </span>
                                <span class="total-questions text-lg font-bold text-red-600" data-root="jawhar">0</span>
                            </div>
                        </div>

                        <!-- Zihn (ذِهن) -->
                        <div class="root-card border-2 border-cyan-200 rounded-2xl p-6 hover:border-cyan-400 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-2xl">🧠</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">ذِهن</h3>
                                    <p class="text-sm text-gray-600">كيف يعمل؟ - التحليل والتفكير النقدي</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">سطحي</label>
                                    <input type="number" id="zihn-1" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">متوسط</label>
                                    <input type="number" id="zihn-2" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">عميق</label>
                                    <input type="number" id="zihn-3" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="text-sm font-medium text-gray-700">المجموع: </span>
                                <span class="total-questions text-lg font-bold text-cyan-600" data-root="zihn">0</span>
                            </div>
                        </div>

                        <!-- Waslat (وَصلات) -->
                        <div class="root-card border-2 border-yellow-200 rounded-2xl p-6 hover:border-yellow-400 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-2xl">🔗</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">وَصلات</h3>
                                    <p class="text-sm text-gray-600">كيف يترابط؟ - العلاقات والتكامل</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">سطحي</label>
                                    <input type="number" id="waslat-1" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">متوسط</label>
                                    <input type="number" id="waslat-2" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">عميق</label>
                                    <input type="number" id="waslat-3" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="text-sm font-medium text-gray-700">المجموع: </span>
                                <span class="total-questions text-lg font-bold text-yellow-600" data-root="waslat">0</span>
                            </div>
                        </div>

                        <!-- Roaya (رُؤية) -->
                        <div class="root-card border-2 border-purple-200 rounded-2xl p-6 hover:border-purple-400 transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                    <span class="text-2xl">👁️</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">رُؤية</h3>
                                    <p class="text-sm text-gray-600">كيف نستخدمه؟ - التطبيق والإبداع</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">سطحي</label>
                                    <input type="number" id="roaya-1" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">متوسط</label>
                                    <input type="number" id="roaya-2" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">عميق</label>
                                    <input type="number" id="roaya-3" min="0" max="10" value="0" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                           onchange="updateTotals()">
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="text-sm font-medium text-gray-700">المجموع: </span>
                                <span class="total-questions text-lg font-bold text-purple-600" data-root="roaya">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Presets -->
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">قوالب جاهزة</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <button type="button" onclick="applyPreset('balanced')" 
                                    class="preset-btn px-4 py-3 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 text-sm font-medium">
                                متوازن
                            </button>
                            <button type="button" onclick="applyPreset('comprehension')" 
                                    class="preset-btn px-4 py-3 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 text-sm font-medium">
                                فهم قرائي
                            </button>
                            <button type="button" onclick="applyPreset('analytical')" 
                                    class="preset-btn px-4 py-3 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 text-sm font-medium">
                                تحليلي
                            </button>
                            <button type="button" onclick="applyPreset('creative')" 
                                    class="preset-btn px-4 py-3 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 text-sm font-medium">
                                إبداعي
                            </button>
                        </div>
                    </div>

                    <!-- Total Questions -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white text-center">
                        <h4 class="text-lg font-semibold mb-2">إجمالي الأسئلة</h4>
                        <div class="text-4xl font-bold" id="grand-total">0</div>
                    </div>

                    <div class="flex justify-between pt-6">
                        <button type="button" 
                                onclick="previousStep()"
                                class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-2xl font-semibold text-lg transition-all duration-300 flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                            </svg>
                            <span>السابق</span>
                        </button>
                        <button type="button" 
                                onclick="generateQuestions()"
                                id="generate-questions-btn"
                                class="group px-8 py-4 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <span>إنشاء الأسئلة</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loading-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-3xl p-8 max-w-md mx-4 text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 id="loading-title" class="text-xl font-bold text-gray-900 mb-3">جاري المعالجة...</h3>
            <p id="loading-message" class="text-gray-600">يرجى الانتظار قليلاً</p>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Step indicator styles */
    .step-indicator {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .step-circle {
        width: 3rem;
        height: 3rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #e5e7eb;
        background: white;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .step-indicator.active .step-circle {
        border-color: #3b82f6;
        background: #3b82f6;
        color: white;
        transform: scale(1.1);
    }

    .step-indicator.completed .step-circle {
        border-color: #10b981;
        background: #10b981;
        color: white;
    }

    .step-indicator.completed .step-number {
        display: none;
    }

    .step-indicator.completed .step-check {
        display: block;
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        transition: all 0.3s ease;
    }

    .step-indicator.active .step-label {
        color: #3b82f6;
        font-weight: 600;
    }

    .step-indicator.completed .step-label {
        color: #10b981;
        font-weight: 600;
    }

    .step-connector {
        width: 4rem;
        height: 3px;
        background: #e5e7eb;
        margin: 0 1rem;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    /* Text source card styles */
    .text-source-card.active {
        border-color: #3b82f6 !important;
        background-color: #dbeafe !important;
        transform: scale(1.02);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.3);
    }

    /* Smooth transitions */
    .step-content {
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step-content.hidden {
        opacity: 0;
        transform: translateY(20px);
        pointer-events: none;
    }

    /* Enhanced button hover effects */
    button:hover {
        transform: translateY(-2px);
    }

    /* Custom scrollbar */
    textarea::-webkit-scrollbar {
        width: 8px;
    }

    textarea::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    textarea::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    textarea::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* RTL support */
    [dir="rtl"] .step-indicator {
        flex-direction: column;
    }

    /* Animation for better UX */
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

@push('scripts')
<script>
    // Global variables
    let currentStep = 1;
    let quizId = null;
    let textSource = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateStepIndicators();
        
        // Set default text source
        setTextSource('ai');
        
        // Initialize totals
        updateTotals();
        
        // Form submission for Step 1
        document.getElementById('step-1-form').addEventListener('submit', handleStep1Submit);
    });

    // Handle Step 1 form submission
    async function handleStep1Submit(e) {
        e.preventDefault();
        
        if (!validateStep(1)) return;
        
        const formData = new FormData(e.target);
        
        try {
            showLoadingModal('جاري حفظ المعلومات', 'يتم معالجة البيانات الأساسية...');
            
            // FIXED: Use correct route for wizard step 1
            const response = await fetch('{{ route("quizzes.create-step-1") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            hideLoadingModal();
            
            if (data.success) {
                quizId = data.quiz_id;
                document.getElementById('quiz-id').value = quizId;
                showNotification('تم حفظ المعلومات الأساسية بنجاح', 'success');
                nextStep();
            } else {
                showNotification(data.message || 'حدث خطأ في حفظ البيانات', 'error');
                console.error('Validation errors:', data.errors);
            }
        } catch (error) {
            hideLoadingModal();
            console.error('Error:', error);
            showNotification('حدث خطأ في الاتصال بالخادم', 'error');
        }
    }

    // Step navigation
    function nextStep() {
        if (currentStep < 3) {
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            currentStep++;
            document.getElementById(`step-${currentStep}`).classList.remove('hidden');
            updateStepIndicators();
        }
    }

    function previousStep() {
        if (currentStep > 1) {
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            currentStep--;
            document.getElementById(`step-${currentStep}`).classList.remove('hidden');
            updateStepIndicators();
        }
    }

    // Update step indicators
    function updateStepIndicators() {
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNum = index + 1;
            indicator.classList.remove('active', 'completed');
            
            if (stepNum < currentStep) {
                indicator.classList.add('completed');
            } else if (stepNum === currentStep) {
                indicator.classList.add('active');
            }
        });
    }

    // Validation
    function validateStep(step) {
        if (step === 1) {
            const title = document.getElementById('title').value.trim();
            const subjectId = document.getElementById('subject_id').value;
            const gradeLevel = document.getElementById('grade_level').value;
            const topic = document.getElementById('topic').value.trim();
            
            if (!title || !subjectId || !gradeLevel || !topic) {
                showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
                return false;
            }
        } else if (step === 2) {
            if (textSource === 'manual' || textSource === 'ai') {
                const text = document.getElementById('educational_text').value.trim();
                if (!text || text.length < 50) {
                    showNotification('النص يجب أن يكون 50 حرف على الأقل', 'error');
                    return false;
                }
            }
        } else if (step === 3) {
            const total = parseInt(document.getElementById('grand-total').textContent);
            if (total === 0) {
                showNotification('يجب إضافة سؤال واحد على الأقل', 'error');
                return false;
            }
        }
        return true;
    }

    // Text source selection
    function setTextSource(source) {
        textSource = source;
        
        // Update active card
        document.querySelectorAll('.text-source-card').forEach(card => {
            card.classList.toggle('active', card.dataset.source === source);
        });
        
        // Show/hide relevant sections
        const aiOptions = document.getElementById('ai-text-options');
        const textEditor = document.getElementById('text-editor-container');
        
        if (source === 'ai') {
            aiOptions.classList.remove('hidden');
            textEditor.classList.remove('hidden');
        } else if (source === 'manual') {
            aiOptions.classList.add('hidden');
            textEditor.classList.remove('hidden');
        } else if (source === 'none') {
            aiOptions.classList.add('hidden');
            textEditor.classList.add('hidden');
            document.getElementById('educational_text').value = '';
            updateWordCount();
        }
    }

    // Generate text using AI
    async function generateText() {
        if (!quizId) {
            showNotification('خطأ: لم يتم العثور على معرف الاختبار', 'error');
            return;
        }
        
        const btn = document.getElementById('generate-text-btn');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-6 h-6 animate-spin mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        try {
            // FIXED: Build URL manually and include all required parameters
            const generateTextUrl = '{{ url("/quizzes") }}/' + quizId + '/generate-text';
            const response = await fetch(generateTextUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    topic: document.getElementById('topic').value,
                    passage_topic: document.getElementById('topic').value,
                    text_type: document.getElementById('text_type').value
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('educational_text').value = data.text;
                updateWordCount();
                showNotification('تم توليد النص بنجاح', 'success');
            } else {
                showNotification(data.message || 'فشل توليد النص', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('حدث خطأ أثناء توليد النص', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    }

    // Generate questions
    async function generateQuestions() {
        if (!quizId) {
            showNotification('خطأ: لم يتم العثور على معرف الاختبار', 'error');
            return;
        }
        
        if (!validateStep(3)) return;
        
        const educationalText = document.getElementById('educational_text').value;
        if (textSource !== 'none' && (!educationalText || educationalText.length < 50)) {
            showNotification('النص التعليمي مطلوب ويجب أن يكون 50 حرف على الأقل', 'error');
            return;
        }
        
        showLoadingModal('جاري إنشاء الأسئلة', 'يتم معالجة البيانات وإنشاء الأسئلة حسب نموذج الجُذور...');
        
        // Prepare roots data
        const roots = {};
        ['jawhar', 'zihn', 'waslat', 'roaya'].forEach(root => {
            let total = 0;
            for (let level = 1; level <= 3; level++) {
                const input = document.getElementById(`${root}-${level}`);
                if (input) {
                    total += parseInt(input.value) || 0;
                }
            }
            if (total > 0) {
                roots[root] = total;
            }
        });
        
        const requestData = {
            topic: document.getElementById('topic').value,
            question_count: parseInt(document.getElementById('grand-total').textContent),
            educational_text: educationalText,
            text_source: textSource,
            roots: roots
        };
        
        try {
            // FIXED: Build URL manually for proper subdirectory handling
            const generateQuestionsUrl = '{{ url("/quizzes") }}/' + quizId + '/generate-questions';
            const response = await fetch(generateQuestionsUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            });
            
            const data = await response.json();
            
            hideLoadingModal();
            
            if (data.success) {
                showNotification('تم إنشاء الأسئلة بنجاح', 'success');
                
                // Redirect to quiz show page using manual URL building
                setTimeout(() => {
                    window.location.href = '{{ url("/quizzes") }}/' + quizId;
                }, 1500);
            } else {
                showNotification(data.message || 'فشل إنشاء الأسئلة', 'error');
            }
        } catch (error) {
            hideLoadingModal();
            console.error('Error:', error);
            showNotification('حدث خطأ أثناء إنشاء الأسئلة', 'error');
        }
    }

    // Update totals
    function updateTotals() {
        let grandTotal = 0;
        
        ['jawhar', 'zihn', 'waslat', 'roaya'].forEach(root => {
            let total = 0;
            for (let level = 1; level <= 3; level++) {
                const input = document.getElementById(`${root}-${level}`);
                if (input) {
                    total += parseInt(input.value) || 0;
                }
            }
            
            const totalElement = document.querySelector(`.total-questions[data-root="${root}"]`);
            if (totalElement) {
                totalElement.textContent = total;
            }
            
            grandTotal += total;
        });
        
        document.getElementById('grand-total').textContent = grandTotal;
    }

    // Apply presets
    function applyPreset(preset) {
        const presets = {
            balanced: {
                jawhar: [2, 2, 1],
                zihn: [2, 2, 1],
                waslat: [1, 1, 1],
                roaya: [1, 1, 0]
            },
            comprehension: {
                jawhar: [3, 2, 1],
                zihn: [2, 1, 0],
                waslat: [1, 1, 0],
                roaya: [0, 0, 0]
            },
            analytical: {
                jawhar: [1, 1, 0],
                zihn: [2, 3, 2],
                waslat: [2, 2, 1],
                roaya: [0, 1, 0]
            },
            creative: {
                jawhar: [1, 0, 0],
                zihn: [1, 1, 0],
                waslat: [1, 2, 1],
                roaya: [2, 2, 2]
            }
        };
        
        const config = presets[preset];
        if (config) {
            Object.keys(config).forEach(root => {
                config[root].forEach((value, index) => {
                    const level = index + 1;
                    const input = document.getElementById(`${root}-${level}`);
                    if (input) {
                        input.value = value;
                    }
                });
            });
            updateTotals();
            showNotification(`تم تطبيق القالب "${preset}"`, 'success');
        }
    }

    // Word count
    function updateWordCount() {
        const text = document.getElementById('educational_text').value;
        const wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
        document.getElementById('word-count').textContent = wordCount;
    }

    // Loading modal
    function showLoadingModal(title, message) {
        document.getElementById('loading-title').textContent = title;
        document.getElementById('loading-message').textContent = message;
        document.getElementById('loading-modal').classList.remove('hidden');
    }

    function hideLoadingModal() {
        document.getElementById('loading-modal').classList.add('hidden');
    }

    // Notifications
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-4 rounded-2xl shadow-lg z-50 flex items-center space-x-3 rtl:space-x-reverse transition-all duration-300 ${
            type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            'bg-blue-100 text-blue-800 border border-blue-200'
        }`;
        
        const icon = type === 'error' ? '❌' :
                     type === 'success' ? '✅' :
                     'ℹ️';
        
        notification.innerHTML = `
            <span class="text-xl">${icon}</span>
            <span class="font-medium">${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
</script>
@endpush
@endsection