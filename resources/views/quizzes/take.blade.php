@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 to-purple-50">
    <!-- Simple Header -->
    <div class="sticky top-0 z-40 bg-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Quiz Info -->
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-bold text-gray-800">{{ $quiz->title }}</h1>
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">
                        {{ $quiz->questions->count() }} أسئلة
                    </span>
                </div>
                
                <!-- Timer or Exit -->
                <div class="flex items-center gap-4">
                    @if($quiz->time_limit)
                    <div class="flex items-center gap-2 text-base">
                        <span class="text-xl">⏰</span>
                        <span id="timer" class="font-bold text-gray-700">{{ $quiz->time_limit }}:00</span>
                    </div>
                    @endif
                    
                    <button onclick="confirmExit()" class="text-gray-500 hover:text-red-500 transition text-xl">
                        ❌
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <!-- Root Progress Cards -->
            @php
                $rootTypes = $quiz->questions->groupBy('root_type');
                $rootInfo = [
                    'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'bg' => 'bg-red-100', 'border' => 'border-red-300', 'text' => 'text-red-700'],
                    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'bg' => 'bg-cyan-100', 'border' => 'border-cyan-300', 'text' => 'text-cyan-700'],
                    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'bg' => 'bg-yellow-100', 'border' => 'border-yellow-300', 'text' => 'text-yellow-700'],
                    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'bg' => 'bg-purple-100', 'border' => 'border-purple-300', 'text' => 'text-purple-700']
                ];
            @endphp
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-3">
                @foreach($rootInfo as $type => $info)
                @if(isset($rootTypes[$type]))
                <div class="{{ $info['bg'] }} {{ $info['border'] }} border-2 rounded-lg p-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xl">{{ $info['icon'] }}</span>
                        <div class="text-right">
                            <div class="text-lg font-bold {{ $info['text'] }}">
                                <span id="{{ $type }}-answered">0</span>/{{ $rootTypes[$type]->count() }}
                            </div>
                            <div class="text-xs {{ $info['text'] }}">{{ $info['name'] }}</div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            
            <!-- Overall Progress Bar -->
            <div class="bg-gray-100 rounded-full h-4 relative overflow-hidden">
                <div id="overall-progress" class="bg-gradient-to-r from-green-400 to-blue-500 h-full rounded-full transition-all duration-500 flex items-center justify-center" style="width: 0%">
                    <span id="progress-text" class="text-white text-xs font-bold"></span>
                </div>
            </div>
            <div class="text-center mt-1">
                <span class="text-sm font-medium text-gray-700">
                    أجبت على <span id="total-answered" class="font-bold text-blue-600">0</span> من {{ $quiz->questions->count() }} سؤال
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-6" id="quiz-content">
        <form id="quiz-form" action="{{ route('quiz.submit', $quiz) }}" method="POST">
            @csrf
            
            <!-- Questions Container -->
            <div id="questions-wrapper">
                @foreach($quiz->questions as $index => $question)
                <div id="question-{{ $index }}" 
                     class="question-card bg-white rounded-xl shadow-lg p-4 mb-4"
                     data-question-index="{{ $index }}"
                     data-root-type="{{ $question->root_type }}"
                     style="{{ $index > 0 ? 'display: none;' : '' }}">
                    
                    <!-- Question Header -->
                    <div class="bg-gradient-to-r {{ 
                        $question->root_type == 'jawhar' ? 'from-red-100 to-red-50' : 
                        ($question->root_type == 'zihn' ? 'from-cyan-100 to-cyan-50' : 
                        ($question->root_type == 'waslat' ? 'from-yellow-100 to-yellow-50' : 
                        'from-purple-100 to-purple-50')) 
                    }} -m-4 mb-4 p-3 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-md">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="text-xl">
                                        {{ $question->root_type == 'jawhar' ? '🎯' : 
                                           ($question->root_type == 'zihn' ? '🧠' : 
                                           ($question->root_type == 'waslat' ? '🔗' : '👁️')) }}
                                    </span>
                                    <span class="text-base font-medium mr-2">
                                        {{ $rootInfo[$question->root_type]['name'] }}
                                    </span>
                                </div>
                            </div>
                            <button type="button" 
                                    onclick="toggleBookmark({{ $index }})"
                                    class="bookmark-btn text-2xl transition-transform hover:scale-125"
                                    style="opacity: 0.3;">
                                ⭐
                            </button>
                        </div>
                    </div>
                    
                    <!-- Question Text -->
                    <div class="text-base leading-relaxed text-gray-800 mb-6">
                        {!! $question->question !!}
                    </div>
                    
                    <!-- Answer Options -->
                    <div class="space-y-3">
                        @foreach($question->options as $optionIndex => $option)
                        <label class="block cursor-pointer transform hover:scale-[1.01] transition-transform">
                            <input type="radio" 
                                   name="answers[{{ $question->id }}]" 
                                   value="{{ $option }}"
                                   onchange="markAnswered({{ $index }}, '{{ $question->root_type }}')"
                                   class="hidden peer">
                            <div class="p-3 border-2 border-gray-300 rounded-lg hover:border-blue-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="flex-shrink-0 w-10 h-10 {{ 
                                        $optionIndex == 0 ? 'bg-red-200' : 
                                        ($optionIndex == 1 ? 'bg-green-200' : 
                                        ($optionIndex == 2 ? 'bg-yellow-200' : 
                                        ($optionIndex == 3 ? 'bg-blue-200' : 
                                        ($optionIndex == 4 ? 'bg-purple-200' : 'bg-pink-200')))) 
                                    }} rounded-full flex items-center justify-center text-sm font-bold">
                                        {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] }}
                                    </span>
                                    <span class="text-base text-gray-700 peer-checked:text-gray-900 peer-checked:font-medium">
                                        {{ $option }}
                                    </span>
                                    <span class="mr-auto text-2xl opacity-0 peer-checked:opacity-100 transition-opacity">
                                        ✅
                                    </span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Navigation Section (Non-sticky) -->
            <div class="mt-6 bg-white rounded-xl shadow-lg p-4">
                <!-- Question Dots Navigator -->
                <div class="mb-4">
                    <div class="flex justify-center items-center gap-2 flex-wrap">
                        @foreach($quiz->questions as $index => $q)
                        <button type="button"
                                onclick="goToQuestion({{ $index }})"
                                class="question-dot w-8 h-8 rounded-full bg-gray-300 hover:bg-gray-400 transition-all text-xs font-medium"
                                data-index="{{ $index }}"
                                data-root="{{ $q->root_type }}">
                            {{ $index + 1 }}
                        </button>
                        @endforeach
                    </div>
                    
                    <!-- Legend -->
                    <div class="mt-3 flex justify-center gap-4 text-xs text-gray-600">
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            مُجاب
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                            الحالي
                        </span>
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between gap-4">
                    <button type="button" 
                            id="prev-btn"
                            onclick="navigateQuestion('prev')"
                            class="invisible flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-base font-medium transition">
                        ⬅️ السابق
                    </button>
                    
                    <div class="text-sm text-gray-600">
                        السؤال <span id="current-question-number" class="font-bold">1</span> من {{ $quiz->questions->count() }}
                    </div>
                    
                    <button type="button" 
                            id="next-btn"
                            onclick="navigateQuestion('next')"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-base font-medium transition shadow">
                        التالي ➡️
                    </button>
                </div>
                
                <!-- Submit Section -->
                <div id="submit-section" class="hidden mt-4 text-center">
                    <div class="bg-gradient-to-r from-green-100 to-blue-100 rounded-lg p-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">🎉 ممتاز! أنهيت جميع الأسئلة</h3>
                        <p class="text-base text-gray-600 mb-3">تأكد من إجاباتك ثم اضغط إرسال</p>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg text-lg font-bold transition shadow-lg transform hover:scale-105">
                            📤 إرسال الاختبار
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Floating Expandable Reading Passage -->
    @if($quiz->questions->where('passage', '!=', null)->first())
    <div id="floating-passage" class="fixed bottom-6 left-6 z-50">
        <!-- Collapsed State (Button) -->
        <div id="passage-button">
            <button onclick="toggleFloatingPassage()" 
                    class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-full px-4 py-3 shadow-2xl transform hover:scale-105 transition-all flex items-center gap-2 animate-bounce-slow">
                <span class="text-2xl">📖</span>
                <span class="text-base font-bold">نص القراءة</span>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                    مهم!
                </span>
            </button>
        </div>
        
        <!-- Expanded State (Panel) -->
        <div id="passage-panel" class="hidden">
            <div class="bg-white rounded-2xl shadow-2xl border-4 border-purple-400 overflow-hidden" 
                 style="width: 380px; max-width: calc(100vw - 3rem); max-height: 70vh;">
                <!-- Panel Header -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-3 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="text-xl">📖</span>
                            {{ $quiz->questions->first()->passage_title ?? 'نص القراءة' }}
                        </h3>
                        <button onclick="toggleFloatingPassage()" 
                                class="text-white hover:bg-white/20 rounded-full p-1 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Panel Content -->
                <div class="p-4 overflow-y-auto bg-gradient-to-b from-purple-50 to-white" style="max-height: calc(70vh - 60px);">
                    <div class="prose prose-base max-w-none leading-relaxed text-gray-800">
                        {!! nl2br(e($quiz->questions->first()->passage)) !!}
                    </div>
                </div>
                
                <!-- Panel Footer -->
                <div class="bg-gray-100 p-2 text-center sticky bottom-0">
                    <p class="text-xs text-gray-600">
                        💡 يمكنك إغلاق النص والرجوع إليه في أي وقت
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Exit Confirmation Modal -->
    <div id="exit-modal" class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center">
                <div class="text-5xl mb-3">😟</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">هل تريد الخروج؟</h3>
                <p class="text-base text-gray-600 mb-4">ستفقد جميع إجاباتك!</p>
                <div class="flex gap-3">
                    <button onclick="closeExitModal()" 
                            class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-base font-medium transition">
                        البقاء ✅
                    </button>
                    <button onclick="window.location.href='{{ route('quizzes.index') }}'" 
                            class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-base font-medium transition">
                        الخروج ❌
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Kid-friendly animations */
@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}

