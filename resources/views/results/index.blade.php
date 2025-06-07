@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">نتائج الاختبارات</h1>
            <p class="text-lg text-gray-600">جميع نتائج اختباراتك السابقة</p>
        </div>

        @if($results->count() > 0)
            <!-- Results Grid -->
            <div class="grid gap-6">
                @foreach($results as $result)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Quiz Info -->
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                                        {{ $result->quiz->title ?? 'اختبار محذوف' }}
                                    </h3>
                                    <div class="flex flex-wrap gap-3 text-sm">
                                        <span class="inline-flex items-center gap-1 text-gray-600">
                                            <span>📅</span>
                                            {{ $result->created_at->format('Y/m/d - h:i A') }}
                                        </span>
                                        @if($result->quiz)
                                            <span class="inline-flex items-center gap-1 text-gray-600">
                                                <span>📚</span>
                                                {{ ['arabic' => 'العربية', 'english' => 'الإنجليزية', 'hebrew' => 'العبرية'][$result->quiz->subject] ?? $result->quiz->subject }}
                                            </span>
                                            <span class="inline-flex items-center gap-1 text-gray-600">
                                                <span>🎓</span>
                                                الصف {{ $result->quiz->grade_level }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Score Display -->
                                <div class="flex items-center gap-6">
                                    <!-- Total Score -->
                                    <div class="text-center">
                                        <div class="text-3xl font-bold {{ $result->total_score >= 60 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $result->total_score }}%
                                        </div>
                                        <div class="text-sm text-gray-600">النتيجة الإجمالية</div>
                                    </div>

                                    <!-- Root Scores -->
                                    @if($result->scores)
                                        <div class="grid grid-cols-2 gap-3">
                                            @php
                                            $roots = [
                                                'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'text-red-600'],
                                                'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'text-blue-600'],
                                                'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'text-yellow-600'],
                                                'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'text-purple-600']
                                            ];
                                            @endphp
                                            @foreach($roots as $key => $root)
                                                @if(isset($result->scores[$key]))
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-lg">{{ $root['icon'] }}</span>
                                                        <span class="font-medium {{ $root['color'] }}">
                                                            {{ $result->scores[$key] }}%
                                                        </span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Action Button -->
                                    <a href="{{ route('results.show', $result) }}" 
                                       class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-medium transition-all flex items-center gap-2">
                                        <span>عرض التفاصيل</span>
                                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $results->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-md p-16 text-center">
                <div class="mb-6">
                    <span class="text-8xl">📊</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-3">لا توجد نتائج بعد</h2>
                <p class="text-gray-600 mb-6">لم تقم بإجراء أي اختبارات حتى الآن</p>
                <a href="{{ route('quizzes.index') }}" 
                   class="inline-block px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-medium transition-all">
                    تصفح الاختبارات
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
/* Pagination Styling */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.pagination .page-link {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    background: white;
    border: 1px solid #e5e7eb;
    color: #374151;
    transition: all 0.2s;
}

.pagination .page-link:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.pagination .page-item.active .page-link {
    background: #7c3aed;
    border-color: #7c3aed;
    color: white;
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
@endpush