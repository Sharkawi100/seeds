@extends('layouts.app')

@section('title', 'إضافة سؤال جديد - ' . $quiz->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        
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
                <li class="text-gray-700 font-medium">إضافة سؤال</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">إضافة سؤال جديد</h1>
                        <p class="text-blue-100">{{ $quiz->title }} - {{ $quiz->subject_name ?? 'اختبار عام' }} - الصف {{ $quiz->grade_level }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/20 rounded-lg px-4 py-2">
                            <span class="text-sm font-medium">الأسئلة الحالية</span>
                            <div class="text-2xl font-bold">{{ $quiz->questions->count() }}</div>
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

        <!-- Main Form -->
        <form action="{{ route('quizzes.questions.store', $quiz) }}" method="POST" id="question-form" novalidate>
            @csrf
            
            <!-- Hidden field for correct answer (will be set by JavaScript) -->
            <input type="hidden" name="correct_answer" id="correct_answer" value="{{ old('correct_answer') }}">
            
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-8 space-y-8">
                    
                    <!-- Question Text -->
                    <div class="animate-fade-in">
                        <label for="question" class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-question-circle text-purple-600" aria-hidden="true"></i>
                            نص السؤال *
                        </label>
                        <div class="relative">
                            <textarea name="question" 
                                      id="question"
                                      class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none"
                                      rows="4"
                                      placeholder="اكتب نص السؤال هنا..."
                                      required
                                      maxlength="1000"
                                      aria-describedby="question-help question-error">{{ old('question') }}</textarea>
                            @error('question')
                            <p id="question-error" class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        <div class="mt-2 flex justify-between text-sm text-gray-500">
                            <span id="question-help">استخدم لغة واضحة ومفهومة للطلاب</span>
                            <span id="char-counter" class="font-medium">0 / 1000 حرف</span>
                        </div>
                    </div>
                    
                    <!-- Root Type and Depth Level -->
                    <div class="grid md:grid-cols-2 gap-6 animate-fade-in" style="animation-delay: 0.1s">
                        
                        <!-- Root Type -->
                        <div>
                            <label for="root_type" class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-sitemap text-purple-600" aria-hidden="true"></i>
                                نوع الجذر *
                            </label>
                            <select name="root_type" 
                                    id="root_type"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                    required
                                    aria-describedby="root-type-help root-type-error">
                                <option value="">اختر نوع الجذر</option>
                                <option value="jawhar" {{ old('root_type') == 'jawhar' ? 'selected' : '' }}>
                                    🎯 جَوهر - الماهية (ما هو؟)
                                </option>
                                <option value="zihn" {{ old('root_type') == 'zihn' ? 'selected' : '' }}>
                                    🧠 ذِهن - التحليل (كيف يعمل؟)
                                </option>
                                <option value="waslat" {{ old('root_type') == 'waslat' ? 'selected' : '' }}>
                                    🔗 وَصلات - الربط (كيف يرتبط؟)
                                </option>
                                <option value="roaya" {{ old('root_type') == 'roaya' ? 'selected' : '' }}>
                                    👁️ رُؤية - التطبيق (كيف نستخدمه؟)
                                </option>
                            </select>
                            @error('root_type')
                            <p id="root-type-error" class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                            @enderror
                            <p id="root-type-help" class="mt-1 text-sm text-gray-500">اختر الجذر المناسب حسب نوع التفكير المطلوب</p>
                        </div>
                        
                        <!-- Depth Level -->
                        <div>
                            <label for="depth_level" class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-layer-group text-purple-600" aria-hidden="true"></i>
                                مستوى العمق *
                            </label>
                            <select name="depth_level" 
                                    id="depth_level"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                    required
                                    aria-describedby="depth-level-help depth-level-error">
                                <option value="">اختر مستوى العمق</option>
                                <option value="1" {{ old('depth_level') == '1' ? 'selected' : '' }}>
                                    🟢 مستوى 1 - سطحي (فهم مباشر)
                                </option>
                                <option value="2" {{ old('depth_level') == '2' ? 'selected' : '' }}>
                                    🟡 مستوى 2 - متوسط (تحليل وربط)
                                </option>
                                <option value="3" {{ old('depth_level') == '3' ? 'selected' : '' }}>
                                    🔴 مستوى 3 - عميق (تقييم وابتكار)
                                </option>
                            </select>
                            @error('depth_level')
                            <p id="depth-level-error" class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                {{ $message }}
                            </p>
                            @enderror
                            <p id="depth-level-help" class="mt-1 text-sm text-gray-500">حدد مستوى التعقيد المطلوب للإجابة</p>
                        </div>
                    </div>
                    
                    <!-- Answer Options -->
                    <div class="animate-fade-in" style="animation-delay: 0.2s">
                        <label class="block text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-list-ul text-purple-600" aria-hidden="true"></i>
                            خيارات الإجابة *
                        </label>
                        
                        <div class="space-y-4" id="options-container">
                            <!-- Option A -->
                            <div class="option-row border-2 border-gray-200 rounded-xl p-4 transition-colors hover:border-purple-300">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <input type="radio" 
                                               name="correct_answer_selector" 
                                               value="0" 
                                               id="correct_0"
                                               class="w-5 h-5 text-purple-600 focus:ring-purple-500"
                                               {{ old('correct_answer') && in_array(old('correct_answer'), old('options', [])) && array_search(old('correct_answer'), old('options', [])) === 0 ? 'checked' : '' }}>
                                        <label for="correct_0" class="mr-3 text-lg font-bold text-gray-700 cursor-pointer">أ)</label>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="options[]" 
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                               placeholder="الخيار الأول"
                                               required
                                               maxlength="500"
                                               value="{{ old('options.0') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Option B -->
                            <div class="option-row border-2 border-gray-200 rounded-xl p-4 transition-colors hover:border-purple-300">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <input type="radio" 
                                               name="correct_answer_selector" 
                                               value="1" 
                                               id="correct_1"
                                               class="w-5 h-5 text-purple-600 focus:ring-purple-500"
                                               {{ old('correct_answer') && in_array(old('correct_answer'), old('options', [])) && array_search(old('correct_answer'), old('options', [])) === 1 ? 'checked' : '' }}>
                                        <label for="correct_1" class="mr-3 text-lg font-bold text-gray-700 cursor-pointer">ب)</label>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="options[]" 
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                               placeholder="الخيار الثاني"
                                               required
                                               maxlength="500"
                                               value="{{ old('options.1') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Option C -->
                            <div class="option-row border-2 border-gray-200 rounded-xl p-4 transition-colors hover:border-purple-300">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <input type="radio" 
                                               name="correct_answer_selector" 
                                               value="2" 
                                               id="correct_2"
                                               class="w-5 h-5 text-purple-600 focus:ring-purple-500"
                                               {{ old('correct_answer') && in_array(old('correct_answer'), old('options', [])) && array_search(old('correct_answer'), old('options', [])) === 2 ? 'checked' : '' }}>
                                        <label for="correct_2" class="mr-3 text-lg font-bold text-gray-700 cursor-pointer">ج)</label>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="options[]" 
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                               placeholder="الخيار الثالث"
                                               required
                                               maxlength="500"
                                               value="{{ old('options.2') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Option D -->
                            <div class="option-row border-2 border-gray-200 rounded-xl p-4 transition-colors hover:border-purple-300">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <input type="radio" 
                                               name="correct_answer_selector" 
                                               value="3" 
                                               id="correct_3"
                                               class="w-5 h-5 text-purple-600 focus:ring-purple-500"
                                               {{ old('correct_answer') && in_array(old('correct_answer'), old('options', [])) && array_search(old('correct_answer'), old('options', [])) === 3 ? 'checked' : '' }}>
                                        <label for="correct_3" class="mr-3 text-lg font-bold text-gray-700 cursor-pointer">د)</label>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="options[]" 
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                               placeholder="الخيار الرابع"
                                               required
                                               maxlength="500"
                                               value="{{ old('options.3') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @error('options')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            {{ $message }}
                        </p>
                        @enderror
                        
                        @error('correct_answer')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            {{ $message }}
                        </p>
                        @enderror
                        
                        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-info-circle text-blue-600 mt-0.5" aria-hidden="true"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">تعليمات مهمة:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>حدد الإجابة الصحيحة بالنقر على الدائرة المجاورة للخيار</li>
                                        <li>تأكد من أن جميع الخيارات واضحة ومختلفة</li>
                                        <li>اجعل الخيارات الخاطئة معقولة لتحدي الطلاب</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Explanation (Optional) -->
                    <div class="animate-fade-in" style="animation-delay: 0.3s">
                        <label for="explanation" class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-purple-600" aria-hidden="true"></i>
                            شرح الإجابة (اختياري)
                        </label>
                        <textarea name="explanation" 
                                  id="explanation"
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none"
                                  rows="3"
                                  placeholder="اكتب شرحاً للإجابة الصحيحة (سيظهر للطلاب بعد الانتهاء من الاختبار)"
                                  maxlength="1000"
                                  aria-describedby="explanation-help">{{ old('explanation') }}</textarea>
                        @error('explanation')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1" role="alert">
                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                            {{ $message }}
                        </p>
                        @enderror
                        <p id="explanation-help" class="mt-1 text-sm text-gray-500">الشرح يساعد الطلاب على فهم سبب الإجابة الصحيحة</p>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex gap-3">
                        <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                           class="inline-flex items-center px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors font-medium">
                            <i class="fas fa-arrow-right mr-2" aria-hidden="true"></i>
                            العودة للأسئلة
                        </a>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" 
                                onclick="resetForm()"
                                class="inline-flex items-center px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors font-medium">
                            <i class="fas fa-undo mr-2" aria-hidden="true"></i>
                            إعادة تعيين
                        </button>
                        
                        <button type="submit" 
                                id="submit-btn"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 font-bold shadow-lg">
                            <i class="fas fa-save mr-2" aria-hidden="true"></i>
                            <span id="submit-text">حفظ السؤال</span>
                            <i class="fas fa-spinner fa-spin mr-2 hidden" id="submit-spinner" aria-hidden="true"></i>
                        </button>
                    </div>
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
    border-color: #10b981;
    background-color: #ecfdf5;
}

