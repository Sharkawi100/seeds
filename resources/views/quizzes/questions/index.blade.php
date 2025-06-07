@extends('layouts.app')

@section('title', 'أسئلة: ' . $quiz->title)

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
    
    .question-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .question-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
    }
    
    .option-box {
        background: #f9fafb;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        color: #4b5563;
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    
    .option-box:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
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
    }
    
    .btn-secondary:hover {
        background: #eff6ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
                <li><a href="{{ route('quizzes.show', $quiz) }}" class="text-gray-600 hover:text-blue-600 transition">{{ $quiz->title }}</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">الأسئلة</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <span class="inline-flex items-center gap-1 text-gray-600">
                            <span>📚</span> {{ $quiz->subject_name }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-gray-600">
                            <span>🎓</span> الصف {{ $quiz->grade_level }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-gray-600">
                            <span>📅</span> {{ $quiz->created_at->format('Y/m/d') }}
                        </span>
                    </div>
                </div>
                
                <!-- PIN Display -->
                <div class="mt-4 md:mt-0 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-200">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">رمز الدخول</p>
                        <p class="text-2xl font-bold text-blue-600 tracking-wider">{{ $quiz->pin }}</p>
                        <button onclick="copyPIN('{{ $quiz->pin }}')" class="mt-2 text-sm text-blue-600 hover:text-blue-800 transition">
                            📋 نسخ
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Questions -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-blue-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">إجمالي الأسئلة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $quiz->questions->count() }}</p>
                    </div>
                    <div class="relative w-16 h-16">
                        <svg class="progress-ring w-16 h-16">
                            <circle class="progress-ring__circle" stroke="#e5e7eb" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"></circle>
                            <circle class="progress-ring__circle" stroke="#3b82f6" stroke-width="4" fill="transparent" r="28" cx="32" cy="32" 
                                    style="stroke-dasharray: 176; stroke-dashoffset: {{ 176 - (176 * ($quiz->questions->count() / 100)) }}"></circle>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-2xl">📝</span>
                    </div>
                </div>
            </div>

            <!-- Attempts -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-green-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">المحاولات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $quiz->results->count() }}</p>
                    </div>
                    <div class="relative w-16 h-16">
                        <svg class="progress-ring w-16 h-16">
                            <circle class="progress-ring__circle" stroke="#e5e7eb" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"></circle>
                            <circle class="progress-ring__circle" stroke="#10b981" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"
                                    style="stroke-dasharray: 176; stroke-dashoffset: {{ 176 - (176 * min($quiz->results->count() / 50, 1)) }}"></circle>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-2xl">👥</span>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-yellow-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">متوسط النجاح</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $quiz->results->count() > 0 ? round($quiz->results->avg('total_score')) : '--' }}%
                        </p>
                    </div>
                    <div class="relative w-16 h-16">
                        <svg class="progress-ring w-16 h-16">
                            <circle class="progress-ring__circle" stroke="#e5e7eb" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"></circle>
                            <circle class="progress-ring__circle" stroke="#eab308" stroke-width="4" fill="transparent" r="28" cx="32" cy="32"
                                    style="stroke-dasharray: 176; stroke-dashoffset: {{ 176 - (176 * (($quiz->results->avg('total_score') ?? 0) / 100)) }}"></circle>
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-2xl">📊</span>
                    </div>
                </div>
            </div>

            <!-- Time Limit -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-r-4 border-purple-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">مدة الاختبار</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $quiz->time_limit ? $quiz->time_limit . ' دقيقة' : 'غير محدد' }}
                        </p>
                    </div>
                    <span class="text-3xl">⏱️</span>
                </div>
            </div>
        </div>

        <!-- Root Distribution Cards -->
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

        <!-- Questions List -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">📋 قائمة الأسئلة</h2>
                @if(!$quiz->has_submissions)
                <a href="{{ route('quizzes.questions.create', $quiz) }}" class="btn-primary">
                    ➕ إضافة سؤال
                </a>
                @endif
            </div>

            @if($quiz->questions->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <span class="text-6xl mb-4 block">❓</span>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد أسئلة بعد</h3>
                    <p class="text-gray-500 mb-6">ابدأ بإضافة أسئلة لهذا الاختبار</p>
                    <a href="{{ route('quizzes.questions.create', $quiz) }}" class="btn-primary inline-block">
                        ➕ إضافة أول سؤال
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($quiz->questions as $index => $question)
                    <div class="question-card bg-white rounded-xl shadow-sm p-5 relative overflow-hidden">
                        <div class="root-stripe {{ $question->root_type }}"></div>
                        
                        <div class="flex items-start gap-4 mr-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center font-bold text-gray-700">
                                {{ $index + 1 }}
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-{{ $roots[$question->root_type]['color'] }}-100 text-{{ $roots[$question->root_type]['color'] }}-700">
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
                                
                                <p class="text-gray-900 text-lg mb-4 leading-relaxed">{!! $question->question !!}</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                                    @foreach($question->options as $optionIndex => $option)
                                    <div class="option-box flex items-center gap-2">
                                        <span class="flex-shrink-0 w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center text-xs font-medium">
                                            {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] ?? $optionIndex + 1 }}
                                        </span>
                                        <span class="{{ $option === $question->correct_answer ? 'text-green-600 font-medium' : '' }}">
                                            {{ $option }}
                                            @if($option === $question->correct_answer)
                                                <span class="text-green-500 mr-1">✓</span>
                                            @endif
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                
                                @if(!$quiz->has_submissions)
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                                        ✏️ تعديل
                                    </a>
                                    <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium transition">
                                            🗑️ حذف
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('quiz.take', $quiz) }}" class="btn-primary text-center">
                ▶️ معاينة الاختبار
            </a>
            <a href="{{ route('quizzes.show', $quiz) }}" class="btn-secondary text-center">
                ↩️ العودة للتفاصيل
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyPIN(pin) {
    navigator.clipboard.writeText(pin).then(() => {
        // Show feedback
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = '✅ تم النسخ';
        setTimeout(() => {
            button.textContent = originalText;
        }, 2000);
    });
}
</script>
@endpush
@endsection