@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <nav class="flex items-center gap-2 text-sm text-gray-500">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700 transition">
                        <i class="fas fa-home"></i>
                    </a>
                    <i class="fas fa-chevron-left text-xs"></i>
                    <a href="{{ route('quizzes.index') }}" class="hover:text-gray-700 transition">
                        الاختبارات
                    </a>
                    <i class="fas fa-chevron-left text-xs"></i>
                    <span class="text-gray-900 font-medium">إنشاء اختبار جديد</span>
                </nav>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                إنشاء اختبار جديد
            </h1>
            <p class="text-lg text-gray-600">
                ابدأ بإنشاء أو اختيار نص تعليمي، ثم قم بتوليد أسئلة بناءً عليه
            </p>
        </div>

        <!-- Progress Steps -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="step-item active" data-step="1">
                        <div class="step-circle">1</div>
                        <span class="step-label">المعلومات الأساسية</span>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="2">
                        <div class="step-circle">2</div>
                        <span class="step-label">النص التعليمي</span>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" data-step="3">
                        <div class="step-circle">3</div>
                        <span class="step-label">توليد الأسئلة</span>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    <span id="progress-text">الخطوة 1 من 3</span>
                </div>
            </div>
        </div>

        <form id="quiz-form" class="space-y-6">
            @csrf
            
            <!-- Step 1: Basic Information -->
            <div id="step-1" class="step-content">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        المعلومات الأساسية
                    </h2>
                    
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                عنوان الاختبار <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title"
                                   name="title" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                   placeholder="مثال: اختبار الفهم القرائي"
                                   required>
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                المادة الدراسية <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="subject"
                                        name="subject" 
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 appearance-none cursor-pointer transition"
                                        required>
                                    <option value="">اختر المادة</option>
                                    <option value="arabic">🌍 اللغة العربية</option>
                                    <option value="english">🌎 اللغة الإنجليزية</option>
                                    <option value="hebrew">🌏 اللغة العبرية</option>
                                </select>
                                <i class="fas fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                        
                        <div>
                            <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-2">
                                الصف الدراسي <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="grade_level"
                                        name="grade_level" 
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 appearance-none cursor-pointer transition"
                                        required>
                                    <option value="">اختر الصف</option>
                                    @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}">الصف {{ $i }}</option>
                                    @endfor
                                </select>
                                <i class="fas fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="topic" class="block text-sm font-medium text-gray-700 mb-2">
                            موضوع الاختبار <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="topic"
                               name="topic" 
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                               placeholder="مثال: الفصول الأربعة، الحضارة الإسلامية، قواعد اللغة..."
                               required>
                        <p class="mt-1 text-xs text-gray-500">حدد الموضوع الذي سيتناوله النص والأسئلة</p>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="button" 
                                onclick="nextStep()"
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                            التالي
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Text Generation/Input -->
            <div id="step-2" class="step-content hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt text-purple-600"></i>
                        </div>
                        النص التعليمي
                    </h2>
                    
                    <!-- Text Source Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">اختر طريقة إدخال النص</label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <button type="button" 
                                    onclick="setTextSource('ai')"
                                    class="text-source-card active"
                                    data-source="ai">
                                <div class="icon-wrapper bg-gradient-to-br from-purple-500 to-pink-600">
                                    <i class="fas fa-magic text-3xl text-white"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mt-4">توليد بالذكاء الاصطناعي</h3>
                                <p class="text-sm text-gray-600 mt-2">دع الذكاء الاصطناعي يكتب نصاً تعليمياً مناسباً</p>
                            </button>
                            
                            <button type="button" 
                                    onclick="setTextSource('manual')"
                                    class="text-source-card"
                                    data-source="manual">
                                <div class="icon-wrapper bg-gradient-to-br from-green-500 to-teal-600">
                                    <i class="fas fa-keyboard text-3xl text-white"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mt-4">كتابة يدوية</h3>
                                <p class="text-sm text-gray-600 mt-2">اكتب أو الصق نصاً جاهزاً</p>
                            </button>
                        </div>
                        <input type="hidden" name="text_source" id="text_source" value="ai">
                    </div>
                    
                    <!-- AI Text Generation Options -->
                    <div id="ai-text-options" class="mb-6">
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نوع النص</label>
                                <select id="text_type" 
                                        name="text_type"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="story">📖 قصة</option>
                                    <option value="article">📰 مقال</option>
                                    <option value="dialogue">💬 حوار</option>
                                    <option value="description">📝 نص وصفي</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">طول النص</label>
                                <select id="text_length" 
                                        name="text_length"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="short">قصير (50-100 كلمة)</option>
                                    <option value="medium" selected>متوسط (150-250 كلمة)</option>
                                    <option value="long">طويل (300-500 كلمة)</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="button" 
                                onclick="generateText()"
                                id="generate-text-btn"
                                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-xl font-medium transition flex items-center justify-center gap-2">
                            <i class="fas fa-magic"></i>
                            توليد النص
                        </button>
                    </div>
                    
                    <!-- Text Editor -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">
                                النص التعليمي
                            </label>
                            <span id="word-count" class="text-sm text-gray-500">0 كلمة</span>
                        </div>
                        <textarea id="educational-text" 
                                  name="educational_text"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                  rows="10"
                                  placeholder="اكتب أو الصق النص التعليمي هنا..."
                                  oninput="updateWordCount()"></textarea>
                        <p class="mt-1 text-xs text-gray-500">النص يجب أن يكون غنياً بالمعلومات ومناسباً لبناء أسئلة متنوعة عليه</p>
                    </div>
                    
                    <div class="mt-6 flex justify-between">
                        <button type="button" 
                                onclick="previousStep()"
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            السابق
                        </button>
                        <button type="button" 
                                onclick="nextStep()"
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                            التالي
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Question Generation Settings -->
            <div id="step-3" class="step-content hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-question-circle text-green-600"></i>
                        </div>
                        إعدادات توليد الأسئلة
                    </h2>
                    
                    <!-- Text Preview -->
                    <div class="mb-6 bg-gray-50 rounded-xl p-4 max-h-40 overflow-y-auto">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">معاينة النص:</h4>
                        <p id="text-preview" class="text-sm text-gray-600 line-clamp-4"></p>
                    </div>
                    
                    <!-- Question Distribution Settings -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">توزيع الأسئلة حسب نموذج جُذور</h3>
                        
                        <!-- Quick presets -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">قوالب جاهزة</label>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                <button type="button" onclick="applyPreset('balanced')" 
                                        class="preset-btn">
                                    <i class="fas fa-balance-scale mb-1"></i>
                                    <span>متوازن</span>
                                </button>
                                <button type="button" onclick="applyPreset('comprehension')" 
                                        class="preset-btn">
                                    <i class="fas fa-book-reader mb-1"></i>
                                    <span>فهم قرائي</span>
                                </button>
                                <button type="button" onclick="applyPreset('analytical')" 
                                        class="preset-btn">
                                    <i class="fas fa-microscope mb-1"></i>
                                    <span>تحليلي</span>
                                </button>
                                <button type="button" onclick="applyPreset('creative')" 
                                        class="preset-btn">
                                    <i class="fas fa-lightbulb mb-1"></i>
                                    <span>إبداعي</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid lg:grid-cols-2 gap-6">
                            @php
                            $roots = [
                                'jawhar' => ['name' => 'جَوهر', 'desc' => 'أسئلة مباشرة من النص', 'emoji' => '🎯', 'color' => 'red'],
                                'zihn' => ['name' => 'ذِهن', 'desc' => 'تحليل وفهم العمليات', 'emoji' => '🧠', 'color' => 'cyan'],
                                'waslat' => ['name' => 'وَصلات', 'desc' => 'ربط المعلومات', 'emoji' => '🔗', 'color' => 'yellow'],
                                'roaya' => ['name' => 'رُؤية', 'desc' => 'تطبيق وإبداع', 'emoji' => '👁️', 'color' => 'purple']
                            ];
                            @endphp
                            
                            @foreach($roots as $key => $root)
                            <div class="root-card" data-root="{{ $key }}">
                                <div class="root-header bg-{{ $root['color'] }}-50 border-{{ $root['color'] }}-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="text-3xl">{{ $root['emoji'] }}</span>
                                            <div>
                                                <h3 class="font-bold text-lg text-gray-900">{{ $root['name'] }}</h3>
                                                <p class="text-sm text-gray-600">{{ $root['desc'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="root-body">
                                    @foreach(['سطحي' => 1, 'متوسط' => 2, 'عميق' => 3] as $levelName => $levelValue)
                                    <div class="level-row">
                                        <div class="level-info">
                                            <span class="level-indicator level-{{ $levelValue }}"></span>
                                            <span class="level-name">{{ $levelName }}</span>
                                        </div>
                                        <div class="level-control">
                                            <button type="button" 
                                                    onclick="decrementLevel('{{ $key }}', {{ $levelValue }})"
                                                    class="control-btn">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" 
                                                   name="roots[{{ $key }}][levels][{{ $levelValue }}][count]" 
                                                   id="{{ $key }}-{{ $levelValue }}"
                                                   min="0" 
                                                   max="10" 
                                                   value="0"
                                                   class="level-input"
                                                   onchange="updateTotals()">
                                            <input type="hidden" 
                                                   name="roots[{{ $key }}][levels][{{ $levelValue }}][depth]" 
                                                   value="{{ $levelValue }}">
                                            <button type="button" 
                                                    onclick="incrementLevel('{{ $key }}', {{ $levelValue }})"
                                                    class="control-btn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    <div class="root-total">
                                        <span class="text-sm text-gray-600">المجموع:</span>
                                        <span class="total-questions text-lg font-bold text-{{ $root['color'] }}-600" data-root="{{ $key }}">0</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Total Summary -->
                        <div class="mt-6 bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-medium text-gray-700">إجمالي الأسئلة:</span>
                                <span id="grand-total" class="text-2xl font-bold text-blue-600">0</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Creation Method -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">طريقة الإنشاء</label>
                        <select id="creation_method" 
                                name="creation_method"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            <option value="ai">توليد تلقائي بالذكاء الاصطناعي</option>
                            <option value="hybrid">توليد بالذكاء الاصطناعي مع إمكانية التعديل</option>
                            <option value="manual">إضافة يدوية للأسئلة</option>
                        </select>
                    </div>
                    
                    <!-- Submit Actions -->
                    <div class="mt-8 flex justify-between items-center">
                        <button type="button" 
                                onclick="previousStep()"
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            السابق
                        </button>
                        
                        <button type="button"
                                onclick="createQuiz()"
                                id="submit-btn"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white rounded-xl font-bold transition transform hover:scale-105 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <span id="submit-text">إنشاء الاختبار</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
                <i class="fas fa-robot text-3xl text-blue-600 animate-pulse"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2" id="loading-title">جاري المعالجة</h3>
            <p class="text-gray-600" id="loading-message">يتم معالجة طلبك...</p>
        </div>
        <div class="space-y-3">
            <div class="loading-step active" id="step-process">
                <i class="fas fa-circle-notch fa-spin"></i>
                <span id="loading-step-text">معالجة البيانات</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Step styling */
.step-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.step-circle {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    transition: all 0.3s;
}

.step-item.active .step-circle {
    background: #3b82f6;
    color: white;
}

.step-item.completed .step-circle {
    background: #10b981;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    color: #6b7280;
    transition: color 0.3s;
}

.step-item.active .step-label {
    color: #1f2937;
    font-weight: 600;
}

.step-connector {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 0.5rem;
}

/* Text source cards */
.text-source-card {
    padding: 2rem;
    border: 2px solid #e5e7eb;
    border-radius: 1rem;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
    background: white;
}

.text-source-card:hover {
    border-color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.text-source-card.active {
    border-color: #3b82f6;
    background: #eff6ff;
}

.icon-wrapper {
    width: 4rem;
    height: 4rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

/* Root cards */
.root-card {
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s;
}

.root-card:hover {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.root-header {
    padding: 1.5rem;
    border-bottom: 1px solid;
}

.root-body {
    padding: 1.5rem;
}

.level-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.level-row:last-of-type {
    border-bottom: none;
}

.level-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.level-indicator {
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
}

.level-indicator.level-1 {
    background: #fbbf24;
}

.level-indicator.level-2 {
    background: #fb923c;
}

.level-indicator.level-3 {
    background: #10b981;
}

.level-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.control-btn {
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    background: white;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.control-btn:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.level-input {
    width: 3rem;
    text-align: center;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 0.25rem;
}

.root-total {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Preset buttons */
.preset-btn {
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: white;
    font-size: 0.875rem;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.preset-btn:hover {
    background: #f3f4f6;
    border-color: #3b82f6;
}

/* Loading modal */
.loading-step {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #6b7280;
}

.loading-step.active {
    color: #3b82f6;
    font-weight: 600;
}

.loading-step i {
    width: 1.25rem;
    display: inline-flex;
    justify-content: center;
}

/* Animations */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>
@endpush

@push('scripts')
<script>
// Global state
let currentStep = 1;
let textSource = 'ai';
let generatedText = '';

// Step navigation
function nextStep() {
    if (validateStep(currentStep)) {
        if (currentStep < 3) {
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            currentStep++;
            document.getElementById(`step-${currentStep}`).classList.remove('hidden');
            updateStepIndicators();
            
            // Update text preview when reaching step 3
            if (currentStep === 3) {
                updateTextPreview();
            }
        }
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

function validateStep(step) {
    if (step === 1) {
        const title = document.getElementById('title').value;
        const subject = document.getElementById('subject').value;
        const gradeLevel = document.getElementById('grade_level').value;
        const topic = document.getElementById('topic').value;
        
        if (!title || !subject || !gradeLevel || !topic) {
            showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
            return false;
        }
    } else if (step === 2) {
        const text = document.getElementById('educational-text').value;
        if (!text || text.length < 50) {
            showNotification('النص يجب أن يكون 50 حرف على الأقل', 'error');
            return false;
        }
    }
    return true;
}

function updateStepIndicators() {
    document.querySelectorAll('.step-item').forEach((item, index) => {
        const stepNum = index + 1;
        if (stepNum < currentStep) {
            item.classList.add('completed');
            item.classList.remove('active');
        } else if (stepNum === currentStep) {
            item.classList.add('active');
            item.classList.remove('completed');
        } else {
            item.classList.remove('active', 'completed');
        }
    });
    
    document.getElementById('progress-text').textContent = `الخطوة ${currentStep} من 3`;
}

// Text source selection
function setTextSource(source) {
    textSource = source;
    document.getElementById('text_source').value = source;
    
    document.querySelectorAll('.text-source-card').forEach(card => {
        card.classList.toggle('active', card.dataset.source === source);
    });
    
    const aiOptions = document.getElementById('ai-text-options');
    if (source === 'ai') {
        aiOptions.style.display = 'block';
    } else {
        aiOptions.style.display = 'none';
    }
}

// Generate text using AI
async function generateText() {
    const btn = document.getElementById('generate-text-btn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التوليد...';
    
    const params = {
        subject: document.getElementById('subject').value,
        grade_level: document.getElementById('grade_level').value,
        topic: document.getElementById('topic').value,
        text_type: document.getElementById('text_type').value,
        length: document.getElementById('text_length').value
    };
    
    try {
        const response = await fetch('{{ route("quizzes.generate-text") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(params)
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('educational-text').value = data.text;
            updateWordCount();
            showNotification('تم توليد النص بنجاح', 'success');
        } else {
            showNotification(data.message || 'فشل توليد النص', 'error');
        }
    } catch (error) {
        showNotification('حدث خطأ في الاتصال', 'error');
        console.error('Error:', error);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Update word count
function updateWordCount() {
    const text = document.getElementById('educational-text').value;
    const words = text.trim().split(/\s+/).filter(word => word.length > 0);
    document.getElementById('word-count').textContent = words.length + ' كلمة';
}

// Update text preview
function updateTextPreview() {
    const text = document.getElementById('educational-text').value;
    document.getElementById('text-preview').textContent = text || 'لا يوجد نص';
}

// Level controls
function incrementLevel(root, level) {
    const input = document.getElementById(`${root}-${level}`);
    const currentValue = parseInt(input.value) || 0;
    if (currentValue < 10) {
        input.value = currentValue + 1;
        updateTotals();
    }
}

function decrementLevel(root, level) {
    const input = document.getElementById(`${root}-${level}`);
    const currentValue = parseInt(input.value) || 0;
    if (currentValue > 0) {
        input.value = currentValue - 1;
        updateTotals();
    }
}

// Update totals
function updateTotals() {
    const roots = ['jawhar', 'zihn', 'waslat', 'roaya'];
    let grandTotal = 0;
    
    roots.forEach(root => {
        let total = 0;
        for (let level = 1; level <= 3; level++) {
            const input = document.getElementById(`${root}-${level}`);
            total += parseInt(input.value) || 0;
        }
        grandTotal += total;
        
        // Update root total display
        const totalElement = document.querySelector(`.total-questions[data-root="${root}"]`);
        if (totalElement) {
            totalElement.textContent = total;
        }
    });
    
    // Update grand total
    document.getElementById('grand-total').textContent = grandTotal;
}

// Presets
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
    
    const presetNames = {
        balanced: 'متوازن',
        comprehension: 'فهم قرائي',
        analytical: 'تحليلي',
        creative: 'إبداعي'
    };
    
    const config = presets[preset];
    if (config) {
        Object.keys(config).forEach(root => {
            config[root].forEach((value, index) => {
                const level = index + 1;
                document.getElementById(`${root}-${level}`).value = value;
            });
        });
        updateTotals();
        showNotification(`تم تطبيق القالب "${presetNames[preset]}"`, 'success');
    }
}

// Create quiz
async function createQuiz() {
    if (!validateStep(3)) return;
    
    const grandTotal = parseInt(document.getElementById('grand-total').textContent);
    if (grandTotal === 0) {
        showNotification('يجب إضافة سؤال واحد على الأقل', 'error');
        return;
    }
    
    showLoadingModal('جاري إنشاء الاختبار', 'يتم معالجة البيانات وإنشاء الاختبار...');
    
    const formData = new FormData(document.getElementById('quiz-form'));
    
    try {
        // First create the quiz
        const response = await fetch('{{ route("quizzes.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success || response.redirected) {
            // Quiz created successfully
            window.location.href = data.redirect || response.url;
        } else {
            hideLoadingModal();
            showNotification(data.message || 'فشل إنشاء الاختبار', 'error');
        }
    } catch (error) {
        hideLoadingModal();
        showNotification('حدث خطأ في الاتصال', 'error');
        console.error('Error:', error);
    }
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
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg z-50 flex items-center gap-3 animate-fade-in ${
        type === 'error' ? 'bg-red-100 text-red-800' :
        type === 'success' ? 'bg-green-100 text-green-800' :
        'bg-blue-100 text-blue-800'
    }`;
    
    const icon = type === 'error' ? 'fa-exclamation-circle' :
                 type === 'success' ? 'fa-check-circle' :
                 'fa-info-circle';
    
    notification.innerHTML = `
        <i class="fas ${icon} text-xl"></i>
        <span class="font-medium">${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateTotals();
});
</script>
@endpush