@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <h2 class="text-3xl font-bold">إضافة أسئلة جديدة</h2>
                <p class="mt-2">{{ $quiz->title }}</p>
            </div>
            
            <form action="{{ route('quizzes.questions.store', $quiz) }}" method="POST" class="p-8">
                @csrf
                
                <!-- Passage Section -->
                <div class="mb-8 bg-blue-50 rounded-xl p-6 border-2 border-blue-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">📄 نص القراءة (اختياري)</h3>
                    <p class="text-sm text-gray-600 mb-4">يمكنك إضافة نص أو مقال ليقرأه الطلاب قبل الإجابة على الأسئلة</p>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">عنوان النص</label>
                        <input type="text" 
                               name="passage_title" 
                               class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500"
                               placeholder="مثال: قصة الأسد والفأر">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">النص</label>
                        <textarea name="passage" 
                                  class="w-full p-4 border-2 border-gray-200 rounded-lg focus:border-blue-500" 
                                  rows="6"
                                  placeholder="اكتب النص هنا..."></textarea>
                    </div>
                </div>

                <div id="questions-container" class="space-y-6">
                    <!-- Question Template -->
                    <div class="question-item bg-gray-50 rounded-xl p-6 border-2 border-gray-200 hover:border-blue-300 transition">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-800">السؤال 1</h3>
                            <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700 hidden">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Question Text -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">نص السؤال</label>
                            <textarea name="questions[0][question]" 
                                      class="w-full p-4 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                      rows="3" 
                                      placeholder="اكتب السؤال هنا..."
                                      required></textarea>
                        </div>
                        
                        <!-- Root Type and Depth Level -->
                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الجذر</label>
                                <select name="questions[0][root_type]" 
                                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                        required>
                                    <option value="jawhar">🎯 جَوهر - ما هو؟</option>
                                    <option value="zihn">🧠 ذِهن - كيف يعمل؟</option>
                                    <option value="waslat">🔗 وَصلات - كيف يرتبط؟</option>
                                    <option value="roaya">👁️ رُؤية - كيف نستخدمه؟</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">مستوى العمق</label>
                                <select name="questions[0][depth_level]" 
                                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                        required>
                                    <option value="1">🟡 سطحي - المستوى 1</option>
                                    <option value="2">🟠 متوسط - المستوى 2</option>
                                    <option value="3">🟢 عميق - المستوى 3</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Answer Options -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-sm font-medium text-gray-700">خيارات الإجابة</label>
                                <div class="flex gap-2">
                                    <button type="button" 
                                            onclick="removeOption(this)" 
                                            class="text-sm text-red-600 hover:text-red-800 transition">
                                        ➖ إزالة خيار
                                    </button>
                                    <button type="button" 
                                            onclick="addOption(this)" 
                                            class="text-sm text-blue-600 hover:text-blue-800 transition">
                                        ➕ إضافة خيار
                                    </button>
                                </div>
                            </div>
                            
                            <div class="options-container space-y-2">
                                <div class="option-item flex items-center gap-3">
                                    <input type="radio" 
                                           name="questions[0][correct_answer]" 
                                           value="0" 
                                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                                           required>
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">أ</span>
                                    <input type="text" 
                                           name="questions[0][options][]" 
                                           class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                           placeholder="الخيار الأول"
                                           required>
                                </div>
                                <div class="option-item flex items-center gap-3">
                                    <input type="radio" 
                                           name="questions[0][correct_answer]" 
                                           value="1" 
                                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                                           required>
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">ب</span>
                                    <input type="text" 
                                           name="questions[0][options][]" 
                                           class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                           placeholder="الخيار الثاني"
                                           required>
                                </div>
                                <div class="option-item flex items-center gap-3">
                                    <input type="radio" 
                                           name="questions[0][correct_answer]" 
                                           value="2" 
                                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                                           required>
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">ج</span>
                                    <input type="text" 
                                           name="questions[0][options][]" 
                                           class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                           placeholder="الخيار الثالث">
                                </div>
                                <div class="option-item flex items-center gap-3">
                                    <input type="radio" 
                                           name="questions[0][correct_answer]" 
                                           value="3" 
                                           class="w-5 h-5 text-green-600 focus:ring-green-500"
                                           required>
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">د</span>
                                    <input type="text" 
                                           name="questions[0][options][]" 
                                           class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                           placeholder="الخيار الرابع">
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">حدد الإجابة الصحيحة بالنقر على الدائرة بجانب الخيار</p>
                        </div>
                    </div>
                </div>
                
                <!-- Add Question Button -->
                <button type="button" 
                        onclick="addQuestion()" 
                        class="mt-6 w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 rounded-lg font-bold hover:from-green-600 hover:to-green-700 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إضافة سؤال آخر
                </button>
                
                <!-- Submit Buttons -->
                <div class="flex gap-4 mt-8">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-lg font-bold hover:from-blue-700 hover:to-purple-700 transition">
                        حفظ جميع الأسئلة
                    </button>
                    <a href="{{ route('quizzes.show', $quiz) }}" 
                       class="px-8 py-4 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let questionCount = 1;
const optionLetters = ['أ', 'ب', 'ج', 'د', 'هـ', 'و'];

function addQuestion() {
    const container = document.getElementById('questions-container');
    const template = container.children[0].cloneNode(true);
    
    // Update question number
    template.querySelector('h3').textContent = `السؤال ${questionCount + 1}`;
    
    // Show remove button for new questions
    template.querySelector('button[onclick*="removeQuestion"]').classList.remove('hidden');
    
    // Update field names and clear values
    template.querySelectorAll('input, textarea, select').forEach(field => {
        if (field.name) {
            field.name = field.name.replace('[0]', `[${questionCount}]`);
        }
        if (field.type === 'text' || field.type === 'textarea') {
            field.value = '';
        }
        if (field.type === 'radio') {
            field.checked = false;
        }
    });
    
    container.appendChild(template);
    questionCount++;
}

function removeQuestion(btn) {
    if (confirm('هل أنت متأكد من حذف هذا السؤال؟')) {
        btn.closest('.question-item').remove();
        updateQuestionNumbers();
    }
}

function addOption(btn) {
    const container = btn.closest('.question-item').querySelector('.options-container');
    const optionCount = container.children.length;
    
    if (optionCount >= 6) {
        alert('لا يمكن إضافة أكثر من 6 خيارات');
        return;
    }
    
    const questionIndex = Array.from(document.querySelectorAll('.question-item')).indexOf(btn.closest('.question-item'));
    
    const newOption = document.createElement('div');
    newOption.className = 'option-item flex items-center gap-3';
    newOption.innerHTML = `
        <input type="radio" 
               name="questions[${questionIndex}][correct_answer]" 
               value="${optionCount}" 
               class="w-5 h-5 text-green-600 focus:ring-green-500"
               required>
        <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">${optionLetters[optionCount]}</span>
        <input type="text" 
               name="questions[${questionIndex}][options][]" 
               class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
               placeholder="خيار جديد">
    `;
    
    container.appendChild(newOption);
}

function removeOption(btn) {
    const container = btn.closest('.question-item').querySelector('.options-container');
    const optionCount = container.children.length;
    
    if (optionCount <= 2) {
        alert('يجب أن يكون هناك خياران على الأقل');
        return;
    }
    
    container.lastElementChild.remove();
}

function updateQuestionNumbers() {
    document.querySelectorAll('.question-item').forEach((item, index) => {
        item.querySelector('h3').textContent = `السؤال ${index + 1}`;
    });
}
</script>
@endsection