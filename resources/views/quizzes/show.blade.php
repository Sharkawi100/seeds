@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-3">{{ $quiz->title }}</h1>
                        <div class="flex flex-wrap gap-4 text-white/90">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ ['arabic' => 'اللغة العربية', 'english' => 'اللغة الإنجليزية', 'hebrew' => 'اللغة العبرية'][$quiz->subject] }}
                            </span>
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                الصف {{ $quiz->grade_level }}
                            </span>
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $quiz->created_at->format('Y/m/d') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('quiz.take', $quiz) }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-bold hover:bg-blue-50 transition">
                            ▶️ تشغيل الاختبار
                        </a>
                        <a href="{{ route('quizzes.edit', $quiz) }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition">
                            ✏️ تعديل
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-4 gap-4 p-6 bg-gray-50">
                @php
                    $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                    $depthCounts = $quiz->questions->groupBy('depth_level')->map->count();
                @endphp
                
                <div class="bg-white rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-gray-800">{{ $quiz->questions->count() }}</div>
                    <div class="text-sm text-gray-600">إجمالي الأسئلة</div>
                </div>
                
                <div class="bg-white rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $depthCounts[3] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">أسئلة عميقة</div>
                </div>
                
                <div class="bg-white rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-orange-600">{{ $depthCounts[2] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">أسئلة متوسطة</div>
                </div>
                
                <div class="bg-white rounded-lg p-4 text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $depthCounts[1] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">أسئلة سطحية</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Reading Passage (if exists) -->
                @if($quiz->questions->where('passage', '!=', null)->first())
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <span class="text-3xl">📄</span>
                        <div>
                            <h3 class="text-xl font-bold text-blue-900">
                                {{ $quiz->questions->first()->passage_title ?? 'نص القراءة' }}
                            </h3>
                            <p class="text-blue-700">يُعرض هذا النص للطلاب قبل الأسئلة</p>
                        </div>
                    </div>
                    <div class="bg-white/70 rounded-lg p-4 text-gray-700">
                        {!! nl2br(e($quiz->questions->first()->passage)) !!}
                    </div>
                </div>
                @endif

                <!-- Questions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        الأسئلة
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($quiz->questions as $index => $question)
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                            <div class="flex items-start gap-3 mb-3">
                                <span class="text-xl font-bold text-gray-400">{{ $index + 1 }}</span>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 mb-2">{{ $question->question }}</p>
                                    <div class="flex gap-2 mb-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                                              style="background-color: {{ 
                                                  $question->root_type == 'jawhar' ? '#fee2e2' : 
                                                  ($question->root_type == 'zihn' ? '#e0f2fe' : 
                                                  ($question->root_type == 'waslat' ? '#fef3c7' : '#ede9fe')) 
                                              }}">
                                            {{ ['jawhar' => '🎯 جَوْهَر', 'zihn' => '🧠 ذِهْن', 'waslat' => '🔗 وَصَلات', 'roaya' => '👁️ رُؤْيَة'][$question->root_type] }}
                                        </span>
                                        <span class="px-3 py-1 rounded-full text-xs {{ 
                                            $question->depth_level == 1 ? 'bg-yellow-100 text-yellow-800' : 
                                            ($question->depth_level == 2 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') 
                                        }}">
                                            مستوى {{ $question->depth_level }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($question->options as $option)
                                        <div class="flex items-center gap-2 p-2 rounded {{ $option == $question->correct_answer ? 'bg-green-50 text-green-700 font-bold' : 'bg-gray-50' }}">
                                            <span class="w-6 h-6 rounded-full bg-gray-300 text-xs flex items-center justify-center {{ $option == $question->correct_answer ? 'bg-green-500 text-white' : '' }}">
                                                {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][array_search($option, $question->options)] }}
                                            </span>
                                            {{ $option }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Root Distribution Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4 text-center">توزيع الجذور</h3>
                    <x-juzoor-chart :scores="[
                        'jawhar' => ($rootCounts['jawhar'] ?? 0) / max($quiz->questions->count(), 1) * 100,
                        'zihn' => ($rootCounts['zihn'] ?? 0) / max($quiz->questions->count(), 1) * 100,
                        'waslat' => ($rootCounts['waslat'] ?? 0) / max($quiz->questions->count(), 1) * 100,
                        'roaya' => ($rootCounts['roaya'] ?? 0) / max($quiz->questions->count(), 1) * 100
                    ]" size="medium" />
                </div>

                <!-- Root Details -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">تفاصيل الجذور</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <span class="font-bold">🎯 جَوْهَر</span>
                            <span class="text-xl font-bold text-red-600">{{ $rootCounts['jawhar'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-cyan-50 rounded-lg">
                            <span class="font-bold">🧠 ذِهْن</span>
                            <span class="text-xl font-bold text-cyan-600">{{ $rootCounts['zihn'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <span class="font-bold">🔗 وَصَلات</span>
                            <span class="text-xl font-bold text-yellow-600">{{ $rootCounts['waslat'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                            <span class="font-bold">👁️ رُؤْيَة</span>
                            <span class="text-xl font-bold text-purple-600">{{ $rootCounts['roaya'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">الإجراءات</h3>
                    <div class="space-y-3">
                        <a href="{{ route('quizzes.questions.index', $quiz) }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-3 rounded-lg font-bold transition">
                            📝 إدارة الأسئلة
                        </a>
                        <a href="{{ route('quiz.take', $quiz) }}" class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 rounded-lg font-bold transition">
                            ▶️ معاينة الاختبار
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-center py-3 rounded-lg font-bold transition">
                            🔙 العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection