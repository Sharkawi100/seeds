@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div id="quiz-container" class="min-h-screen flex flex-col bg-gradient-to-br from-purple-600 to-blue-600">
    <!-- Simple Header - Fixed height -->
    <header class="h-16 bg-black/20 backdrop-blur-sm flex items-center flex-shrink-0 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto w-full px-4 flex items-center justify-between">
            <h1 class="text-white text-lg md:text-xl font-bold truncate">{{ $quiz->title }}</h1>
            
            @if($quiz->time_limit)
            <div class="bg-red-500 text-white px-3 py-2 rounded-full font-mono text-base md:text-lg font-bold">
                <span id="timer">{{ $quiz->time_limit }}:00</span>
            </div>
            @endif
            
            <div class="flex gap-2">
                <button onclick="toggleFullscreen()" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg">
                    <svg id="fullscreen-icon" class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                    </svg>
                </button>
                <button onclick="exitQuiz()" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Quiz Area - Scrollable container -->
    <div id="quiz-content" class="flex-1 flex flex-col relative">
        <!-- Reading Passage Button - Fixed position -->
        @if($quiz->questions->where('passage', '!=', null)->first())
        <div class="absolute top-4 right-4 z-20">
            <button onclick="togglePassage()" 
                    class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 md:px-4 md:py-2 rounded-full 
                           shadow-lg flex items-center gap-2 font-bold transform hover:scale-105 transition-all
                           animate-pulse text-sm">
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="hidden md:inline">نص القراءة</span>
            </button>
        </div>
        @endif
        
        <form id="quiz-form" action="{{ route('quiz.submit', $quiz) }}" method="POST" class="flex-1 flex flex-col">
            @csrf
            
            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto min-h-0">
                @foreach($questions as $index => $question)
                <!-- Question Container -->
                <div id="question-{{ $index }}" 
                     class="question-container min-h-full flex flex-col p-4 pb-32"
                     style="display: {{ $index === 0 ? 'flex' : 'none' }}">
                    
                    <!-- Question Header -->
                    <div class="text-center mb-6 flex-shrink-0">
                        <div class="flex flex-col md:flex-row items-center justify-center gap-3 mb-4">
                            <span class="bg-white text-purple-600 text-lg md:text-2xl font-bold px-4 md:px-6 py-2 md:py-3 rounded-full">
                                السؤال {{ $index + 1 }} من {{ $quiz->questions->count() }}
                            </span>
                            <span class="bg-white/20 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full text-sm md:text-lg">
                                {{ $question->root_type == 'jawhar' ? '🎯 جَوهر' : 
                                   ($question->root_type == 'zihn' ? '🧠 ذِهن' : 
                                   ($question->root_type == 'waslat' ? '🔗 وَصلات' : '👁️ رُؤية')) }}
                            </span>
                        </div>
                        
                        <h2 class="text-white text-xl md:text-2xl lg:text-3xl font-bold drop-shadow-lg leading-relaxed">
                            {!! $question->question !!}
                        </h2>
                    </div>
                    
                    <!-- Answers Grid -->
                    <div class="flex-1 flex items-start justify-center">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 w-full max-w-4xl">
                            @php
                                $colors = ['bg-red-500', 'bg-blue-500', 'bg-yellow-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500'];
                                $shapes = ['▲', '●', '■', '◆', '★', '♥'];
                                $options = $question->options;
                            @endphp
                            
                            @foreach(($question->shuffled_options ?? $question->options) as $optionIndex => $option)
                            @if($option)
                            <label class="block cursor-pointer transform hover:scale-[1.02] transition-transform">
                                <input type="radio" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $option }}"
                                       onchange="selectAnswer({{ $index }})"
                                       class="hidden peer">
                                
                                <div class="{{ $colors[$optionIndex % 6] }} hover:brightness-110 
                                            peer-checked:ring-4 md:peer-checked:ring-8 peer-checked:ring-white peer-checked:ring-opacity-50
                                            peer-checked:brightness-125 peer-checked:scale-[1.02]
                                            rounded-xl p-4 md:p-6 min-h-[80px] md:min-h-[100px]
                                            flex items-center justify-center relative
                                            shadow-lg transition-all duration-200
                                            peer-checked:shadow-2xl">
                                    
                                    <!-- Shape/Checkmark Container -->
                                    <div class="absolute left-3 top-3 text-2xl md:text-4xl lg:text-5xl transition-all duration-300">
                                        <!-- Original Shape -->
                                        <span class="shape-icon text-white/20">{{ $shapes[$optionIndex % 6] }}</span>
                                        <!-- Green Checkmarks (hidden by default) -->
                                        <span class="checkmark-icon hidden">✅</span>
                                    </div>
                                    
                                    <!-- Answer Text -->
                                    <p class="text-white text-lg md:text-xl lg:text-2xl font-bold text-center px-8 md:px-12
                                             peer-checked:text-white peer-checked:drop-shadow-lg leading-relaxed">
                                             {!! $option !!}                                    
                                    <!-- Selected Badge -->
                                    <span class="selected-badge absolute top-3 right-3 bg-white text-xs px-2 py-1 rounded-full font-bold
                                               opacity-0 transition-all duration-300
                                               text-gray-800 shadow-md">
                                        ✓
                                    </span>
                                </div>
                            </label>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Bottom Navigation - Sticky at bottom -->
            <div class="bg-black/20 backdrop-blur-sm p-4 flex-shrink-0 sticky bottom-0 z-20">
                <!-- Progress Dots -->
                <div class="flex gap-1 md:gap-2 flex-wrap justify-center mb-3 max-w-full overflow-x-auto">
                    @foreach($quiz->questions as $qIndex => $q)
                    <button type="button"
                            onclick="goToQuestion({{ $qIndex }})"
                            class="progress-dot w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-600 hover:bg-gray-500 
                                   flex items-center justify-center text-white font-bold text-xs md:text-sm
                                   transition-all duration-200 border-2 border-transparent flex-shrink-0"
                            data-index="{{ $qIndex }}">
                        {{ $qIndex + 1 }}
                    </button>
                    @endforeach
                </div>
                
                <!-- Navigation Buttons -->
                <div class="flex gap-2 md:gap-3 justify-center">
                    <button type="button" 
                            id="prev-btn"
                            onclick="navigateQuestion('prev')"
                            class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl 
                                   font-bold text-sm md:text-lg disabled:opacity-50 disabled:cursor-not-allowed
                                   transition-all duration-200"
                            disabled>
                        ← السابق
                    </button>
                    
                    <button type="button" 
                            id="next-btn"
                            onclick="navigateQuestion('next')"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl 
                                   font-bold text-sm md:text-lg transition-all duration-200">
                        التالي →
                    </button>
                    
                    <button type="submit" 
                            id="submit-btn"
                            class="hidden bg-green-500 hover:bg-green-600 text-white px-6 py-2 md:px-8 md:py-3 rounded-xl 
                                   font-bold text-sm md:text-lg transition-all duration-200">
                        إرسال الاختبار ✓
                    </button>
                </div>
            </div>
        </form>
        
        <!-- Reading Passage Modal -->
        @if($quiz->questions->where('passage', '!=', null)->first())
        <div id="passage-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden z-50 p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-3xl mx-auto my-8 shadow-2xl">
                <div class="bg-purple-600 text-white p-4 md:p-6 flex justify-between items-center sticky top-0 rounded-t-2xl">
                    <h3 class="text-xl md:text-2xl font-bold">{{ $quiz->questions->first()->passage_title ?? 'نص القراءة' }}</h3>
                    <button onclick="togglePassage()" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4 md:p-6 max-h-[70vh] overflow-y-auto">
                    <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
                        {!! $quiz->questions->first()->passage !!}                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Exit Confirmation Modal -->
    <div id="exit-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
        <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-3xl p-1 transform scale-0 transition-transform duration-300" id="exit-modal-content">
            <div class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full">
                <!-- Animated Warning Icon -->
                <div class="mx-auto w-16 h-16 md:w-24 md:h-24 bg-red-100 rounded-full flex items-center justify-center mb-4 md:mb-6 animate-bounce">
                    <svg class="w-10 h-10 md:w-16 md:h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                
                <!-- Title with Gradient -->
                <h3 class="text-2xl md:text-3xl font-bold text-center mb-3 md:mb-4 bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent">
                    انتظر! 🛑
                </h3>
                
                <p class="text-lg md:text-xl text-gray-700 text-center mb-2 font-semibold">
                    هل أنت متأكد من الخروج؟
                </p>
                
                <p class="text-gray-500 text-center text-sm md:text-base mb-6 md:mb-8">
                    ستفقد جميع إجاباتك وتقدمك في الاختبار
                </p>
                
                <!-- Progress Lost Indicator -->
                <div class="bg-gray-100 rounded-full h-3 md:h-4 mb-6 md:mb-8 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-full transition-all duration-500" 
                         style="width: 0%" 
                         id="progress-lost-bar"></div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3 md:gap-4">
                    <button onclick="closeExitModal()" 
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 md:px-6 md:py-4 rounded-xl font-bold text-sm md:text-lg
                                   transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span class="hidden md:inline">البقاء في الاختبار</span>
                        <span class="md:hidden">البقاء</span>
                    </button>
                    <button onclick="confirmExit()" 
                            class="flex-1 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 
                                   text-white px-4 py-3 md:px-6 md:py-4 rounded-xl font-bold text-sm md:text-lg
                                   transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                        الخروج
                        <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
   /* TinyMCE Content Styling for Quiz Taking */
strong, b { 
    font-weight: bold !important; 
}

em, i { 
    font-style: italic !important; 
}

u { 
    text-decoration: underline !important; 
}

ul { 
    list-style: disc !important; 
    margin-right: 20px !important; 
    padding-right: 20px !important;
}

ol { 
    list-style: decimal !important; 
    margin-right: 20px !important; 
    padding-right: 20px !important;
}

p { 
    margin-bottom: 1em !important; 
}

/* Specific for white text on colored backgrounds */
.question-container h2 strong,
.answer-option div p strong {
    font-weight: 900 !important;
    color: inherit !important;
}

.question-container h2 em,
.answer-option div p em {
    font-style: italic !important;
    color: inherit !important;
}
/* Remove the overflow: hidden restriction */
html, body {
    height: 100%;
    /* overflow: hidden; - REMOVED */
    scroll-behavior: smooth;
}

/* Progress dot styles */
.progress-dot {
    background: #4b5563; /* Gray for unanswered */
    border: 2px solid transparent;
}

.progress-dot.answered {
    background: #10b981; /* Green for answered */
    border-color: #34d399;
    transform: scale(1.1);
}

.progress-dot.current {
    background: #ffffff;
    color: #7c3aed;
    border-color: #ffffff;
    transform: scale(1.3);
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
}

