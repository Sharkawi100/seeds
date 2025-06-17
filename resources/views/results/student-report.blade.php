@extends('layouts.app')

@section('title', 'تقرير رحلة التعلم')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Report Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">تقرير رحلة التعلم والنمو</h1>
            <div class="flex justify-between text-sm text-gray-600">
                <span>{{ $result['quiz']['title'] }}</span>
                <span>{{ $result['created_at'] }}</span>
            </div>
        </div>

        <!-- Overall Performance -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">مستوى النمو الإجمالي</h2>
            <div class="text-center">
                <div class="text-5xl font-bold {{ $result['total_score'] >= 80 ? 'text-green-600' : ($result['total_score'] >= 60 ? 'text-blue-600' : 'text-purple-600') }}">
                    {{ $result['total_score'] }}%
                </div>
                <p class="text-gray-600 mt-2">
                    @if($result['total_score'] >= 80)
                        أداء متقن! استمر في إلهام الآخرين
                    @elseif($result['total_score'] >= 60)
                        أداء متقدم مع نمو مستمر رائع
                    @else
                        أنت في رحلة تعلم جميلة - كل خطوة تبني مهاراتك!
                    @endif
                </p>
            </div>
        </div>

        <!-- Root Analysis -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">خريطة نمو الجذور الأربعة</h2>
            <div class="space-y-4">
                @foreach(['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'] as $root => $name)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold text-gray-800">{{ $name }}</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold {{ $result['scores'][$root] >= 80 ? 'text-green-600' : ($result['scores'][$root] >= 60 ? 'text-blue-600' : 'text-purple-600') }}">
                                {{ $result['scores'][$root] }}%
                            </span>
                            @if($result['scores'][$root] >= 80)
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">متقن</span>
                            @elseif($result['scores'][$root] >= 60)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">متقدم</span>
                            @else
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">مبتدئ</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        @include('results.partials.root-feedback', ['root' => $root, 'score' => $result['scores'][$root]])
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Growth Journey Recommendations -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">🌱 خطوات النمو القادمة</h2>
            <div class="space-y-3 text-gray-700">
                @php
                $hasGrowthOpportunities = false;
                @endphp
                
                @foreach($result['scores'] as $root => $score)
                    @if($score < 80)
                        @php $hasGrowthOpportunities = true; @endphp
                        <div class="flex items-start gap-3 p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg">
                            <span class="text-blue-500 text-lg">🚀</span>
                            <div>
                                <span class="font-medium text-gray-800">
                                    @if($root == 'jawhar') جَوهر @elseif($root == 'zihn') ذِهن @elseif($root == 'waslat') وَصلات @else رُؤية @endif:
                                </span>
                                <span class="text-gray-600">
                                    @include('results.partials.root-tips', ['root' => $root])
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
                
                @if(!$hasGrowthOpportunities)
                    <div class="text-center py-6">
                        <span class="text-2xl">🎉</span>
                        <p class="text-gray-600 mt-2">رائع! لقد حققت مستوى متقدم في جميع الجذور. استمر في التحدي واستكشاف مستويات أعمق من التعلم!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Motivational Quote -->
        <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg shadow-sm p-6 mt-6 text-white text-center">
            <p class="text-lg font-medium italic">"كما تنمو الشجرة بجذورها الأربعة، تنمو معرفتك بالجذور الأربعة"</p>
            <p class="text-sm mt-2 opacity-90">تذكر: كل محاولة خطوة نحو الإتقان 🌟</p>
        </div>
    </div>
</div>
@endsection