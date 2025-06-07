@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">تعديل جميع الأسئلة - {{ $quiz->title }}</h1>
                <a href="{{ route('quizzes.questions.index', $quiz) }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <form action="{{ route('quizzes.questions.bulk-update', $quiz) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    @foreach($quiz->questions as $index => $question)
                    <div class="border rounded-lg p-4">
                        <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                        
                        <div class="grid grid-cols-12 gap-4 mb-4">
                            <div class="col-span-1 text-center pt-2">
                                <span class="font-bold text-gray-500">{{ $index + 1 }}</span>
                            </div>
                            
                            <div class="col-span-5">
                                <textarea name="questions[{{ $index }}][question]" 
                                          class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" 
                                          rows="2" required>{{ $question->question }}</textarea>
                            </div>
                            
                            <div class="col-span-2">
                                <select name="questions[{{ $index }}][root_type]" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="jawhar" {{ $question->root_type == 'jawhar' ? 'selected' : '' }}>🎯 جَوهر</option>
                                    <option value="zihn" {{ $question->root_type == 'zihn' ? 'selected' : '' }}>🧠 ذِهن</option>
                                    <option value="waslat" {{ $question->root_type == 'waslat' ? 'selected' : '' }}>🔗 وَصلات</option>
                                    <option value="roaya" {{ $question->root_type == 'roaya' ? 'selected' : '' }}>👁️ رُؤية</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <select name="questions[{{ $index }}][depth_level]" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="1" {{ $question->depth_level == 1 ? 'selected' : '' }}>مستوى 1</option>
                                    <option value="2" {{ $question->depth_level == 2 ? 'selected' : '' }}>مستوى 2</option>
                                    <option value="3" {{ $question->depth_level == 3 ? 'selected' : '' }}>مستوى 3</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <select name="questions[{{ $index }}][correct_answer]" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    @foreach($question->options as $option)
                                    <option value="{{ $option }}" {{ $question->correct_answer == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($question->options as $optIndex => $option)
                            <input type="text" 
                                   name="questions[{{ $index }}][options][]" 
                                   value="{{ $option }}"
                                   class="px-3 py-1 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                                   placeholder="خيار {{ $optIndex + 1 }}">
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        حفظ جميع التغييرات
                    </button>
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection