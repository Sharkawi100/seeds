@extends('layouts.app')

@section('title', 'تعديل جماعي للأسئلة - ' . $quiz->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="مسار التنقل">
            <ol class="flex items-center gap-3 text-sm">
                <li>
                    <a href="{{ route('quizzes.index') }}" class="text-gray-500 hover:text-purple-600 transition-colors">
                        <i class="fas fa-home" aria-hidden="true"></i>
                        <span class="sr-only">الرئيسية</span>
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('quizzes.show', $quiz) }}" class="text-gray-500 hover:text-purple-600 transition-colors">
                        {{ $quiz->title }}
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" class="text-gray-500 hover:text-purple-600 transition-colors">
                        الأسئلة
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-700 font-medium">تعديل جماعي</li>
            </ol>
        </nav>

       <!-- Header -->
<div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-orange-600 to-red-600 p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2" style="color: #1b1f23;">تعديل جماعي للأسئلة</h1>
                <p style="color: #2d3a2f; font-weight: 500;">
                    {{ $quiz->title }} - {{ $quiz->subject_name ?? 'اختبار عام' }} - الصف {{ $quiz->grade_level }}
                </p>
            </div>
            <div class="text-right">
                <div class="bg-white/20 rounded-lg px-4 py-2">
                    <span class="text-sm font-medium" style="color: #2c3e50;">عدد الأسئلة</span>
                    <div class="text-2xl font-bold" style="color: #004225;">{{ $quiz->questions->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- Alert Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2" aria-hidden="true"></i>
                <span class="font-medium">يرجى إصلاح الأخطاء التالية:</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Instructions Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-600 text-xl mt-0.5" aria-hidden="true"></i>
                <div>
                    <h3 class="font-bold text-blue-900 mb-2">تعليمات التعديل الجماعي</h3>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p>• يمكنك تعديل جميع أسئلة الاختبار في صفحة واحدة</p>
                        <p>• التغييرات لن تُحفظ حتى تضغط على زر "حفظ جميع التغييرات"</p>
                        <p>• تأكد من تحديد الإجابة الصحيحة لكل سؤال</p>
                        <p>• استخدم أزرار التوسيط/الطي لسهولة التنقل</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" onclick="expandAllQuestions()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm">
                        <i class="fas fa-expand-alt mr-2" aria-hidden="true"></i>
                        توسيع الكل
                    </button>
                    <button type="button" onclick="collapseAllQuestions()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm">
                        <i class="fas fa-compress-alt mr-2" aria-hidden="true"></i>
                        طي الكل
                    </button>
                    <button type="button" onclick="validateAllQuestions()" 
                            class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors text-sm">
                        <i class="fas fa-check-double mr-2" aria-hidden="true"></i>
                        فحص جميع الأسئلة
                    </button>
                </div>
                <div class="text-sm text-gray-500">
                    <span id="validation-status">جاهز للتعديل</span>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('quizzes.questions.bulk-update', $quiz) }}" method="POST" id="bulk-edit-form" novalidate>
            @csrf
            @method('PUT')
            
            <div class="space-y-6 mb-8">
                @foreach($quiz->questions as $index => $question)
                <div class="question-edit-card bg-white rounded-2xl shadow-lg overflow-hidden" data-question-index="{{ $index }}">
                    <!-- Hidden fields -->
                    <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                    <input type="hidden" name="questions[{{ $index }}][correct_answer]" id="correct_answer_{{ $index }}" value="{{ old("questions.{$index}.correct_answer", $question->correct_answer) }}">
                    
                    <!-- Question Header -->
                    <div class="question-header bg-gradient-to-r from-gray-100 to-gray-200 px-6 py-4 border-b cursor-pointer" onclick="toggleQuestion({{ $index }})">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="question-number w-10 h-10 rounded-full bg-gradient-to-r from-orange-500 to-red-500 text-white flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">السؤال {{ $index + 1 }}</h3>
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="root-badge root-{{ $question->root_type }}">
                                            @switch($question->root_type)
                                                @case('jawhar')
                                                    🎯 جَوهر
                                                    @break
                                                @case('zihn')
                                                    🧠 ذِهن
                                                    @break
                                                @case('waslat')
                                                    🔗 وَصلات
                                                    @break
                                                @case('roaya')
                                                    👁️ رُؤية
                                                    @break
                                            @endswitch
                                        </span>
                                        <span class="depth-badge depth-{{ $question->depth_level }}">
                                            مستوى {{ $question->depth_level }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="validation-icon hidden text-green-600" id="valid_{{ $index }}">
                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                </span>
                                <span class="validation-icon hidden text-red-600" id="invalid_{{ $index }}">
                                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                </span>
                                <i class="fas fa-chevron-down transition-transform duration-200" id="chevron_{{ $index }}" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Question Content -->
                    <div class="question-content p-6 space-y-6" id="content_{{ $index }}">
                        
                        <!-- Question Text -->
                        <div class="animate-fade-in">
                            <label for="question_{{ $index }}" class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-question-circle text-orange-600" aria-hidden="true"></i>
                                نص السؤال *
                            </label>
                            <textarea name="questions[{{ $index }}][question]" 
                                      id="question_{{ $index }}"
                                      class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
                                      rows="3"
                                      placeholder="اكتب نص السؤال هنا..."
                                      required
                                      maxlength="1000">{{ old("questions.{$index}.question", $question->question) }}</textarea>
                            @error("questions.{$index}.question")
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                            @enderror
                            <div class="mt-1 text-right">
                                <span class="char-counter text-sm text-gray-500" data-target="question_{{ $index }}">0 / 1000 حرف</span>
                            </div>
                        </div>
                        
                        <!-- Root Type and Depth Level -->
                        <div class="grid md:grid-cols-2 gap-6">
                            
                            <!-- Root Type -->
                            <div>
                                <label for="root_type_{{ $index }}" class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                                    <i class="fas fa-sitemap text-orange-600" aria-hidden="true"></i>
                                    نوع الجذر *
                                </label>
                                <select name="questions[{{ $index }}][root_type]" 
                                        id="root_type_{{ $index }}"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                        required>
                                    <option value="">اختر نوع الجذر</option>
                                    <option value="jawhar" {{ old("questions.{$index}.root_type", $question->root_type) == 'jawhar' ? 'selected' : '' }}>
                                        🎯 جَوهر - الماهية (ما هو؟)
                                    </option>
                                    <option value="zihn" {{ old("questions.{$index}.root_type", $question->root_type) == 'zihn' ? 'selected' : '' }}>
                                        🧠 ذِهن - التحليل (كيف يعمل؟)
                                    </option>
                                    <option value="waslat" {{ old("questions.{$index}.root_type", $question->root_type) == 'waslat' ? 'selected' : '' }}>
                                        🔗 وَصلات - الربط (كيف يرتبط؟)
                                    </option>
                                    <option value="roaya" {{ old("questions.{$index}.root_type", $question->root_type) == 'roaya' ? 'selected' : '' }}>
                                        👁️ رُؤية - التطبيق (كيف نستخدمه؟)
                                    </option>
                                </select>
                                @error("questions.{$index}.root_type")
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            
                            <!-- Depth Level -->
                            <div>
                                <label for="depth_level_{{ $index }}" class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                                    <i class="fas fa-layer-group text-orange-600" aria-hidden="true"></i>
                                    مستوى العمق *
                                </label>
                                <select name="questions[{{ $index }}][depth_level]" 
                                        id="depth_level_{{ $index }}"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                        required>
                                    <option value="">اختر مستوى العمق</option>
                                    <option value="1" {{ old("questions.{$index}.depth_level", $question->depth_level) == '1' ? 'selected' : '' }}>
                                        🟢 مستوى 1 - سطحي (فهم مباشر)
                                    </option>
                                    <option value="2" {{ old("questions.{$index}.depth_level", $question->depth_level) == '2' ? 'selected' : '' }}>
                                        🟡 مستوى 2 - متوسط (تحليل وربط)
                                    </option>
                                    <option value="3" {{ old("questions.{$index}.depth_level", $question->depth_level) == '3' ? 'selected' : '' }}>
                                        🔴 مستوى 3 - عميق (تقييم وابتكار)
                                    </option>
                                </select>
                                @error("questions.{$index}.depth_level")
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Answer Options -->
                        <div>
                            <label class="block text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-list-ul text-orange-600" aria-hidden="true"></i>
                                خيارات الإجابة *
                            </label>
                            
                            <div class="space-y-4">
                                @php
                                    $currentOptions = old("questions.{$index}.options", $question->options ?? []);
                                    $currentCorrectAnswer = old("questions.{$index}.correct_answer", $question->correct_answer);
                                    $labels = ['أ', 'ب', 'ج', 'د'];
                                @endphp
                                
                                @for($i = 0; $i < 4; $i++)
                                <div class="option-row border-2 border-gray-200 rounded-xl p-4 transition-colors hover:border-orange-300 {{ isset($currentOptions[$i]) && $currentOptions[$i] === $currentCorrectAnswer ? 'correct-answer' : '' }}" data-question="{{ $index }}" data-option="{{ $i }}">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            <input type="radio" 
                                                   name="questions[{{ $index }}][correct_answer_selector]" 
                                                   value="{{ $i }}" 
                                                   id="correct_{{ $index }}_{{ $i }}"
                                                   class="w-5 h-5 text-orange-600 focus:ring-orange-500"
                                                   {{ isset($currentOptions[$i]) && $currentOptions[$i] === $currentCorrectAnswer ? 'checked' : '' }}>
                                            <label for="correct_{{ $index }}_{{ $i }}" class="mr-3 text-lg font-bold text-gray-700 cursor-pointer">{{ $labels[$i] }})</label>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" 
                                                   name="questions[{{ $index }}][options][]" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                                   placeholder="الخيار {{ $labels[$i] }}"
                                                   required
                                                   maxlength="500"
                                                   value="{{ $currentOptions[$i] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                            
                            @error("questions.{$index}.options")
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                            @enderror
                            
                            @error("questions.{$index}.correct_answer")
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Explanation (Optional) -->
                        <div>
                            <label for="explanation_{{ $index }}" class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-lightbulb text-orange-600" aria-hidden="true"></i>
                                شرح الإجابة (اختياري)
                            </label>
                            <textarea name="questions[{{ $index }}][explanation]" 
                                      id="explanation_{{ $index }}"
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
                                      rows="2"
                                      placeholder="اكتب شرحاً للإجابة الصحيحة (اختياري)"
                                      maxlength="1000">{{ old("questions.{$index}.explanation", $question->explanation) }}</textarea>
                            @error("questions.{$index}.explanation")
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Form Actions -->
            <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex flex-col sm:flex-row justify-between gap-4 shadow-lg rounded-t-xl">
                <div class="flex gap-3">
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                       class="inline-flex items-center px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors font-medium">
                        <i class="fas fa-arrow-right mr-2" aria-hidden="true"></i>
                        العودة للأسئلة
                    </a>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="resetAllQuestions()"
                            class="inline-flex items-center px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors font-medium">
                        <i class="fas fa-undo mr-2" aria-hidden="true"></i>
                        استعادة الأصل
                    </button>
                    
                    <button type="submit" 
        id="submit-btn"
        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl hover:from-orange-700 hover:to-red-700 transition-all transform hover:scale-105 font-bold shadow-lg">
    <i class="fas fa-save mr-2" aria-hidden="true"></i>
    <span id="submit-text" style="color: #006400;">حفظ جميع التغييرات</span>
    <i class="fas fa-spinner fa-spin mr-2 hidden" id="submit-spinner" aria-hidden="true"></i>
