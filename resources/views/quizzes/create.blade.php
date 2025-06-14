@extends('layouts.app')

@section('content')
{{-- 
    WIZARD-BASED QUIZ CREATION FLOW
    
    This implements a step-by-step wizard where each step is submitted separately.
    Backend routes needed:
    - POST /quizzes/create-step-1 (basic info)
    - POST /quizzes/{quiz}/update-method (creation method)
    - POST /quizzes/{quiz}/generate-text (AI text generation)
    - POST /quizzes/{quiz}/generate-questions (AI question generation)
    - POST /quizzes/{quiz}/finalize (final settings)
--}}

<!-- Debug Panel (only in development) -->
@if(config('app.debug'))
<div class="fixed bottom-4 right-4 bg-black bg-opacity-75 text-white p-4 rounded-lg text-xs max-w-md" id="debug-panel" style="display: none;">
    <div class="flex justify-between items-center mb-2">
        <h4 class="font-bold">Debug Info</h4>
        <button onclick="document.getElementById('debug-panel').style.display='none'" class="text-red-400">×</button>
    </div>
    <div>
        <div>Current Step: <span id="debug-step">1</span></div>
        <div>Quiz ID: <span id="debug-quiz-id">null</span></div>
        <div>User ID: {{ Auth::id() ?? 'guest' }}</div>
        <div>CSRF Token: {{ csrf_token() }}</div>
        <div>Current URL: <span id="debug-url"></span></div>
    </div>
</div>
<button onclick="document.getElementById('debug-panel').style.display='block'" 
        class="fixed bottom-4 right-4 bg-red-600 text-white p-2 rounded-full text-xs">
    Debug
