@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Quiz Header -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 px-8 py-12">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium">
                                {{ ($quiz->subject ?? 'arabic') == 'arabic' ? '🌍 عربي' : (($quiz->subject ?? 'arabic') == 'english' ? '🌎 إنجليزي' : '🌏 عبري') }}
                            </span>
                            <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium">
                                الصف {{ $quiz->grade_level }}
                            </span>
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                            {{ $quiz->title }}
                        </h1>
                        @if($quiz->description)
                        <div class="text-white/90 text-lg leading-relaxed prose prose-lg max-w-none prose-invert">
                            {!! $quiz->description !!}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Quiz Stats -->
                    <div class="flex flex-col sm:flex-row lg:flex-col gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
                            <div class="text-3xl font-bold text-white">{{ $quiz->questions->count() }}</div>
                            <div class="text-white/80 text-sm">سؤال</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
                            <div class="text-3xl font-bold text-white">{{ $quiz->results->count() }}</div>
                            <div class="text-white/80 text-sm">محاولة</div>
                        </div>
                        @if($quiz->time_limit)
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
                            <div class="text-3xl font-bold text-white">{{ $quiz->time_limit }}</div>
                            <div class="text-white/80 text-sm">دقيقة</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="p-6 bg-gray-50 border-t">
                <div class="flex flex-wrap items-center gap-4">
                    @if(!$quiz->has_submissions)
    <a href="{{ route('quizzes.edit', $quiz) }}" 
       class="btn-primary">
        <i class="fas fa-edit"></i>
        تعديل الاختبار
    </a>
