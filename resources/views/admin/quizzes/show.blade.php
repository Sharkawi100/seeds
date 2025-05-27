@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">{{ $quiz->title }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('quiz.take', $quiz) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            معاينة
                        </a>
                        <a href="{{ route('admin.quizzes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            رجوع
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p><strong>المادة:</strong> {{ ['arabic' => 'العربية', 'english' => 'الإنجليزية', 'hebrew' => 'العبرية'][$quiz->subject] }}</p>
                        <p><strong>الصف:</strong> {{ $quiz->grade_level }}</p>
                        <p><strong>المستخدم:</strong> {{ $quiz->user->name }}</p>
                        <p><strong>تاريخ الإنشاء:</strong> {{ $quiz->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                    <div>
                        <p><strong>عدد الأسئلة:</strong> {{ $quiz->questions->count() }}</p>
                        <p><strong>عدد النتائج:</strong> {{ $quiz->results->count() }}</p>
                    </div>
                </div>

                <h3 class="text-xl font-bold mb-4">الأسئلة</h3>
                <div class="space-y-4">
                    @foreach($quiz->questions as $index => $question)
                    <div class="border p-4 rounded">
                        <div class="flex justify-between mb-2">
                            <strong>السؤال {{ $index + 1 }}</strong>
                            <span class="text-sm bg-{{ ['jawhar' => 'red', 'zihn' => 'blue', 'waslat' => 'yellow', 'roaya' => 'purple'][$question->root_type] }}-100 px-2 py-1 rounded">
                                {{ $question->root_type }} - مستوى {{ $question->depth_level }}
                            </span>
                        </div>
                        <p class="mb-2">{{ $question->question }}</p>
                        <ul class="list-disc list-inside">
                            @foreach($question->options as $option)
                            <li class="{{ $option == $question->correct_answer ? 'text-green-600 font-bold' : '' }}">
                                {{ $option }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection