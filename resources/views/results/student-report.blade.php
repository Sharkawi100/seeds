@extends('layouts.app')

@section('title', 'تقرير الأداء')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Report Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">تقرير الأداء التفصيلي</h1>
            <div class="flex justify-between text-sm text-gray-600">
                <span>{{ $result->quiz->title }}</span>
                <span>{{ $result->created_at->format('Y/m/d') }}</span>
            </div>
        </div>

        <!-- Overall Performance -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">النتيجة الإجمالية</h2>
            <div class="text-center">
                <div class="text-5xl font-bold {{ $result->total_score >= 80 ? 'text-green-600' : ($result->total_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $result->total_score }}%
                </div>
                <p class="text-gray-600 mt-2">
                    @if($result->total_score >= 80)
                        أداء ممتاز! استمر على هذا المستوى
                    @elseif($result->total_score >= 60)
                        أداء جيد مع إمكانية التحسين
                    @else
                        تحتاج إلى مزيد من الممارسة والتركيز
                    @endif
                </p>
            </div>
        </div>

        <!-- Root Analysis -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">تحليل الجذور</h2>
            <div class="space-y-4">
                @foreach(['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'] as $root => $name)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold">{{ $name }}</h3>
                        <span class="text-lg font-bold {{ $result->scores[$root] >= 80 ? 'text-green-600' : ($result->scores[$root] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $result->scores[$root] }}%
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        @include('results.partials.root-feedback', ['root' => $root, 'score' => $result->scores[$root]])
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recommendations -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">توصيات للتحسين</h2>
            <ul class="space-y-2 text-gray-700">
                @foreach($result->scores as $root => $score)
                    @if($score < 60)
                        <li>• @include('results.partials.root-tips', ['root' => $root])</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection