@extends('layouts.app')

@section('title', 'إضافة أسئلة جديدة - ' . $quiz->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center gap-3 text-sm">
                <li>
                    <a href="{{ route('quizzes.index') }}" class="text-gray-500 hover:text-purple-600 transition-colors">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('quizzes.show', $quiz) }}" class="text-gray-500 hover:text-purple-600 transition-colors">
                        {{ $quiz->title }}
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-700 font-medium">إضافة أسئلة</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">إضافة أسئلة جديدة</h1>
                        <p class="text-blue-100">{{ $quiz->title }} - {{ $quiz->subject_name }} - الصف {{ $quiz->grade_level }}</p>
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

        <form action="{{ route('quizzes.questions.store', $quiz) }}" method="POST" id="questions-form">
            @csrf
            
            <!-- Educational Passage Section -->
            <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        📖 النص التعليمي (اختياري)
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">يمكنك إضافة نص أو مقال ليقرأه الطلاب قبل الإجابة على الأسئلة</p>
                </div>
                
                <div class="p-6">
                    <!-- Check if quiz has saved passage data -->
                    @php
                        $savedPassage = null;
                        $savedPassageTitle = null;
                        
                        // Try to get from passage_data JSON field first
                        if ($quiz->passage_data) {
                            $passageData = is_string($quiz->passage_data) 
                                ? json_decode($quiz->passage_data, true) 
                                : $quiz->passage_data;
                            $savedPassage = $passageData['passage'] ?? null;
                            $savedPassageTitle = $passageData['passage_title'] ?? null;
                        }
                        
                        // Fallback to first question's passage
                        if (!$savedPassage) {
                            $firstQuestion = $quiz->questions->where('passage', '!=', null)->first();
                            if ($firstQuestion) {
                                $savedPassage = $firstQuestion->passage;
                                $savedPassageTitle = $firstQuestion->passage_title;
                            }
                        }
                    @endphp

                    @if($savedPassage)
                        <!-- Display saved passage with option to edit -->
                        <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-bold text-green-800 flex items-center gap-2">
                                    ✅ تم حفظ النص مسبقاً
                                </h3>
                                <button type="button" onclick="togglePassageEdit()" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                                    <i class="fas fa-edit"></i> تعديل النص
                                </button>
                            </div>
                            
                            <!-- Display Mode -->
                            <div id="passage-display">
                                @if($savedPassageTitle)
                                    <h4 class="font-bold text-gray-800 mb-2">{{ $savedPassageTitle }}</h4>
                                @endif
                                <div class="text-gray-700 leading-relaxed bg-white p-4 rounded-lg border">
                                    {!! nl2br(e($savedPassage)) !!}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Edit Mode (hidden by default) -->
                        <div id="passage-edit" class="hidden">
                    @else
                        <!-- New passage entry -->
                        <div id="passage-edit">
                    @endif
                    
                        <div class="grid md:grid-cols-4 gap-4 mb-4">
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">عنوان النص</label>
                                <input type="text" 
                                       name="passage_title" 
                                       value="{{ old('passage_title', $savedPassageTitle) }}"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="مثال: قصة الأسد والفأر">
                                @error('passage_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">النص التعليمي</label>
                                <textarea name="passage" 
                                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                          rows="6"
                                          placeholder="اكتب النص التعليمي هنا..."
                                          onkeyup="updateWordCount()">{{ old('passage', $savedPassage) }}</textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <small class="text-gray-500">سيظهر هذا النص للطلاب قبل الأسئلة</small>
                                    <small id="word-count" class="text-gray-500">0 كلمة</small>
                                </div>
                                @error('passage')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                    </div> <!-- Close passage-edit div -->
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            ❓ الأسئلة
                        </h2>
                        <button type="button" onclick="addQuestion()" 
                                class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة سؤال
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id="questions-container" class="space-y-8">
                        <!-- Initial Question Template -->
                        <div class="question-item" data-question="1">
                            <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200 hover:border-purple-300 transition">
                                <!-- Question Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-gray-800">السؤال الأول</h3>
                                    <button type="button" onclick="removeQuestion(1)" 
                                            class="text-red-500 hover:text-red-700 transition hidden">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Question Text -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نص السؤال *</label>
                                    <textarea name="questions[1][question]" 
                                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" 
                                              rows="3"
                                              placeholder="اكتب السؤال هنا..."
                                              required></textarea>
                                </div>

                                <!-- Root Type and Depth -->
                                <div class="grid md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع الجذر *</label>
                                        <select name="questions[1][root_type]" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                                required>
                                            <option value="">اختر نوع الجذر</option>
                                            <option value="jawhar">🎯 جَوهر (Jawhar) - الجوهر والماهية</option>
                                            <option value="zihn">🧠 ذِهن (Zihn) - التحليل والتفكير</option>
                                            <option value="waslat">🔗 وَصلات (Waslat) - الروابط والعلاقات</option>
                                            <option value="roaya">👁️ رُؤية (Roaya) - الرؤية والتطبيق</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">مستوى العمق *</label>
                                        <select name="questions[1][depth_level]" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                                required>
                                            <option value="">اختر المستوى</option>
                                            <option value="1">1 - سطحي</option>
                                            <option value="2">2 - متوسط</option>
                                            <option value="3">3 - عميق</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Answer Options -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">خيارات الإجابة *</label>
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <input type="radio" name="questions[1][correct_answer_index]" value="0" class="w-4 h-4 text-purple-600" required>
                                                <label class="mr-2 text-sm font-medium text-gray-700">أ)</label>
                                            </div>
                                            <input type="text" name="questions[1][options][]" 
                                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                   placeholder="الخيار الأول"
                                                   required>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <input type="radio" name="questions[1][correct_answer_index]" value="1" class="w-4 h-4 text-purple-600" required>
                                                <label class="mr-2 text-sm font-medium text-gray-700">ب)</label>
                                            </div>
                                            <input type="text" name="questions[1][options][]" 
                                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                   placeholder="الخيار الثاني"
                                                   required>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <input type="radio" name="questions[1][correct_answer_index]" value="2" class="w-4 h-4 text-purple-600" required>
                                                <label class="mr-2 text-sm font-medium text-gray-700">ج)</label>
                                            </div>
                                            <input type="text" name="questions[1][options][]" 
                                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                   placeholder="الخيار الثالث"
                                                   required>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <input type="radio" name="questions[1][correct_answer_index]" value="3" class="w-4 h-4 text-purple-600" required>
                                                <label class="mr-2 text-sm font-medium text-gray-700">د)</label>
                                            </div>
                                            <input type="text" name="questions[1][options][]" 
                                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                   placeholder="الخيار الرابع"
                                                   required>
                                        </div>
                                    </div>
                                    <small class="text-gray-500 mt-2 block">حدد الإجابة الصحيحة بالنقر على الدائرة المجاورة للخيار</small>
                                </div>

                                <!-- Explanation (Optional) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">شرح الإجابة (اختياري)</label>
                                    <textarea name="questions[1][explanation]" 
                                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" 
                                              rows="2"
                                              placeholder="اكتب شرحاً للإجابة الصحيحة (سيظهر للطلاب بعد الانتهاء من الاختبار)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Question Button -->
                    <div class="mt-6 text-center">
                        <button type="button" onclick="addQuestion()" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 mx-auto">
                            <i class="fas fa-plus"></i>
                            إضافة سؤال آخر
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('quizzes.show', $quiz) }}" 
                   class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-xl font-medium transition text-center">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للاختبار
                </a>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <button type="button" onclick="saveDraft()" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-medium transition">
                        <i class="fas fa-save ml-2"></i>
                        حفظ كمسودة
                    </button>
                    
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-medium transition">
                        <i class="fas fa-check ml-2"></i>
                        حفظ الأسئلة
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-purple-600 mx-auto mb-4"></div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">جاري الحفظ...</h3>
        <p class="text-gray-600">يرجى الانتظار</p>
    </div>
