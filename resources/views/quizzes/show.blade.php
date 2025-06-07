@extends('layouts.app')

@section('title', $quiz->title)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    body, * {
        font-family: 'Tajawal', sans-serif !important;
    }
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    
    .root-stripe {
        width: 6px;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        border-radius: 8px 0 0 8px;
    }
    
    .root-stripe.jawhar { background: #ef4444; }
    .root-stripe.zihn { background: #06b6d4; }
    .root-stripe.waslat { background: #eab308; }
    .root-stripe.roaya { background: #9333ea; }
    
    .progress-ring {
        transform: rotate(-90deg);
    }
    
    .progress-ring__circle {
        transition: stroke-dashoffset 0.5s ease;
    }
    
    .question-preview {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .question-preview:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
    }
    
    .option-badge {
        background: #f9fafb;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        font-weight: 500;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.3s;
        border: none;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background: white;
        color: #3b82f6;
        font-weight: 500;
        padding: 12px 24px;
        border-radius: 12px;
        border: 2px solid #3b82f6;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-secondary:hover {
        background: #eff6ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn-danger {
        background: white;
        color: #ef4444;
        font-weight: 500;
        padding: 12px 24px;
        border-radius: 12px;
        border: 2px solid #ef4444;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-danger:hover {
        background: #fef2f2;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .pin-card {
        background: linear-gradient(135deg, #eff6ff 0%, #f5f3ff 100%);
        border: 2px solid #3b82f6;
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
    }
    
    .question-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    .answers-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 16px;
    }
    
    @media (max-width: 768px) {
        .answers-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .answer-option {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #f9fafb;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    
    .answer-option:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
        transform: translateX(-2px);
    }
    
    .answer-option-letter {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6b7280;
    }
    
    .answer-option-text {
        flex: 1;
        color: #4b5563;
        line-height: 1.5;
    }
    
    .passage-collapsed {
        max-height: 200px;
        overflow: hidden;
        position: relative;
    }
    
    .passage-collapsed::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 80px;
        background: linear-gradient(to bottom, transparent, white);
    }
    
    .passage-content {
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition">🏠 الرئيسية</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('quizzes.index') }}" class="text-gray-600 hover:text-blue-600 transition">📚 اختباراتي</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">{{ $quiz->title }}</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $quiz->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4">
                        <span class="inline-flex items-center gap-2 text-gray-600">
                            <span class="text-lg">📚</span>
                            <span class="font-medium">{{ $quiz->subject_name }}</span>
                        </span>
                        <span class="inline-flex items-center gap-2 text-gray-600">
                            <span class="text-lg">🎓</span>
                            <span class="font-medium">الصف {{ $quiz->grade_level }}</span>
                        </span>
                        <span class="inline-flex items-center gap-2 text-gray-600">
                            <span class="text-lg">📅</span>
                            <span class="font-medium">{{ $quiz->created_at->format('Y/m/d') }}</span>
                        </span>
                        @if($quiz->has_submissions)
                            <span class="locked-badge">
                                🔒 الاختبار مقفل
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('quiz.take', $quiz) }}" class="btn-primary">
                        ▶️ بدء الاختبار
                    </a>
                    
                    @if($quiz->has_submissions)
                        <a href="#" class="btn-secondary" onclick="alert('يرجى إضافة وظيفة النسخ'); return false;">
                            📋 نسخ الاختبار
                        </a>
                    @else
                        <a href="{{ route('quizzes.edit', $quiz) }}" class="btn-secondary">
                            ✏️ تعديل
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- PIN Display Card -->
        <div class="pin-card rounded-2xl shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">🚀 رمز الدخول السريع</h3>
                    <p class="text-gray-600">شارك هذا الرمز مع الطلاب للدخول المباشر</p>
                </div>
                <div class="mt-4 md:mt-0 text-center">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <p class="text-4xl font-bold tracking-wider text-blue-600 mb-3">{{ $quiz->pin }}</p>
                        <div class="flex gap-3 justify-center">
                            <button onclick="copyPIN('{{ $quiz->pin }}')"
                                    class="text-blue-600 hover:text-blue-800 font-medium transition">
                                📋 نسخ
                            </button>
                            <button onclick="shareQuiz('{{ $quiz->pin }}', '{{ $quiz->title }}')"
                                    class="text-blue-600 hover:text-blue-800 font-medium transition">
                                📤 مشاركة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <span class="font-medium">رابط الدخول المباشر:</span> {{ url('/') }}/?pin={{ $quiz->pin }}
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Questions -->
            <div class="bg-white rounded-xl shadow-sm p-5 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">عدد الأسئلة</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $quiz->questions->count() }}</p>
                    </div>
                    <div class="relative w-16 h-16">
                        <svg class="progress-ring w-16 h-16">
                            <circle class="progress-ring__circle" stroke="#e5e7eb" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"></circle>
                            <circle class="progress-ring__circle" stroke="#3b82f6" stroke-width="4" fill="transparent" r="28" cx="32" cy="32" 
                                    style="stroke-dasharray: 176; stroke-dashoffset: {{ 176 - (176 * min($quiz->questions->count() / 50, 1)) }}"></circle>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-2xl">📝</span>
                    </div>
                </div>
            </div>

            <!-- Attempts -->
            <div class="bg-white rounded-xl shadow-sm p-5 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">المحاولات</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $quiz->results->count() }}</p>
                    </div>
                    <div class="relative w-16 h-16">
                        <svg class="progress-ring w-16 h-16">
                            <circle class="progress-ring__circle" stroke="#e5e7eb" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"></circle>
                            <circle class="progress-ring__circle" stroke="#10b981" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"
                                    style="stroke-dasharray: 176; stroke-dashoffset: {{ 176 - (176 * min($quiz->results->count() / 100, 1)) }}"></circle>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-2xl">👥</span>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-xl shadow-sm p-5 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">متوسط النجاح</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $quiz->results->count() > 0 ? round($quiz->results->avg('total_score')) : '--' }}%
                        </p>
                    </div>
                    <div class="relative w-16 h-16">
                        @php $avgScore = $quiz->results->avg('total_score') ?? 0; @endphp
                        <svg class="progress-ring w-16 h-16">
                            <circle class="progress-ring__circle" stroke="#e5e7eb" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"></circle>
                            <circle class="progress-ring__circle" stroke="#eab308" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"
                                    style="stroke-dasharray: 176; stroke-dashoffset: {{ 176 - (176 * ($avgScore / 100)) }}"></circle>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-2xl">⭐</span>
                    </div>
                </div>
            </div>

            <!-- Last Update -->
            <div class="bg-white rounded-xl shadow-sm p-5 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">آخر تحديث</p>
                        <p class="text-base font-bold text-gray-900">{{ $quiz->updated_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-3xl">🕐</span>
                </div>
            </div>
        </div>

        <!-- Root Distribution -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">📊 توزيع الأسئلة حسب الجذور</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @php
                    $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                    $totalQuestions = $quiz->questions->count();
                    $roots = [
                        'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'red'],
                        'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'cyan'],
                        'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'yellow'],
                        'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'purple']
                    ];
                @endphp
                
                @foreach($roots as $key => $root)
                <div class="bg-white rounded-xl shadow-sm p-4 hover-lift border-r-4 border-{{ $root['color'] }}-500">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">{{ $root['icon'] }}</span>
                            <h3 class="font-bold text-gray-900">{{ $root['name'] }}</h3>
                        </div>
                        <span class="text-2xl font-bold text-{{ $root['color'] }}-600">
                            {{ $rootCounts[$key] ?? 0 }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-{{ $root['color'] }}-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ $totalQuestions > 0 ? (($rootCounts[$key] ?? 0) / $totalQuestions) * 100 : 0 }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        {{ $totalQuestions > 0 ? round((($rootCounts[$key] ?? 0) / $totalQuestions) * 100) : 0 }}% من الأسئلة
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Educational Passage -->
        @if($quiz->passage)
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        📖 {{ $quiz->passage_title ?: 'النص التعليمي' }}
                    </h2>
                    <button onclick="togglePassage()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <span id="toggleText">إخفاء</span>
                        <svg id="toggleIcon" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="passageContent" class="passage-content">
                <div class="p-6">
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($quiz->passage)) !!}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Questions Preview -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    ❓ معاينة الأسئلة
                </h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($quiz->questions as $index => $question)
                <div class="question-preview p-6 hover:bg-gray-50 transition-colors relative">
                    <div class="root-stripe {{ $question->root_type }}"></div>
                    
                    <div class="flex items-start gap-4 mr-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center font-bold text-gray-700 text-lg shadow-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-{{ $roots[$question->root_type]['color'] }}-100 text-{{ $roots[$question->root_type]['color'] }}-700 font-medium">
                                    {{ $roots[$question->root_type]['icon'] }} {{ $roots[$question->root_type]['name'] }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    @for($i = 1; $i <= 3; $i++)
                                        @if($i <= $question->depth_level)
                                            <span class="text-yellow-500">●</span>
                                        @else
                                            <span class="text-gray-300">●</span>
                                        @endif
                                    @endfor
                                    المستوى {{ $question->depth_level }}
                                </span>
                            </div>
                            
                            <h3 class="question-title">{!! $question->question !!}</h3>
                            
                            <div class="answers-grid">
                                @foreach($question->options as $optionIndex => $option)
                                <div class="answer-option">
                                    <div class="answer-option-letter">
                                        {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] ?? $optionIndex + 1 }}
                                    </div>
                                    <div class="answer-option-text">
                                        {{ $option }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <span class="text-6xl mb-4 block">❓</span>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد أسئلة بعد</h3>
                    <p class="text-gray-500">ابدأ بإضافة أسئلة لهذا الاختبار</p>
                </div>
                @endforelse
            </div>
            
            @if($quiz->questions->count() > 0)
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium text-sm transition">
                        🔧 إدارة الأسئلة
                    </a>
                    <span class="text-sm text-gray-600">
                        إجمالي {{ $quiz->questions->count() }} سؤال
                    </span>
                </div>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('quiz.take', $quiz) }}" class="btn-primary text-center">
                ▶️ بدء الاختبار
            </a>
            <a href="{{ route('quizzes.questions.create', $quiz) }}" class="btn-secondary text-center">
                ➕ إضافة أسئلة
            </a>
            <a href="{{ route('quizzes.index') }}" class="btn-secondary text-center">
                ↩️ العودة للقائمة
            </a>
            @if($quiz->results->count() > 0)
    <a href="{{ route('results.quiz', $quiz->id) }}" class="btn-secondary">
        📊 عرض النتائج ({{ $quiz->results->count() }})
    </a>
@endif
        </div>
        
    </div>
</div>

<script>
function copyPIN(pin) {
    navigator.clipboard.writeText(pin).then(() => {
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = '✅ تم النسخ';
        setTimeout(() => {
            button.textContent = originalText;
        }, 2000);
    });
}

function shareQuiz(pin, title) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: `رمز دخول الاختبار: ${pin}`,
            url: window.location.href
        });
    } else {
        copyPIN(pin);
    }
}

function togglePassage() {
    const content = document.getElementById('passageContent');
    const toggleText = document.getElementById('toggleText');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (!content) {
        console.error('Passage content not found');
        return;
    }
    
    if (content.classList.contains('passage-collapsed')) {
        content.classList.remove('passage-collapsed');
        toggleText.textContent = 'إخفاء';
        toggleIcon.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('passage-collapsed');
        toggleText.textContent = 'عرض';
        toggleIcon.style.transform = 'rotate(180deg)';
    }
}

// Start with passage collapsed if it's longer than 400px
window.addEventListener('load', function() {
    const passageContent = document.getElementById('passageContent');
    if (passageContent && passageContent.scrollHeight > 400) {
        togglePassage();
    }
});
</script>

@push('scripts')
<script>
// Additional scripts can go here
</script>
@endpush
@endsection