/* Question dots */
.question-dot {
    transition: all 0.3s;
    position: relative;
}

.question-dot.answered {
    background-color: #10b981;
    color: white;
}

.question-dot.current {
    background-color: #3b82f6;
    color: white;
    transform: scale(1.2);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.question-dot.bookmarked::before {
    content: '⭐';
    position: absolute;
    top: -10px;
    right: -5px;
    font-size: 10px;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Border width fix */
.border-2 {
    border-width: 2px;
}

.border-4 {
    border-width: 4px;
}

/* Big readable text in passage */
.prose-base {
    font-size: 1rem;
    line-height: 1.625;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .question-card {
        padding: 1rem;
    }
    
    #floating-passage {
        bottom: 1rem;
        left: 1rem;
    }
    
    #passage-panel > div {
        width: calc(100vw - 2rem) !important;
        max-height: 80vh !important;
    }
}

/* Print friendly */
@media print {
    #floating-passage,
    .sticky,
    button {
        display: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Global state
let currentQuestion = 0;
const totalQuestions = {{ $quiz->questions->count() }};
const bookmarkedQuestions = new Set();
const answeredQuestions = new Set();
const rootTypeAnswered = {
    jawhar: 0,
    zihn: 0,
    waslat: 0,
    roaya: 0
};
const rootTypeTotals = {
    @foreach($quiz->questions->groupBy('root_type') as $type => $questions)
    {{ $type }}: {{ $questions->count() }},
    @endforeach
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateQuestionDisplay();
    updateAllProgress();
    updateNavigationButtons();
    
    // Show initial notification about passage
    @if($quiz->questions->where('passage', '!=', null)->first())
    setTimeout(() => {
        showNotification('📖 اضغط على زر "نص القراءة" لقراءة النص!', 'info');
        // Make button pulse more prominently
        const btn = document.querySelector('#passage-button button');
        if (btn) {
            btn.style.transform = 'scale(1.15)';
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 1000);
        }
    }, 1000);
    @endif
    
    @if($quiz->time_limit)
    startTimer({{ $quiz->time_limit }});
    @endif
});