.option-row.correct-answer input[type="text"] {
    border-color: #10b981;
    background-color: #f0fdf4;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for question
    const questionTextarea = document.getElementById('question');
    const charCounter = document.getElementById('char-counter');
    
    function updateCharCounter() {
        const length = questionTextarea.value.length;
        charCounter.textContent = `${length} / 1000 حرف`;
        
        if (length > 900) {
            charCounter.classList.add('text-red-600');
        } else if (length > 700) {
            charCounter.classList.add('text-yellow-600');
        } else {
            charCounter.classList.remove('text-red-600', 'text-yellow-600');
        }
    }
    
    questionTextarea.addEventListener('input', updateCharCounter);
    updateCharCounter();
    
    // Handle correct answer selection
    const correctAnswerSelectors = document.querySelectorAll('input[name="correct_answer_selector"]');
    const correctAnswerHidden = document.getElementById('correct_answer');
    const optionInputs = document.querySelectorAll('input[name="options[]"]');
    const optionRows = document.querySelectorAll('.option-row');
    
    function updateCorrectAnswer() {
        // Remove all correct-answer classes
        optionRows.forEach(row => row.classList.remove('correct-answer'));
        
        // Find which radio is checked
        const checkedRadio = document.querySelector('input[name="correct_answer_selector"]:checked');
        if (checkedRadio) {
            const optionIndex = parseInt(checkedRadio.value);
            const optionInput = optionInputs[optionIndex];
            
            if (optionInput && optionInput.value.trim()) {
                correctAnswerHidden.value = optionInput.value.trim();
                // Add visual indicator
                optionRows[optionIndex].classList.add('correct-answer');
            }
        }
    }
    
    // Listen for radio button changes
    correctAnswerSelectors.forEach(radio => {
        radio.addEventListener('change', updateCorrectAnswer);
    });
    
    // Listen for option text changes to update correct answer if it's selected
    optionInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            const correspondingRadio = document.querySelector(`input[name="correct_answer_selector"][value="${index}"]`);
            if (correspondingRadio && correspondingRadio.checked) {
                correctAnswerHidden.value = this.value.trim();
            }
        });
    });
    
    // Form submission handling
    const form = document.getElementById('question-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    
    form.addEventListener('submit', function(e) {
        // Validate correct answer is selected
        if (!correctAnswerHidden.value.trim()) {
            e.preventDefault();
            alert('يرجى تحديد الإجابة الصحيحة بالنقر على الدائرة المجاورة للخيار الصحيح.');
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
                submitText.textContent = 'حفظ السؤال';
                submitSpinner.classList.add('hidden');
            }
        }, 10000); // 10 seconds
    });
    
    // Initialize correct answer on page load (for old values)
    updateCorrectAnswer();
});

