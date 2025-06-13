@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $quiz->title }}</h1>
                        <div class="flex gap-4 text-white/90">
                            <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium">
                                {{ ($quiz->subject ?? 'arabic') == 'arabic' ? '🌍 عربي' : (($quiz->subject ?? 'arabic') == 'english' ? '🌎 إنجليزي' : '🌏 عبري') }}
                            </span>
                            <span>🎓 الصف {{ $quiz->grade_level }}</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('quizzes.edit', $quiz) }}" class="bg-white text-purple-600 px-5 py-2.5 rounded-lg font-bold hover:bg-purple-50 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            تعديل
                        </a>
                        <a href="{{ route('quiz.take', $quiz) }}" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-lg font-bold transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            معاينة
                        </a>
                        <a href="{{ route('admin.quizzes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg font-bold transition">
                            رجوع
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-4 gap-4 p-6 bg-gray-50">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $quiz->questions->count() }}</div>
                    <div class="text-sm text-gray-600">إجمالي الأسئلة</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $quiz->results->count() }}</div>
                    <div class="text-sm text-gray-600">عدد المحاولات</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $quiz->user->name }}</div>
                    <div class="text-sm text-gray-600">المنشئ</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $quiz->created_at->diffForHumans() }}</div>
                    <div class="text-sm text-gray-600">تاريخ الإنشاء</div>
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <span class="bg-indigo-100 text-indigo-600 p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
                الأسئلة
            </h2>
            
            @php
                $rootColors = [
                    'jawhar' => ['bg' => 'bg-red-50', 'border' => 'border-red-300', 'text' => 'text-red-700', 'icon' => '🎯'],
                    'zihn' => ['bg' => 'bg-cyan-50', 'border' => 'border-cyan-300', 'text' => 'text-cyan-700', 'icon' => '🧠'],
                    'waslat' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-300', 'text' => 'text-yellow-700', 'icon' => '🔗'],
                    'roaya' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-300', 'text' => 'text-purple-700', 'icon' => '👁️']
                ];
                $rootNames = [
                    'jawhar' => 'جَوهر',
                    'zihn' => 'ذِهن',
                    'waslat' => 'وَصلات',
                    'roaya' => 'رُؤية'
                ];
            @endphp
            
            <div class="space-y-6">
                @foreach($quiz->questions as $index => $question)
                <div class="{{ $rootColors[$question->root_type]['bg'] }} {{ $rootColors[$question->root_type]['border'] }} border-2 rounded-xl p-6 transition-all hover:shadow-lg">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl font-bold text-gray-400">{{ $index + 1 }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl">{{ $rootColors[$question->root_type]['icon'] }}</span>
                                <span class="font-bold {{ $rootColors[$question->root_type]['text'] }}">
                                    {{ $rootNames[$question->root_type] }}
                                </span>
                                <span class="px-3 py-1 bg-white rounded-full text-xs font-medium {{ $rootColors[$question->root_type]['text'] }}">
                                    مستوى {{ $question->depth_level }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-lg font-medium text-gray-800 mb-4">{{ $question->question }}</p>
                    
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($question->options as $optionIndex => $option)
                        <div class="flex items-center gap-3 p-3 rounded-lg {{ $option == $question->correct_answer ? 'bg-green-100 border-2 border-green-400' : 'bg-white' }}">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $option == $question->correct_answer ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ ['أ', 'ب', 'ج', 'د'][$optionIndex] }}
                            </span>
                            <span class="{{ $option == $question->correct_answer ? 'font-bold text-green-700' : 'text-gray-700' }}">
                                {{ $option }}
                            </span>
                            @if($option == $question->correct_answer)
                            <svg class="w-5 h-5 text-green-600 mr-auto" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($quiz->questions->isEmpty())
            <div class="text-center py-12">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 text-lg">لا توجد أسئلة في هذا الاختبار</p>
                <a href="{{ route('quizzes.questions.create', $quiz) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-bold">
                    إضافة أسئلة
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection