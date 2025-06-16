@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 mb-2">إدارة الاختبارات</h1>
                    <p class="text-lg text-gray-600">أنشئ وراقب اختباراتك بنموذج جُذور الأربعة</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    <div class="bg-white/90 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30 shadow-sm">
                        <div class="text-2xl font-black text-purple-700">{{ $quizzes->count() }}</div>
                        <div class="text-sm text-gray-700 font-semibold">اختبار</div>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30 shadow-sm">
                        <div class="text-2xl font-black text-green-700">{{ $quizzes->where('is_active', true)->count() }}</div>
                        <div class="text-sm text-gray-700 font-semibold">نشط</div>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30 shadow-sm">
                        <div class="text-2xl font-black text-blue-700">{{ $quizzes->sum(fn($q) => $q->questions->count()) }}</div>
                        <div class="text-sm text-gray-700 font-semibold">سؤال</div>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm rounded-xl p-4 text-center border border-white/30 shadow-sm">
                        <div class="text-2xl font-black text-orange-700">{{ $quizzes->where('has_submissions', true)->count() }}</div>
                        <div class="text-sm text-gray-700 font-semibold">مُجرب</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="mb-8">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/40 shadow-sm">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    
                    <!-- Search & Filters -->
                    <div class="flex flex-col sm:flex-row gap-4 flex-1">
                        <div class="relative flex-1">
                            <input type="text" 
                                   id="search-quizzes" 
                                   placeholder="البحث في الاختبارات..." 
                                   class="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent font-medium">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <select id="filter-subject" class="px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 font-medium text-gray-700">
                                <option value="">جميع المواد</option>
                                @php
                                    // Get unique subjects from existing quizzes
                                    $uniqueSubjects = $quizzes->map(function($quiz) {
                                        if (is_string($quiz->subject)) {
                                            return $quiz->subject;
                                        } elseif (is_object($quiz->subject)) {
                                            return $quiz->subject->code ?? $quiz->subject->name ?? null;
                                        }
                                        return null;
                                    })->filter()->unique()->sort();
                                    
                                    // Subject name mapping
                                    $subjectNames = [
                                        'arabic' => 'العربية',
                                        'english' => 'الإنجليزية', 
                                        'hebrew' => 'العبرية'
                                    ];
                                @endphp
                                @foreach($uniqueSubjects as $subjectCode)
                                    <option value="{{ $subjectCode }}">
                                        {{ $subjectNames[$subjectCode] ?? ucfirst($subjectCode) }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <select id="filter-grade" class="px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 font-medium text-gray-700">
                                <option value="">جميع الصفوف</option>
                                @php
                                    // Get unique grade levels from existing quizzes
                                    $uniqueGrades = $quizzes->pluck('grade_level')->filter()->unique()->sort();
                                @endphp
                                @foreach($uniqueGrades as $grade)
                                    <option value="{{ $grade }}">الصف {{ $grade }}</option>
                                @endforeach
                            </select>
                            
                            <select id="filter-status" class="px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 font-medium text-gray-700">
                                <option value="">جميع الحالات</option>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                                <option value="submitted">مُجرب</option>
                            </select>
                        </div>
                    </div>

                    <!-- View Toggle & Create Button -->
                    <div class="flex items-center gap-4">
                        <div class="flex bg-gray-200 rounded-xl p-1">
                            <button id="grid-view" class="px-3 py-2 rounded-lg bg-white shadow-sm text-purple-700 font-bold">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button id="list-view" class="px-3 py-2 rounded-lg text-gray-700 font-bold">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                        
                        <a href="{{ route('quizzes.create') }}" 
                           class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-plus ml-2"></i>
                            إنشاء اختبار
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quizzes Grid -->
        @if($quizzes->count() > 0)
            <div id="quizzes-container" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($quizzes as $quiz)
                    @php
                        $totalQuestions = $quiz->questions->count();
                        $rootCounts = [
                            'jawhar' => $quiz->questions->where('root_type', 'jawhar')->count(),
                            'zihn' => $quiz->questions->where('root_type', 'zihn')->count(),
                            'waslat' => $quiz->questions->where('root_type', 'waslat')->count(),
                            'roaya' => $quiz->questions->where('root_type', 'roaya')->count(),
                        ];
                        
                        // Convert counts to percentages for chart
                        $chartScores = [
                            'jawhar' => $totalQuestions > 0 ? round(($rootCounts['jawhar'] / $totalQuestions) * 100) : 0,
                            'zihn' => $totalQuestions > 0 ? round(($rootCounts['zihn'] / $totalQuestions) * 100) : 0,
                            'waslat' => $totalQuestions > 0 ? round(($rootCounts['waslat'] / $totalQuestions) * 100) : 0,
                            'roaya' => $totalQuestions > 0 ? round(($rootCounts['roaya'] / $totalQuestions) * 100) : 0,
                        ];
                        
                        $hasPassage = !empty($quiz->educational_text);
                        $averageScore = $quiz->results()->avg('total_score') ?? 0;
                        $completionRate = $quiz->results()->count();
                    @endphp
                    
                    <div class="quiz-card bg-white/80 backdrop-blur-sm rounded-3xl border border-white/60 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 overflow-hidden"
                         data-subject="{{ is_string($quiz->subject) ? $quiz->subject : ($quiz->subject->code ?? ($quiz->subject->name ?? 'unknown')) }}" 
                         data-grade="{{ $quiz->grade_level }}"
                         data-status="{{ $quiz->is_active ? 'active' : 'inactive' }}"
                         data-submitted="{{ $quiz->has_submissions ? 'submitted' : 'not-submitted' }}"
                         data-title="{{ strtolower($quiz->title) }}">
                        
                        <!-- Card Header -->
                        <div class="relative p-6 pb-4 bg-gradient-to-br from-purple-50/80 to-blue-50/80">
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                @if($quiz->is_active)
                                    <span class="bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                        <i class="fas fa-check-circle mr-1"></i>نشط
                                    </span>
                                @else
                                    <span class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                        <i class="fas fa-pause-circle mr-1"></i>معطل
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Quiz Title with Enhanced Styling -->
                            <div class="mt-12 mb-6">
                                <h3 class="text-2xl font-black text-gray-900 mb-3 leading-tight bg-gradient-to-r from-purple-800 to-blue-800 bg-clip-text text-transparent">
                                    {{ $quiz->title }}
                                </h3>
                                <div class="flex items-center gap-3">
                                    <span class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">
                                        📚 @if(is_string($quiz->subject))
                                            {{ ['arabic' => 'العربية', 'english' => 'الإنجليزية', 'hebrew' => 'العبرية'][$quiz->subject] ?? $quiz->subject }}
                                        @elseif($quiz->subject)
                                            {{ $quiz->subject->name ?? $quiz->subject->title ?? 'غير محدد' }}
                                        @else
                                            غير محدد
                                        @endif
                                    </span>
                                    <span class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">
                                        🎓 الصف {{ $quiz->grade_level }}
                                    </span>
                                </div>
                            </div>

                            <!-- Stylish Stats Bar -->
                            <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-4 shadow-inner border border-gray-100">
                                <div class="grid grid-cols-4 gap-3">
                                    <!-- Questions Count -->
                                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white p-3 rounded-xl text-center shadow-lg hover:shadow-xl transition-all">
                                        <div class="text-lg font-black">{{ $totalQuestions }}</div>
                                        <div class="text-xs font-semibold opacity-95">📝 سؤال</div>
                                    </div>
                                    
                                    <!-- Average Score -->
                                    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white p-3 rounded-xl text-center shadow-lg hover:shadow-xl transition-all">
                                        <div class="text-lg font-black">
                                            {{ $averageScore > 0 ? number_format($averageScore, 0) . '%' : '--' }}
                                        </div>
                                        <div class="text-xs font-semibold opacity-95">⭐ متوسط</div>
                                    </div>
                                    
                                    <!-- Attempts -->
                                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-3 rounded-xl text-center shadow-lg hover:shadow-xl transition-all">
                                        <div class="text-lg font-black">{{ $completionRate }}</div>
                                        <div class="text-xs font-semibold opacity-95">🎯 محاولة</div>
                                    </div>
                                    
                                    <!-- PIN Code -->
                                    <div class="bg-gradient-to-r from-pink-600 to-rose-600 text-white p-3 rounded-xl text-center shadow-lg hover:shadow-xl transition-all cursor-pointer"
                                         onclick="copyQuizPin('{{ $quiz->pin_code }}')">
                                        <div class="text-lg font-black">{{ $quiz->pin_code }}</div>
                                        <div class="text-xs font-semibold opacity-95">🔑 الرمز</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Juzoor Chart Section -->
                        @if($totalQuestions > 0)
                            <div class="px-8 py-6 bg-gradient-to-r from-indigo-50/80 to-purple-50/80 backdrop-blur-sm">
                                <h4 class="text-xl font-black text-slate-800 mb-6 text-center">
                                    🌱 توزيع جُذور التعلم
                                </h4>
                                
                                <!-- Chart Component with Animation -->
                                <div class="flex justify-center mb-6 chart-container">
                                    <x-juzoor-chart :scores="$chartScores" size="small" />
                                </div>
                                
                                <!-- Animated Root Progress Bars -->
                                <div class="grid grid-cols-4 gap-4">
                                    <div class="root-progress-card text-center p-4 bg-gradient-to-br from-red-500/20 to-red-600/20 rounded-2xl border-2 border-red-500/40 backdrop-blur-sm">
                                        <div class="text-2xl font-black text-red-800 mb-2">{{ $rootCounts['jawhar'] }}</div>
                                        <div class="text-xs font-black text-red-700 mb-3">🎯 جَوْهَر</div>
                                        <div class="progress-bar bg-red-200/50 rounded-full h-2 overflow-hidden">
                                            <div class="progress-fill bg-gradient-to-r from-red-500 to-red-600 h-full rounded-full transition-all duration-1000" style="width: {{ $totalQuestions > 0 ? ($rootCounts['jawhar'] / $totalQuestions) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="root-progress-card text-center p-4 bg-gradient-to-br from-cyan-500/20 to-cyan-600/20 rounded-2xl border-2 border-cyan-500/40 backdrop-blur-sm">
                                        <div class="text-2xl font-black text-cyan-800 mb-2">{{ $rootCounts['zihn'] }}</div>
                                        <div class="text-xs font-black text-cyan-700 mb-3">🧠 ذِهْن</div>
                                        <div class="progress-bar bg-cyan-200/50 rounded-full h-2 overflow-hidden">
                                            <div class="progress-fill bg-gradient-to-r from-cyan-500 to-cyan-600 h-full rounded-full transition-all duration-1000" style="width: {{ $totalQuestions > 0 ? ($rootCounts['zihn'] / $totalQuestions) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="root-progress-card text-center p-4 bg-gradient-to-br from-yellow-500/20 to-yellow-600/20 rounded-2xl border-2 border-yellow-500/40 backdrop-blur-sm">
                                        <div class="text-2xl font-black text-yellow-800 mb-2">{{ $rootCounts['waslat'] }}</div>
                                        <div class="text-xs font-black text-yellow-700 mb-3">🔗 وَصَلات</div>
                                        <div class="progress-bar bg-yellow-200/50 rounded-full h-2 overflow-hidden">
                                            <div class="progress-fill bg-gradient-to-r from-yellow-500 to-yellow-600 h-full rounded-full transition-all duration-1000" style="width: {{ $totalQuestions > 0 ? ($rootCounts['waslat'] / $totalQuestions) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="root-progress-card text-center p-4 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-2xl border-2 border-purple-500/40 backdrop-blur-sm">
                                        <div class="text-2xl font-black text-purple-800 mb-2">{{ $rootCounts['roaya'] }}</div>
                                        <div class="text-xs font-black text-purple-700 mb-3">👁️ رُؤْيَة</div>
                                        <div class="progress-bar bg-purple-200/50 rounded-full h-2 overflow-hidden">
                                            <div class="progress-fill bg-gradient-to-r from-purple-500 to-purple-600 h-full rounded-full transition-all duration-1000" style="width: {{ $totalQuestions > 0 ? ($rootCounts['roaya'] / $totalQuestions) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Premium Features & Meta Info -->
                        <div class="px-8 py-5 bg-gradient-to-r from-slate-50/80 to-white/80 backdrop-blur-sm">
                            <div class="flex flex-wrap gap-3 justify-center">
                                @if($hasPassage)
                                    <span class="feature-badge bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-xl text-sm font-black shadow-md border border-blue-500/30 backdrop-blur-sm">
                                        <i class="fas fa-file-text mr-1"></i>📄 يحتوي على نص
                                    </span>
                                @endif
                                @if($quiz->has_submissions)
                                    <span class="feature-badge bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-black shadow-md border border-emerald-500/30 backdrop-blur-sm">
                                        <i class="fas fa-users mr-1"></i>👥 مُجرب
                                    </span>
                                @endif
                                <span class="feature-badge bg-gradient-to-r from-slate-600 to-slate-700 text-white px-4 py-2 rounded-xl text-sm font-black shadow-md border border-slate-500/30 backdrop-blur-sm">
                                    <i class="fas fa-calendar mr-1"></i>📅 {{ $quiz->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <!-- Premium Action Buttons with Enhanced Contrast -->
                        <div class="px-8 pb-8">
                            <div class="grid grid-cols-2 gap-5 mb-4">
                                <!-- Primary Actions with Premium Design -->
                                <a href="{{ route('quizzes.show', $quiz) }}" 
                                   class="premium-action-btn bg-gradient-to-r from-slate-800 via-slate-700 to-slate-900 text-white px-6 py-5 rounded-2xl text-center font-black hover:from-slate-900 hover:via-slate-800 hover:to-black transition-all duration-500 transform hover:scale-105 shadow-premium border border-slate-600/30 backdrop-blur-sm">
                                    <i class="fas fa-eye mr-2 text-lg"></i>👀 عرض
                                </a>
                                
                                <a href="{{ route('quizzes.edit', $quiz) }}" 
                                   class="premium-action-btn bg-gradient-to-r from-teal-700 via-teal-600 to-teal-800 text-white px-6 py-5 rounded-2xl text-center font-black hover:from-teal-800 hover:via-teal-700 hover:to-teal-900 transition-all duration-500 transform hover:scale-105 shadow-premium border border-teal-500/30 backdrop-blur-sm">
                                    <i class="fas fa-edit mr-2 text-lg"></i>✏️ تعديل
                                </a>
                            </div>
                            
                            <!-- Secondary Actions with Enhanced Contrast -->
                            <div class="grid grid-cols-2 gap-4">
                                @if($quiz->has_submissions)
                                    <a href="{{ route('results.quiz', $quiz) }}" 
                                       class="secondary-action-btn bg-gradient-to-r from-emerald-700 via-emerald-600 to-emerald-800 text-white px-5 py-4 rounded-xl text-center text-sm font-black hover:from-emerald-800 hover:via-emerald-700 hover:to-emerald-900 transition-all duration-400 transform hover:scale-105 shadow-lg border border-emerald-500/30 backdrop-blur-sm">
                                        <i class="fas fa-chart-bar mr-1 text-base"></i>📊 النتائج
                                    </a>
                                @else
                                    <div class="secondary-action-btn bg-gradient-to-r from-slate-500 via-slate-400 to-slate-600 text-white px-5 py-4 rounded-xl text-center text-sm font-black border border-slate-400/30 backdrop-blur-sm opacity-75">
                                        <i class="fas fa-chart-bar mr-1 text-base"></i>📊 لا توجد نتائج
                                    </div>
                                @endif
                                
                                <form action="{{ route('quizzes.duplicate', $quiz) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full secondary-action-btn bg-gradient-to-r from-violet-700 via-violet-600 to-violet-800 text-white px-5 py-4 rounded-xl text-center text-sm font-black hover:from-violet-800 hover:via-violet-700 hover:to-violet-900 transition-all duration-400 transform hover:scale-105 shadow-lg border border-violet-500/30 backdrop-blur-sm">
                                        <i class="fas fa-copy mr-1 text-base"></i>📋 نسخ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Premium Empty State with Pulse Animation -->
            <div class="text-center py-20">
                <div class="empty-state-card bg-gradient-to-br from-white/90 via-white/80 to-slate-50/80 backdrop-blur-xl rounded-3xl p-16 border border-white/60 max-w-lg mx-auto shadow-neumorphic-lg">
                    <div class="pulse-container w-32 h-32 bg-gradient-to-br from-purple-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-8 shadow-neumorphic animate-pulse-slow">
                        <i class="fas fa-clipboard-list text-6xl bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent animate-bounce-slow"></i>
                    </div>
                    <h3 class="text-3xl font-black bg-gradient-to-r from-slate-800 to-slate-700 bg-clip-text text-transparent mb-6">
                        لا توجد اختبارات بعد
                    </h3>
                    <p class="text-slate-600 mb-10 text-lg font-medium leading-relaxed">
                        ابدأ بإنشاء أول اختبار لك باستخدام نموذج جُذور الأربعة
                    </p>
                    <a href="{{ route('quizzes.create') }}" 
                       class="premium-cta-button bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 text-white px-10 py-5 rounded-2xl font-black hover:from-purple-700 hover:via-blue-700 hover:to-indigo-700 transition-all duration-500 transform hover:scale-110 shadow-premium hover:shadow-premium-xl border border-purple-500/30 backdrop-blur-sm">
                        <i class="fas fa-plus ml-3 text-xl"></i>
                        إنشاء اختبار جديد
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Search and Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-quizzes');
    const subjectFilter = document.getElementById('filter-subject');
    const gradeFilter = document.getElementById('filter-grade');
    const statusFilter = document.getElementById('filter-status');
    const quizCards = document.querySelectorAll('.quiz-card');
    
    function filterQuizzes() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedSubject = subjectFilter.value;
        const selectedGrade = gradeFilter.value;
        const selectedStatus = statusFilter.value;
        
        quizCards.forEach(card => {
            const title = card.dataset.title;
            const subject = card.dataset.subject.toLowerCase();
            const grade = card.dataset.grade;
            const status = card.dataset.status;
            const submitted = card.dataset.submitted;
            
            let showCard = true;
            
            // Search filter
            if (searchTerm && !title.includes(searchTerm)) {
                showCard = false;
            }
            
            // Subject filter
            if (selectedSubject && subject !== selectedSubject.toLowerCase()) {
                showCard = false;
            }
            
            // Grade filter
            if (selectedGrade && grade !== selectedGrade) {
                showCard = false;
            }
            
            // Status filter
            if (selectedStatus) {
                if (selectedStatus === 'active' && status !== 'active') showCard = false;
                if (selectedStatus === 'inactive' && status !== 'inactive') showCard = false;
                if (selectedStatus === 'submitted' && submitted !== 'submitted') showCard = false;
            }
            
            card.style.display = showCard ? 'block' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterQuizzes);
    subjectFilter.addEventListener('change', filterQuizzes);
    gradeFilter.addEventListener('change', filterQuizzes);
    statusFilter.addEventListener('change', filterQuizzes);
    
    // View toggle functionality
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const container = document.getElementById('quizzes-container');
    
    gridView.addEventListener('click', function() {
        container.className = 'grid grid-cols-1 lg:grid-cols-2 gap-8';
        gridView.classList.add('bg-white', 'shadow-sm', 'text-purple-700');
        gridView.classList.remove('text-gray-700');
        listView.classList.remove('bg-white', 'shadow-sm', 'text-purple-700');
        listView.classList.add('text-gray-700');
    });
    
    listView.addEventListener('click', function() {
        container.className = 'grid grid-cols-1 gap-6';
        listView.classList.add('bg-white', 'shadow-sm', 'text-purple-700');
        listView.classList.remove('text-gray-700');
        gridView.classList.remove('bg-white', 'shadow-sm', 'text-purple-700');
        gridView.classList.add('text-gray-700');
    });
});

// Copy PIN functionality with enhanced notification
function copyQuizPin(pin) {
    navigator.clipboard.writeText(pin).then(function() {
        // Show success notification with premium styling
        const notification = document.createElement('div');
        notification.className = 'notification fixed top-4 right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl z-50 font-bold';
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <div>
                    <div class="font-black">تم نسخ الرمز بنجاح! ✨</div>
                    <div class="text-sm opacity-90">الرمز: ${pin}</div>
                </div>
            </div>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }).catch(function() {
        // Fallback notification
        const notification = document.createElement('div');
        notification.className = 'notification fixed top-4 right-4 bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-4 rounded-2xl shadow-2xl z-50 font-bold';
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-xl"></i>
                <div>فشل في نسخ الرمز: ${pin}</div>
            </div>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });
}

// Add enhanced smooth animations
document.querySelectorAll('.quiz-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-12px) rotateX(5deg)';
        this.style.boxShadow = '0 35px 60px -12px rgba(0, 0, 0, 0.2)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) rotateX(0deg)';
        this.style.boxShadow = '';
    });
});

