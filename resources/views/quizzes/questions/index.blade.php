@extends('layouts.app')

@section('title', 'إدارة الأسئلة - ' . $quiz->title)

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
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
                    <li class="text-gray-700 font-medium">إدارة الأسئلة</li>
                </ol>
            </nav>

            <!-- Title & Actions -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">إدارة الأسئلة</h1>
                    <p class="text-xl text-gray-600">{{ $quiz->title }}</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2 bg-purple-100 text-purple-700 px-4 py-2 rounded-full">
                        <i class="fas fa-question-circle"></i>
                        <span class="font-medium">{{ $quiz->questions->count() }} سؤال</span>
                    </div>
                    
                    @if(!$quiz->has_submissions)
                    <a href="{{ route('quizzes.questions.create', $quiz) }}" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        إضافة سؤال جديد
                    </a>
                    
                    @if($quiz->questions->count() > 1)
                    <a href="{{ route('quizzes.questions.bulk-edit', $quiz) }}" class="btn-secondary">
                        <i class="fas fa-edit"></i>
                        تعديل متعدد
                    </a>
                    @endif
                    @else
                    <div class="locked-badge">
                        <i class="fas fa-lock"></i>
                        لا يمكن التعديل - توجد محاولات
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Educational Passage Section -->
        @if($quiz->passage)
        <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        📖 {{ $quiz->passage_title ?: 'النص التعليمي' }}
                    </h2>
                    @if(!$quiz->has_submissions)
                    <button onclick="togglePassageEdit()" 
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-pen"></i>
                        تعديل النص
                    </button>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                <!-- View Mode -->
                <div id="passage-view">
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! $quiz->passage !!}
                    </div>
                </div>
                
                <!-- Edit Mode -->
                @if(!$quiz->has_submissions)
                <form id="passage-edit" class="hidden" action="{{ route('quizzes.update', $quiz) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="title" value="{{ $quiz->title }}">
                    <input type="hidden" name="subject" value="{{ $quiz->subject }}">
                    <input type="hidden" name="grade_level" value="{{ $quiz->grade_level }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان النص</label>
                            <input type="text" name="passage_title" value="{{ $quiz->passage_title }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">النص</label>
                            <textarea name="passage" id="passage-editor" class="tinymce-editor">{!! $quiz->passage !!}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="btn-success">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </button>
                            <button type="button" onclick="togglePassageEdit()" class="btn-cancel">
                                إلغاء
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
        @endif

        <!-- Questions Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        ❓ قائمة الأسئلة
                    </h2>
                    @if($quiz->questions->count() > 0)
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">{{ $quiz->questions->count() }} سؤال</span>
                        @if(!$quiz->has_submissions)
                        <button onclick="toggleSelectAll()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            تحديد الكل
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($quiz->questions as $index => $question)
                <div class="question-card {{ $question->root_type }} p-6 group relative">
                    <!-- Root color stripe -->
                    <div class="absolute right-0 top-0 bottom-0 w-1 bg-{{ $roots[$question->root_type]['color'] ?? 'gray' }}-500"></div>
                    
                    <div class="flex items-start gap-4 mr-3">
                        <div class="flex-shrink-0">
                            <div class="number-badge {{ $question->root_type }} w-12 h-12 rounded-full flex items-center justify-center font-bold text-white shadow-lg">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="root-tag {{ $question->root_type }} inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                                    @php
                                        $rootData = [
                                            'jawhar' => ['icon' => '🎯', 'name' => 'جَوهر', 'color' => 'red'],
                                            'zihn' => ['icon' => '🧠', 'name' => 'ذِهن', 'color' => 'blue'],
                                            'waslat' => ['icon' => '🔗', 'name' => 'وَصلات', 'color' => 'orange'],
                                            'roaya' => ['icon' => '👁️', 'name' => 'رُؤية', 'color' => 'purple']
                                        ];
                                        $root = $rootData[$question->root_type] ?? $rootData['jawhar'];
                                    @endphp
                                    {{ $root['icon'] }} {{ $root['name'] }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                                    <i class="fas fa-layer-group mr-1.5"></i>
                                    مستوى {{ $question->depth_level }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    @for($i = 1; $i <= 3; $i++)
                                        @if($i <= $question->depth_level)
                                            <span class="text-yellow-500">●</span>
                                        @else
                                            <span class="text-gray-300">●</span>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900 leading-relaxed">
                                    {!! $question->question !!}
                                </h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($question->options as $optionIndex => $option)
                                <div class="answer-option flex items-center gap-3 p-3 bg-gray-50 rounded-lg {{ $option === $question->correct_answer ? 'bg-green-50 border border-green-200' : '' }}">
                                    <span class="flex-shrink-0 w-8 h-8 {{ $option === $question->correct_answer ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full flex items-center justify-center text-sm font-bold">
                                        {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] ?? $optionIndex + 1 }}
                                    </span>
                                    <span class="{{ $option === $question->correct_answer ? 'text-green-700 font-medium' : 'text-gray-700' }} flex-1">
                                        {!! $option !!}
                                    </span>
                                    @if($option === $question->correct_answer)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        @if(!$quiz->has_submissions)
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-200">
                            <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" 
                               class="action-btn edit-btn">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('quizzes.questions.clone', [$quiz, $question]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="action-btn clone-btn" title="نسخ السؤال">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </form>
                            
                            <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')"
                                        class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <span class="text-4xl text-gray-400">❓</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد أسئلة بعد</h3>
                    <p class="text-gray-500 mb-6">ابدأ بإضافة أسئلة لهذا الاختبار</p>
                    <a href="{{ route('quizzes.questions.create', $quiz) }}" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        إضافة السؤال الأول
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        @if($quiz->questions->count() > 0)
        <div class="mt-8 text-center">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('quiz.take', $quiz) }}" 
                   class="btn-success" target="_blank">
                    <i class="fas fa-play"></i>
                    تجربة الاختبار
                </a>
                
                <a href="{{ route('quizzes.show', $quiz) }}" class="btn-secondary">
                    <i class="fas fa-eye"></i>
                    عرض الاختبار
                </a>
                
                @if(!$quiz->has_submissions)
                <a href="{{ route('quizzes.questions.create', $quiz) }}" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    إضافة أسئلة جديدة
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-secondary {
        background: white;
        color: #4f46e5;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 12px;
        border: 2px solid #4f46e5;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background: #eff6ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        color: #4f46e5;
        text-decoration: none;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .btn-cancel:hover {
        background: #e5e7eb;
        color: #374151;
    }
    
    /* Root-specific colors */
    .question-card.jawhar .number-badge { background: linear-gradient(135deg, #dc2626, #b91c1c); }
    .question-card.zihn .number-badge { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .question-card.waslat .number-badge { background: linear-gradient(135deg, #ea580c, #c2410c); }
    .question-card.roaya .number-badge { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
    
    .root-tag.jawhar { background: #fef2f2; color: #dc2626; }
    .root-tag.zihn { background: #eff6ff; color: #2563eb; }
    .root-tag.waslat { background: #fff7ed; color: #ea580c; }
    .root-tag.roaya { background: #f5f3ff; color: #7c3aed; }
    
    /* Action buttons */
    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .edit-btn {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .edit-btn:hover {
        background: #bfdbfe;
        transform: scale(1.1);
    }
    
    .clone-btn {
        background: #f3e8ff;
        color: #7c3aed;
    }
    
    .clone-btn:hover {
        background: #e9d5ff;
        transform: scale(1.1);
    }
    
    .delete-btn {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .delete-btn:hover {
        background: #fecaca;
        transform: scale(1.1);
    }
    
    .locked-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fef3c7;
        color: #d97706;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        border: 1px solid #fcd34d;
        font-size: 14px;
    }
    
    /* TinyMCE styling */
    .tox-tinymce {
        border-radius: 0.75rem !important;
        border-color: #d1d5db !important;
        border-width: 2px !important;
    }
    
    .tox-tinymce:focus-within {
        border-color: #a855f7 !important;
        box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1) !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-primary, .btn-secondary, .btn-success {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .number-badge {
            width: 10px;
            height: 10px;
            font-size: 14px;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
        }
    }
</style>
@endpush

@push('scripts')
<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/cmtwmtmif3u7ducaiqvogvq1wvc280ugtxjzo2ffaymjmuxg/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
// Initialize TinyMCE for passage editing
function initializeTinyMCE() {
    tinymce.init({
        selector: '.tinymce-editor',
        language: 'ar',
        directionality: 'rtl',
        height: 400,
        menubar: false,
        plugins: 'lists link charmap preview searchreplace autolink directionality code fullscreen table emoticons image media wordcount textcolor colorpicker',
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | bullist numlist | link image media | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | preview fullscreen | emoticons | wordcount',
        content_style: 'body { font-family: "Tajawal", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; font-size: 16px; line-height: 1.6; direction: rtl; }',
        branding: false,
        promotion: false,
        entity_encoding: 'raw',
        paste_as_text: false
    });
}

// Toggle passage edit mode
function togglePassageEdit() {
    const view = document.getElementById('passage-view');
    const edit = document.getElementById('passage-edit');
    
    if (view.classList.contains('hidden')) {
        // Switch to view mode
        view.classList.remove('hidden');
        edit.classList.add('hidden');
        if (tinymce.get('passage-editor')) {
            tinymce.get('passage-editor').remove();
        }
    } else {
        // Switch to edit mode
        view.classList.add('hidden');
        edit.classList.remove('hidden');
        initializeTinyMCE();
    }
}

// Select all functionality
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}

// Copy PIN function
function copyPIN(pin) {
    navigator.clipboard.writeText(pin).then(() => {
        showNotification('تم نسخ رمز الدخول بنجاح', 'success');
    }).catch(() => {
        showNotification('فشل في نسخ الرمز', 'error');
    });
}

// Notification system
function showNotification(message, type = 'success') {
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-pink-500',
        warning: 'from-yellow-500 to-orange-500',
        info: 'from-blue-500 to-cyan-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 transform translate-x-full transition-transform duration-300 z-50`;
    notification.innerHTML = `
        <i class="fas ${icons[type]} text-xl"></i>
        <p class="font-medium">${message}</p>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Any additional initialization can go here
    console.log('Questions management page loaded');
});
</script>
@endpush