</button>

                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
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
    animation: fade-in 0.6s ease-out forwards;
}

.option-row.correct-answer {
    border-color: #ea580c;
    background-color: #fff7ed;
}

.option-row.correct-answer input[type="text"] {
    border-color: #ea580c;
    background-color: #ffedd5;
}

.question-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

.question-content.expanded {
    max-height: 2000px;
}

.root-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.root-jawhar { background-color: #d1fae5; color: #065f46; }
.root-zihn { background-color: #dbeafe; color: #1e40af; }
.root-waslat { background-color: #e9d5ff; color: #7c2d12; }
.root-roaya { background-color: #fed7aa; color: #9a3412; }

.depth-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.depth-1 { background-color: #d1fae5; color: #065f46; }
.depth-2 { background-color: #fef3c7; color: #92400e; }
.depth-3 { background-color: #fecaca; color: #991b1b; }

.loading {
    opacity: 0.6;
    pointer-events: none;
}

.validation-passed {
    border-color: #10b981 !important;
    background-color: #ecfdf5 !important;
}

.validation-failed {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
}
</style>
@endpush

@push('scripts')
<script>
// Store original data for reset functionality
const originalData = [
    @foreach($quiz->questions as $index => $question)
    {
        id: {{ $question->id }},
        question: @json($question->question),
        options: @json($question->options ?? []),
        correct_answer: @json($question->correct_answer),
        root_type: @json($question->root_type),
        depth_level: {{ $question->depth_level }},
        explanation: @json($question->explanation)
    }@if(!$loop->last),@endif
    @endforeach
];

document.addEventListener('DOMContentLoaded', function() {
    // Initialize character counters
    initializeCharCounters();
    
    // Initialize correct answer handlers
    initializeCorrectAnswerHandlers();
    
    // Initialize form submission
    initializeFormSubmission();
    
    // Expand first question by default
    toggleQuestion(0);
});

function initializeCharCounters() {
    document.querySelectorAll('.char-counter').forEach(counter => {
        const targetId = counter.getAttribute('data-target');
        const target = document.getElementById(targetId);
        
        if (target) {
            function updateCounter() {
                const length = target.value.length;
                counter.textContent = `${length} / 1000 حرف`;
                
                if (length > 900) {
                    counter.classList.add('text-red-600');
                    counter.classList.remove('text-yellow-600');
                } else if (length > 700) {
                    counter.classList.add('text-yellow-600');
                    counter.classList.remove('text-red-600');
                } else {
                    counter.classList.remove('text-red-600', 'text-yellow-600');
                }
            }
            
            target.addEventListener('input', updateCounter);
            updateCounter();
        }
    });
}

function initializeCorrectAnswerHandlers() {
    // Handle correct answer selection for each question
    document.querySelectorAll('input[type="radio"][name*="correct_answer_selector"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const matches = this.name.match(/questions\[(\d+)\]\[correct_answer_selector\]/);
            if (matches) {
                const questionIndex = matches[1];
                updateCorrectAnswer(questionIndex);
            }
        });
    });
    
    // Handle option text changes
    document.querySelectorAll('input[name*="[options][]"]').forEach(input => {
        input.addEventListener('input', function() {
            const optionRow = this.closest('.option-row');
            if (optionRow) {
                const questionIndex = optionRow.getAttribute('data-question');
                const optionIndex = optionRow.getAttribute('data-option');
                const correspondingRadio = document.querySelector(`input[name="questions[${questionIndex}][correct_answer_selector]"][value="${optionIndex}"]`);
                
                if (correspondingRadio && correspondingRadio.checked) {
                    document.getElementById(`correct_answer_${questionIndex}`).value = this.value.trim();
                }
            }
        });
    });
    
    // Initialize correct answers on page load
    for (let i = 0; i < originalData.length; i++) {
        updateCorrectAnswer(i);
    }
}

function updateCorrectAnswer(questionIndex) {
    const optionRows = document.querySelectorAll(`.option-row[data-question="${questionIndex}"]`);
    const correctAnswerHidden = document.getElementById(`correct_answer_${questionIndex}`);
    
    // Remove all correct-answer classes
    optionRows.forEach(row => row.classList.remove('correct-answer'));
    
    // Find which radio is checked
    const checkedRadio = document.querySelector(`input[name="questions[${questionIndex}][correct_answer_selector]"]:checked`);
    if (checkedRadio) {
        const optionIndex = parseInt(checkedRadio.value);
        const optionInput = document.querySelector(`input[name="questions[${questionIndex}][options][]"]:nth-of-type(${optionIndex + 1})`);
        
        if (optionInput && optionInput.value.trim()) {
            correctAnswerHidden.value = optionInput.value.trim();
            // Add visual indicator
            optionRows[optionIndex].classList.add('correct-answer');
        }
    }
}

function initializeFormSubmission() {
    const form = document.getElementById('bulk-edit-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    
    form.addEventListener('submit', function(e) {
        // Validate all questions
        let isValid = true;
        
        for (let i = 0; i < originalData.length; i++) {
            const correctAnswer = document.getElementById(`correct_answer_${i}`).value.trim();
            if (!correctAnswer) {
                isValid = false;
                alert(`يرجى تحديد الإجابة الصحيحة للسؤال رقم ${i + 1}`);
                scrollToQuestion(i);
                break;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        submitText.textContent = 'جاري الحفظ...';
        submitSpinner.classList.remove('hidden');
        
        // Optional: Add a timeout to re-enable the button if something goes wrong
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitText.textContent = 'حفظ جميع التغييرات';
                submitSpinner.classList.add('hidden');
            }
        }, 30000); // 30 seconds for bulk operations
    });
}

// Question expand/collapse functions
function toggleQuestion(index) {
    const content = document.getElementById(`content_${index}`);
    const chevron = document.getElementById(`chevron_${index}`);
    
    if (content.classList.contains('expanded')) {
        content.classList.remove('expanded');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('expanded');
        chevron.style.transform = 'rotate(180deg)';
    }
}

function expandAllQuestions() {
    for (let i = 0; i < originalData.length; i++) {
        const content = document.getElementById(`content_${i}`);
        const chevron = document.getElementById(`chevron_${i}`);
        content.classList.add('expanded');
        chevron.style.transform = 'rotate(180deg)';
    }
}

function collapseAllQuestions() {
    for (let i = 0; i < originalData.length; i++) {
        const content = document.getElementById(`content_${i}`);
        const chevron = document.getElementById(`chevron_${i}`);
        content.classList.remove('expanded');
        chevron.style.transform = 'rotate(0deg)';
    }
}

function scrollToQuestion(index) {
    const questionCard = document.querySelector(`[data-question-index="${index}"]`);
    if (questionCard) {
        questionCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // Expand the question if collapsed
        const content = document.getElementById(`content_${index}`);
        if (!content.classList.contains('expanded')) {
            toggleQuestion(index);
        }
    }
}

// Validation functions
function validateAllQuestions() {
    let validCount = 0;
    let invalidCount = 0;
    
    for (let i = 0; i < originalData.length; i++) {
        const isValid = validateQuestion(i);
        if (isValid) {
            validCount++;
            document.getElementById(`valid_${i}`).classList.remove('hidden');
            document.getElementById(`invalid_${i}`).classList.add('hidden');
        } else {
            invalidCount++;
            document.getElementById(`valid_${i}`).classList.add('hidden');
            document.getElementById(`invalid_${i}`).classList.remove('hidden');
        }
    }
    
    const statusElement = document.getElementById('validation-status');
    if (invalidCount === 0) {
        statusElement.textContent = `جميع الأسئلة صحيحة (${validCount} سؤال)`;
        statusElement.className = 'text-sm text-green-600 font-medium';
    } else {
        statusElement.textContent = `${invalidCount} سؤال يحتاج إصلاح من أصل ${validCount + invalidCount}`;
        statusElement.className = 'text-sm text-red-600 font-medium';
    }
}

function validateQuestion(index) {
    const question = document.getElementById(`question_${index}`).value.trim();
    const correctAnswer = document.getElementById(`correct_answer_${index}`).value.trim();
    const rootType = document.getElementById(`root_type_${index}`).value;
    const depthLevel = document.getElementById(`depth_level_${index}`).value;
    
    // Check if all required fields are filled
    if (!question || !correctAnswer || !rootType || !depthLevel) {
        return false;
    }
    
    // Check if all options are filled
    const options = document.querySelectorAll(`input[name="questions[${index}][options][]"]`);
    for (let option of options) {
        if (!option.value.trim()) {
            return false;
        }
    }
    
    return true;
}

// Reset function
function resetAllQuestions() {
    if (confirm('هل أنت متأكد من استعادة جميع الأسئلة للقيم الأصلية؟ ستفقد جميع التعديلات.')) {
        for (let i = 0; i < originalData.length; i++) {
            const data = originalData[i];
            
            // Reset form fields
            document.getElementById(`question_${i}`).value = data.question;
            document.getElementById(`root_type_${i}`).value = data.root_type;
            document.getElementById(`depth_level_${i}`).value = data.depth_level;
            document.getElementById(`explanation_${i}`).value = data.explanation || '';
            
            // Reset options
            const optionInputs = document.querySelectorAll(`input[name="questions[${i}][options][]"]`);
            optionInputs.forEach((input, optionIndex) => {
                input.value = data.options[optionIndex] || '';
            });
            
            // Reset correct answer
            document.getElementById(`correct_answer_${i}`).value = data.correct_answer;
            
            // Find and check the correct radio button
            const correctIndex = data.options.findIndex(option => option === data.correct_answer);
            if (correctIndex !== -1) {
                const radio = document.querySelector(`input[name="questions[${i}][correct_answer_selector]"][value="${correctIndex}"]`);
                if (radio) radio.checked = true;
            }
            
            // Update visual indicators
            updateCorrectAnswer(i);
        }
        
        // Update character counters
        initializeCharCounters();
        
        // Clear validation indicators
        document.querySelectorAll('.validation-icon').forEach(icon => {
            icon.classList.add('hidden');
        });
        
        document.getElementById('validation-status').textContent = 'تم استعادة جميع القيم الأصلية';
        document.getElementById('validation-status').className = 'text-sm text-blue-600 font-medium';
    }
}
</script>
@endpush
@endsection