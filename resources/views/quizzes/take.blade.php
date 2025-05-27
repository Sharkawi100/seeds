@extends('layouts.app')
@if($quiz->questions->first()->passage)
<div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-6">
    @if($quiz->questions->first()->passage_title)
        <h3 class="text-xl font-bold text-blue-800 mb-3">{{ $quiz->questions->first()->passage_title }}</h3>
    @endif
    <div class="prose max-w-none text-gray-700">
        {!! nl2br(e($quiz->questions->first()->passage)) !!}
    </div>
</div>
@endif
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">{{ $quiz->title }}</h2>
                
                <form action="{{ route('quiz.submit', $quiz) }}" method="POST" id="quiz-form">
                    @csrf
                    
                    @foreach($quiz->questions as $index => $question)
                    <div class="question-block mb-6 p-4 border rounded">
                        <div class="flex justify-between mb-3">
                            <h4 class="font-bold">Question {{ $index + 1 }} of {{ $quiz->questions->count() }}</h4>
                            <span class="text-sm px-3 py-1 rounded" style="background-color: 
                                @if($question->root_type == 'jawhar') #ff6b6b 
                                @elseif($question->root_type == 'zihn') #4ecdc4
                                @elseif($question->root_type == 'waslat') #f7b731
                                @else #5f27cd 
                                @endif; color: white;">
                                {{ ucfirst($question->root_type) }}
                            </span>
                        </div>
                        
                        <p class="mb-4 text-lg">{{ $question->question }}</p>
                        
                        @foreach($question->options as $optionIndex => $option)
                        <label class="block mb-2 p-3 border rounded hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" required>
                            <span class="ml-2">{{ $option }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endforeach
                    
                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded font-bold">Submit Quiz</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection