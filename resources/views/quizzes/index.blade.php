@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Header -->
        <div class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="space-y-3">
                    <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        اختباراتي
                    </h1>
                    <p class="text-xl text-gray-600">أنشئ وأدِر اختباراتك بنموذج جُذور الأربعة</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="text-2xl font-bold text-gray-900">{{ $quizzes->count() }}</div>
                        <div class="text-sm text-gray-600 font-medium">إجمالي الاختبارات</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="text-2xl font-bold text-emerald-600">{{ $quizzes->where('is_active', true)->count() }}</div>
                        <div class="text-sm text-gray-600 font-medium">نشط</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="text-2xl font-bold text-blue-600">{{ $quizzes->sum(fn($q) => $q->questions->count()) }}</div>
                        <div class="text-sm text-gray-600 font-medium">الأسئلة</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="text-2xl font-bold text-orange-600">{{ $quizzes->sum(fn($q) => $q->results->count()) }}</div>
                        <div class="text-sm text-gray-600 font-medium">المحاولات</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Control Panel -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl p-6 mb-8 border border-gray-100 shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                
                <!-- Search & Filters -->
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    <!-- Search -->
                    <div class="relative flex-1 min-w-0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               id="search-input"
                               placeholder="ابحث في الاختبارات..." 
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 text-right focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300"
                               value="{{ request('search') }}">
                    </div>
                    
                    <!-- Subject Filter -->
                    <select id="subject-filter" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                        <option value="">جميع المواد</option>
                        @foreach(App\Models\Subject::active()->ordered()->get() as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <!-- Grade Filter -->
                    <select id="grade-filter" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                        <option value="">جميع الصفوف</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('grade_level') == $i ? 'selected' : '' }}>
                                الصف {{ $i }}
                            </option>
                        @endfor
                    </select>
                    
                    <!-- Status Filter -->
                    <select id="status-filter" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="has_submissions" {{ request('status') == 'has_submissions' ? 'selected' : '' }}>تم استخدامه</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button onclick="clearFilters()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-medium transition-all duration-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        مسح الفلاتر
                    </button>
                    <a href="{{ route('quizzes.create') }}" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-2xl font-medium transition-all duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        اختبار جديد
                    </a>
                </div>
            </div>
        </div>

        <!-- Quizzes Grid -->
        @if($quizzes->count() > 0)
            <div id="quizzes-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($quizzes as $quiz)
                <div class="quiz-card bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-200 group h-full flex flex-col"
                     data-subject="{{ $quiz->subject_id ?? '' }}" 
                     data-grade="{{ $quiz->grade_level }}" 
                     data-status="{{ $quiz->is_active ? 'active' : 'inactive' }}{{ $quiz->has_submissions ? ' has_submissions' : '' }}"
                     data-title="{{ strtolower($quiz->title) }}">
                    
                    <!-- Quiz Header -->
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
                    <div class="relative {{ $selectedGradient }} p-6 text-white min-h-[160px] flex flex-col justify-between overflow-hidden"
                         style="{{ $selectedStyle }} background-size: cover; background-attachment: local;">
                        <!-- Strong Dark Overlay for Extra Insurance -->
                        <div class="absolute inset-0" style="background: rgba(0,0,0,0.2);"></div>
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                        <div class="absolute top-4 left-4 w-16 h-16 bg-white/5 rounded-full blur-xl"></div>
                        
                        <!-- Content Layer -->
                        <div class="relative z-10">
                            <!-- Status Badge & PIN -->
                            <div class="flex justify-between items-start mb-4">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold {{ $quiz->is_active ? 'bg-green-400 text-green-900' : 'bg-gray-400 text-gray-900' }} shadow-lg backdrop-blur-sm border border-white/20">
                                    <div class="w-2.5 h-2.5 rounded-full {{ $quiz->is_active ? 'bg-green-700' : 'bg-gray-700' }} ml-2 animate-pulse"></div>
                                    {{ $quiz->is_active ? 'نشط' : 'معطل' }}
                                </span>
                                <div class="text-2xl font-mono font-black text-white bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/30 shadow-lg hover:bg-white/30 transition-all duration-300">
                                    {{ $quiz->pin ?? '---' }}
                                </div>
                            </div>
                            
                            <!-- Quiz Title -->
                            <div>
                                <h3 class="text-xl font-bold leading-tight mb-4 text-white line-clamp-2 min-h-[3rem] flex items-center drop-shadow-sm">
                                    {{ $quiz->title }}
                                </h3>
                                <div class="flex items-center justify-between text-white/90 text-sm">
                                    <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-full border border-white/20 shadow-sm hover:bg-white/30 transition-all duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span class="font-bold">{{ $quiz->subject->name ?? 'غير محدد' }}</span>
                                    </span>
                                    <span class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-2 rounded-full border border-white/20 shadow-sm hover:bg-white/30 transition-all duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="font-bold">الصف {{ $quiz->grade_level }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Subtle Pattern Overlay -->
                        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="7" cy="7" r="1"/><circle cx="27" cy="7" r="1"/><circle cx="47" cy="7" r="1"/><circle cx="7" cy="27" r="1"/><circle cx="27" cy="27" r="1"/><circle cx="47" cy="27" r="1"/><circle cx="7" cy="47" r="1"/><circle cx="27" cy="47" r="1"/><circle cx="47" cy="47" r="1"/></g></g></svg>');"></div>
                    </div>
                    
                    <!-- Quiz Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Statistics -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-2xl p-4 text-center border border-blue-100">
                                <div class="text-3xl font-black text-blue-700 mb-1">{{ $quiz->questions->count() }}</div>
                                <div class="text-sm text-blue-600 font-bold">سؤال</div>
                            </div>
                            <div class="bg-green-50 rounded-2xl p-4 text-center border border-green-100">
                                <div class="text-3xl font-black text-green-700 mb-1">{{ $quiz->results->count() }}</div>
                                <div class="text-sm text-green-600 font-bold">محاولة</div>
                            </div>
                        </div>
                        
                        <!-- Roots Distribution -->
                        @if($quiz->questions->count() > 0)
                        <div class="mb-6">
                            <div class="text-sm font-bold text-gray-800 mb-4 text-center">توزيع الجذور الأربعة</div>
                            <div class="grid grid-cols-4 gap-3">
                                @php
                                    $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                                    $rootColors = [
                                        'jawhar' => ['name' => 'جَوهر', 'bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                                        'zihn' => ['name' => 'ذِهن', 'bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200'],
                                        'waslat' => ['name' => 'وَصلات', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
                                        'roaya' => ['name' => 'رُؤية', 'bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-200']
                                    ];
                                @endphp
                                @foreach($rootColors as $rootType => $rootData)
                                    @php $count = $rootCounts[$rootType] ?? 0; @endphp
                                    <div class="text-center">
                                        <div class="w-12 h-12 mx-auto rounded-xl {{ $rootData['bg'] }} {{ $rootData['border'] }} border flex items-center justify-center mb-2 shadow-sm">
                                            <span class="{{ $rootData['text'] }} font-black text-lg">{{ $count }}</span>
                                        </div>
                                        <div class="text-xs {{ $rootData['text'] }} font-bold leading-tight">{{ $rootData['name'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Meta Information -->
                        <div class="text-sm text-gray-600 mb-6 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-gray-700">تاريخ الإنشاء</span>
                                <span class="font-mono bg-white px-2 py-1 rounded-md border">{{ $quiz->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($quiz->has_submissions)
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-gray-700">آخر نشاط</span>
                                <span class="text-green-600 font-bold">{{ $quiz->results->sortByDesc('created_at')->first()?->created_at?->diffForHumans() ?? 'لا يوجد' }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('quizzes.show', $quiz) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-2xl font-bold text-center transition-all duration-300 flex items-center justify-center gap-2 border border-gray-200 shadow-sm hover:shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    عرض
                                </a>
                                <a href="{{ route('quizzes.edit', $quiz) }}" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-2xl font-bold text-center transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    تعديل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">لا توجد اختبارات بعد</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">ابدأ رحلة التعليم الرقمي بإنشاء أول اختبار بنموذج جُذور الأربعة</p>
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-2xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    إنشاء اختبار جديد
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const subjectFilter = document.getElementById('subject-filter');
    const gradeFilter = document.getElementById('grade-filter');
    const statusFilter = document.getElementById('status-filter');
    const quizCards = document.querySelectorAll('.quiz-card');
    
    // Filter function
    function filterQuizzes() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedSubject = subjectFilter.value;
        const selectedGrade = gradeFilter.value;
        const selectedStatus = statusFilter.value;
        
        quizCards.forEach(card => {
            const title = card.dataset.title;
            const subject = card.dataset.subject;
            const grade = card.dataset.grade;
            const status = card.dataset.status;
            
            let shouldShow = true;
            
            // Search filter
            if (searchTerm && !title.includes(searchTerm)) {
                shouldShow = false;
            }
            
            // Subject filter
            if (selectedSubject && subject !== selectedSubject) {
                shouldShow = false;
            }
            
            // Grade filter
            if (selectedGrade && grade !== selectedGrade) {
                shouldShow = false;
            }
            
            // Status filter
            if (selectedStatus && !status.includes(selectedStatus)) {
                shouldShow = false;
            }
            
            // Apply filter with animation
            if (shouldShow) {
                card.style.display = 'block';
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 10);
            } else {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    }
    
    // Clear filters function
    window.clearFilters = function() {
        searchInput.value = '';
        subjectFilter.value = '';
        gradeFilter.value = '';
        statusFilter.value = '';
        filterQuizzes();
    };
    
    // Add event listeners
    searchInput.addEventListener('input', filterQuizzes);
    subjectFilter.addEventListener('change', filterQuizzes);
    gradeFilter.addEventListener('change', filterQuizzes);
    statusFilter.addEventListener('change', filterQuizzes);
    
    // Add smooth scroll and highlight effect
    quizCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Initial animation
    quizCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions for all elements */
.quiz-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.quiz-card:hover {
    transform: translateY(-4px);
}

/* Custom scrollbar for modern look */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Loading animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}
</style>
@endsection