.progress-dot.answered.current {
    background: #ffffff;
    color: #10b981;
    border-color: #10b981;
}

/* Answer card selected state enhancement */
input[type="radio"]:checked + div {
    transform: scale(1.02);
    filter: brightness(1.25);
}

/* Shape to checkmark transformation */
input[type="radio"]:checked + div .shape-icon {
    display: none;
}

input[type="radio"]:checked + div .checkmark-icon {
    display: inline !important;
    animation: checkmarkPop 0.3s ease-out;
}

input[type="radio"]:checked + div .selected-badge {
    opacity: 1;
}

@keyframes checkmarkPop {
    0% {
        transform: scale(0) rotate(-180deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.2) rotate(10deg);
    }
    100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
}

/* Smooth transitions */
.question-container {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Exit modal animation */
#exit-modal-content {
    transform: scale(0);
    transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* Progress bar animation */
#progress-lost-bar {
    transition: width 1s ease-out;
}

/* Pulse animation for reading button */
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.05); }
}

.animate-pulse {
    animation: pulse 2s infinite;
}

/* Better mobile touch targets */
@media (max-width: 768px) {
    .progress-dot {
        min-width: 2rem;
        min-height: 2rem;
        font-size: 0.75rem;
    }
    
    /* Ensure proper spacing on mobile */
    .question-container {
        padding-bottom: 8rem; /* Extra space for sticky navigation */
    }
    
    /* Better button sizing on mobile */
    button {
        min-height: 44px; /* iOS recommended touch target */
        min-width: 44px;
    }
}