// Add ripple effect to buttons
document.querySelectorAll('button, a').forEach(element => {
    element.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced custom animations and transitions */
.quiz-card {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.quiz-card:hover {
    transform: translateY(-12px) rotateX(5deg);
    box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.2);
}

/* Enhanced glassmorphism effect */
.bg-white\/80 {
    backdrop-filter: blur(12px);
    background: rgba(255, 255, 255, 0.85);
}

.bg-white\/90 {
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.92);
}

/* Gradient text effect */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

/* Premium button hover effects */
.quiz-card .grid > div:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Smooth transitions for all interactive elements */
button, a, input, select {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, rgba(147, 51, 234, 0.4), rgba(59, 130, 246, 0.4));
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, rgba(147, 51, 234, 0.6), rgba(59, 130, 246, 0.6));
}

/* Pulse animation for PIN click */
.quiz-card .grid > div:nth-child(4) {
    animation: subtlePulse 3s infinite;
}

@keyframes subtlePulse {
    0%, 100% { 
        box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.4); 
    }
    50% { 
        box-shadow: 0 0 0 10px rgba(244, 63, 94, 0); 
    }
}

/* Loading animation for charts */
.juzoor-chart {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from { 
        opacity: 0; 
        transform: translateY(30px) scale(0.9); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

/* Gradient border animation */
.quiz-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 24px;
    padding: 2px;
    background: linear-gradient(45deg, transparent, rgba(147, 51, 234, 0.3), transparent);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.quiz-card:hover::before {
    opacity: 1;
}

/* Enhanced text gradients */
h3.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradientShift 4s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Floating effect for stats */
.grid > div {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Click ripple effect */
.ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.ripple {
    position: relative;
    overflow: hidden;
}

.ripple::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
}

/* Notification styles */
.notification {
    animation: slideInRight 0.5s ease-out, slideOutRight 0.5s ease-in 2.5s forwards;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>
@endpush
@endsection