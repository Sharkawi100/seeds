@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="quiz-container-modern">
    <!-- SVG Gradient Definitions -->
    <svg style="width: 0; height: 0; position: absolute;">
        <defs>
            <linearGradient id="progress-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
            </linearGradient>
        </defs>
    </svg>

    <!-- Quiz Header - Sticky -->
    <div class="quiz-header">
        <div class="quiz-header-content">
            <div class="quiz-info">
                <h1 class="quiz-title">{{ $quiz->title     .submit-section {
        bottom: 60px;
        padding: 1.5rem 1rem;
    }
    
    .submit-title {
        font-size: 1.25rem;
    }
    
    .submit-text {
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .completion-summary {
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .root-summary {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .summary-icon {
        font-size: 0.875rem;
    }
    
    .submit-button {
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
    }
}}</h1>
                <span class="quiz-badge">{{ $quiz->questions->count() }} أسئلة</span>
            </div>
            
            <div class="quiz-actions">
                @if($quiz->time_limit)
                <div class="timer-display">
                    <i class="far fa-clock"></i>
                    <span id="timer" class="timer-text">{{ $quiz->time_limit }}:00</span>
                </div>
                @endif
                
                <button onclick="confirmExit()" class="exit-button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Quiz Content -->
    <div class="quiz-content">
        <form id="quiz-form" action="{{ route('quiz.submit', $quiz) }}" method="POST">
            @csrf
            
            <!-- Questions Container -->
            <div class="questions-container">
                @foreach($questions as $index => $question)
                @php
                    $rootInfo = [
                        'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => '#ef4444'],
                        'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => '#06b6d4'],
                        'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => '#eab308'],
                        'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => '#a855f7']
                    ];
                    $currentRoot = $rootInfo[$question->root_type];
                @endphp
                
                <div class="question-screen" 
                     id="question-{{ $index }}"
                     data-question-index="{{ $index }}"
                     data-root-type="{{ $question->root_type }}"
                     style="{{ $index > 0 ? 'display: none;' : '' }}">
                    
                    <!-- Enhanced Question Number Circle -->
                    <div class="question-number-container">
                        <div class="question-number-circle">
                            <span class="number">{{ $index + 1 }}</span>
                            <span class="total-number">/ {{ $quiz->questions->count() }}</span>
                            <svg class="progress-ring" viewBox="0 0 80 80">
                                <circle class="progress-ring-bg" cx="40" cy="40" r="36"></circle>
                                <circle class="progress-ring-fill" cx="40" cy="40" r="36"
                                        style="stroke-dasharray: {{ 226 * (($index + 1) / $quiz->questions->count()) . ' 226' }}"></circle>
                            </svg>
                            <div class="pulse-effect"></div>
                        </div>
                        <div class="question-root-info">
                            <div class="root-badge" data-root-color="{{ $currentRoot['color'] }}">
                                <span class="root-icon">{{ $currentRoot['icon'] }}</span>
                                <span class="root-name">{{ $currentRoot['name'] }}</span>
                            </div>
                            <div class="depth-indicator">
                                <span class="depth-label">العمق:</span>
                                <div class="depth-dots">
                                    @for($i = 1; $i <= 3; $i++)
                                        <span class="depth-dot {{ $i <= $question->depth_level ? 'active' : '' }}"></span>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Question Text -->
                    <div class="question-text-container">
                        <h2 class="question-text">{!! $question->question !!}</h2>
                    </div>
                    
                    <!-- Enhanced Answer Cards -->
                    <div class="answer-cards-container">
                        @php
                            $optionColors = ['#ef4444', '#10b981', '#f59e0b', '#3b82f6', '#a855f7', '#ec4899'];
                            $optionLetters = ['أ', 'ب', 'ج', 'د', 'هـ', 'و'];
                        @endphp
                        
                        @foreach(($quiz->shuffle_answers ? $question->shuffled_options : $question->options) as $optionIndex => $option)
                        <label class="answer-card" data-card-color="{{ $optionColors[$optionIndex] }}">
                            <input type="radio" 
                                   name="answers[{{ $question->id }}]" 
                                   value="{{ $option }}"
                                   onchange="selectAnswer({{ $index }}, '{{ $question->root_type }}')"
                                   class="answer-input">
                            <div class="answer-card-content">
                                <div class="answer-letter" data-letter="{{ $optionLetters[$optionIndex] }}">
                                    @if($optionIndex == 2)
                                        {{-- Special handling for diamond shape --}}
                                        <span style="{{ 'display: block; transform: rotate(-45deg);' }}">{{ $optionLetters[$optionIndex] }}</span>
                                    @else
                                        {{ $optionLetters[$optionIndex] }}
                                    @endif
                                </div>
                                <div class="answer-text">{{ $option }}</div>
                                <div class="answer-checkmark">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Navigation Bar with Visual Progress -->
            <div class="quiz-navigation">
                <button type="button" 
                        id="prev-btn"
                        onclick="navigateQuestion('prev')"
                        class="nav-button nav-prev"
                        style="visibility: hidden;">
                    <i class="fas fa-chevron-right"></i>
                    <span>السابق</span>
                </button>
                
                <div class="nav-center">
                    <div class="progress-indicator">
                        <div class="progress-dots">
                            @foreach($quiz->questions as $idx => $q)
                            <button type="button"
                                    class="progress-dot"
                                    data-index="{{ $idx }}"
                                    data-root="{{ $q->root_type }}"
                                    onclick="goToQuestion({{ $idx }})"
                                    style="--dot-color: {{ $rootInfo[$q->root_type]['color'] }}"
                                    title="السؤال {{ $idx + 1 }} - {{ $rootInfo[$q->root_type]['name'] }}">
                            </button>
                            @endforeach
                        </div>
                        <div class="progress-line">
                            <div class="progress-line-fill" id="progressLineFill"></div>
                        </div>
                    </div>
                </div>
                
                <button type="button" 
                        id="next-btn"
                        onclick="navigateQuestion('next')"
                        class="nav-button nav-next">
                    <span>التالي</span>
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <!-- Submit Section -->
            <div id="submit-section" class="submit-section" style="display: none;">
                <div class="submit-content">
                    <h3 class="submit-title">🎉 أحسنت! أكملت جميع الأسئلة</h3>
                    <p class="submit-text">راجع إجاباتك ثم اضغط إرسال</p>
                    <div class="completion-summary">
                        @foreach($rootInfo as $type => $info)
                        <div class="root-summary" data-summary-color="{{ $info['color'] }}">
                            <span class="summary-icon">{{ $info['icon'] }}</span>
                            <span class="summary-name">{{ $info['name'] }}</span>
                            <span class="summary-count" id="summary-{{ $type }}">0</span>
                        </div>
                        @endforeach
                    </div>
                    <button type="submit" class="submit-button">
                        <i class="fas fa-paper-plane"></i>
                        <span>إرسال الاختبار</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Mobile Passage Drawer -->
    @if($quiz->questions->where('passage', '!=', null)->first())
    <div class="passage-drawer" id="passageDrawer">
        <div class="drawer-handle" onclick="togglePassageDrawer()">
            <div class="handle-bar"></div>
            <div class="handle-text">
                <i class="fas fa-book-open"></i>
                <span>نص القراءة</span>
                <i class="fas fa-chevron-up drawer-arrow"></i>
            </div>
        </div>
        <div class="drawer-content">
            <div class="passage-header">
                <h3>{{ $quiz->questions->first()->passage_title ?? 'نص القراءة' }}</h3>
                <button class="close-drawer" onclick="closePassageDrawer()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="passage-body">
                {!! nl2br(e($quiz->questions->first()->passage)) !!}
            </div>
        </div>
    </div>
    
    <!-- Desktop Passage Button -->
    <button class="passage-button-desktop" id="passageButtonDesktop" onclick="openPassageModal()">
        <i class="fas fa-book-open"></i>
        <span>نص القراءة</span>
    </button>
    
    <!-- Desktop Passage Modal -->
    <div class="passage-modal" id="passageModal" style="display: none;">
        <div class="passage-modal-content">
            <div class="passage-modal-header">
                <h3>{{ $quiz->questions->first()->passage_title ?? 'نص القراءة' }}</h3>
                <button class="close-modal" onclick="closePassageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="passage-modal-body">
                {!! nl2br(e($quiz->questions->first()->passage)) !!}
            </div>
        </div>
    </div>
    @endif

    <!-- Exit Modal -->
    <div id="exit-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-icon">😟</div>
            <h3 class="modal-title">هل تريد الخروج؟</h3>
            <p class="modal-text">ستفقد جميع إجاباتك!</p>
            <div class="modal-actions">
                <button onclick="closeExitModal()" class="modal-button modal-stay">
                    البقاء في الاختبار
                </button>
                <button onclick="window.location.href='{{ route('quizzes.index') }}'" class="modal-button modal-exit">
                    الخروج
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Reset and Base Styles */
* {
    box-sizing: border-box;
}

.quiz-container-modern {
    min-height: 100vh;
    background: #f3f4f6;
    overflow-x: hidden;
}

/* Quiz Header */
.quiz-header {
    position: sticky;
    top: 0;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 100;
}

.quiz-header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.quiz-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.quiz-title {
    font-size: 1.25rem;
    font-weight: bold;
    color: #1f2937;
    margin: 0;
}

.quiz-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.quiz-actions {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.timer-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.125rem;
    color: #374151;
}

.timer-text {
    font-weight: bold;
    font-variant-numeric: tabular-nums;
}

.exit-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #fee2e2;
    color: #dc2626;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.exit-button:hover {
    background: #fecaca;
    transform: scale(1.05);
}

/* Main Content Area */
.quiz-content {
    max-width: 900px;
    margin: 0 auto;
    padding: 1rem 1rem 80px;
    height: calc(100vh - 80px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Question Screen - Full Height */
.question-screen {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1.5rem;
    height: calc(100vh - 180px);
    display: flex;
    flex-direction: column;
    animation: fadeIn 0.3s ease-out;
}

.questions-container {
    flex: 1;
    overflow: hidden;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced Question Number Container - Compact */
.question-number-container {
    text-align: center;
    margin-bottom: 1rem;
    position: relative;
}

.question-number-circle {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.pulse-effect {
    position: absolute;
    inset: -8px;
    border-radius: 50%;
    opacity: 0;
    animation: pulse 2s ease-out infinite;
    pointer-events: none;
}

/* Pulse effect colors per root */
.question-screen[data-root-type="jawhar"] .pulse-effect {
    background: radial-gradient(circle, transparent, #ef4444);
}
.question-screen[data-root-type="zihn"] .pulse-effect {
    background: radial-gradient(circle, transparent, #06b6d4);
}
.question-screen[data-root-type="waslat"] .pulse-effect {
    background: radial-gradient(circle, transparent, #eab308);
}
.question-screen[data-root-type="roaya"] .pulse-effect {
    background: radial-gradient(circle, transparent, #a855f7);
}

@keyframes pulse {
    0% {
        transform: scale(0.95);
        opacity: 0.7;
    }
    70% {
        transform: scale(1.3);
        opacity: 0;
    }
    100% {
        transform: scale(1.3);
        opacity: 0;
    }
}

.question-number-circle .number {
    position: absolute;
    top: 45%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.75rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    transition: all 0.3s ease;
}

.question-number-circle .total-number {
    position: absolute;
    top: 60%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.progress-ring {
    position: absolute;
    top: 0;
    left: 0;
    transform: rotate(-90deg);
    filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));
    width: 80px;
    height: 80px;
}

.progress-ring-bg {
    fill: none;
    stroke: #e5e7eb;
    stroke-width: 6;
}

.progress-ring-fill {
    fill: none;
    stroke: url(#progress-gradient);
    stroke-width: 6;
    stroke-linecap: round;
    transition: stroke-dasharray 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Root information styling - Compact */
.question-root-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.root-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: 2px solid;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
}

.root-icon {
    font-size: 1.25rem;
}

.root-name {
    font-weight: 600;
}

/* Depth indicator - Inline */
.depth-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: #f9fafb;
    border-radius: 50px;
}

.depth-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
}

.depth-dots {
    display: flex;
    gap: 0.25rem;
}

.depth-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #e5e7eb;
    transition: all 0.3s ease;
}

.depth-dot.active {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    transform: scale(1.2);
}

/* Question Text - Compact */
.question-text-container {
    margin-bottom: 1rem;
    flex-shrink: 0;
}

.question-text {
    font-size: 1.25rem;
    line-height: 1.5;
    color: #1f2937;
    text-align: center;
    margin: 0;
}

/* Enhanced Answer Cards - Scrollable if needed */
.answer-cards-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    flex: 1;
    overflow-y: auto;
    padding: 0.5rem 0;
    margin-bottom: 1rem;
}

.answer-card {
    position: relative;
    cursor: pointer;
    transition: transform 0.2s;
    animation: cardEntrance 0.3s ease-out backwards;
}

.answer-card:nth-child(1) { animation-delay: 0.05s; }
.answer-card:nth-child(2) { animation-delay: 0.1s; }
.answer-card:nth-child(3) { animation-delay: 0.15s; }
.answer-card:nth-child(4) { animation-delay: 0.2s; }
.answer-card:nth-child(5) { animation-delay: 0.25s; }
.answer-card:nth-child(6) { animation-delay: 0.3s; }

@keyframes cardEntrance {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.answer-card:hover {
    transform: translateY(-2px) scale(1.01);
}

.answer-card:active {
    transform: translateY(0) scale(0.99);
}

.answer-input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.answer-card-content {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    background: white;
    border: 3px solid transparent;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
}

.answer-input:checked ~ .answer-card-content {
    border-width: 3px;
}

/* Ripple effect */
.answer-card-content::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.1);
    transform: translate(-50%, -50%);
    transition: width 0.5s, height 0.5s;
}

.answer-input:checked ~ .answer-card-content::before {
    width: 100%;
    height: 100%;
}

/* Geometric letter shapes - Compact */
.answer-letter {
    width: 40px;
    height: 40px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.125rem;
    margin-left: 0.75rem;
    flex-shrink: 0;
    position: relative;
    transition: all 0.3s;
}

/* Update the CSS to handle colors via data attributes */
.answer-card[data-card-color="#ef4444"] .answer-letter { background: #ef4444; }
.answer-card[data-card-color="#10b981"] .answer-letter { background: #10b981; }
.answer-card[data-card-color="#f59e0b"] .answer-letter { background: #f59e0b; }
.answer-card[data-card-color="#3b82f6"] .answer-letter { background: #3b82f6; }
.answer-card[data-card-color="#a855f7"] .answer-letter { background: #a855f7; }
.answer-card[data-card-color="#ec4899"] .answer-letter { background: #ec4899; }

.answer-card[data-card-color="#ef4444"] .answer-input:checked ~ .answer-card-content {
    border-color: #ef4444;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.05));
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
}
.answer-card[data-card-color="#10b981"] .answer-input:checked ~ .answer-card-content {
    border-color: #10b981;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.05));
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}
.answer-card[data-card-color="#f59e0b"] .answer-input:checked ~ .answer-card-content {
    border-color: #f59e0b;
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.05));
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
}
.answer-card[data-card-color="#3b82f6"] .answer-input:checked ~ .answer-card-content {
    border-color: #3b82f6;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.05));
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}
.answer-card[data-card-color="#a855f7"] .answer-input:checked ~ .answer-card-content {
    border-color: #a855f7;
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.15), rgba(168, 85, 247, 0.05));
    box-shadow: 0 8px 25px rgba(168, 85, 247, 0.3);
}
.answer-card[data-card-color="#ec4899"] .answer-input:checked ~ .answer-card-content {
    border-color: #ec4899;
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.15), rgba(236, 72, 153, 0.05));
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.answer-card[data-card-color="#ef4444"] .answer-checkmark { color: #ef4444; }
.answer-card[data-card-color="#10b981"] .answer-checkmark { color: #10b981; }
.answer-card[data-card-color="#f59e0b"] .answer-checkmark { color: #f59e0b; }
.answer-card[data-card-color="#3b82f6"] .answer-checkmark { color: #3b82f6; }
.answer-card[data-card-color="#a855f7"] .answer-checkmark { color: #a855f7; }
.answer-card[data-card-color="#ec4899"] .answer-checkmark { color: #ec4899; }

/* Root badge colors */
.root-badge[data-root-color="#ef4444"] {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    border-color: rgba(239, 68, 68, 0.3);
    box-shadow: 0 2px 10px rgba(239, 68, 68, 0.2);
}
.root-badge[data-root-color="#ef4444"] .root-name { color: #ef4444; }

.root-badge[data-root-color="#06b6d4"] {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(6, 182, 212, 0.05));
    border-color: rgba(6, 182, 212, 0.3);
    box-shadow: 0 2px 10px rgba(6, 182, 212, 0.2);
}
.root-badge[data-root-color="#06b6d4"] .root-name { color: #06b6d4; }

.root-badge[data-root-color="#eab308"] {
    background: linear-gradient(135deg, rgba(234, 179, 8, 0.1), rgba(234, 179, 8, 0.05));
    border-color: rgba(234, 179, 8, 0.3);
    box-shadow: 0 2px 10px rgba(234, 179, 8, 0.2);
}
.root-badge[data-root-color="#eab308"] .root-name { color: #eab308; }

.root-badge[data-root-color="#a855f7"] {
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(168, 85, 247, 0.05));
    border-color: rgba(168, 85, 247, 0.3);
    box-shadow: 0 2px 10px rgba(168, 85, 247, 0.2);
}
.root-badge[data-root-color="#a855f7"] .root-name { color: #a855f7; }

/* Progress dot colors */
.progress-dot[data-dot-color="#ef4444"].answered { background: #ef4444; }
.progress-dot[data-dot-color="#06b6d4"].answered { background: #06b6d4; }
.progress-dot[data-dot-color="#eab308"].answered { background: #eab308; }
.progress-dot[data-dot-color="#a855f7"].answered { background: #a855f7; }

.progress-dot[data-dot-color="#ef4444"].current { 
    background: #ef4444; 
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
}
.progress-dot[data-dot-color="#06b6d4"].current { 
    background: #06b6d4; 
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.2);
}
.progress-dot[data-dot-color="#eab308"].current { 
    background: #eab308; 
    box-shadow: 0 0 0 4px rgba(234, 179, 8, 0.2);
}
.progress-dot[data-dot-color="#a855f7"].current { 
    background: #a855f7; 
    box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.2);
}

/* Remove the old CSS variable-based styles */

/* Different shapes for each option */
.answer-card:nth-child(1) .answer-letter { border-radius: 50%; }
.answer-card:nth-child(2) .answer-letter { border-radius: 12px; }
.answer-card:nth-child(3) .answer-letter { border-radius: 12px; transform: rotate(45deg); }
.answer-card:nth-child(3) .answer-letter span { display: block; transform: rotate(-45deg); }
.answer-card:nth-child(4) .answer-letter { clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%); }
.answer-card:nth-child(5) .answer-letter { clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%); }
.answer-card:nth-child(6) .answer-letter { border-radius: 50% 20%; }

.answer-input:checked ~ .answer-card-content .answer-letter {
    transform: scale(1.1);
}

.answer-card:nth-child(3) .answer-input:checked ~ .answer-card-content .answer-letter {
    transform: rotate(45deg) scale(1.1);
}

.answer-text {
    flex: 1;
    font-size: 1rem;
    color: #374151;
    line-height: 1.4;
    font-weight: 500;
    padding: 0 0.75rem;
}

.answer-input:checked ~ .answer-card-content .answer-text {
    color: #1f2937;
    font-weight: 600;
}

.answer-checkmark {
    opacity: 0;
    transform: scale(0) rotate(-180deg);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    font-size: 1.25rem;
    margin-left: 0.25rem;
}

.answer-input:checked ~ .answer-card-content .answer-checkmark {
    opacity: 1;
    transform: scale(1) rotate(0);
}

/* Navigation Bar with Progress Visualization */
.quiz-navigation {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 1px solid #e5e7eb;
    padding: 0.75rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 90;
}

.nav-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.9375rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.nav-button:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.nav-button:active {
    transform: translateY(0);
}

.nav-prev {
    background: #6b7280;
}

.nav-prev:hover {
    background: #4b5563;
}

.nav-center {
    flex: 1;
    display: flex;
    justify-content: center;
    padding: 0 1rem;
}

/* Enhanced Progress Indicator */
.progress-indicator {
    position: relative;
    width: 100%;
    max-width: 400px;
}

.progress-dots {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
}

.progress-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #e5e7eb;
    border: 2px solid white;
    cursor: pointer;
    transition: all 0.3s;
    flex-shrink: 0;
    position: relative;
}

.progress-dot::before {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    background: transparent;
    transition: all 0.3s;
}

.progress-dot.answered {
    transform: scale(1.2);
}

.progress-dot.current {
    transform: scale(1.5);
}

.progress-dot.current::before {
    background: rgba(59, 130, 246, 0.3);
}

.progress-line {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e5e7eb;
    transform: translateY(-50%);
    z-index: 1;
}

.progress-line-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    width: 0%;
    transition: width 0.3s ease;
}

/* Submit Section - Overlay */
.submit-section {
    position: fixed;
    bottom: 70px;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 2rem;
    text-align: center;
    color: white;
    z-index: 80;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
}

.submit-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.submit-text {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
}

.completion-summary {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.root-summary {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    backdrop-filter: blur(10px);
}

.summary-icon {
    font-size: 1rem;
}

.summary-count {
    font-weight: bold;
    font-size: 1rem;
}

.submit-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    background: white;
    color: #059669;
    border: none;
    border-radius: 10px;
    font-size: 1.0625rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s;
}

.submit-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Mobile Passage Drawer */
.passage-drawer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-radius: 24px 24px 0 0;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
    transform: translateY(calc(100% - 60px));
    transition: transform 0.3s ease;
    z-index: 95;
    max-height: 80vh;
}

.passage-drawer.open {
    transform: translateY(0);
}

.drawer-handle {
    padding: 1rem;
    cursor: pointer;
    user-select: none;
}

.handle-bar {
    width: 40px;
    height: 4px;
    background: #d1d5db;
    border-radius: 2px;
    margin: 0 auto 0.75rem;
}

.handle-text {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #374151;
    font-weight: 500;
}

.drawer-arrow {
    transition: transform 0.3s;
}

.passage-drawer.open .drawer-arrow {
    transform: rotate(180deg);
}

.drawer-content {
    height: calc(80vh - 60px);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.passage-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.passage-header h3 {
    font-size: 1.25rem;
    color: #1f2937;
    margin: 0;
}

.close-drawer {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #f3f4f6;
    color: #6b7280;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.passage-body {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    font-size: 1.125rem;
    line-height: 1.8;
    color: #374151;
}

/* Desktop Passage Button */
.passage-button-desktop {
    display: none;
    position: fixed;
    bottom: 100px;
    right: 20px;
    padding: 0.75rem 1.5rem;
    background: #8b5cf6;
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    transition: all 0.2s;
    z-index: 50;
}

.passage-button-desktop:hover {
    background: #7c3aed;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
}

.passage-button-desktop i {
    margin-left: 0.5rem;
}

/* Desktop Passage Modal */
.passage-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 300;
    padding: 2rem;
}

.passage-modal-content {
    background: white;
    border-radius: 16px;
    max-width: 800px;
    width: 100%;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    animation: modalIn 0.3s ease-out;
}

.passage-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.passage-modal-header h3 {
    font-size: 1.5rem;
    color: #1f2937;
    margin: 0;
}

.close-modal {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #f3f4f6;
    color: #6b7280;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.close-modal:hover {
    background: #e5e7eb;
    color: #374151;
}

.passage-modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 2rem;
    font-size: 1.125rem;
    line-height: 1.8;
    color: #374151;
}

@media (min-width: 1024px) {
    .passage-drawer {
        display: none;
    }
    
    .passage-button-desktop {
        display: flex;
        align-items: center;
    }
}

/* Remove old desktop panel styles */

/* Modal Styles */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 200;
    padding: 1rem;
}

.modal-content {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    max-width: 400px;
    width: 100%;
    text-align: center;
    animation: modalIn 0.3s ease-out;
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.modal-text {
    font-size: 1.125rem;
    color: #6b7280;
    margin-bottom: 2rem;
}

.modal-actions {
    display: flex;
    gap: 1rem;
}

.modal-button {
    flex: 1;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-stay {
    background: #10b981;
    color: white;
}

.modal-stay:hover {
    background: #059669;
}

.modal-exit {
    background: #ef4444;
    color: white;
}

.modal-exit:hover {
    background: #dc2626;
}

/* Mobile Optimizations */
@media (max-width: 640px) {
    .quiz-header-content {
        padding: 0.75rem 1rem;
    }
    
    .quiz-title {
        font-size: 1.125rem;
    }
    
    .quiz-content {
        padding: 0.5rem 0.5rem 70px;
        height: calc(100vh - 60px);
    }
    
    .question-screen {
        padding: 1rem;
        border-radius: 12px;
        height: calc(100vh - 140px);
    }
    
    .question-number-circle {
        width: 70px;
        height: 70px;
        margin-bottom: 0.5rem;
    }
    
    .question-number-circle .number {
        font-size: 1.5rem;
    }
    
    .question-number-circle .total-number {
        font-size: 0.75rem;
    }
    
    .progress-ring {
        width: 70px;
        height: 70px;
    }
    
    .progress-ring-bg,
    .progress-ring-fill {
        r: 32;
        cx: 35;
        cy: 35;
        stroke-width: 5;
    }
    
    .root-badge {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .root-icon {
        font-size: 1rem;
    }
    
    .question-text {
        font-size: 1.125rem;
    }
    
    .answer-card-content {
        padding: 0.875rem;
    }
    
    .answer-letter {
        width: 36px;
        height: 36px;
        font-size: 1rem;
        margin-left: 0.75rem;
    }
    
    .answer-text {
        font-size: 0.9rem;
        padding: 0 0.75rem;
    }
    
    .answer-checkmark {
        font-size: 1.25rem;
    }
    
    .nav-button span {
        display: none;
    }
    
    .progress-dots {
        gap: 0.5rem;
    }
    
    .progress-dot {
        width: 10px;
        height: 10px;
    }
}

/* RTL Support */
[dir="rtl"] .answer-letter {
    margin-left: 0;
    margin-right: 0.75rem;
}

[dir="rtl"] .nav-prev i,
[dir="rtl"] .nav-next i {
    transform: scaleX(-1);
}

[dir="rtl"] .passage-button-desktop i {
    margin-left: 0;
    margin-right: 0.5rem;
}

/* Animation for number changes */
@keyframes numberChange {
    0% {
        transform: translate(-50%, -50%) scale(1) rotate(0deg);
    }
    50% {
        transform: translate(-50%, -50%) scale(1.3) rotate(180deg);
    }
    100% {
        transform: translate(-50%, -50%) scale(1) rotate(360deg);
    }
}

.question-number-circle .number.changing {
    animation: numberChange 0.5s ease-out;
}

/* Hover effects for desktop */
@media (hover: hover) {
    .answer-card-content:hover {
        border-color: #e5e7eb;
        transform: translateX(4px);
    }
    
    .answer-card-content:hover .answer-letter {
        transform: scale(1.05);
    }
    
    .answer-card:nth-child(3) .answer-card-content:hover .answer-letter {
        transform: rotate(45deg) scale(1.05);
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .answer-card-content {
        border-width: 4px;
    }
    
    .answer-input:checked ~ .answer-card-content {
        background: #000;
        color: white;
    }
    
    .answer-input:checked ~ .answer-card-content .answer-text {
        color: white;
    }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Global State
let currentQuestion = 0;
const totalQuestions = {{ $quiz->questions->count() }};
const answeredQuestions = new Set();
const rootTypeAnswered = {
    jawhar: 0,
    zihn: 0,
    waslat: 0,
    roaya: 0
};

// Initialize colors on page load
document.addEventListener('DOMContentLoaded', function() {
    // Apply answer card colors
    document.querySelectorAll('.answer-card').forEach(card => {
        const color = card.getAttribute('data-card-color');
        if (color) {
            const letter = card.querySelector('.answer-letter');
            if (letter) {
                letter.style.backgroundColor = color;
            }
        }
    });
    
    // Apply root badge colors  
    document.querySelectorAll('.root-badge').forEach(badge => {
        const color = badge.getAttribute('data-root-color');
        if (color) {
            const name = badge.querySelector('.root-name');
            if (name) {
                name.style.color = color;
            }
        }
    });
    
    updateQuestionDisplay();
    updateProgress();
    
    // Initialize timer if exists
    @if($quiz->time_limit)
    startTimer({{ $quiz->time_limit }});
    @endif
    
    // Mobile swipe gestures
    if (window.innerWidth <= 768) {
        enableSwipeGestures();
    }
});

// Navigation Functions
function navigateQuestion(direction) {
    const questions = document.querySelectorAll('.question-screen');
    
    if (direction === 'next' && currentQuestion < totalQuestions - 1) {
        questions[currentQuestion].style.display = 'none';
        currentQuestion++;
        updateQuestionDisplay();
        updateQuestionNumber(currentQuestion + 1);
    } else if (direction === 'prev' && currentQuestion > 0) {
        questions[currentQuestion].style.display = 'none';
        currentQuestion--;
        updateQuestionDisplay();
        updateQuestionNumber(currentQuestion + 1);
    }
}

function goToQuestion(index) {
    if (index >= 0 && index < totalQuestions) {
        document.querySelectorAll('.question-screen').forEach(q => q.style.display = 'none');
        currentQuestion = index;
        updateQuestionDisplay();
        updateQuestionNumber(currentQuestion + 1);
    }
}

function updateQuestionDisplay() {
    const questions = document.querySelectorAll('.question-screen');
    questions[currentQuestion].style.display = 'block';
    
    // Update navigation buttons
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    prevBtn.style.visibility = currentQuestion === 0 ? 'hidden' : 'visible';
    
    if (currentQuestion === totalQuestions - 1) {
        nextBtn.style.display = 'none';
        if (answeredQuestions.size === totalQuestions) {
            document.getElementById('submit-section').style.display = 'block';
        }
    } else {
        nextBtn.style.display = 'flex';
        document.getElementById('submit-section').style.display = 'none';
    }
    
    updateProgressDots();
    updateProgressLine();
    
    // Smooth scroll to top on mobile
    if (window.innerWidth <= 768) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// Answer Selection
function selectAnswer(questionIndex, rootType) {
    const wasAnswered = answeredQuestions.has(questionIndex);
    answeredQuestions.add(questionIndex);
    
    if (!wasAnswered) {
        rootTypeAnswered[rootType] = (rootTypeAnswered[rootType] || 0) + 1;
        updateRootSummary();
    }
    
    updateProgress();
    updateProgressDots();
    updateProgressLine();
    
    // Check if all questions answered
    if (answeredQuestions.size === totalQuestions) {
        document.getElementById('submit-section').style.display = 'block';
    }
    
    // Auto-advance after short delay
    setTimeout(() => {
        if (currentQuestion < totalQuestions - 1) {
            navigateQuestion('next');
        }
    }, 300);
}

// Progress Updates
function updateProgress() {
    const progress = (answeredQuestions.size / totalQuestions) * 100;
    
    // Update progress ring
    const currentRing = document.querySelector(`#question-${currentQuestion} .progress-ring-fill`);
    if (currentRing) {
        const radius = currentRing.getAttribute('r');
        const circumference = 2 * Math.PI * radius;
        const offset = circumference - (progress / 100) * circumference;
        currentRing.style.strokeDasharray = `${circumference - offset} ${circumference}`;
    }
}

function updateProgressDots() {
    const dots = document.querySelectorAll('.progress-dot');
    
    dots.forEach((dot, index) => {
        dot.classList.remove('current', 'answered');
        
        if (index === currentQuestion) {
            dot.classList.add('current');
        }
        
        if (answeredQuestions.has(index)) {
            dot.classList.add('answered');
        }
    });
}

function updateProgressLine() {
    const progress = (answeredQuestions.size / totalQuestions) * 100;
    const progressLine = document.getElementById('progressLineFill');
    if (progressLine) {
        progressLine.style.width = `${progress}%`;
    }
}

function updateRootSummary() {
    Object.keys(rootTypeAnswered).forEach(root => {
        const element = document.getElementById(`summary-${root}`);
        if (element) {
            element.textContent = rootTypeAnswered[root];
        }
    });
}

function updateQuestionNumber(newNumber) {
    const numberElement = document.querySelector(`#question-${currentQuestion} .number`);
    if (numberElement) {
        numberElement.classList.add('changing');
        setTimeout(() => {
            numberElement.textContent = newNumber;
            numberElement.classList.remove('changing');
        }, 250);
    }
    
    // Update progress ring
    const progress = (newNumber / totalQuestions) * 100;
    const progressRing = document.querySelector(`#question-${currentQuestion} .progress-ring-fill`);
    if (progressRing) {
        const radius = progressRing.getAttribute('r');
        const circumference = 2 * Math.PI * radius;
        const offset = circumference - (progress / 100) * circumference;
        progressRing.style.strokeDasharray = `${circumference - offset} ${circumference}`;
    }
}

// Passage Functions
function togglePassageDrawer() {
    const drawer = document.getElementById('passageDrawer');
    drawer.classList.toggle('open');
}

function closePassageDrawer() {
    const drawer = document.getElementById('passageDrawer');
    drawer.classList.remove('open');
}

function openPassageModal() {
    document.getElementById('passageModal').style.display = 'flex';
}

function closePassageModal() {
    document.getElementById('passageModal').style.display = 'none';
}

// Close modal on background click
document.getElementById('passageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closePassageModal();
    }
});

// Exit Confirmation
function confirmExit() {
    if (answeredQuestions.size > 0) {
        document.getElementById('exit-modal').style.display = 'flex';
    } else {
        window.location.href = '{{ route('quizzes.index') }}';
    }
}

function closeExitModal() {
    document.getElementById('exit-modal').style.display = 'none';
}

// Timer Function
@if($quiz->time_limit)
function startTimer(minutes) {
    let timeLeft = minutes * 60;
    const timerElement = document.getElementById('timer');
    
    const interval = setInterval(() => {
        const mins = Math.floor(timeLeft / 60);
        const secs = timeLeft % 60;
        timerElement.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 300) {
            timerElement.style.color = '#ef4444';
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

// Mobile Swipe Gestures
function enableSwipeGestures() {
    let touchStartX = 0;
    let touchEndX = 0;
    
    const container = document.querySelector('.quiz-content');
    
    container.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    container.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left - next question
                navigateQuestion('next');
            } else {
                // Swipe right - previous question
                navigateQuestion('prev');
            }
        }
    }
}

// Keyboard Navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowRight') {
        navigateQuestion('next');
    } else if (e.key === 'ArrowLeft') {
        navigateQuestion('prev');
    } else if (e.key === 'Escape') {
        // Close passage modal if open, otherwise show exit confirmation
        const passageModal = document.getElementById('passageModal');
        if (passageModal && passageModal.style.display === 'flex') {
            closePassageModal();
        } else {
            confirmExit();
        }
    } else if (e.key === 'p' || e.key === 'P') {
        // Press P to open passage
        if (window.innerWidth >= 1024) {
            openPassageModal();
        }
    }
});

// Prevent accidental navigation
window.addEventListener('beforeunload', function(e) {
    if (answeredQuestions.size > 0) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endpush