@else
    <!-- Toggle Status -->
    <form action="{{ route('quizzes.toggle-status', $quiz) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <button type="submit" class="flex items-center gap-2 px-4 py-3 rounded-lg font-medium transition
            {{ $quiz->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
            <i class="fas {{ $quiz->is_active ? 'fa-pause' : 'fa-play' }}"></i>
            {{ $quiz->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
        </button>
    </form>
    
    <!-- Copy Quiz -->
    <form action="{{ route('quizzes.duplicate', $quiz) }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="flex items-center gap-2 px-4 py-3 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-medium transition">
            <i class="fas fa-copy"></i>
            نسخ الاختبار
        </button>
    </form>
@endif
                    
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                       class="btn-secondary">
                        <i class="fas fa-question-circle"></i>
                        إدارة الأسئلة
                    </a>
                    
                    <a href="{{ route('quiz.take', $quiz) }}" 
                       class="btn-success" 
                       target="_blank">
                        <i class="fas fa-play"></i>
                        بدء الاختبار
                    </a>
                    
                    <!-- PIN Display -->
                    <div class="mr-auto">
                        <div class="pin-card">
                            <div class="flex items-center gap-3">
                                <div class="text-lg font-bold text-purple-600">{{ $quiz->pin }}</div>
                                <button onclick="copyPIN('{{ $quiz->pin }}')" 
                                        class="text-gray-500 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">رمز الدخول</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Analytics -->
        @if($quiz->results->count() > 0)
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">متوسط الدرجات</h3>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ round($quiz->results->avg('total_score')) }}%
                </div>
                <p class="text-sm text-gray-500 mt-1">من جميع المحاولات</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">معدل النجاح</h3>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-trophy text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">
                    {{ $quiz->results->where('total_score', '>=', $quiz->passing_score ?? 60)->count() }}
                </div>
                <p class="text-sm text-gray-500 mt-1">من {{ $quiz->results->count() }} محاولة</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">آخر نشاط</h3>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="text-lg font-bold text-gray-900">
                    {{ $quiz->results->sortByDesc('created_at')->first()?->created_at?->diffForHumans() ?? 'لا يوجد' }}
                </div>
                <p class="text-sm text-gray-500 mt-1">آخر محاولة</p>
            </div>
        </div>
        @endif

        <!-- Roots Distribution -->
        @if($quiz->questions->count() > 0)
        @php
            $roots = [
                'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'red'],
                'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'blue'],
                'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'orange'],
                'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'purple']
            ];
            $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
            $totalQuestions = $quiz->questions->count();
        @endphp
        
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                🌳 توزيع الجذور التعليمية
            </h2>
            
            <div class="grid md:grid-cols-4 gap-6">
                @foreach($roots as $key => $root)
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-{{ $root['color'] }}-100 rounded-full flex items-center justify-center">
                        <span class="text-3xl">{{ $root['icon'] }}</span>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">{{ $root['name'] }}</h3>
                    <div class="text-center">
                        <span class="text-2xl font-bold text-{{ $root['color'] }}-600">
                            {{ $rootCounts[$key] ?? 0 }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
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
        @endif

        <!-- Educational Passage -->
        @if($quiz->questions->where('passage', '!=', null)->first())
        <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        📖 {{ $quiz->questions->where('passage', '!=', null)->first()->passage_title ?: 'النص التعليمي' }}
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
                        {!! $quiz->questions->where('passage', '!=', null)->first()->passage !!}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Questions Preview -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        ❓ معاينة الأسئلة
                    </h2>
                    @if($quiz->questions->count() > 0)
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">{{ $quiz->questions->count() }} سؤال</span>
                        <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm transition">
                            إدارة الأسئلة →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($quiz->questions as $index => $question)
                <div class="question-preview p-6 hover:bg-gray-50 transition-colors relative">
                    <!-- Root indicator stripe -->
                    @php
                        $questionRootType = $question->root_type ?: 'jawhar'; // Default to jawhar if empty
                        $rootData = $roots[$questionRootType] ?? $roots['jawhar']; // Fallback to jawhar
                    @endphp
                    <div class="absolute right-0 top-0 bottom-0 w-1 bg-{{ $rootData['color'] }}-500"></div>
                    
                    <div class="flex items-start gap-4 mr-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center font-bold text-gray-700 text-lg shadow-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-{{ $rootData['color'] }}-100 text-{{ $rootData['color'] }}-700 font-medium">
                                    {{ $rootData['icon'] }} {{ $rootData['name'] }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                                    <i class="fas fa-layer-group mr-1.5"></i>
                                    مستوى {{ $question->depth_level ?? 1 }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    @for($i = 1; $i <= 3; $i++)
                                        @if($i <= ($question->depth_level ?? 1))
                                            <span class="text-yellow-500">●</span>
                                        @else
                                            <span class="text-gray-300">●</span>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            
                            <h3 class="question-title text-lg font-bold text-gray-900 mb-4 leading-relaxed">
                                {!! $question->question !!}
                            </h3>
                            
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
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <span class="text-4xl text-gray-400">❓</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد أسئلة بعد</h3>
                    <p class="text-gray-500 mb-6">ابدأ بإضافة أسئلة لهذا الاختبار</p>
                    <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                       class="btn-primary">
                        <i class="fas fa-plus"></i>
                        إضافة سؤال جديد
                    </a>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Bottom Actions -->
        @if($quiz->questions->count() > 0)
        <div class="mt-8 text-center">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('quiz.take', $quiz) }}" 
                   class="btn-success"
                   target="_blank">
                    <i class="fas fa-play"></i>
                    تجربة الاختبار
                </a>
                
                <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                   class="btn-secondary">
                    <i class="fas fa-plus"></i>
                    إضافة أسئلة جديدة
                </a>
                
                @if($quiz->results->count() > 0)
                <button onclick="showResults()" class="btn-info">
                    <i class="fas fa-chart-bar"></i>
                    عرض تقرير مفصل
                </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Results Modal -->
<div id="results-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">تقرير النتائج المفصل</h3>
                    <button onclick="closeResults()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <!-- Results content will be loaded here -->
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="text-gray-500 mt-2">جاري تحميل البيانات...</p>
                </div>
            </div>
        </div>
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
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    
    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }
    
    .pin-card {
        background: linear-gradient(135deg, #eff6ff 0%, #f5f3ff 100%);
        border: 2px solid #3b82f6;
        padding: 12px 16px;
        border-radius: 12px;
        text-align: center;
        min-width: 120px;
    }
    
    .question-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    .passage-content {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .passage-content.hidden {
        max-height: 0;
        padding: 0;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-primary, .btn-secondary, .btn-success, .btn-info {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .question-title {
            font-size: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Toggle passage visibility
function togglePassage() {
    const content = document.getElementById('passageContent');
    const toggleText = document.getElementById('toggleText');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        toggleText.textContent = 'إخفاء';
        toggleIcon.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('hidden');
        toggleText.textContent = 'إظهار';
        toggleIcon.style.transform = 'rotate(180deg)';
    }
}

// Copy PIN to clipboard
function copyPIN(pin) {
    navigator.clipboard.writeText(pin).then(() => {
        showNotification('تم نسخ رمز الدخول بنجاح', 'success');
    }).catch(() => {
        showNotification('فشل في نسخ الرمز', 'error');
    });
}

// Show results modal
function showResults() {
    document.getElementById('results-modal').classList.remove('hidden');
    // Here you would load the actual results data
}

// Close results modal
function closeResults() {
    document.getElementById('results-modal').classList.add('hidden');
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

// Close modal on background click
document.getElementById('results-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResults();
    }
});
</script>
@endpush