/* Improved accessibility */
@media (prefers-reduced-motion: reduce) {
    .animate-pulse,
    .checkmarkPop,
    .fadeIn {
        animation: none;
    }
    
    .transition-all,
    .transition-transform,
    .transition-colors {
        transition: none;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-white\/20 {
        background-color: rgba(255, 255, 255, 0.8);
    }
    
    .bg-black\/20 {
        background-color: rgba(0, 0, 0, 0.8);
    }
}
</style>
@endpush

@push('scripts')
<script>
// Simple, working JavaScript with improved mobile support
let currentQuestion = 0;
const totalQuestions = {{ $quiz->questions->count() }};
const answeredQuestions = new Set();
let touchStartX = 0;
let touchEndX = 0;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateProgressDots();
    updateNavigationButtons();
    initializeSwipeGestures();
    initializeKeyboardShortcuts();
    
    // Ensure proper mobile viewport
    const viewport = document.querySelector('meta[name="viewport"]');
    if (viewport) {
        viewport.setAttribute('content', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no');
    }
    
    @if($quiz->time_limit)
    startTimer({{ $quiz->time_limit }});
    @endif
});

// Navigate between questions
function navigateQuestion(direction) {
    // Hide current question
    document.getElementById(`question-${currentQuestion}`).style.display = 'none';
    
    // Update index
    if (direction === 'next' && currentQuestion < totalQuestions - 1) {
        currentQuestion++;
    } else if (direction === 'prev' && currentQuestion > 0) {
        currentQuestion--;
    }
    
    // Show new question
    document.getElementById(`question-${currentQuestion}`).style.display = 'flex';
    
    // Scroll to top of question area for mobile
    const questionContainer = document.getElementById(`question-${currentQuestion}`);
    questionContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    updateProgressDots();
    updateNavigationButtons();
}

// Jump to specific question
function goToQuestion(index) {
    if (index === currentQuestion) return;
    
    document.getElementById(`question-${currentQuestion}`).style.display = 'none';
    currentQuestion = index;
    document.getElementById(`question-${currentQuestion}`).style.display = 'flex';
    
    // Scroll to top of question for mobile
    const questionContainer = document.getElementById(`question-${currentQuestion}`);
    questionContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    updateProgressDots();
    updateNavigationButtons();
}

// Update progress dots
function updateProgressDots() {
    document.querySelectorAll('.progress-dot').forEach((dot, index) => {
        dot.classList.remove('current', 'answered');
        
        if (index === currentQuestion) {
            dot.classList.add('current');
        }
        
        if (answeredQuestions.has(index)) {
            dot.classList.add('answered');
        }
    });
}

// Update navigation buttons
function updateNavigationButtons() {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    
    prevBtn.disabled = currentQuestion === 0;
    
    if (currentQuestion === totalQuestions - 1) {
        nextBtn.classList.add('hidden');
        if (answeredQuestions.size === totalQuestions) {
            submitBtn.classList.remove('hidden');
        }
    } else {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    }
}

// Select answer
function selectAnswer(questionIndex) {
    answeredQuestions.add(questionIndex);
    updateProgressDots();
    
    if (answeredQuestions.size === totalQuestions && currentQuestion === totalQuestions - 1) {
        document.getElementById('submit-btn').classList.remove('hidden');
    }
    
    // Auto-advance on mobile after a short delay (optional UX improvement)
    if (window.innerWidth < 768 && questionIndex < totalQuestions - 1) {
        setTimeout(() => {
            navigateQuestion('next');
        }, 1000);
    }
}

// Improved swipe gestures for mobile
function initializeSwipeGestures() {
    const container = document.getElementById('quiz-container');
    
    container.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, {passive: true});
    
    container.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipeGesture();
    }, {passive: true});
}

function handleSwipeGesture() {
    const swipeThreshold = 100; // Increased threshold for better mobile UX
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0 && currentQuestion < totalQuestions - 1) {
            // Swipe left - next question
            navigateQuestion('next');
        } else if (diff < 0 && currentQuestion > 0) {
            // Swipe right - previous question
            navigateQuestion('prev');
        }
    }
}

