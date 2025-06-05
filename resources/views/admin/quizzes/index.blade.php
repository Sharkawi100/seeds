@extends('layouts.app')

@section('content')
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<style>
    body, * {
        font-family: 'Tajawal', sans-serif !important;
    }
    .quiz-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .quiz-card:hover {
        transform: translateY(-4px);
        border-color: #818cf8;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .status-badge {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .pin-code {
        font-family: 'Courier New', monospace;
        letter-spacing: 0.2em;
        font-weight: bold;
    }
</style>
@endpush

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">إدارة الاختبارات</h1>
                    <p class="text-indigo-100">عرض وإدارة جميع الاختبارات في المنصة</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportData()" class="bg-white/20 backdrop-blur hover:bg-white/30 text-white px-5 py-2.5 rounded-lg font-medium transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        تصدير
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="bg-white text-purple-600 hover:bg-purple-50 px-5 py-2.5 rounded-lg font-bold transition">
                        رجوع للوحة التحكم
                    </a>
                </div>
            </div>
            
            {{-- Statistics --}}
            <div class="grid grid-cols-4 gap-4 mt-8">
                <div class="bg-white/20 backdrop-blur rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-indigo-100">إجمالي الاختبارات</div>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-white">{{ $stats['active'] ?? 0 }}</div>
                    <div class="text-sm text-indigo-100">اختبارات نشطة</div>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-white">{{ $stats['total_attempts'] ?? 0 }}</div>
                    <div class="text-sm text-indigo-100">إجمالي المحاولات</div>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-white">{{ $stats['this_week'] ?? 0 }}</div>
                    <div class="text-sm text-indigo-100">هذا الأسبوع</div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.quizzes.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[300px]">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="البحث بالعنوان أو الرمز..."
                               class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <svg class="w-5 h-5 absolute right-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <select name="subject" class="rounded-lg border-gray-200 focus:border-indigo-500 px-4 py-3">
                    <option value="">جميع المواد</option>
                    <option value="arabic" {{ request('subject') == 'arabic' ? 'selected' : '' }}>اللغة العربية</option>
                    <option value="english" {{ request('subject') == 'english' ? 'selected' : '' }}>اللغة الإنجليزية</option>
                    <option value="hebrew" {{ request('subject') == 'hebrew' ? 'selected' : '' }}>اللغة العبرية</option>
                </select>
                
                <select name="grade" class="rounded-lg border-gray-200 focus:border-indigo-500 px-4 py-3">
                    <option value="">جميع الصفوف</option>
                    @for($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}" {{ request('grade') == $i ? 'selected' : '' }}>الصف {{ $i }}</option>
                    @endfor
                </select>
                
                <select name="status" class="rounded-lg border-gray-200 focus:border-indigo-500 px-4 py-3">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
                    <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    تصفية
                </button>
                
                @if(request()->hasAny(['search', 'subject', 'grade', 'status']))
                    <a href="{{ route('admin.quizzes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition">
                        إلغاء
                    </a>
                @endif
            </form>
        </div>

        {{-- Quizzes Grid --}}
        <div class="grid gap-6">
            @forelse($quizzes as $quiz)
            <div class="quiz-card bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        {{-- Quiz Info --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h3 class="text-xl font-bold text-gray-800">{{ $quiz->title }}</h3>
                                @if($quiz->is_active)
                                    <span class="status-badge bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        نشط
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                        غير نشط
                                    </span>
                                @endif
                                @if($quiz->is_demo)
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                        تجريبي
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $quiz->user->name ?? 'غير معروف' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    {{ ['arabic' => 'العربية', 'english' => 'الإنجليزية', 'hebrew' => 'العبرية'][$quiz->subject] }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                    </svg>
                                    الصف {{ $quiz->grade_level }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $quiz->created_at->format('Y/m/d') }}
                                </span>
                            </div>
                            
                            {{-- Statistics Row --}}
                            <div class="flex gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-indigo-600">{{ $quiz->questions_count }}</div>
                                    <div class="text-xs text-gray-500">سؤال</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $quiz->results->count() }}</div>
                                    <div class="text-xs text-gray-500">محاولة</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ $quiz->results->count() > 0 ? number_format($quiz->results->avg('total_score'), 1) : '-' }}%
                                    </div>
                                    <div class="text-xs text-gray-500">متوسط</div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- PIN and Actions --}}
                        <div class="text-center ml-6">
                            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                                <div class="text-xs text-gray-500 mb-1">رمز الدخول</div>
                                <div class="pin-code text-2xl text-gray-800">{{ $quiz->pin }}</div>
                            </div>
                            
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('admin.quizzes.show', $quiz) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    عرض
                                </a>
                                <a href="{{ route('quizzes.edit', $quiz) }}" 
                                   class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    تعديل
                                </a>
                                <button onclick="toggleStatus({{ $quiz->id }})" 
                                        class="{{ $quiz->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    {{ $quiz->is_active ? 'تعطيل' : 'تفعيل' }}
                                </button>
                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاختبار؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Root Distribution Bar --}}
                @php
                    $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                    $total = $quiz->questions_count ?: 1;
                @endphp
                <div class="bg-gray-50 px-6 py-3 border-t">
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-500">توزيع الجذور:</span>
                        <div class="flex-1 flex gap-1">
                            <div class="bg-red-400 h-6 rounded-l" style="width: {{ ($rootCounts['jawhar'] ?? 0) / $total * 100 }}%" title="جَوهر: {{ $rootCounts['jawhar'] ?? 0 }}"></div>
                            <div class="bg-cyan-400 h-6" style="width: {{ ($rootCounts['zihn'] ?? 0) / $total * 100 }}%" title="ذِهن: {{ $rootCounts['zihn'] ?? 0 }}"></div>
                            <div class="bg-yellow-400 h-6" style="width: {{ ($rootCounts['waslat'] ?? 0) / $total * 100 }}%" title="وَصلات: {{ $rootCounts['waslat'] ?? 0 }}"></div>
                            <div class="bg-purple-400 h-6 rounded-r" style="width: {{ ($rootCounts['roaya'] ?? 0) / $total * 100 }}%" title="رُؤية: {{ $rootCounts['roaya'] ?? 0 }}"></div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-xl text-gray-500">لا توجد اختبارات تطابق معايير البحث</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($quizzes->hasPages())
        <div class="mt-8">
            {{ $quizzes->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
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
@endpush
@endsection