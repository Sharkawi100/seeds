@extends('layouts.app')

@push('styles')
<!-- Tailwind CSS Fallback -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/cmtwmtmif3u7ducaiqvogvq1wvc280ugtxjzo2ffaymjmuxg/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<style>
    /* Root color definitions */
    :root {
        --jawhar-color: #dc2626;
        --zihn-color: #2563eb;
        --waslat-color: #ea580c;
        --roaya-color: #7c3aed;
    }
    
    /* Force Tailwind styles to load properly */
    body {
        font-family: 'Tajawal', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
    }
    
    /* Modern card with colored left accent */
    .question-edit-card {
        position: relative;
        background: #ffffff;
        border-radius: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    
    .question-edit-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        transition: width 0.3s ease;
    }
    
    .question-edit-card:hover::before {
        width: 8px;
    }
    
    .question-edit-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: #d1d5db;
    }
    
    /* Root-specific accents */
    .question-edit-card.jawhar::before { background-color: var(--jawhar-color); }
    .question-edit-card.zihn::before { background-color: var(--zihn-color); }
    .question-edit-card.waslat::before { background-color: var(--waslat-color); }
    .question-edit-card.roaya::before { background-color: var(--roaya-color); }
    
    /* Number badge modern style */
    .number-badge {
        width: 3rem;
        height: 3rem;
        background: #ffffff;
        border: 3px solid;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
    }
    
    .number-badge.jawhar { 
        border-color: var(--jawhar-color); 
        color: var(--jawhar-color);
    }
    .number-badge.zihn { 
        border-color: var(--zihn-color); 
        color: var(--zihn-color);
    }
    .number-badge.waslat { 
        border-color: var(--waslat-color); 
        color: var(--waslat-color);
    }
    .number-badge.roaya { 
        border-color: var(--roaya-color); 
        color: var(--roaya-color);
    }
    
    /* Modern form inputs */
    .modern-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.2s ease;
        background-color: #ffffff;
        color: #111827;
    }
    
    .modern-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .modern-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.2s ease;
        background-color: #ffffff;
        color: #111827;
        resize: vertical;
        min-height: 80px;
    }
    
    .modern-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .modern-select {
        width: 100%;
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.2s ease;
        background-color: #ffffff;
        color: #111827;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.25rem;
    }
    
    .modern-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Option input styling */
    .option-input {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: #f9fafb;
        color: #111827;
    }
    
    .option-input:focus {
        outline: none;
        border-color: #3b82f6;
        background-color: #ffffff;
    }
    
    /* Option letter badge */
    .option-letter {
        width: 2rem;
        height: 2rem;
        background: #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: #4b5563;
        flex-shrink: 0;
    }
    
    /* Success animation */
    @keyframes checkmark {
        0% { transform: scale(0) rotate(45deg); opacity: 0; }
        50% { transform: scale(1.2) rotate(45deg); opacity: 1; }
        100% { transform: scale(1) rotate(45deg); opacity: 1; }
    }
    
    .success-checkmark {
        animation: checkmark 0.6s ease-out;
    }
    
    /* Loading spinner */
    .spinner {
        border: 3px solid #f3f4f6;
        border-top: 3px solid #3b82f6;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Sticky header */
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 40;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    /* Root selector button */
    .root-selector {
        padding: 0.5rem 1rem;
        border: 2px solid;
        border-radius: 0.5rem;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .root-selector.jawhar {
        border-color: #fee2e2;
        color: var(--jawhar-color);
    }
    .root-selector.jawhar.active {
        background-color: #fee2e2;
        border-color: var(--jawhar-color);
    }
    
    .root-selector.zihn {
        border-color: #dbeafe;
        color: var(--zihn-color);
    }
    .root-selector.zihn.active {
        background-color: #dbeafe;
        border-color: var(--zihn-color);
    }
    
    .root-selector.waslat {
        border-color: #fed7aa;
        color: var(--waslat-color);
    }
    .root-selector.waslat.active {
        background-color: #fed7aa;
        border-color: var(--waslat-color);
    }
    
    .root-selector.roaya {
        border-color: #e9d5ff;
        color: var(--roaya-color);
    }
    .root-selector.roaya.active {
        background-color: #e9d5ff;
        border-color: var(--roaya-color);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Sticky Header -->
    <div class="sticky-header bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                           class="text-gray-500 hover:text-gray-700 transition-all hover:scale-110">
                            <i class="fas fa-arrow-right text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">تعديل جميع الأسئلة</h1>
                            <p class="text-sm text-gray-600 mt-1">{{ $quiz->title }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button type="button" 
                                onclick="saveAllQuestions()"
                                class="px-6 py-2.5 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md hover:shadow-lg flex items-center gap-2"
                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                            <i class="fas fa-save"></i>
                            حفظ جميع التغييرات
                        </button>
                        
                        <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition-all">
                            إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Instructions Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-600 text-xl mt-0.5"></i>
            <div>
                <h3 class="font-semibold text-blue-900 mb-1">تعليمات التعديل الجماعي</h3>
                <p class="text-sm text-blue-800">يمكنك تعديل جميع الأسئلة في وقت واحد. التغييرات لن تُحفظ حتى تضغط على زر "حفظ جميع التغييرات".</p>
            </div>
        </div>

        <form id="bulk-edit-form" action="{{ route('quizzes.questions.bulk-update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                @foreach($quiz->questions as $index => $question)
                <div class="question-edit-card {{ $question->root_type }} p-6" data-question-index="{{ $index }}">
                    <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                    
                    <div class="flex items-start gap-4">
                        <!-- Question Number -->
                        <div class="number-badge {{ $question->root_type }}">
                            {{ $index + 1 }}
                        </div>
                        
                        <!-- Question Content -->
                        <div class="flex-1 space-y-4">
                            <!-- Question Text -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نص السؤال</label>
                                <textarea name="questions[{{ $index }}][question]" 
                                          class="modern-textarea"
                                          rows="3" 
                                          required>{{ $question->question }}</textarea>
                            </div>
                            
                            <!-- Root Type and Depth Level -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Root Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الجذر</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @php
                                            $roots = [
                                                'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯'],
                                                'zihn' => ['name' => 'ذِهن', 'icon' => '🧠'],
                                                'waslat' => ['name' => 'وَصلات', 'icon' => '🔗'],
                                                'roaya' => ['name' => 'رُؤية', 'icon' => '👁️']
                                            ];
                                        @endphp
                                        @foreach($roots as $rootKey => $root)
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="questions[{{ $index }}][root_type]" 
                                                   value="{{ $rootKey }}"
                                                   {{ $question->root_type == $rootKey ? 'checked' : '' }}
                                                   class="sr-only"
                                                   onchange="updateQuestionCard({{ $index }}, '{{ $rootKey }}')">
                                            <div class="root-selector {{ $rootKey }} {{ $question->root_type == $rootKey ? 'active' : '' }}">
                                                <span>{{ $root['icon'] }}</span>
                                                <span>{{ $root['name'] }}</span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Depth Level -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">مستوى العمق</label>
                                    <select name="questions[{{ $index }}][depth_level]" 
                                            class="modern-select">
                                        <option value="1" {{ $question->depth_level == 1 ? 'selected' : '' }}>🟡 المستوى 1 - سطحي</option>
                                        <option value="2" {{ $question->depth_level == 2 ? 'selected' : '' }}>🟠 المستوى 2 - متوسط</option>
                                        <option value="3" {{ $question->depth_level == 3 ? 'selected' : '' }}>🟢 المستوى 3 - عميق</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Answer Options -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="text-sm font-medium text-gray-700">خيارات الإجابة</label>
                                    <span class="text-xs text-gray-500">حدد الإجابة الصحيحة بالنقر على الدائرة</span>
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach($question->options as $optIndex => $option)
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all">
                                        <input type="radio" 
                                               name="questions[{{ $index }}][correct_answer]" 
                                               value="{{ $option }}"
                                               {{ $question->correct_answer == $option ? 'checked' : '' }}
                                               class="w-4 h-4 text-green-600 focus:ring-green-500 cursor-pointer"
                                               id="correct-{{ $index }}-{{ $optIndex }}">
                                        
                                        <label for="correct-{{ $index }}-{{ $optIndex }}" class="flex items-center gap-3 flex-1 cursor-pointer">
                                            <span class="option-letter">
                                                {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optIndex] ?? $optIndex + 1 }}
                                            </span>
                                            <input type="text" 
                                                   name="questions[{{ $index }}][options][]" 
                                                   value="{{ $option }}"
                                                   class="option-input"
                                                   placeholder="خيار {{ $optIndex + 1 }}"
                                                   required>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-8 max-w-sm mx-4 text-center transform scale-0 transition-transform" id="success-modal-content">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-green-600 text-3xl success-checkmark"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">تم الحفظ بنجاح!</h3>
        <p class="text-gray-600 mb-4">تم حفظ جميع التغييرات بنجاح</p>
        <button onclick="closeSuccessModal()" 
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-all">
            حسناً
        </button>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 flex items-center gap-4">
        <div class="spinner"></div>
        <span class="text-gray-700 font-medium">جاري حفظ التغييرات...</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Update question card root type dynamically
function updateQuestionCard(index, rootType) {
    const card = document.querySelector(`[data-question-index="${index}"]`);
    const badge = card.querySelector('.number-badge');
    const selectors = card.querySelectorAll('.root-selector');
    
    // Remove all root classes
    ['jawhar', 'zihn', 'waslat', 'roaya'].forEach(root => {
        card.classList.remove(root);
        badge.classList.remove(root);
    });
    
    // Add new root class
    card.classList.add(rootType);
    badge.classList.add(rootType);
    
    // Update selector states
    selectors.forEach(selector => {
        selector.classList.remove('active');
        if (selector.parentElement.querySelector(`input[value="${rootType}"]`)) {
            selector.classList.add('active');
        }
    });
}

// Save all questions
async function saveAllQuestions() {
    const form = document.getElementById('bulk-edit-form');
    const formData = new FormData(form);
    
    // Show loading
    document.getElementById('loading-overlay').classList.remove('hidden');
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            // Hide loading
            document.getElementById('loading-overlay').classList.add('hidden');
            
            // Show success modal
            const modal = document.getElementById('success-modal');
            const modalContent = document.getElementById('success-modal-content');
            modal.classList.remove('hidden');
            
            setTimeout(() => {
                modalContent.style.transform = 'scale(1)';
            }, 10);
            
            // Redirect after 2 seconds
            setTimeout(() => {
                window.location.href = '{{ route("quizzes.questions.index", $quiz) }}';
            }, 2000);
        } else {
            throw new Error('Failed to save');
        }
    } catch (error) {
        document.getElementById('loading-overlay').classList.add('hidden');
        alert('حدث خطأ أثناء الحفظ. يرجى المحاولة مرة أخرى.');
    }
}

// Close success modal
function closeSuccessModal() {
    const modal = document.getElementById('success-modal');
    const modalContent = document.getElementById('success-modal-content');
    
    modalContent.style.transform = 'scale(0)';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Auto-resize textareas
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('.modern-textarea');
    
    textareas.forEach(textarea => {
        // Initial resize
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
        
        // Resize on input
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
    
    // Add keyboard shortcut for save (Ctrl+S)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            saveAllQuestions();
        }
    });
});

// Add input validation feedback
document.querySelectorAll('.modern-input, .modern-textarea, .option-input').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value.trim() === '' && this.hasAttribute('required')) {
            this.style.borderColor = '#ef4444';
        } else {
            this.style.borderColor = '#e5e7eb';
        }
    });
    
    input.addEventListener('focus', function() {
        this.style.borderColor = '#3b82f6';
    });
});
</script>
@endpush