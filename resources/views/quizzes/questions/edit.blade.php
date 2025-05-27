@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <h2 class="text-3xl font-bold">تعديل السؤال</h2>
                <p class="mt-2">{{ $quiz->title }}</p>
            </div>
            
            <form action="{{ route('quizzes.questions.update', [$quiz, $question]) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')
                
                <!-- Question Text -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نص السؤال</label>
                    <textarea name="question" 
                              class="w-full p-4 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                              rows="3" 
                              required>{{ $question->question }}</textarea>
                </div>
                
                <!-- Root Type and Depth Level -->
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع الجذر</label>
                        <select name="root_type" 
                                class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                required>
                            <option value="jawhar" {{ $question->root_type == 'jawhar' ? 'selected' : '' }}>🎯 جَوهر - ما هو؟</option>
                            <option value="zihn" {{ $question->root_type == 'zihn' ? 'selected' : '' }}>🧠 ذِهن - كيف يعمل؟</option>
                            <option value="waslat" {{ $question->root_type == 'waslat' ? 'selected' : '' }}>🔗 وَصلات - كيف يرتبط؟</option>
                            <option value="roaya" {{ $question->root_type == 'roaya' ? 'selected' : '' }}>👁️ رُؤية - كيف نستخدمه؟</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">مستوى العمق</label>
                        <select name="depth_level" 
                                class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                required>
                            <option value="1" {{ $question->depth_level == 1 ? 'selected' : '' }}>🟡 سطحي - المستوى 1</option>
                            <option value="2" {{ $question->depth_level == 2 ? 'selected' : '' }}>🟠 متوسط - المستوى 2</option>
                            <option value="3" {{ $question->depth_level == 3 ? 'selected' : '' }}>🟢 عميق - المستوى 3</option>
                        </select>
                    </div>
                </div>
                
                <!-- Answer Options -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-sm font-medium text-gray-700">خيارات الإجابة</label>
                        <div class="flex gap-2">
                            <button type="button" 
                                    onclick="removeOption()" 
                                    class="text-sm text-red-600 hover:text-red-800 transition">
                                ➖ إزالة خيار
                            </button>
                            <button type="button" 
                                    onclick="addOption()" 
                                    class="text-sm text-blue-600 hover:text-blue-800 transition">
                                ➕ إضافة خيار
                            </button>
                        </div>
                    </div>
                    
                    <div id="options-container" class="space-y-2">
                        @foreach($question->options as $index => $option)
                        <div class="option-item flex items-center gap-3">
                            <input type="radio" 
                                   name="correct_answer_index" 
                                   value="{{ $index }}" 
                                   class="w-5 h-5 text-green-600 focus:ring-green-500"
                                   {{ $question->correct_answer == $option ? 'checked' : '' }}
                                   required>
                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">{{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$index] }}</span>
                            <input type="text" 
                                   name="options[]" 
                                   value="{{ $option }}" 
                                   class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                   required>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-500 mt-2">حدد الإجابة الصحيحة بالنقر على الدائرة بجانب الخيار</p>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-lg font-bold hover:from-blue-700 hover:to-purple-700 transition">
                        حفظ التعديلات
                    </button>
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                       class="px-8 py-4 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const optionLetters = ['أ', 'ب', 'ج', 'د', 'هـ', 'و'];

function addOption() {
    const container = document.getElementById('options-container');
    const optionCount = container.children.length;
    
    if (optionCount >= 6) {
        alert('لا يمكن إضافة أكثر من 6 خيارات');
        return;
    }
    
    const newOption = document.createElement('div');
    newOption.className = 'option-item flex items-center gap-3';
    newOption.innerHTML = `
        <input type="radio" 
               name="correct_answer_index" 
               value="${optionCount}" 
               class="w-5 h-5 text-green-600 focus:ring-green-500"
               required>
        <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">${optionLetters[optionCount]}</span>
        <input type="text" 
               name="options[]" 
               class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
               placeholder="خيار جديد"
               required>
    `;
    
    container.appendChild(newOption);
}

function removeOption() {
    const container = document.getElementById('options-container');
    const optionCount = container.children.length;
    
    if (optionCount <= 2) {
        alert('يجب أن يكون هناك خياران على الأقل');
        return;
    }
    
    container.lastElementChild.remove();
}
</script>
@endsection