// Reset form function
function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
        document.getElementById('question-form').reset();
        document.getElementById('correct_answer').value = '';
        document.querySelectorAll('.option-row').forEach(row => {
            row.classList.remove('correct-answer');
        });
        document.getElementById('char-counter').textContent = '0 / 1000 حرف';
    }
}

// Auto-save functionality (optional)
let autoSaveTimer;
function autoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        // Save form data to localStorage
        const formData = new FormData(document.getElementById('question-form'));
        const data = {};
        for (let [key, value] of formData.entries()) {
            if (data[key]) {
                if (Array.isArray(data[key])) {
                    data[key].push(value);
                } else {
                    data[key] = [data[key], value];
                }
            } else {
                data[key] = value;
            }
        }
        localStorage.setItem('question_draft_quiz_{{ $quiz->id }}', JSON.stringify(data));
    }, 2000);
}

// Add auto-save listeners to all form inputs
document.querySelectorAll('#question-form input, #question-form textarea, #question-form select').forEach(input => {
    input.addEventListener('input', autoSave);
    input.addEventListener('change', autoSave);
});

// Load draft on page load
window.addEventListener('load', function() {
    const draft = localStorage.getItem('question_draft_quiz_{{ $quiz->id }}');
    if (draft && !document.querySelector('input[name="question"]').value) {
        if (confirm('تم العثور على مسودة محفوظة. هل تريد استعادتها؟')) {
            const data = JSON.parse(draft);
            Object.keys(data).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = Array.isArray(data[key]) ? data[key][0] : data[key];
                    if (input.type === 'radio' && input.value === data[key]) {
                        input.checked = true;
                    }
                }
            });
            // Update correct answer
            document.querySelector('input[name="correct_answer_selector"]:checked')?.dispatchEvent(new Event('change'));
        }
    }
});

// Clear draft after successful submission
if (window.location.search.includes('success')) {
    localStorage.removeItem('question_draft_quiz_{{ $quiz->id }}');
}
</script>
@endpush
@endsection