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
    .question-card {
        position: relative;
        background: #ffffff;
        border-radius: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    
    .question-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        transition: width 0.3s ease;
    }
    
    .question-card:hover::before {
        width: 8px;
    }
    
    .question-card:hover {
        transform: translateX(6px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: #d1d5db;
    }
    
    /* Root-specific accents */
    .question-card.jawhar::before { background-color: var(--jawhar-color); }
    .question-card.zihn::before { background-color: var(--zihn-color); }
    .question-card.waslat::before { background-color: var(--waslat-color); }
    .question-card.roaya::before { background-color: var(--roaya-color); }
    
    /* Stats card modern design */
    .stats-card {
        background: #ffffff;
        border-radius: 1rem;
        border: 2px solid #e5e7eb;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .stats-card::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #d1d5db;
    }
    
    .stats-card:hover::after {
        opacity: 1;
    }
    
    .stats-card.jawhar::after { background-color: var(--jawhar-color); }
    .stats-card.zihn::after { background-color: var(--zihn-color); }
    .stats-card.waslat::after { background-color: var(--waslat-color); }
    .stats-card.roaya::after { background-color: var(--roaya-color); }
    
    /* Progress bar animations */
    @keyframes fillProgress {
        from { width: 0; }
    }
    
    .progress-fill {
        animation: fillProgress 1s ease-out forwards;
    }
    
    /* Number badge modern style */
    .number-badge {
        width: 3.5rem;
        height: 3.5rem;
        background: #ffffff;
        border: 3px solid;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
    
    .question-card:hover .number-badge {
        transform: scale(1.1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    /* Root tag modern style */
    .root-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid;
        transition: all 0.2s ease;
    }
    
    .root-tag.jawhar {
        background-color: #fee2e2;
        color: var(--jawhar-color);
        border-color: #fecaca;
    }
    .root-tag.zihn {
        background-color: #dbeafe;
        color: var(--zihn-color);
        border-color: #bfdbfe;
    }
    .root-tag.waslat {
        background-color: #fed7aa;
        color: var(--waslat-color);
        border-color: #fdba74;
    }
    .root-tag.roaya {
        background-color: #e9d5ff;
        color: var(--roaya-color);
        border-color: #d8b4fe;
    }
    
    /* Smooth transitions */
    * {
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Answer option cards */
    .answer-option {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }
    
    .answer-option.correct {
        background: #dcfce7;
        border-color: #86efac;
    }
    
    .answer-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Modern Header -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-40 backdrop-blur-lg bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('quizzes.show', $quiz) }}" 
                           class="text-gray-500 hover:text-gray-700 transition-all hover:scale-110">
                            <i class="fas fa-arrow-right text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $quiz->title }}</h1>
                            <div class="flex items-center gap-4 mt-1">
                                <span class="inline-flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-book"></i>
                                    {{ $quiz->subject_name }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="inline-flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-graduation-cap"></i>
                                    الصف {{ $quiz->grade_level }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="inline-flex items-center gap-2 bg-gray-100 px-3 py-1 rounded-full">
                                    <i class="fas fa-key text-gray-500"></i>
                                    <span class="font-mono font-bold">{{ $quiz->pin }}</span>
                                    <button onclick="copyPIN('{{ $quiz->pin }}')" 
                                            class="text-gray-500 hover:text-gray-700 ml-1">
                                        <i class="fas fa-copy text-xs"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        @if($quiz->questions->count() > 0)
                            <a href="{{ route('quiz.take', $quiz) }}" 
                               class="px-5 py-2.5 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md hover:shadow-lg flex items-center gap-2"
                               style="background: linear-gradient(135deg, #d97706 0%, #dc2626 100%); color: white; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: all 0.3s;">
                                <i class="fas fa-play-circle"></i>
                                معاينة الاختبار
                            </a>
                        @endif
                        
                        @if(!$quiz->has_submissions)
                            <a href="{{ route('quizzes.questions.bulk-edit', $quiz) }}" 
                               class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md hover:shadow-lg flex items-center gap-2">
                                <i class="fas fa-edit"></i>
                                تعديل جماعي
                            </a>
                            
                            <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md hover:shadow-lg flex items-center gap-2">
                                <i class="fas fa-plus-circle"></i>
                                إضافة سؤال
                            </a>
                        @else
                            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-2.5 rounded-lg flex items-center gap-2">
                                <i class="fas fa-lock"></i>
                                <span class="font-medium">الاختبار مقفل - لا يمكن التعديل</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Modern Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            @php
                $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                $roots = [
                    'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯'],
                    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠'],
                    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗'],
                    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️']
                ];
            @endphp
            
            @foreach($roots as $key => $root)
            <div class="stats-card {{ $key }}">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-4xl">{{ $root['icon'] }}</span>
                    <span class="text-3xl font-bold text-gray-800">{{ $rootCounts[$key] ?? 0 }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $root['name'] }}</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">من إجمالي</span>
                        <span class="font-bold text-gray-700">
                            {{ $quiz->questions->count() > 0 ? round(($rootCounts[$key] ?? 0) / $quiz->questions->count() * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="progress-fill h-full {{ $key }}" 
                             style="width: {{ $quiz->questions->count() > 0 ? (($rootCounts[$key] ?? 0) / $quiz->questions->count() * 100) : 0 }}%; background-color: var(--{{ $key }}-color);"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Passage Section (if exists) -->
        @if($quiz->passage)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="passage-header-section p-6" style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%);">
                <div class="flex items-center justify-between" style="display: flex; align-items: center; justify-content: space-between;">
                    <h2 class="text-xl font-semibold flex items-center gap-3" style="color: white; font-size: 1.25rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-file-alt" style="font-size: 1.5rem; color: white;"></i>
                        <span style="color: white;">{{ $quiz->passage_title ?: 'النص التعليمي' }}</span>
                    </h2>
                    @if(!$quiz->has_submissions)
                    <button onclick="togglePassageEdit()" 
                            class="px-5 py-2.5 rounded-lg transition-all" 
                            style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.625rem 1.25rem; border-radius: 0.5rem; border: 1px solid rgba(255, 255, 255, 0.3); transition: all 0.3s;"
                            onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'"
                            onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
                        <i class="fas fa-pen" style="margin-right: 0.5rem;"></i>
                        تعديل النص
                    </button>
                    @endif
                </div>
            </div>
            
            <div class="p-6 bg-gray-50">
                <div id="passage-view" class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap text-lg">{{ $quiz->passage }}</p>
                </div>
                
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
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">النص</label>
                            <textarea name="passage" id="passage-editor" class="text-gray-900">{{ $quiz->passage }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium">
                                <i class="fas fa-save mr-2"></i>
                                حفظ التغييرات
                            </button>
                            <button type="button" onclick="togglePassageEdit()" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2.5 rounded-lg font-medium">
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
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-3">
                        <i class="fas fa-question-circle text-gray-600"></i>
                        الأسئلة
                        <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">
                            {{ $quiz->questions->count() }}
                        </span>
                    </h2>
                </div>
            </div>

            @if($quiz->questions->isEmpty())
                <div class="p-20 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                        <i class="fas fa-inbox text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-3">لا توجد أسئلة بعد</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">ابدأ بإضافة أسئلة لهذا الاختبار لتوفير تقييم شامل وفقاً لنموذج جُذور التعليمي</p>
                    @if(!$quiz->has_submissions)
                    <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus-circle"></i>
                        إضافة أول سؤال
                    </a>
                    @endif
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($quiz->questions as $index => $question)
                    <div class="question-card {{ $question->root_type }} p-6 group">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="number-badge {{ $question->root_type }}">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-3">
                                            <span class="root-tag {{ $question->root_type }}">
                                                {{ $roots[$question->root_type]['icon'] }} {{ $roots[$question->root_type]['name'] }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                                                <i class="fas fa-layer-group mr-1.5"></i>
                                                مستوى {{ $question->depth_level }}
                                            </span>
                                        </div>
                                        <p class="text-lg text-gray-900 font-medium leading-relaxed">{!! $question->question !!}</p>
                                    </div>
                                    
                                    @if(!$quiz->has_submissions)
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-200 mr-4">
                                        <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" 
                                           class="text-blue-600 hover:text-blue-700 p-2 hover:bg-blue-50 rounded-lg transition-all">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('quizzes.questions.clone', [$quiz, $question]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-purple-600 hover:text-purple-700 p-2 hover:bg-purple-50 rounded-lg transition-all">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')"
                                                    class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-all">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                    @foreach($question->options as $optionIndex => $option)
                                    <div class="answer-option {{ $option === $question->correct_answer ? 'correct' : '' }} flex items-center gap-3">
                                        <span class="flex-shrink-0 w-8 h-8 {{ $option === $question->correct_answer ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }} rounded-full flex items-center justify-center text-sm font-bold">
                                            {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] ?? $optionIndex + 1 }}
                                        </span>
                                        <span class="{{ $option === $question->correct_answer ? 'text-green-700 font-medium' : 'text-gray-700' }}">
                                            {{ $option }}
                                        </span>
                                        @if($option === $question->correct_answer)
                                        <i class="fas fa-check-circle text-green-500 mr-auto"></i>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassageEdit() {
    const view = document.getElementById('passage-view');
    const edit = document.getElementById('passage-edit');
    
    if (view.classList.contains('hidden')) {
        view.classList.remove('hidden');
        edit.classList.add('hidden');
        if (tinymce.get('passage-editor')) {
            tinymce.get('passage-editor').remove();
        }
    } else {
        view.classList.add('hidden');
        edit.classList.remove('hidden');
        tinymce.init({
            selector: '#passage-editor',
            height: 400,
            language: 'ar',
            directionality: 'rtl',
            plugins: 'lists link image table code fullscreen',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image | code fullscreen',
            menubar: false,
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; font-size: 16px; line-height: 1.6; }'
        });
    }
}

function copyPIN(pin) {
    navigator.clipboard.writeText(pin).then(() => {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
        toast.innerHTML = '<i class="fas fa-check-circle"></i> تم نسخ رمز الدخول';
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
        }, 2000);
        
        setTimeout(() => toast.remove(), 2500);
    });
}

// Add smooth scroll animations
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    // Observe all question cards
    document.querySelectorAll('.question-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transitionDelay = `${index * 50}ms`;
        observer.observe(card);
    });
});
</script>
@endpush