@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">إدارة الأسئلة</h1>
                        <h2 class="text-xl text-white/90">{{ $quiz->title }}</h2>
                        <div class="mt-2 flex gap-4 text-sm text-white/80">
                            <span>📚 {{ $quiz->subject == 'arabic' ? 'اللغة العربية' : ($quiz->subject == 'english' ? 'اللغة الإنجليزية' : 'اللغة العبرية') }}</span>
                            <span>📊 الصف {{ $quiz->grade_level }}</span>
                            <span>❓ {{ $quiz->questions->count() }} سؤال</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="toggleBulkEdit()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            تحديد متعدد
                        </button>
                        <a href="{{ route('quiz.take', $quiz) }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            معاينة
                        </a>
                        <a href="{{ route('quizzes.questions.create', $quiz) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            إضافة أسئلة جديدة
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="bg-gray-50 p-4">
                <div class="grid grid-cols-4 gap-4">
                    @php
                        $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                        $depthCounts = $quiz->questions->groupBy('depth_level')->map->count();
                    @endphp
                    <div class="bg-red-50 rounded-lg p-3 text-center border border-red-200">
                        <div class="text-2xl font-bold text-red-600">{{ $rootCounts['jawhar'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">🎯 جَوْهَر</div>
                    </div>
                    <div class="bg-cyan-50 rounded-lg p-3 text-center border border-cyan-200">
                        <div class="text-2xl font-bold text-cyan-600">{{ $rootCounts['zihn'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">🧠 ذِهْن</div>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-3 text-center border border-yellow-200">
                        <div class="text-2xl font-bold text-yellow-600">{{ $rootCounts['waslat'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">🔗 وَصَلات</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-3 text-center border border-purple-200">
                        <div class="text-2xl font-bold text-purple-600">{{ $rootCounts['roaya'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">👁️ رُؤْيَة</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        <div id="bulkActionsBar" class="hidden bg-blue-600 text-white rounded-lg p-4 mb-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="w-5 h-5">
                <span>تحديد الكل</span>
                <span id="selectedCount">0 محدد</span>
            </div>
            <div class="flex gap-2">
                <button onclick="bulkDelete()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    حذف المحدد
                </button>
            </div>
        </div>

        <!-- Reading Passage Section (if exists) -->
        @if($quiz->questions->where('passage', '!=', null)->first())
        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-3 mb-4">
                <span class="text-3xl">📄</span>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-blue-900 mb-2">
                        {{ $quiz->questions->first()->passage_title ?? 'نص القراءة' }}
                    </h3>
                    <div class="text-gray-700 bg-white/50 rounded-lg p-4" id="passageContent">
                        {!! nl2br(e($quiz->questions->first()->passage)) !!}
                    </div>
                </div>
                <button onclick="editPassage()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm">
                    تعديل النص
                </button>
            </div>
        </div>
        @endif

        <!-- Questions List -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">الأسئلة</h3>
                <div class="flex gap-2">
                    <select id="filterRoot" onchange="filterQuestions()" class="px-3 py-2 border rounded-lg text-sm">
                        <option value="">جميع الجذور</option>
                        <option value="jawhar">جَوْهَر</option>
                        <option value="zihn">ذِهْن</option>
                        <option value="waslat">وَصَلات</option>
                        <option value="roaya">رُؤْيَة</option>
                    </select>
                    <select id="filterDepth" onchange="filterQuestions()" class="px-3 py-2 border rounded-lg text-sm">
                        <option value="">جميع المستويات</option>
                        <option value="1">مستوى 1</option>
                        <option value="2">مستوى 2</option>
                        <option value="3">مستوى 3</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($quiz->questions as $index => $question)
                <div class="question-item border-2 border-gray-200 rounded-xl p-5 hover:border-blue-300 hover:shadow-md transition group" 
                     data-root="{{ $question->root_type }}" 
                     data-depth="{{ $question->depth_level }}"
                     data-question-id="{{ $question->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <!-- Question Header -->
                            <div class="flex items-center gap-3 mb-3">
                                <input type="checkbox" class="question-checkbox hidden w-5 h-5" value="{{ $question->id }}">
                                <span class="text-2xl font-bold text-gray-400">{{ $index + 1 }}</span>
                                <span class="px-4 py-1.5 rounded-full text-sm font-bold flex items-center gap-1"
                                      style="background-color: {{ 
                                          $question->root_type == 'jawhar' ? '#fee2e2' : 
                                          ($question->root_type == 'zihn' ? '#e0f2fe' : 
                                          ($question->root_type == 'waslat' ? '#fef3c7' : '#ede9fe')) 
                                      }}">
                                    @if($question->root_type == 'jawhar')
                                        <span>🎯</span> جَوْهَر
                                    @elseif($question->root_type == 'zihn')
                                        <span>🧠</span> ذِهْن
                                    @elseif($question->root_type == 'waslat')
                                        <span>🔗</span> وَصَلات
                                    @else
                                        <span>👁️</span> رُؤْيَة
                                    @endif
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ 
                                    $question->depth_level == 1 ? 'bg-yellow-100 text-yellow-800' : 
                                    ($question->depth_level == 2 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') 
                                }}">
                                    مستوى {{ $question->depth_level }}
                                </span>
                            </div>
                            
                            <!-- Question Text -->
                            <div class="question-text-container">
                                <div class="question-display">
                                    <p class="font-medium text-lg text-gray-800 mb-4">{!! $question->question !!}</p>
                                </div>
                                <div class="question-edit hidden">
                                    <textarea class="question-editor" id="editor-{{ $question->id }}">{{ $question->question }}</textarea>
                                    <div class="mt-2 flex gap-2">
                                        <button onclick="saveQuestion({{ $question->id }})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">حفظ</button>
                                        <button onclick="cancelEdit({{ $question->id }})" class="bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600">إلغاء</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Answer Options -->
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($question->options as $optionIndex => $option)
                                <div class="flex items-center gap-2 p-2 rounded-lg {{ $option == $question->correct_answer ? 'bg-green-50 text-green-700 font-bold' : 'bg-gray-50 text-gray-600' }}">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $option == $question->correct_answer ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }}">
                                        {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] }}
                                    </span>
                                    <span>{{ $option }}</span>
                                    @if($option == $question->correct_answer)
                                        <svg class="w-4 h-4 text-green-600 mr-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-2 mr-4 opacity-0 group-hover:opacity-100 transition">
                            <button onclick="editInline({{ $question->id }})" 
                                    class="bg-purple-100 hover:bg-purple-200 text-purple-700 p-2 rounded-lg transition"
                                    title="تعديل سريع">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" 
                               class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition"
                               title="تعديل كامل">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')"
                                        title="حذف">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Bottom Actions -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t-2 border-gray-200">
                <div class="flex gap-4">
                    <a href="{{ route('quizzes.show', $quiz) }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-bold transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        انتهيت من التعديل
                    </a>
                    <a href="{{ route('quizzes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-bold transition">
                        العودة لقائمة الاختبارات
                    </a>
                </div>
                
                <!-- Chart Preview -->
                <div class="bg-gray-50 rounded-lg p-4">
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

<script src="https://cdn.tiny.cloud/1/cmtwmtmif3u7ducaiqvogvq1wvc280ugtxjzo2ffaymjmuxg/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
let activeEditor = null;
let bulkMode = false;

// Inline editing
function editInline(questionId) {
    document.querySelectorAll('.question-edit').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.question-display').forEach(el => el.classList.remove('hidden'));
    
    const container = document.querySelector(`[data-question-id="${questionId}"] .question-text-container`);
    container.querySelector('.question-display').classList.add('hidden');
    container.querySelector('.question-edit').classList.remove('hidden');
    
    if (activeEditor) {
        tinymce.remove(activeEditor);
    }
    
    tinymce.init({
        selector: `#editor-${questionId}`,
        language: 'ar',
        directionality: 'rtl',
        height: 200,
        menubar: false,
        plugins: 'lists link charmap',
        toolbar: 'bold italic underline | bullist numlist | link | removeformat',
        content_style: 'body { font-family: Arial, sans-serif; font-size: 16px; direction: rtl; }',
        setup: function(editor) {
            activeEditor = editor;
        }
    });
}

function cancelEdit(questionId) {
    const container = document.querySelector(`[data-question-id="${questionId}"] .question-text-container`);
    container.querySelector('.question-display').classList.remove('hidden');
    container.querySelector('.question-edit').classList.add('hidden');
    
    if (activeEditor) {
        tinymce.remove(activeEditor);
        activeEditor = null;
    }
}

async function saveQuestion(questionId) {
    const content = tinymce.get(`editor-${questionId}`).getContent();
    
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
            const container = document.querySelector(`[data-question-id="${questionId}"] .question-text-container`);
            container.querySelector('.question-display p').innerHTML = content;
            cancelEdit(questionId);
        }
    } catch (error) {
        alert('حدث خطأ في الحفظ');
    }
}

// Filtering
function filterQuestions() {
    const rootFilter = document.getElementById('filterRoot').value;
    const depthFilter = document.getElementById('filterDepth').value;
    
    document.querySelectorAll('.question-item').forEach(item => {
        const matchRoot = !rootFilter || item.dataset.root === rootFilter;
        const matchDepth = !depthFilter || item.dataset.depth === depthFilter;
        item.style.display = matchRoot && matchDepth ? '' : 'none';
    });
}

// Bulk operations
function toggleBulkEdit() {
    bulkMode = !bulkMode;
    document.getElementById('bulkActionsBar').classList.toggle('hidden');
    document.querySelectorAll('.question-checkbox').forEach(cb => {
        cb.classList.toggle('hidden');
        cb.checked = false;
    });
    updateSelectedCount();
}

function toggleSelectAll() {
    const isChecked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.question-checkbox:not(.hidden)').forEach(cb => {
        cb.checked = isChecked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.question-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count + ' محدد';
}

async function bulkDelete() {
    const selected = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;
    
    if (!confirm(`حذف ${selected.length} سؤال؟`)) return;
    
    try {
        const response = await fetch(`/quizzes/{{ $quiz->id }}/questions/bulk-delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: selected })
        });
        
        if (response.ok) {
            location.reload();
        }
    } catch (error) {
        alert('حدث خطأ');
    }
}

document.querySelectorAll('.question-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});
</script>
@endsection