// Navigation functions
function navigateQuestion(direction) {
    if (direction === 'next' && currentQuestion < totalQuestions - 1) {
        currentQuestion++;
    } else if (direction === 'prev' && currentQuestion > 0) {
        currentQuestion--;
    }
    updateQuestionDisplay();
    scrollToQuestion();
}

function goToQuestion(index) {
    currentQuestion = index;
    updateQuestionDisplay();
    scrollToQuestion();
}

function scrollToQuestion() {
    const questionCard = document.getElementById(`question-${currentQuestion}`);
    if (questionCard) {
        questionCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function updateQuestionDisplay() {
    // Hide all questions
    document.querySelectorAll('.question-card').forEach(card => {
        card.style.display = 'none';
    });
    
    // Show current question
    const currentCard = document.getElementById(`question-${currentQuestion}`);
    if (currentCard) {
        currentCard.style.display = 'block';
    }
    
    // Update navigation
    updateNavigationButtons();
    updateQuestionDots();
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitSection = document.getElementById('submit-section');
    
    // Previous button
    prevBtn.classList.toggle('invisible', currentQuestion === 0);
    
    // Next button and submit
    if (currentQuestion === totalQuestions - 1) {
        nextBtn.style.display = 'none';
        if (answeredQuestions.size === totalQuestions) {
            submitSection.classList.remove('hidden');
        }
    } else {
        nextBtn.style.display = 'flex';
        submitSection.classList.add('hidden');
    }
    
    // Update current question number
    document.getElementById('current-question-number').textContent = currentQuestion + 1;
}

function updateQuestionDots() {
    document.querySelectorAll('.question-dot').forEach(dot => {
        const index = parseInt(dot.dataset.index);
        
        // Remove all classes
        dot.classList.remove('current', 'answered', 'bookmarked');
        
        // Current question
        if (index === currentQuestion) {
            dot.classList.add('current');
        }
        
        // Answered questions
        if (answeredQuestions.has(index)) {
            dot.classList.add('answered');
        }
        
        // Bookmarked
        if (bookmarkedQuestions.has(index)) {
            dot.classList.add('bookmarked');
        }
    });
}

// Answer tracking
function markAnswered(questionIndex, rootType) {
    const wasAnswered = answeredQuestions.has(questionIndex);
    
    answeredQuestions.add(questionIndex);
    
    if (!wasAnswered && rootType && rootTypeTotals[rootType] > 0) {
        rootTypeAnswered[rootType]++;
        updateRootTypeProgress(rootType);
        checkAchievements(rootType);
    }
    
    updateAllProgress();
    updateQuestionDots();
    
    if (answeredQuestions.size === totalQuestions) {
        document.getElementById('submit-section').classList.remove('hidden');
        showCelebration();
    }
}

function updateRootTypeProgress(rootType) {
    if (!rootTypeTotals[rootType]) return;
    
    const answered = rootTypeAnswered[rootType];
    const total = rootTypeTotals[rootType];
    
    const counterEl = document.getElementById(`${rootType}-answered`);
    if (counterEl) counterEl.textContent = answered;
}

function updateAllProgress() {
    Object.keys(rootTypeTotals).forEach(rootType => {
        updateRootTypeProgress(rootType);
    });
    
    const totalAnswered = answeredQuestions.size;
    const percentage = Math.round((totalAnswered / totalQuestions) * 100);
    
    document.getElementById('total-answered').textContent = totalAnswered;
    const progressBar = document.getElementById('overall-progress');
    progressBar.style.width = `${percentage}%`;
    
    const progressText = document.getElementById('progress-text');
    if (percentage > 0) {
        progressText.textContent = `${percentage}%`;
    }
}

function checkAchievements(rootType) {
    const answered = rootTypeAnswered[rootType];
    const total = rootTypeTotals[rootType];
    
    if (answered === total) {
        showNotification(`🏆 أحسنت! أكملت جميع أسئلة ${rootType === 'jawhar' ? 'الجَوهر' : rootType === 'zihn' ? 'الذِهن' : rootType === 'waslat' ? 'الوَصلات' : 'الرُؤية'}!`, 'success');
    }
}

function showCelebration() {
    showNotification('🎉🎊 رائع! أنهيت جميع الأسئلة! أنت بطل! 🌟', 'celebration');
}

// Bookmark function
function toggleBookmark(questionIndex) {
    const btn = event.currentTarget;
    
    if (bookmarkedQuestions.has(questionIndex)) {
        bookmarkedQuestions.delete(questionIndex);
        btn.style.opacity = '0.3';
    } else {
        bookmarkedQuestions.add(questionIndex);
        btn.style.opacity = '1';
    }
    
    updateQuestionDots();
}

// Floating passage functions
function toggleFloatingPassage() {
    const button = document.getElementById('passage-button');
    const panel = document.getElementById('passage-panel');
    
    if (panel.classList.contains('hidden')) {
        // Show panel
        button.style.display = 'none';
        panel.classList.remove('hidden');
        
        // Animate in
        setTimeout(() => {
            panel.querySelector('div').style.transform = 'scale(1)';
            panel.querySelector('div').style.opacity = '1';
        }, 10);
    } else {
        // Hide panel
        panel.querySelector('div').style.transform = 'scale(0.95)';
        panel.querySelector('div').style.opacity = '0.9';
        
        setTimeout(() => {
            panel.classList.add('hidden');
            button.style.display = 'block';
        }, 300);
    }
}

// Exit confirmation
function confirmExit() {
    if (answeredQuestions.size > 0) {
        document.getElementById('exit-modal').classList.remove('hidden');
    } else {
        window.location.href = '{{ route('quizzes.index') }}';
    }
}

function closeExitModal() {
    document.getElementById('exit-modal').classList.add('hidden');
}

// Timer
@if($quiz->time_limit)
function startTimer(minutes) {
    let timeLeft = minutes * 60;
    const timerElement = document.getElementById('timer');
    
    const interval = setInterval(() => {
        const mins = Math.floor(timeLeft / 60);
        const secs = timeLeft % 60;
        timerElement.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 300) {
            timerElement.classList.add('text-red-600');
        }
        
        if (timeLeft === 0) {
            clearInterval(interval);
            alert('انتهى الوقت! سيتم إرسال الاختبار.');
            document.getElementById('quiz-form').submit();
        }
        
        timeLeft--;
    }, 1000);
}
@endif

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-xl shadow-2xl z-[80] transition-all duration-500 ${
        type === 'celebration' ? 'bg-gradient-to-r from-yellow-400 to-pink-500 text-white text-lg' :
        type === 'success' ? 'bg-green-500 text-white text-base' :
        'bg-blue-500 text-white text-base'
    }`;
    notification.innerHTML = `<div class="font-bold">${message}</div>`;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translate(-50%, 0) scale(1.05)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowRight') {
        navigateQuestion('next');
    } else if (e.key === 'ArrowLeft') {
        navigateQuestion('prev');
    } else if (e.key === ' ' && e.ctrlKey) {
        // Ctrl+Space to toggle passage
        e.preventDefault();
        toggleFloatingPassage();
    } else if (e.key === 'Escape') {
        // Escape to close passage panel
        const panel = document.getElementById('passage-panel');
        if (panel && !panel.classList.contains('hidden')) {
            toggleFloatingPassage();
        }
    }
});
</script>
@endpush