</div>

<script>
let questionCount = 1;

// Toggle passage edit mode
function togglePassageEdit() {
    const display = document.getElementById('passage-display');
    const edit = document.getElementById('passage-edit');
    
    if (display && edit) {
        display.classList.toggle('hidden');
        edit.classList.toggle('hidden');
    }
}

// Update word count
function updateWordCount() {
    const textarea = document.querySelector('textarea[name="passage"]');
    const wordCount = document.getElementById('word-count');
    
    if (textarea && wordCount) {
        const words = textarea.value.trim().split(/\s+/).filter(word => word.length > 0);
        wordCount.textContent = words.length + ' كلمة';
    }
}

// Add new question
function addQuestion() {
    questionCount++;
    const container = document.getElementById('questions-container');
    
    const questionHTML = `
        <div class="question-item" data-question="${questionCount}">
            <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200 hover:border-purple-300 transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">السؤال ${getArabicNumber(questionCount)}</h3>
                    <button type="button" onclick="removeQuestion(${questionCount})" 
                            class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نص السؤال *</label>
                    <textarea name="questions[${questionCount}][question]" 
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" 
                              rows="3"
                              placeholder="اكتب السؤال هنا..."
                              required></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع الجذر *</label>
                        <select name="questions[${questionCount}][root_type]" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                required>
                            <option value="">اختر نوع الجذر</option>
                            <option value="jawhar">🎯 جَوهر (Jawhar) - الجوهر والماهية</option>
                            <option value="zihn">🧠 ذِهن (Zihn) - التحليل والتفكير</option>
                            <option value="waslat">🔗 وَصلات (Waslat) - الروابط والعلاقات</option>
                            <option value="roaya">👁️ رُؤية (Roaya) - الرؤية والتطبيق</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">مستوى العمق *</label>
                        <select name="questions[${questionCount}][depth_level]" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                required>
                            <option value="">اختر المستوى</option>
                            <option value="1">1 - سطحي</option>
                            <option value="2">2 - متوسط</option>
                            <option value="3">3 - عميق</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">خيارات الإجابة *</label>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <input type="radio" name="questions[${questionCount}][correct_answer_index]" value="0" class="w-4 h-4 text-purple-600" required>
                                <label class="mr-2 text-sm font-medium text-gray-700">أ)</label>
                            </div>
                            <input type="text" name="questions[${questionCount}][options][]" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="الخيار الأول"
                                   required>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <input type="radio" name="questions[${questionCount}][correct_answer_index]" value="1" class="w-4 h-4 text-purple-600" required>
                                <label class="mr-2 text-sm font-medium text-gray-700">ب)</label>
                            </div>
                            <input type="text" name="questions[${questionCount}][options][]" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="الخيار الثاني"
                                   required>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <input type="radio" name="questions[${questionCount}][correct_answer_index]" value="2" class="w-4 h-4 text-purple-600" required>
                                <label class="mr-2 text-sm font-medium text-gray-700">ج)</label>
                            </div>
                            <input type="text" name="questions[${questionCount}][options][]" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="الخيار الثالث"
                                   required>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <input type="radio" name="questions[${questionCount}][correct_answer_index]" value="3" class="w-4 h-4 text-purple-600" required>
                                <label class="mr-2 text-sm font-medium text-gray-700">د)</label>
                            </div>
                            <input type="text" name="questions[${questionCount}][options][]" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="الخيار الرابع"
                                   required>
                        </div>
                    </div>
                    <small class="text-gray-500 mt-2 block">حدد الإجابة الصحيحة بالنقر على الدائرة المجاورة للخيار</small>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">شرح الإجابة (اختياري)</label>
                    <textarea name="questions[${questionCount}][explanation]" 
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" 
                              rows="2"
                              placeholder="اكتب شرحاً للإجابة الصحيحة"></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHTML);
    updateDeleteButtons();
}

// Remove question
function removeQuestion(questionId) {
    if (questionCount <= 1) {
        alert('يجب أن يحتوي الاختبار على سؤال واحد على الأقل');
        return;
    }
    
    const questionElement = document.querySelector(`[data-question="${questionId}"]`);
    if (questionElement) {
        questionElement.remove();
        questionCount--;
        updateDeleteButtons();
        updateQuestionNumbers();
    }
}

// Update delete button visibility
function updateDeleteButtons() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((question, index) => {
        const deleteBtn = question.querySelector('button[onclick*="removeQuestion"]');
        if (deleteBtn) {
            if (questions.length <= 1) {
                deleteBtn.classList.add('hidden');
            } else {
                deleteBtn.classList.remove('hidden');
            }
        }
    });
}

// Update question numbers
function updateQuestionNumbers() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((question, index) => {
        const header = question.querySelector('h3');
        if (header) {
            header.textContent = `السؤال ${getArabicNumber(index + 1)}`;
        }
    });
}

// Get Arabic number
function getArabicNumber(num) {
    const arabicNumbers = ['', 'الأول', 'الثاني', 'الثالث', 'الرابع', 'الخامس', 'السادس', 'السابع', 'الثامن', 'التاسع', 'العاشر'];
    return arabicNumbers[num] || `رقم ${num}`;
}

// Save as draft
function saveDraft() {
    // Implementation for saving as draft
    alert('سيتم إضافة خاصية حفظ المسودة قريباً');
}

// Form validation
document.getElementById('questions-form').addEventListener('submit', function(e) {
    const questions = document.querySelectorAll('.question-item');
    
    if (questions.length === 0) {
        e.preventDefault();
        alert('يجب إضافة سؤال واحد على الأقل');
        return;
    }
    
    // Validate each question has a correct answer selected
    let hasErrors = false;
    questions.forEach((question, index) => {
        const radios = question.querySelectorAll('input[type="radio"]');
        const isChecked = Array.from(radios).some(radio => radio.checked);
        
        if (!isChecked) {
            hasErrors = true;
            question.scrollIntoView({ behavior: 'smooth' });
            question.style.borderColor = '#ef4444';
            setTimeout(() => {
                question.style.borderColor = '';
            }, 3000);
        }
    });
    
    if (hasErrors) {
        e.preventDefault();
        alert('يرجى تحديد الإجابة الصحيحة لجميع الأسئلة');
    } else {
        // Show loading modal
        document.getElementById('loading-modal').classList.remove('hidden');
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateWordCount();
    updateDeleteButtons();
});
</script>

@error('questions')
    <div class="fixed bottom-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg">
        {{ $message }}
    </div>
@enderror

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
@endif

@endsection