// Keyboard shortcuts
function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Arrow keys for navigation
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            navigateQuestion('next');
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            navigateQuestion('prev');
        }
        
        // Number keys for answer selection (1-6)
        const num = parseInt(e.key);
        if (num >= 1 && num <= 6) {
            const currentSlide = document.getElementById(`question-${currentQuestion}`);
            const options = currentSlide.querySelectorAll('input[type="radio"]');
            if (options[num - 1]) {
                options[num - 1].checked = true;
                options[num - 1].dispatchEvent(new Event('change'));
            }
        }
        
        // Enter to submit when on last question
        if (e.key === 'Enter' && currentQuestion === totalQuestions - 1 && answeredQuestions.size === totalQuestions) {
            e.preventDefault();
            document.getElementById('quiz-form').submit();
        }
        
        // F11 to toggle fullscreen
        if (e.key === 'F11') {
            e.preventDefault();
            toggleFullscreen();
        }
    });
}

// Fullscreen functionality
function toggleFullscreen() {
    const quizContent = document.getElementById('quiz-content');
    const icon = document.getElementById('fullscreen-icon');
    
    if (!document.fullscreenElement && !document.webkitFullscreenElement) {
        // Enter fullscreen
        if (quizContent.requestFullscreen) {
            quizContent.requestFullscreen();
        } else if (quizContent.webkitRequestFullscreen) {
            quizContent.webkitRequestFullscreen();
        }
        // Update icon to exit fullscreen
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4m0 0h5m-5 0l7 7m-7 7v-5m0 5h5m-5 0l7-7m7 7h-5m5 0v-5m0-5l-7 7"></path>`;
    } else {
        // Exit fullscreen
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
        // Update icon to enter fullscreen
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>`;
    }
}

// Exit quiz
function exitQuiz() {
    const modal = document.getElementById('exit-modal');
    const modalContent = document.getElementById('exit-modal-content');
    const progressBar = document.getElementById('progress-lost-bar');
    
    modal.classList.remove('hidden');
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        
        // Animate progress bar to show what will be lost
        const progressPercentage = (answeredQuestions.size / totalQuestions) * 100;
        setTimeout(() => {
            progressBar.style.width = progressPercentage + '%';
        }, 300);
    }, 50);
}

function closeExitModal() {
    const modal = document.getElementById('exit-modal');
    const modalContent = document.getElementById('exit-modal-content');
    const progressBar = document.getElementById('progress-lost-bar');
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0)';
    progressBar.style.width = '0%';
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function confirmExit() {
    // Add a fade out effect before redirecting
    document.getElementById('quiz-container').style.opacity = '0';
    document.getElementById('quiz-container').style.transition = 'opacity 0.5s';
    
    setTimeout(() => {
        window.location.href = '{{ route('home') }}';
    }, 500);
}

// Toggle passage
function togglePassage() {
    const modal = document.getElementById('passage-modal');
    modal.classList.toggle('hidden');
    
    // Prevent body scroll when modal is open
    if (!modal.classList.contains('hidden')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Timer
@if($quiz->time_limit)
let timeLeft = {{ $quiz->time_limit }} * 60;

function startTimer(minutes) {
    const timer = setInterval(() => {
        if (timeLeft > 0) {
            timeLeft--;
            const mins = Math.floor(timeLeft / 60);
            const secs = timeLeft % 60;
            const timerElement = document.getElementById('timer');
            if (timerElement) {
                timerElement.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
                
                // Change color when time is running out
                if (timeLeft <= 60) {
                    timerElement.parentElement.classList.add('animate-pulse');
                }
                
                if (timeLeft === 0) {
                    clearInterval(timer);
                    document.getElementById('quiz-form').submit();
                }
            }
        }
    }, 1000);
}
@endif

// Mobile-specific optimizations
if (window.innerWidth < 768) {
    // Reduce animation duration on mobile for better performance
    document.documentElement.style.setProperty('--animation-duration', '150ms');
    
    // Add haptic feedback for mobile devices
    if ('vibrate' in navigator) {
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio') {
                navigator.vibrate(50); // Short vibration on answer selection
            }
        });
    }
}

// Keyboard shortcuts info (only show on desktop)
if (window.innerWidth >= 768) {
    console.log('Quiz Controls: Arrow keys to navigate, 1-6 for answers, F11 for fullscreen');
}

// Prevent zoom on double tap (iOS)
let lastTouchEnd = 0;
document.addEventListener('touchend', function (event) {
    const now = (new Date()).getTime();
    if (now - lastTouchEnd <= 300) {
        event.preventDefault();
    }
    lastTouchEnd = now;
}, false);
</script>
@endpush