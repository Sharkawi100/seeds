@extends('layouts.app')

@section('title', $quiz->title . ' - إدارة الاختبار')

@section('content')
{{-- 
    IMPORTANT: Controller must load relationships:
    $quiz->load(['questions', 'user', 'subject', 'results']);
--}}

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center gap-2 text-sm text-gray-500">
                <li><a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition">الرئيسية</a></li>
                <li>←</li>
                <li><a href="{{ route('quizzes.index') }}" class="hover:text-purple-600 transition">اختباراتي</a></li>
                <li>←</li>
                <li class="text-gray-900 font-medium">{{ $quiz->title }}</li>
            </ol>
        </nav>

        <!-- Quiz Header Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            @php
                // Force dark gradients with inline styles as backup
                $gradientStyles = [
                    'background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #000000 100%);',           // Gray to black
                    'background: linear-gradient(135deg, #1e3a8a 0%, #312e81 50%, #581c87 100%);',           // Blue to purple
                    'background: linear-gradient(135deg, #14532d 0%, #064e3b 50%, #134e4a 100%);',           // Green to teal
                    'background: linear-gradient(135deg, #7f1d1d 0%, #881337 50%, #831843 100%);',           // Red to pink
                    'background: linear-gradient(135deg, #581c87 0%, #6b21a8 50%, #86198f 100%);',           // Purple to violet
                    'background: linear-gradient(135deg, #78350f 0%, #9a3412 50%, #7f1d1d 100%);'            // Orange to red
                ];
                $gradientClasses = [
                    'bg-gradient-to-br from-gray-800 via-gray-900 to-black',
                    'bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900',
                    'bg-gradient-to-br from-green-900 via-emerald-900 to-teal-900',
                    'bg-gradient-to-br from-red-900 via-rose-900 to-pink-900',
                    'bg-gradient-to-br from-purple-900 via-violet-900 to-fuchsia-900',
                    'bg-gradient-to-br from-amber-900 via-orange-900 to-red-900'
                ];
                $gradientIndex = abs($quiz->id) % count($gradientStyles);
                $selectedGradient = $gradientClasses[$gradientIndex];
                $selectedStyle = $gradientStyles[$gradientIndex];
            @endphp
            
            <div class="relative {{ $selectedGradient }} p-6 text-white min-h-[160px] flex items-center justify-between" style="{{ $selectedStyle }}">
                <!-- Background decorations -->
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="7" cy="7" r="1"/><circle cx="27" cy="7" r="1"/><circle cx="47" cy="7" r="1"/><circle cx="7" cy="27" r="1"/><circle cx="27" cy="27" r="1"/><circle cx="47" cy="27" r="1"/><circle cx="7" cy="47" r="1"/><circle cx="27" cy="47" r="1"/><circle cx="47" cy="47" r="1"/></g></g></svg>');"></div>
                
                <div class="relative z-10 flex-1">
                    <!-- Quiz Title -->
                    <h1 class="text-2xl md:text-3xl font-bold leading-tight mb-4 text-white drop-shadow-sm">
                        {{ $quiz->title }}
                    </h1>
                    
                    <!-- Teacher Info - Stylishly added -->
                    <div class="flex items-center gap-3 mb-4 p-3 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            @if($quiz->user->avatar)
                                <img src="{{ $quiz->user->avatar }}" alt="{{ $quiz->user->name }}" class="w-full h-full rounded-full object-cover">
                            @else
                                <i class="fas fa-user-tie text-white/80"></i>
                            @endif
                        </div>
                        <div>
                            <p class="text-white/90 font-medium">المعلم: {{ $quiz->user->name }}</p>
                            <p class="text-white/70 text-sm">
                                @if($quiz->user->experience_years)
                                    <i class="fas fa-graduation-cap ml-1"></i>
                                    {{ $quiz->user->experience_years }} سنوات خبرة
                                @else
                                    <i class="fas fa-chalkboard-teacher ml-1"></i>
                                    معلم
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <!-- Quiz Meta Info -->
                    <div class="flex flex-wrap items-center gap-4 text-white/90 text-sm">
                        <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-full border border-white/20">
                            <i class="fas fa-book-open"></i>
                            <span class="font-bold">{{ $quiz->subject->name ?? 'اللغة العربية' }}</span>
                        </span>
                        <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-full border border-white/20">
                            <i class="fas fa-layer-group"></i>
                            <span class="font-bold">الصف {{ $quiz->grade_level }}</span>
                        </span>
                        @if($quiz->time_limit)
                        <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-full border border-white/20">
                            <i class="fas fa-clock"></i>
                            <span class="font-bold">{{ $quiz->time_limit }} دقيقة</span>
                        </span>
                        @endif
                    </div>
                    
                    @if($quiz->description)
                    <div class="mt-4 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                        <p class="text-white/90 leading-relaxed">{!! nl2br(e($quiz->description)) !!}</p>
                    </div>
                    @endif
                </div>
                
                <!-- Status and PIN -->
                <div class="relative z-10 text-left">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <span class="flex items-center gap-2 text-sm font-bold">
                            <div class="w-3 h-3 rounded-full {{ $quiz->is_active ? 'bg-green-400' : 'bg-gray-400' }} animate-pulse"></div>
                            {{ $quiz->is_active ? 'نشط' : 'معطل' }}
                        </span>
                        <div class="text-2xl font-mono font-black text-white bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/30 shadow-lg hover:bg-white/30 transition-all duration-300">
                            {{ $quiz->pin ?? '---' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quiz Stats -->
            <div class="p-6 flex-1 flex flex-col">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-2xl p-4 text-center border border-blue-100">
                        <div class="text-3xl font-black text-blue-700 mb-1">{{ $quiz->questions->count() }}</div>
                        <div class="text-sm text-blue-600 font-bold">سؤال</div>
                    </div>
                    <div class="bg-green-50 rounded-2xl p-4 text-center border border-green-100">
                        <div class="text-3xl font-black text-green-700 mb-1">{{ $quiz->results->count() }}</div>
                        <div class="text-sm text-green-600 font-bold">محاولة</div>
                    </div>
                    @if($quiz->time_limit)
                    <div class="bg-orange-50 rounded-2xl p-4 text-center border border-orange-100">
                        <div class="text-3xl font-black text-orange-700 mb-1">{{ $quiz->time_limit }}</div>
                        <div class="text-sm text-orange-600 font-bold">دقيقة</div>
                    </div>
                    @endif
                    <div class="bg-purple-50 rounded-2xl p-4 text-center border border-purple-100">
                        <div class="text-3xl font-black text-purple-700 mb-1">
                            {{ $quiz->results->count() > 0 ? number_format($quiz->results->avg('total_score'), 1) : 0 }}%
                        </div>
                        <div class="text-sm text-purple-600 font-bold">متوسط النتائج</div>
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
                            {{ $quiz->is_active ? 'إيقاف الاختبار' : 'تفعيل الاختبار' }}
                        </button>
                    </form>
                    @endif
                    
                    <button onclick="copyQuizLink()" class="btn-secondary">
                        <i class="fas fa-link"></i>
                        نسخ الرابط
                    </button>
                    
                    <button onclick="shareQuiz()" class="btn-info">
                        <i class="fas fa-share-alt"></i>
                        مشاركة
                    </button>
                    
                    @if($quiz->results->count() > 0)
                    <a href="{{ route('quizzes.results', $quiz) }}" class="btn-purple">
                        <i class="fas fa-chart-bar"></i>
                        تقرير النتائج
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Educational Passage Section -->
        @if($quiz->passage)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-file-text"></i>
                        {{ $quiz->passage_title ?: 'النص التعليمي' }}
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
                        {!! $quiz->passage !!}
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
                        $roots = [
                            'jawhar' => ['name' => 'جَوهر', 'description' => 'ما هو؟', 'color' => 'emerald', 'icon' => '💎'],
                            'zihn' => ['name' => 'ذِهن', 'description' => 'كيف يعمل؟', 'color' => 'blue', 'icon' => '🧠'],
                            'waslat' => ['name' => 'وَصلات', 'description' => 'كيف يرتبط؟', 'color' => 'orange', 'icon' => '🔗'],
                            'roaya' => ['name' => 'رُؤية', 'description' => 'كيف نستخدمه؟', 'color' => 'purple', 'icon' => '👁️']
                        ];
                        $rootData = $roots[$questionRootType] ?? $roots['jawhar'];
                    @endphp
                    <div class="absolute right-0 top-0 bottom-0 w-1 bg-{{ $rootData['color'] }}-500 rounded-r-lg"></div>
                    
                    <div class="flex items-start gap-4">
                        <!-- Question Number & Root Badge -->
                        <div class="flex-shrink-0 flex flex-col items-center gap-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-600 to-gray-700 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="px-2 py-1 bg-{{ $rootData['color'] }}-100 text-{{ $rootData['color'] }}-700 border border-{{ $rootData['color'] }}-200 rounded-lg text-center min-w-16">
                                <span class="text-lg">{{ $rootData['icon'] }}</span>
                                <div class="text-xs font-bold">{{ $rootData['name'] }}</div>
                            </div>
                            <!-- Depth Level Badge -->
                            <div class="px-2 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded-lg text-center min-w-16">
                                <div class="text-xs font-bold mb-1">المستوى</div>
                                <div class="flex items-center justify-center gap-1">
                                    @for($i = 1; $i <= 3; $i++)
                                        @if($i <= $question->depth_level)
                                            <span class="text-yellow-500 text-xs">●</span>
                                        @else
                                            <span class="text-gray-300 text-xs">●</span>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-xs">
                                    {{ $question->depth_level == 1 ? 'سطحي' : ($question->depth_level == 2 ? 'متوسط' : 'عميق') }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Question Content -->
                        <div class="flex-1">
                            <!-- Question Text -->
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-2 leading-relaxed">
                                    {!! $question->question !!}
                                </h3>
                            </div>
                            
                            <!-- Answer Options -->
                            <div class="space-y-2">
                                @foreach($question->options as $optionIndex => $option)
                                <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 transition-colors {{ $option === $question->correct_answer ? 'bg-green-50 border border-green-200' : '' }}">
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

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-bounce-in">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">مشاركة الاختبار</h3>
                <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <!-- Quiz Link -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رابط الاختبار</label>
                    <div class="flex gap-2">
                        <input type="text" 
                               id="quizLink" 
                               value="{{ route('quiz.take', $quiz) }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                               readonly>
                        <button onclick="copyText('quizLink')" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <!-- PIN Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رمز الاختبار (PIN)</label>
                    <div class="flex gap-2">
                        <input type="text" 
                               id="quizPin" 
                               value="{{ $quiz->pin }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono text-center text-lg"
                               readonly>
                        <button onclick="copyText('quizPin')" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-medium text-blue-900 mb-2">تعليمات للطلاب:</h4>
                    <ol class="text-sm text-blue-800 space-y-1">
                        <li>1. اذهب إلى الموقع: <span class="font-mono">iseraj.com/roots</span></li>
                        <li>2. أدخل الرمز: <span class="font-mono font-bold">{{ $quiz->pin }}</span></li>
                        <li>3. اكتب اسمك وابدأ الاختبار</li>
                    </ol>
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

.btn-info {
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
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

.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(14, 165, 233, 0.4);
    color: white;
    text-decoration: none;
}

.btn-purple {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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

.btn-purple:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
    color: white;
    text-decoration: none;
}

/* Passage Content */
.passage-content {
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    max-height: 1000px;
}

.passage-content.hidden {
    max-height: 0;
}

/* Animation */
@keyframes bounce-in {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-bounce-in {
    animation: bounce-in 0.5s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .btn-primary, .btn-secondary, .btn-success, .btn-info, .btn-purple {
        padding: 10px 16px;
        font-size: 14px;
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

// Copy quiz link
function copyQuizLink() {
    const link = "{{ route('quiz.take', $quiz) }}";
    navigator.clipboard.writeText(link).then(() => {
        showToast('تم نسخ رابط الاختبار!', 'success');
    });
}

// Share quiz modal
function shareQuiz() {
    document.getElementById('shareModal').classList.remove('hidden');
}

function closeShareModal() {
    document.getElementById('shareModal').classList.add('hidden');
}

// Copy text from input
function copyText(inputId) {
    const input = document.getElementById(inputId);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(() => {
        showToast('تم النسخ!', 'success');
    });
}

// Show results
function showResults() {
    window.open("{{ route('quizzes.results', $quiz) }}", '_blank');
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.style.transform = 'translate(-50%, 0)', 10);
    
    setTimeout(() => {
        toast.style.transform = 'translate(-50%, -100%)';
        toast.style.opacity = '0';
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('shareModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeShareModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeShareModal();
    }
});
</script>
@endpush