</button>
@endif

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Wizard Header -->
        <div class="mb-8">
            <nav class="flex items-center space-x-4 text-sm text-gray-500 mb-6" dir="rtl">
                <a href="{{ route('quizzes.index') }}" class="hover:text-gray-700 transition-colors">الاختبارات</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-medium">إنشاء اختبار جديد</span>
            </nav>
            
            <!-- Progress Steps -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between" dir="rtl">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-purple-100 to-blue-100 rounded-xl">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">معالج إنشاء الاختبار</h1>
                            <p class="text-gray-600">دليلك خطوة بخطوة لإنشاء اختبار متميز</p>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Indicator -->
                <div class="mt-8">
                    <div class="flex items-center justify-between" dir="rtl">
                        <div class="flex items-center space-x-3" id="step-indicator-1">
                            <div class="w-10 h-10 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                            <div>
                                <div class="text-sm font-bold text-purple-600">المعلومات الأساسية</div>
                                <div class="text-xs text-gray-500">العنوان والمادة والصف</div>
                            </div>
                        </div>
                        
                        <div class="w-20 h-1 bg-gray-300 rounded-full">
                            <div class="h-1 bg-purple-600 rounded-full transition-all duration-500" style="width: 0%" id="progress-bar-1"></div>
                        </div>
                        
                        <div class="flex items-center space-x-3" id="step-indicator-2">
                            <div class="w-10 h-10 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center font-bold">2</div>
                            <div>
                                <div class="text-sm font-medium text-gray-400">طريقة الإنشاء</div>
                                <div class="text-xs text-gray-400">ذكي أو يدوي أو مختلط</div>
                            </div>
                        </div>
                        
                        <div class="w-20 h-1 bg-gray-300 rounded-full">
                            <div class="h-1 bg-gray-300 rounded-full transition-all duration-500" style="width: 0%" id="progress-bar-2"></div>
                        </div>
                        
                        <div class="flex items-center space-x-3" id="step-indicator-3">
                            <div class="w-10 h-10 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center font-bold">3</div>
                            <div>
                                <div class="text-sm font-medium text-gray-400">المحتوى والأسئلة</div>
                                <div class="text-xs text-gray-400">النصوص والتوزيع</div>
                            </div>
                        </div>
                        
                        <div class="w-20 h-1 bg-gray-300 rounded-full">
                            <div class="h-1 bg-gray-300 rounded-full transition-all duration-500" style="width: 0%" id="progress-bar-3"></div>
                        </div>
                        
                        <div class="flex items-center space-x-3" id="step-indicator-4">
                            <div class="w-10 h-10 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center font-bold">4</div>
                            <div>
                                <div class="text-sm font-medium text-gray-400">الإعدادات النهائية</div>
                                <div class="text-xs text-gray-400">الوقت والدرجات</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step Content Container -->
        <div class="max-w-4xl mx-auto">
            
            <!-- Step 1: Basic Information -->
            <div class="step-content" id="step-1-content">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-blue-50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold ml-3">1</span>
                            المعلومات الأساسية للاختبار
                        </h3>
                        <p class="text-sm text-gray-600 mt-2">ابدأ بإدخال المعلومات الأساسية لاختبارك</p>
                    </div>
                    
                    <form id="step-1-form" class="p-6 space-y-6">
                        @csrf
                        
                        <!-- Title -->
                        <div class="space-y-2">
                            <label for="title" class="block text-sm font-bold text-gray-900">
                                عنوان الاختبار
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200" 
                                   placeholder="مثال: اختبار فهم المقروء - درس الأمانة"
                                   required>
                            <div class="text-red-600 text-sm hidden" id="title-error"></div>
                        </div>

                        <!-- Subject and Grade Row -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Subject -->
                            <div class="space-y-2">
                                <label for="subject_id" class="block text-sm font-bold text-gray-900">
                                    المادة الدراسية
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="subject_id" 
                                        id="subject_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200" 
                                        required>
                                    <option value="">اختر المادة</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-red-600 text-sm hidden" id="subject_id-error"></div>
                            </div>

                            <!-- Grade Level -->
                            <div class="space-y-2">
                                <label for="grade_level" class="block text-sm font-bold text-gray-900">
                                    الصف الدراسي
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="grade_level" 
                                        id="grade_level"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200" 
                                        required>
                                    <option value="">اختر الصف</option>
                                    <optgroup label="المرحلة الابتدائية">
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>
                                                الصف {{ $i }}
                                            </option>
                                        @endfor
                                    </optgroup>
                                    <optgroup label="المرحلة المتوسطة">
                                        @for($i = 7; $i <= 9; $i++)
                                            <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>
                                                الصف {{ $i }}
                                            </option>
                                        @endfor
                                    </optgroup>
                                </select>
                                <div class="text-red-600 text-sm hidden" id="grade_level-error"></div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-bold text-gray-900">
                                وصف الاختبار
                                <span class="text-gray-500 font-normal">(اختياري)</span>
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200" 
                                      placeholder="وصف مختصر عن محتوى وأهداف الاختبار">{{ old('description') }}</textarea>
                            <p class="text-sm text-gray-500">سيظهر هذا الوصف للطلاب عند دخولهم للاختبار</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('quizzes.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                إلغاء
                            </a>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <span id="step-1-btn-text">التالي: اختر طريقة الإنشاء</span>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Step 2: Creation Method -->
            <div class="step-content hidden" id="step-2-content">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold ml-3">2</span>
                            اختر طريقة إنشاء الاختبار
                        </h3>
                        <p class="text-sm text-gray-600 mt-2">حدد الطريقة التي تناسب احتياجاتك</p>
                    </div>
                    
                    <form id="step-2-form" class="p-6">
                        @csrf
                        <input type="hidden" id="quiz_id" name="quiz_id" value="">
                        
                        <div class="grid md:grid-cols-3 gap-6 mb-8">
                            <!-- AI Method -->
                            <label class="cursor-pointer group">
                                <input type="radio" name="creation_method" value="ai" class="sr-only" checked>
                                <div class="p-6 border-2 border-purple-500 bg-purple-50 rounded-xl transition-all duration-200 hover:shadow-lg group-hover:transform group-hover:scale-105">
                                    <div class="text-center space-y-4">
                                        <div class="p-3 bg-purple-100 rounded-xl inline-block">
                                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900 mb-2">ذكي (AI)</h4>
                                            <p class="text-sm text-gray-600 leading-relaxed">إنشاء تلقائي للنصوص والأسئلة باستخدام الذكاء الاصطناعي</p>
                                            <div class="mt-3 text-xs text-purple-600 font-medium">
                                                ✨ سريع ومتوازن وذكي
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Manual Method -->
                            <label class="cursor-pointer group">
                                <input type="radio" name="creation_method" value="manual" class="sr-only">
                                <div class="p-6 border-2 border-gray-200 rounded-xl transition-all duration-200 hover:border-blue-300 hover:shadow-lg group-hover:transform group-hover:scale-105">
                                    <div class="text-center space-y-4">
                                        <div class="p-3 bg-blue-100 rounded-xl inline-block">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900 mb-2">يدوي</h4>
                                            <p class="text-sm text-gray-600 leading-relaxed">إنشاء النصوص والأسئلة يدوياً بالكامل</p>
                                            <div class="mt-3 text-xs text-blue-600 font-medium">
                                                🎯 تحكم كامل ودقيق
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Hybrid Method -->
                            <label class="cursor-pointer group">
                                <input type="radio" name="creation_method" value="hybrid" class="sr-only">
                                <div class="p-6 border-2 border-gray-200 rounded-xl transition-all duration-200 hover:border-green-300 hover:shadow-lg group-hover:transform group-hover:scale-105">
                                    <div class="text-center space-y-4">
                                        <div class="p-3 bg-green-100 rounded-xl inline-block">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900 mb-2">مختلط</h4>
                                            <p class="text-sm text-gray-600 leading-relaxed">ابدأ بالذكاء الاصطناعي ثم عدّل يدوياً</p>
                                            <div class="mt-3 text-xs text-green-600 font-medium">
                                                🚀 الأفضل من العالمين
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Method Description -->
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6" id="method-description">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-purple-900 mb-1">الطريقة الذكية (الموصى بها)</h4>
                                    <p class="text-sm text-purple-800">
                                        سيقوم الذكاء الاصطناعي بإنشاء نص تعليمي مناسب للموضوع والصف، ثم توليد أسئلة متوازنة 
                                        عبر جُذور التقييم الأربعة. يمكنك مراجعة وتعديل كل شيء قبل النشر.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between pt-6 border-t border-gray-200">
                            <button type="button" id="back-to-step-1" 
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                السابق
                            </button>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <span id="step-2-btn-text">التالي: إعداد المحتوى</span>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Step 3: Content & AI Settings -->
            <div class="step-content hidden" id="step-3-content">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold ml-3">3</span>
                            إعداد المحتوى والأسئلة
                        </h3>
                        <p class="text-sm text-gray-600 mt-2">حدد موضوع الاختبار وتوزيع الأسئلة</p>
                    </div>
                    
                    <div class="p-6 space-y-8">
                        <!-- Topic and Question Count -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="topic" class="block text-sm font-bold text-gray-900">
                                    موضوع الاختبار
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="topic" 
                                       name="topic" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                                       placeholder="مثال: الأمانة في الإسلام، النباتات، الكسور العادية"
                                       required>
                                <p class="text-sm text-gray-500">الموضوع الذي ستدور حوله الأسئلة</p>
                            </div>
                            
                            <div class="space-y-2">
                                <label for="question_count" class="block text-sm font-bold text-gray-900">
                                    عدد الأسئلة
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="question_count" 
                                        id="question_count"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                                        required>
                                    <option value="">اختر العدد</option>
                                    <option value="8">8 أسئلة</option>
                                    <option value="12" selected>12 سؤال</option>
                                    <option value="16">16 سؤال</option>
                                    <option value="20">20 سؤال</option>
                                </select>
                            </div>
                        </div>

                        <!-- Text Generation Section -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-blue-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                إنشاء النص التعليمي
                            </h4>
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div class="space-y-2">
                                    <label for="passage_topic" class="block text-sm font-bold text-gray-900">موضوع النص</label>
                                    <input type="text" 
                                           id="passage_topic" 
                                           name="passage_topic" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                           placeholder="مثال: قصة عن الأمانة">
                                </div>
                                
                                <div class="space-y-2">
                                    <label for="text_type" class="block text-sm font-bold text-gray-900">نوع النص</label>
                                    <select name="text_type" id="text_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="story">قصة</option>
                                        <option value="article">مقال</option>
                                        <option value="dialogue">حوار</option>
                                        <option value="informational">معلوماتي</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="button" id="generate-text-btn" 
                                    class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span id="generate-text-btn-text">إنشاء النص التعليمي</span>
                            </button>
                            
                            <!-- Generated Text Preview -->
                            <div id="text-preview" class="hidden mt-6">
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h5 class="font-bold text-gray-900 mb-3">النص المُنشأ:</h5>
                                    <div id="text-content" class="text-gray-700 leading-relaxed"></div>
                                    <div class="flex items-center space-x-3 mt-4 pt-4 border-t border-gray-200">
                                        <button type="button" id="regenerate-text" class="text-blue-600 hover:text-blue-700 text-sm font-medium">إعادة إنشاء</button>
                                        <button type="button" id="edit-text" class="text-green-600 hover:text-green-700 text-sm font-medium">تعديل</button>
                                        <button type="button" id="approve-text" class="text-purple-600 hover:text-purple-700 text-sm font-medium">الموافقة والمتابعة</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Question Distribution -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-6" id="question-distribution" style="display: none;">
                            <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-purple-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                توزيع الأسئلة على جُذور التقييم
                            </h4>
                            
                            <div class="text-center mb-6">
                                <button type="button" id="balanced-distribution" 
                                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    توزيع متوازن تلقائي
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="roots-preview">
                                <!-- Roots will be populated by JavaScript -->
                            </div>
                            
                            <button type="button" id="generate-questions-btn" 
                                    class="w-full mt-6 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span id="generate-questions-btn-text">إنشاء الأسئلة</span>
                            </button>
                        </div>

                        <!-- Questions Preview -->
                        <div id="questions-preview" class="hidden">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-green-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    الأسئلة المُنشأة
                                </h4>
                                <div id="questions-content">
                                    <!-- Questions will be populated by JavaScript -->
                                </div>
                                <div class="flex items-center justify-center space-x-4 mt-6 pt-4 border-t border-green-200">
                                    <button type="button" id="regenerate-questions" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg">إعادة إنشاء</button>
                                    <button type="button" id="proceed-to-final" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg">المتابعة للإعدادات النهائية</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Final Settings -->
            <div class="step-content hidden" id="step-4-content">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-orange-50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-amber-600 text-white rounded-full flex items-center justify-center text-sm font-bold ml-3">4</span>
                            الإعدادات النهائية
                        </h3>
                        <p class="text-sm text-gray-600 mt-2">أضبط الوقت والدرجات والخيارات الأخيرة</p>
                    </div>
                    
                    <form id="step-4-form" class="p-6 space-y-6">
                        @csrf
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Time Limit -->
                            <div class="space-y-2">
                                <label for="time_limit" class="block text-sm font-bold text-gray-900">
                                    المدة الزمنية (بالدقائق)
                                </label>
                                <input type="number" 
                                       id="time_limit" 
                                       name="time_limit" 
                                       min="5" 
                                       max="180"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200" 
                                       placeholder="30">
                                <p class="text-sm text-gray-500">اترك فارغاً لعدم تحديد وقت</p>
                            </div>

                            <!-- Passing Score -->
                            <div class="space-y-2">
                                <label for="passing_score" class="block text-sm font-bold text-gray-900">
                                    درجة النجاح (%)
                                </label>
                                <input type="number" 
                                       id="passing_score" 
                                       name="passing_score" 
                                       value="60" 
                                       min="0" 
                                       max="100"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                            </div>
                        </div>

                        <!-- Quiz Options -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-bold text-gray-900">خيارات الاختبار</h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="shuffle_questions" value="1" class="form-checkbox h-5 w-5 text-amber-600 rounded">
                                    <span class="mr-3 text-sm font-medium text-gray-900">خلط ترتيب الأسئلة</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" name="shuffle_answers" value="1" class="form-checkbox h-5 w-5 text-amber-600 rounded">
                                    <span class="mr-3 text-sm font-medium text-gray-900">خلط ترتيب الإجابات</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" name="show_results" value="1" class="form-checkbox h-5 w-5 text-amber-600 rounded" checked>
                                    <span class="mr-3 text-sm font-medium text-gray-900">إظهار النتائج للطلاب</span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="checkbox" name="allow_retake" value="1" class="form-checkbox h-5 w-5 text-amber-600 rounded">
                                    <span class="mr-3 text-sm font-medium text-gray-900">السماح بإعادة المحاولة</span>
                                </label>
                            </div>
                        </div>

                        <!-- Final Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <button type="button" id="back-to-step-3" 
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                السابق
                            </button>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span id="step-4-btn-text">إنهاء وإنشاء الاختبار</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    let quizId = null;
    let generatedText = null;
    let generatedQuestions = null;
    
    // Wizard navigation
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Show current step
        document.getElementById(`step-${step}-content`).classList.remove('hidden');
        
        // Update progress indicators
        updateProgressIndicators(step);
        
        currentStep = step;
        
        // Update debug info
        updateDebugInfo();
    }
    
    function updateDebugInfo() {
        const debugStep = document.getElementById('debug-step');
        const debugQuizId = document.getElementById('debug-quiz-id');
        const debugUrl = document.getElementById('debug-url');
        
        if (debugStep) debugStep.textContent = currentStep;
        if (debugQuizId) debugQuizId.textContent = quizId || 'null';
        if (debugUrl) debugUrl.textContent = window.location.href;
    }
    
    function updateProgressIndicators(step) {
        for (let i = 1; i <= 4; i++) {
            const indicator = document.getElementById(`step-indicator-${i}`);
            const progressBar = document.getElementById(`progress-bar-${i}`);
            const circle = indicator.querySelector('.w-10.h-10');
            const title = indicator.querySelector('.text-sm');
            
            if (i <= step) {
                circle.classList.remove('bg-gray-300', 'text-gray-500');
                circle.classList.add('bg-purple-600', 'text-white');
                title.classList.remove('text-gray-400');
                title.classList.add('text-purple-600', 'font-bold');
                if (progressBar) progressBar.style.width = '100%';
            } else {
                circle.classList.remove('bg-purple-600', 'text-white');
                circle.classList.add('bg-gray-300', 'text-gray-500');
                title.classList.remove('text-purple-600', 'font-bold');
                title.classList.add('text-gray-400');
                if (progressBar) progressBar.style.width = '0%';
            }
        }
    }
    
    // Step 1: Basic Information
    document.getElementById('step-1-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('step-1-btn-text');
        const originalText = btn.textContent;
        btn.textContent = 'جاري الحفظ...';
        
        // Clear previous errors
        document.querySelectorAll('[id$="-error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        try {
            const formData = new FormData(this);
            
            console.log('Submitting step 1 to:', '{{ route("quizzes.create-step-1") }}');
            console.log('Form data:', Object.fromEntries(formData));
            
            const response = await fetch('{{ route("quizzes.create-step-1") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            
            console.log('Step 1 response status:', response.status);
            
            if (!response.ok) {
                if (response.status === 422) {
                    // Validation errors
                    const errorData = await response.json();
                    showErrors(errorData.errors || {});
                    return;
                } else if (response.status === 419) {
                    throw new Error('انتهت صلاحية الجلسة. الرجاء إعادة تحديث الصفحة.');
                } else {
                    throw new Error(`خطأ HTTP: ${response.status}`);
                }
            }
            
            const data = await response.json();
            console.log('Step 1 response data:', data);
            
            if (data.success) {
                quizId = data.quiz_id;
                document.getElementById('quiz_id').value = quizId;
                console.log('Quiz created with ID:', quizId);
                showStep(2);
            } else {
                showErrors(data.errors || {'general': ['حدث خطأ أثناء حفظ البيانات']});
            }
        } catch (error) {
            console.error('Step 1 error:', error);
            if (error.message.includes('Failed to fetch')) {
                alert('فشل الاتصال بالخادم. تحقق من الاتصال بالإنترنت.');
            } else {
                alert('حدث خطأ: ' + error.message);
            }
        } finally {
            btn.textContent = originalText;
        }
    });
    
    // Step 2: Creation Method
    document.getElementById('step-2-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('step-2-btn-text');
        const originalText = btn.textContent;
        btn.textContent = 'جاري الحفظ...';
        
        // Check if we have quizId
        if (!quizId) {
            alert('خطأ: لم يتم العثور على معرف الاختبار. الرجاء البدء من جديد.');
            window.location.href = '{{ route("quizzes.create") }}';
            return;
        }
        
        try {
            const formData = new FormData(this);
            
            // Add debug info
            console.log('Submitting to:', `/quizzes/${quizId}/update-method`);
            console.log('Quiz ID:', quizId);
            console.log('Form data:', Object.fromEntries(formData));
            
            const response = await fetch(`/quizzes/${quizId}/update-method`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // Check if response is ok
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('الطريق غير موجود (404). تحقق من إعدادات الراوت.');
                } else if (response.status === 419) {
                    throw new Error('انتهت صلاحية الجلسة. الرجاء إعادة تحديث الصفحة.');
                } else if (response.status === 403) {
                    throw new Error('غير مسموح بالوصول لهذا الإجراء.');
                } else if (response.status >= 500) {
                    throw new Error(`خطأ في الخادم (${response.status}). الرجاء المحاولة لاحقاً.`);
                } else {
                    throw new Error(`خطأ HTTP: ${response.status}`);
                }
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', text);
                throw new Error('استجابة غير صالحة من الخادم');
            }
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                showStep(3);
            } else {
                alert(data.message || 'حدث خطأ أثناء حفظ الطريقة');
                if (data.errors) {
                    console.error('Validation errors:', data.errors);
                }
            }
        } catch (error) {
            console.error('Detailed error:', error);
            console.error('Error stack:', error.stack);
            
            // More specific error messages
            if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                alert('فشل الاتصال بالخادم. تحقق من الاتصال بالإنترنت.');
            } else if (error.message.includes('NetworkError')) {
                alert('خطأ في الشبكة. الرجاء المحاولة مرة أخرى.');
            } else {
                alert('حدث خطأ: ' + error.message);
            }
        } finally {
            btn.textContent = originalText;
        }
    });
    
    // Text Generation
    document.getElementById('generate-text-btn').addEventListener('click', async function() {
        const btn = this;
        const btnText = document.getElementById('generate-text-btn-text');
        const originalText = btnText.textContent;
        
        // Validate required fields
        const topic = document.getElementById('topic').value.trim();
        const passageTopic = document.getElementById('passage_topic').value.trim();
        
        if (!topic) {
            alert('الرجاء إدخال موضوع الاختبار أولاً');
            document.getElementById('topic').focus();
            return;
        }
        
        if (!passageTopic) {
            alert('الرجاء إدخال موضوع النص أولاً');
            document.getElementById('passage_topic').focus();
            return;
        }
        
        if (!quizId) {
            alert('خطأ: لم يتم العثور على معرف الاختبار');
            return;
        }
        
        btnText.textContent = 'جاري إنشاء النص...';
        btn.disabled = true;
        
        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('topic', topic);
            formData.append('passage_topic', passageTopic);
            formData.append('text_type', document.getElementById('text_type').value);
            
            console.log('Generating text for quiz:', quizId);
            console.log('Text generation data:', Object.fromEntries(formData));
            
            const response = await fetch(`/quizzes/${quizId}/generate-text`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            
            console.log('Text generation response status:', response.status);
            
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('الطريق غير موجود. تحقق من إعدادات النظام.');
                } else if (response.status === 422) {
                    const errorData = await response.json();
                    throw new Error('بيانات غير صحيحة: ' + (errorData.message || 'تحقق من البيانات المدخلة'));
                } else if (response.status >= 500) {
                    throw new Error('خطأ في الخادم. الرجاء المحاولة لاحقاً.');
                } else {
                    throw new Error(`خطأ HTTP: ${response.status}`);
                }
            }
            
            const data = await response.json();
            console.log('Text generation response:', data);
            
            if (data.success) {
                generatedText = data.text;
                document.getElementById('text-content').innerHTML = data.text.replace(/\n/g, '<br>');
                document.getElementById('text-preview').classList.remove('hidden');
                document.getElementById('question-distribution').style.display = 'block';
                setupQuestionDistribution();
            } else {
                throw new Error(data.message || 'فشل في إنشاء النص');
            }
        } catch (error) {
            console.error('Text generation error:', error);
            if (error.message.includes('Failed to fetch')) {
                alert('فشل الاتصال بالخادم أثناء إنشاء النص');
            } else {
                alert('حدث خطأ أثناء إنشاء النص: ' + error.message);
            }
        } finally {
            btnText.textContent = originalText;
            btn.disabled = false;
        }
    });
    
    // Question Distribution Setup
    function setupQuestionDistribution() {
        const questionCount = parseInt(document.getElementById('question_count').value) || 12;
        const rootsContainer = document.getElementById('roots-preview');
        
        const roots = [
            {key: 'jawhar', name: 'جَوهر', icon: '🎯', color: 'red'},
            {key: 'zihn', name: 'ذِهن', icon: '🧠', color: 'cyan'},
            {key: 'waslat', name: 'وَصلات', icon: '🔗', color: 'yellow'},
            {key: 'roaya', name: 'رُؤية', icon: '👁️', color: 'purple'}
        ];
        
        rootsContainer.innerHTML = '';
        
        roots.forEach(root => {
            const rootDiv = document.createElement('div');
            rootDiv.className = 'text-center p-3 border-2 border-gray-200 rounded-lg';
            rootDiv.innerHTML = `
                <div class="text-2xl mb-2">${root.icon}</div>
                <div class="text-sm font-bold text-gray-900">${root.name}</div>
                <input type="number" 
                       id="root-${root.key}" 
                       class="w-16 mt-2 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-purple-500"
                       min="0" max="${questionCount}" value="0">
                <div class="text-xs text-gray-500 mt-1">أسئلة</div>
            `;
            rootsContainer.appendChild(rootDiv);
        });
    }
    
    // Balanced Distribution
    document.getElementById('balanced-distribution').addEventListener('click', function() {
        const questionCount = parseInt(document.getElementById('question_count').value) || 12;
        const perRoot = Math.floor(questionCount / 4);
        const remainder = questionCount % 4;
        
        const roots = ['jawhar', 'zihn', 'waslat', 'roaya'];
        roots.forEach((root, index) => {
            const input = document.getElementById(`root-${root}`);
            if (input) {
                input.value = perRoot + (index < remainder ? 1 : 0);
            }
        });
    });
    
    // Generate Questions
    document.getElementById('generate-questions-btn').addEventListener('click', async function() {
        const btn = this;
        const btnText = document.getElementById('generate-questions-btn-text');
        const originalText = btnText.textContent;
        
        btnText.textContent = 'جاري إنشاء الأسئلة...';
        btn.disabled = true;
        
        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('topic', document.getElementById('topic').value);
            formData.append('question_count', document.getElementById('question_count').value);
            
            // Add roots distribution
            const roots = ['jawhar', 'zihn', 'waslat', 'roaya'];
            roots.forEach(root => {
                const count = document.getElementById(`root-${root}`)?.value || 0;
                formData.append(`roots[${root}]`, count);
            });
            
            const response = await fetch(`/quizzes/${quizId}/generate-questions`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                generatedQuestions = data.questions;
                displayQuestions(data.questions);
                document.getElementById('questions-preview').classList.remove('hidden');
            } else {
                alert(data.message || 'حدث خطأ أثناء إنشاء الأسئلة');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إنشاء الأسئلة');
        } finally {
            btnText.textContent = originalText;
            btn.disabled = false;
        }
    });
    
    function displayQuestions(questions) {
        const container = document.getElementById('questions-content');
        container.innerHTML = '';
        
        questions.forEach((question, index) => {
            const questionDiv = document.createElement('div');
            questionDiv.className = 'mb-4 p-4 border border-gray-200 rounded-lg';
            questionDiv.innerHTML = `
                <div class="font-bold text-gray-900 mb-2">${index + 1}. ${question.question}</div>
                <div class="space-y-1">
                    ${question.options.map((option, i) => `
                        <div class="text-sm text-gray-700 ${option === question.correct_answer ? 'font-bold text-green-600' : ''}">
                            ${String.fromCharCode(65 + i)}. ${option}
                        </div>
                    `).join('')}
                </div>
                <div class="text-xs text-gray-500 mt-2">
                    الجذر: ${question.root_type} | المستوى: ${question.depth_level}
                </div>
            `;
            container.appendChild(questionDiv);
        });
    }
    
    // Final Step
    document.getElementById('step-4-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('step-4-btn-text');
        const originalText = btn.textContent;
        btn.textContent = 'جاري الإنهاء...';
        
        try {
            const formData = new FormData(this);
            const response = await fetch(`/quizzes/${quizId}/finalize`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = data.redirect_url || '/quizzes';
            } else {
                alert(data.message || 'حدث خطأ أثناء إنهاء الاختبار');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال');
        } finally {
            btn.textContent = originalText;
        }
    });
    
    // Navigation buttons
    document.getElementById('back-to-step-1').addEventListener('click', () => showStep(1));
    document.getElementById('back-to-step-3').addEventListener('click', () => showStep(3));
    document.getElementById('proceed-to-final').addEventListener('click', () => showStep(4));
    
    // Method selection visual updates
    document.querySelectorAll('input[name="creation_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="creation_method"]').forEach(r => {
                const container = r.closest('label').querySelector('div');
                container.classList.remove('border-purple-500', 'bg-purple-50', 'border-blue-500', 'bg-blue-50', 'border-green-500', 'bg-green-50');
                container.classList.add('border-gray-200');
            });
            
            const container = this.closest('label').querySelector('div');
            container.classList.remove('border-gray-200');
            
            if (this.value === 'ai') {
                container.classList.add('border-purple-500', 'bg-purple-50');
            } else if (this.value === 'manual') {
                container.classList.add('border-blue-500', 'bg-blue-50');
            } else if (this.value === 'hybrid') {
                container.classList.add('border-green-500', 'bg-green-50');
            }
        });
    });
    
    // Error handling
    function showErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(`${field}-error`);
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
            }
        });
    }
    
    // Initialize
    showStep(1);
    updateDebugInfo();
    
    // Add route debugging
    console.log('Available routes for debugging:');
    console.log('Step 1:', '{{ route("quizzes.create-step-1") }}');
    console.log('Current URL:', window.location.href);
    console.log('CSRF Token:', document.querySelector('input[name="_token"]')?.value);
});
</script>
@endsection