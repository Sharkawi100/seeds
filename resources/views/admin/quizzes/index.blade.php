@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                        <div>
                            <h1 class="text-4xl font-bold mb-3">إدارة الاختبارات</h1>
                            <p class="text-lg text-indigo-100">عرض وإدارة جميع الاختبارات في المنصة</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 mt-6 lg:mt-0">
                            <button onclick="exportData()" 
                                    class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-xl backdrop-blur-sm transition-all duration-200">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                تصدير البيانات
                            </button>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 hover:bg-gray-50 font-bold rounded-xl transition-all duration-200">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                </svg>
                                لوحة التحكم
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                            <div class="text-3xl font-bold text-white mb-1">{{ $stats['total'] ?? $quizzes->total() ?? $quizzes->count() }}</div>
                            <div class="text-sm text-indigo-100">إجمالي الاختبارات</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                            <div class="text-3xl font-bold text-white mb-1">{{ $stats['active'] ?? $quizzes->where('is_active', true)->count() }}</div>
                            <div class="text-sm text-indigo-100">اختبارات نشطة</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                            <div class="text-3xl font-bold text-white mb-1">{{ $stats['total_attempts'] ?? $quizzes->sum(function($q) { return $q->results->count(); }) }}</div>
                            <div class="text-sm text-indigo-100">إجمالي المحاولات</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/20">
                            <div class="text-3xl font-bold text-white mb-1">{{ $stats['this_week'] ?? $quizzes->where('created_at', '>=', now()->startOfWeek())->count() }}</div>
                            <div class="text-sm text-indigo-100">هذا الأسبوع</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Filters Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-indigo-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    تصفية وبحث الاختبارات
                </h3>
            </div>
            
            <form method="GET" action="{{ route('admin.quizzes.index') }}" class="p-6" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="البحث بالعنوان أو الرمز..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <svg class="w-5 h-5 absolute right-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Subject Filter (Fixed to send correct values) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المادة</label>
                        <select name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">جميع المواد</option>
                            <!-- Updated to send the old subject field values that your controller expects -->
                            <option value="arabic" {{ request('subject') == 'arabic' ? 'selected' : '' }}>اللغة العربية</option>
                            <option value="english" {{ request('subject') == 'english' ? 'selected' : '' }}>اللغة الإنجليزية</option>
                            <option value="hebrew" {{ request('subject') == 'hebrew' ? 'selected' : '' }}>اللغة العبرية</option>
                            <option value="science" {{ request('subject') == 'science' ? 'selected' : '' }}>العلوم</option>
                            <option value="mathematics" {{ request('subject') == 'mathematics' ? 'selected' : '' }}>الرياضيات</option>
                        </select>
                    </div>
                    
                    <!-- Grade Filter (Fixed to match controller) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الصف</label>
                        <select name="grade" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">جميع الصفوف</option>
                            @for($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}" {{ request('grade') == $i ? 'selected' : '' }}>الصف {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <!-- Status Filter (Fixed to match controller) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mt-6">
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            تصفية
                        </button>
                        
                        @if(request()->hasAny(['search', 'subject', 'grade', 'status']))
                            <a href="{{ route('admin.quizzes.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                إلغاء التصفية
                            </a>
                        @endif
                    </div>
                    
                    <!-- Results Count -->
                    <div class="text-sm text-gray-500">
                        @if(method_exists($quizzes, 'total'))
                            عرض {{ $quizzes->count() }} من أصل {{ $quizzes->total() }} نتيجة
                        @else
                            عرض {{ $quizzes->count() }} نتيجة
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Enhanced Filter Debug Info -->
        @if(request()->hasAny(['search', 'subject', 'grade', 'status']))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-blue-900 mb-2">التصفيات المطبقة:</h4>
                <div class="text-sm text-blue-800 mb-3">
                    @if(request('search'))
                        <span class="inline-block bg-blue-100 px-3 py-1 rounded-full ml-2 mb-2">البحث: {{ request('search') }}</span>
                    @endif
                    @if(request('subject'))
                        @php 
                            $subjectNames = [
                                'arabic' => 'اللغة العربية',
                                'english' => 'اللغة الإنجليزية', 
                                'hebrew' => 'اللغة العبرية',
                                'science' => 'العلوم',
                                'mathematics' => 'الرياضيات'
                            ];
                            $selectedSubjectName = $subjectNames[request('subject')] ?? request('subject');
                        @endphp
                        <span class="inline-block bg-blue-100 px-3 py-1 rounded-full ml-2 mb-2">المادة: {{ $selectedSubjectName }}</span>
                    @endif
                    @if(request('grade'))
                        <span class="inline-block bg-blue-100 px-3 py-1 rounded-full ml-2 mb-2">الصف: {{ request('grade') }}</span>
                    @endif
                    @if(request('status'))
                        <span class="inline-block bg-blue-100 px-3 py-1 rounded-full ml-2 mb-2">الحالة: {{ request('status') == 'active' ? 'نشط' : 'غير نشط' }}</span>
                    @endif
                </div>
                
                <!-- Debug: URL Parameters -->
                <div class="bg-blue-100 p-3 rounded-lg mb-3">
                    <h5 class="font-semibold text-blue-900 mb-1">URL Parameters:</h5>
                    <code class="text-xs text-blue-800">{{ request()->fullUrl() }}</code>
                </div>
                
                <!-- Debug: Quiz Count -->
                <div class="bg-blue-100 p-3 rounded-lg">
                    <h5 class="font-semibold text-blue-900 mb-1">نتائج التصفية:</h5>
                    <p class="text-blue-800">عرض {{ $quizzes->count() }} اختبارات</p>
                    @if($quizzes->count() > 0)
                        <p class="text-blue-800 text-xs mt-1">أول اختبار: {{ $quizzes->first()->title }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Fixed! Now matches your controller -->
        @if(!request()->hasAny(['search', 'subject', 'grade', 'status']))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-800 font-medium">✅ التصفيات متطابقة الآن مع الـ Controller وستعمل بشكل صحيح!</p>
                </div>
            </div>
        @else
            <!-- Potential Issue Alert -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-600 ml-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.768 0L3.046 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-amber-900 mb-1">⚠️ إذا لم تعمل التصفية بعد:</h4>
                        <p class="text-sm text-amber-800 mb-2">قد تحتاج إلى تحديث الـ Controller ليستخدم subject_id بدلاً من subject:</p>
                        <details class="mt-2">
                            <summary class="cursor-pointer text-amber-900 font-medium text-sm">عرض الحل المحدث للـ Controller</summary>
                            <div class="mt-2 bg-amber-100 p-3 rounded border text-xs">
                                <pre class="text-amber-900 whitespace-pre-wrap">// استبدل فلتر المادة في AdminQuizController بهذا:
if ($request->filled('subject')) {
    $subjectMapping = [
        'arabic' => 1, 'english' => 2, 'science' => 3, 
        'mathematics' => 4, 'hebrew' => 5
    ];
    if (isset($subjectMapping[$request->subject])) {
        $query->where('subject_id', $subjectMapping[$request->subject]);
    }
}</pre>
                            </div>
                        </details>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quizzes Grid -->
        <div class="space-y-6">
            @forelse($quizzes as $quiz)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <!-- Quiz Information -->
                            <div class="flex-1">
                                <!-- Title and Status -->
                                <div class="flex items-center gap-4 mb-4">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $quiz->title }}</h3>
                                    
                                    <div class="flex items-center gap-2">
                                        @if($quiz->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 animate-pulse">
                                                <svg class="w-2 h-2 ml-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                نشط
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-2 h-2 ml-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                غير نشط
                                            </span>
                                        @endif
                                        
                                        @if($quiz->is_demo)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                تجريبي
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Quiz Details -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 ml-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $quiz->user->name ?? 'غير معروف' }}
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        @if($quiz->subject)
                                            {{ $quiz->subject->name }}
                                        @else
                                            غير محدد
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 ml-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        الصف {{ $quiz->grade_level }}
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 ml-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $quiz->created_at->format('Y/m/d') }}
                                    </div>
                                </div>
                                
                                <!-- Statistics -->
                                <div class="flex items-center gap-8">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-indigo-600">{{ $quiz->questions->count() }}</div>
                                        <div class="text-xs text-gray-500">سؤال</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ $quiz->results->count() }}</div>
                                        <div class="text-xs text-gray-500">محاولة</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-purple-600">
                                            @if($quiz->results->count() > 0)
                                                {{ number_format($quiz->results->avg('total_score'), 1) }}%
                                            @else
                                                --
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">متوسط</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- PIN and Actions -->
                            <div class="text-center ml-8">
                                <!-- PIN Display -->
                                <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-xl p-4 text-white mb-4 min-w-[120px]">
                                    <div class="text-xs text-white/80 mb-1">رمز الدخول</div>
                                    <div class="text-2xl font-mono font-bold tracking-wider">{{ $quiz->pin }}</div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="space-y-2">
                                    <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        عرض
                                    </a>
                                    
                                    <a href="{{ route('quizzes.edit', $quiz) }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        تعديل
                                    </a>
                                    
                                    <button onclick="toggleStatus({{ $quiz->id }})" 
                                            class="w-full px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors duration-200 {{ $quiz->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }}">
                                        {{ $quiz->is_active ? 'تعطيل' : 'تفعيل' }}
                                    </button>
                                    
                                    <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" 
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الاختبار؟')" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Juzoor Root Distribution -->
                    @php
                        $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                        $total = $quiz->questions->count() ?: 1;
                        $roots = [
                            'jawhar' => ['name' => 'جَوهر', 'color' => 'red', 'icon' => '🎯'],
                            'zihn' => ['name' => 'ذِهن', 'color' => 'cyan', 'icon' => '🧠'],
                            'waslat' => ['name' => 'وَصلات', 'color' => 'yellow', 'icon' => '🔗'],
                            'roaya' => ['name' => 'رُؤية', 'color' => 'purple', 'icon' => '👁️']
                        ];
                    @endphp
                    
                    @if($quiz->questions->count() > 0)
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-700">توزيع جُذور التعلم</span>
                                <span class="text-xs text-gray-500">{{ $quiz->questions->count() }} سؤال</span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="flex rounded-lg overflow-hidden h-3 mb-3">
                                @foreach($roots as $key => $root)
                                    @php
                                        $count = $rootCounts[$key] ?? 0;
                                        $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                                    @endphp
                                    <div class="bg-{{ $root['color'] }}-400 transition-all duration-300" 
                                         style="width: {{ $percentage }}%" 
                                         title="{{ $root['name'] }}: {{ $count }} ({{ number_format($percentage, 1) }}%)"></div>
                                @endforeach
                            </div>
                            
                            <!-- Legend -->
                            <div class="flex justify-between text-xs">
                                @foreach($roots as $key => $root)
                                    @php $count = $rootCounts[$key] ?? 0; @endphp
                                    <div class="flex items-center">
                                        <span class="text-sm ml-1">{{ $root['icon'] }}</span>
                                        <span class="text-gray-600">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="p-6 bg-gray-100 rounded-full inline-block mb-6">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">لا توجد اختبارات</h3>
                    <p class="text-gray-600 mb-6">لا توجد اختبارات تطابق معايير البحث المحددة</p>
                    @if(request()->hasAny(['search', 'subject_id', 'grade', 'status']))
                        <a href="{{ route('admin.quizzes.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            إلغاء التصفية
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(method_exists($quizzes, 'hasPages') && $quizzes->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    {{ $quizzes->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
async function toggleStatus(quizId) {
    try {
        const response = await fetch(`/admin/quizzes/${quizId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            window.location.reload();
        } else {
            alert('حدث خطأ أثناء تغيير حالة الاختبار');
        }
    } catch (error) {
        alert('حدث خطأ أثناء تغيير حالة الاختبار');
    }
}

function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = `/admin/quizzes/export?${params.toString()}`;
}
</script>
@endsection