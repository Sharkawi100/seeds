@extends('layouts.app')

@push('styles')
<link href="{{ asset('build/assets/app-CB9m2Z7p.css') }}" rel="stylesheet">
<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('quizzes.show', $quiz) }}" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-arrow-right text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $quiz->title }}</h1>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-book text-gray-400"></i>
                                    {{ $quiz->subject_name }}
                                </span>
                                <span class="mx-2">•</span>
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-graduation-cap text-gray-400"></i>
                                    الصف {{ $quiz->grade_level }}
                                </span>
                                <span class="mx-2">•</span>
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-key text-gray-400"></i>
                                    {{ $quiz->pin }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        @if(!$quiz->has_submissions)
                            <a href="{{ route('quizzes.questions.bulk-edit', $quiz) }}" 
                               class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md">
                                <i class="fas fa-edit mr-2"></i>
                                تعديل الكل
                            </a>
                            
                            <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                               class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md">
                                <i class="fas fa-plus-circle mr-2"></i>
                                إضافة سؤال
                            </a>
                        @else
                            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg flex items-center gap-2">
                                <i class="fas fa-lock"></i>
                                <span class="font-medium">الاختبار مقفل - لا يمكن التعديل</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            @php
                $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                $roots = [
                    'jawhar' => [
                        'name' => 'جَوهر', 
                        'icon' => '🎯', 
                        'bg' => 'bg-gradient-to-br from-red-500 to-rose-600',
                        'light' => 'bg-red-50',
                        'text' => 'text-red-600',
                        'border' => 'border-red-200'
                    ],
                    'zihn' => [
                        'name' => 'ذِهن', 
                        'icon' => '🧠', 
                        'bg' => 'bg-gradient-to-br from-blue-500 to-indigo-600',
                        'light' => 'bg-blue-50',
                        'text' => 'text-blue-600',
                        'border' => 'border-blue-200'
                    ],
                    'waslat' => [
                        'name' => 'وَصلات', 
                        'icon' => '🔗', 
                        'bg' => 'bg-gradient-to-br from-amber-500 to-orange-600',
                        'light' => 'bg-amber-50',
                        'text' => 'text-amber-600',
                        'border' => 'border-amber-200'
                    ],
                    'roaya' => [
                        'name' => 'رُؤية', 
                        'icon' => '👁️', 
                        'bg' => 'bg-gradient-to-br from-purple-500 to-violet-600',
                        'light' => 'bg-purple-50',
                        'text' => 'text-purple-600',
                        'border' => 'border-purple-200'
                    ]
                ];
            @endphp
            
            @foreach($roots as $key => $root)
            <div class="relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border {{ $root['border'] }}">
                <div class="{{ $root['bg'] }} p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="text-4xl">{{ $root['icon'] }}</div>
                        <div class="text-3xl font-bold">{{ $rootCounts[$key] ?? 0 }}</div>
                    </div>
                    <h3 class="font-semibold mt-2">{{ $root['name'] }}</h3>
                </div>
                <div class="{{ $root['light'] }} p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm {{ $root['text'] }}">من إجمالي الأسئلة</span>
                        <span class="text-lg font-bold {{ $root['text'] }}">
                            {{ $quiz->questions->count() > 0 ? number_format(($rootCounts[$key] ?? 0) / $quiz->questions->count() * 100, 0) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Passage Section -->
        @if($quiz->passage)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gray-800 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white flex items-center gap-3">
                        <i class="fas fa-file-alt text-xl"></i>
                        {{ $quiz->passage_title ?: 'النص التعليمي' }}
                    </h2>
                    @if(!$quiz->has_submissions)
                    <button onclick="togglePassageEdit()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
                        <i class="fas fa-pen mr-2"></i>
                        تعديل النص
                    </button>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                <div id="passage-view" class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $quiz->passage }}</p>
                </div>
                
                @if(!$quiz->has_submissions)
                <form id="passage-edit" class="hidden" action="{{ route('quizzes.update', $quiz) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="title" value="{{ $quiz->title }}">
                    <input type="hidden" name="subject" value="{{ $quiz->subject }}">
                    <input type="hidden" name="grade_level" value="{{ $quiz->grade_level }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان النص</label>
                            <input type="text" name="passage_title" value="{{ $quiz->passage_title }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">النص</label>
                            <textarea name="passage" id="passage-editor">{{ $quiz->passage }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-all">
                                <i class="fas fa-save mr-2"></i>
                                حفظ التغييرات
                            </button>
                            <button type="button" onclick="togglePassageEdit()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-all">
                                إلغاء
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
        @endif

        <!-- Questions Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-3">
                    <i class="fas fa-question-circle text-gray-500"></i>
                    الأسئلة ({{ $quiz->questions->count() }})
                </h2>
            </div>

            @if($quiz->questions->isEmpty())
                <div class="p-16 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                        <i class="fas fa-inbox text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد أسئلة بعد</h3>
                    <p class="text-gray-500 mb-6">ابدأ بإضافة أسئلة لهذا الاختبار</p>
                    @if(!$quiz->has_submissions)
                    <a href="{{ route('quizzes.questions.create', $quiz) }}" 
                       class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105">
                        <i class="fas fa-plus-circle"></i>
                        إضافة أول سؤال
                    </a>
                    @endif
                </div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($quiz->questions as $index => $question)
                    <div class="p-6 hover:bg-gray-50 transition-colors group">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 {{ $roots[$question->root_type]['bg'] }} rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $roots[$question->root_type]['light'] }} {{ $roots[$question->root_type]['text'] }}">
                                                {{ $roots[$question->root_type]['icon'] }} {{ $roots[$question->root_type]['name'] }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                                                <i class="fas fa-layer-group mr-1"></i>
                                                مستوى {{ $question->depth_level }}
                                            </span>
                                        </div>
                                        <p class="text-lg text-gray-900 font-medium leading-relaxed">{!! $question->question !!}</p>
                                    </div>
                                    
                                    @if(!$quiz->has_submissions)
                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity mr-4">
                                        <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" 
                                           class="text-blue-500 hover:text-blue-700 p-2 hover:bg-blue-50 rounded-lg transition-all" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('quizzes.questions.clone', [$quiz, $question]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-purple-500 hover:text-purple-700 p-2 hover:bg-purple-50 rounded-lg transition-all" title="نسخ">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')"
                                                    class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-all" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                    @foreach($question->options as $optionIndex => $option)
                                    <div class="flex items-center gap-3 p-3 rounded-lg {{ $option === $question->correct_answer ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                        <span class="flex-shrink-0 w-8 h-8 {{ $option === $question->correct_answer ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }} rounded-full flex items-center justify-center text-sm font-medium">
                                            {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$optionIndex] ?? $optionIndex + 1 }}
                                        </span>
                                        <span class="{{ $option === $question->correct_answer ? 'text-green-700 font-medium' : 'text-gray-700' }}">
                                            {{ $option }}
                                        </span>
                                        @if($option === $question->correct_answer)
                                        <i class="fas fa-check-circle text-green-500 mr-auto"></i>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quiz Preview Button -->
@if(!$quiz->has_submissions && $quiz->questions->count() > 0)
<div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50">
    <a href="{{ route('quiz.take', $quiz) }}" 
       class="group bg-gray-800 hover:bg-gray-900 text-white px-8 py-3 rounded-full shadow-xl flex items-center gap-3 transition-all">
        <i class="fas fa-play-circle text-lg"></i>
        <span class="font-medium">معاينة الاختبار</span>
    </a>
</div>
@endif

@endsection

@push('scripts')
<script>
function togglePassageEdit() {
    const view = document.getElementById('passage-view');
    const edit = document.getElementById('passage-edit');
    
    if (view.classList.contains('hidden')) {
        view.classList.remove('hidden');
        edit.classList.add('hidden');
        if (tinymce.get('passage-editor')) {
            tinymce.get('passage-editor').remove();
        }
    } else {
        view.classList.add('hidden');
        edit.classList.remove('hidden');
        tinymce.init({
            selector: '#passage-editor',
            height: 400,
            language: 'ar',
            directionality: 'rtl',
            plugins: 'lists link image table code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent',
            menubar: false
        });
    }
}
</script>
@endpush