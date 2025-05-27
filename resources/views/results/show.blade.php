@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Quiz Results: {{ $result->quiz->title }}</h2>
                
                <div class="mb-6">
                    <h3 class="text-xl font-bold">Overall Score: {{ $result->total_score }}%</h3>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    @foreach($result->scores as $root => $score)
                    <div class="text-center p-4 rounded" style="background-color: 
                        @if($root == 'jawhar') #ffebee 
                        @elseif($root == 'zihn') #e0f2f1
                        @elseif($root == 'waslat') #fff8e1
                        @else #ede7f6 
                        @endif;">
                        <h4 class="font-bold">{{ ucfirst($root) }}</h4>
                        <p class="text-2xl">{{ $score }}%</p>
                    </div>
                    @endforeach
                </div>

                <h3 class="text-xl font-bold mb-3">Your Answers</h3>
                @foreach($result->answers as $answer)
                <div class="mb-3 p-3 border rounded {{ $answer->is_correct ? 'bg-green-50' : 'bg-red-50' }}">
                    <p class="font-bold">{{ $answer->question->question }}</p>
                    <p>Your answer: {{ $answer->selected_answer }} 
                        @if($answer->is_correct) 
                            ✓
                        @else
                            ✗ (Correct: {{ $answer->question->correct_answer }})
                        @endif
                    </p>
                </div>
                @endforeach

                <a href="{{ route('quiz.take', $result->quiz) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Retake Quiz</a>
            </div>
        </div>
    </div>
</div>
@endsection