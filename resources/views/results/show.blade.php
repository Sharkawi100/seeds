@extends('layouts.app')

@section('content')
@php
// Calculate attempt information
$isRegisteredUser = $result->user_id !== null;
$totalAttempts = 1;
$currentAttemptNumber = 1;
$finalScore = $result->total_score;
$allAttempts = collect();

if ($isRegisteredUser) {
    $allAttempts = \App\Models\Result::where('quiz_id', $result->quiz_id)
        ->where('user_id', $result->user_id)
        ->orderBy('attempt_number')
        ->get();
    $totalAttempts = $allAttempts->count();
    $currentAttemptNumber = $result->attempt_number ?? 1;
    $finalScore = \App\Models\Result::getFinalScore($result->quiz_id, $result->user_id) ?? $result->total_score;
} else {
    // For guests, get attempts by name
    $allAttempts = \App\Models\Result::where('quiz_id', $result->quiz_id)
        ->where('guest_name', $result->guest_name)
        ->orderBy('created_at')
        ->get();
    $totalAttempts = $allAttempts->count();
    $currentAttemptNumber = $allAttempts->search(function($item) use ($result) {
        return $item->id === $result->id;
    }) + 1;
}

$isLatestAttempt = $result->is_latest_attempt ?? true;
$showFinalScore = $totalAttempts > 1 && $finalScore != $result->total_score;
$studentName = $result->user ? $result->user->name : ($result->guest_name ?? 'طالب ضيف');
@endphp

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white/90 backdrop-blur-lg overflow-hidden shadow-2xl rounded-3xl border border-white/20">
            <!-- Enhanced Header -->
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-8">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <a href="{{ route('quizzes.show', $result->quiz) }}" 
                               class="p-2 bg-white/20 hover:bg-white/30 rounded-xl transition-colors">
                                <i class="fas fa-arrow-right text-white"></i>
                            </a>
                            <div>
                                <h2 class="text-3xl md:text-4xl font-black text-white mb-2">
                                    📊 نتائج الاختبار
                                </h2>
                                <h3 class="text-xl md:text-2xl text-white/90 font-medium">
                                    {{ $result->quiz->title }}
                                </h3>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-6 text-white/80 text-sm flex-wrap">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user"></i>
                                <span>{{ $studentName }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-book"></i>
                                <span>{{ $result->quiz->subject->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-layer-group"></i>
                                <span>الصف {{ $result->quiz->grade_level }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $result->created_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Attempt Information -->
                    @if($totalAttempts > 1 || $isRegisteredUser)
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 text-center min-w-32">
                        <div class="text-2xl font-bold text-white mb-1">{{ $currentAttemptNumber }}</div>
                        <div class="text-sm text-white/80">من {{ $totalAttempts }}</div>
                        <div class="text-xs text-white/70 mt-1">
                            @if($isLatestAttempt)
                                آخر محاولة
                            @else
                                محاولة قديمة
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-8">
                <!-- Score Dashboard -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Overall Score Card -->
                    <div class="lg:col-span-2">
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg border border-gray-200 p-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">النتيجة الإجمالية</h3>
                            
                            <!-- Current Score -->
                            <div class="text-center mb-6">
                                <div class="mb-4">
                                    <div class="text-sm text-gray-600 mb-2">
                                        @if($totalAttempts > 1)
                                            درجة هذه المحاولة ({{ $currentAttemptNumber }})
                                        @else
                                            النتيجة النهائية
                                        @endif
                                    </div>
                                    <div class="text-6xl font-black mb-4 {{ $result->total_score >= 80 ? 'text-green-600' : ($result->total_score >= 60 ? 'text-blue-600' : ($result->total_score >= 40 ? 'text-orange-600' : 'text-red-600')) }}">
                                        {{ $result->total_score }}%
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="w-full max-w-md mx-auto">
                                        <div class="w-full bg-gray-200 rounded-full h-4 shadow-inner">
                                            <div class="h-4 rounded-full bg-gradient-to-r {{ $result->total_score >= 80 ? 'from-green-400 to-green-600' : ($result->total_score >= 60 ? 'from-blue-400 to-blue-600' : ($result->total_score >= 40 ? 'from-orange-400 to-orange-600' : 'from-red-400 to-red-600')) }} transition-all duration-1000 shadow-sm" 
                                                 style="width: {{ $result->total_score }}%"></div>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-2">{{ $result->total_score }}/100</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Final Score (if different) -->
                            @if($showFinalScore)
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6 border border-indigo-200">
                                <div class="text-center">
                                    <div class="text-sm text-indigo-700 mb-2 font-medium">
                                        🎯 الدرجة النهائية 
                                        @switch($result->quiz->scoring_method ?? 'latest')
                                            @case('average') (متوسط جميع المحاولات) @break
                                            @case('highest') (أعلى درجة حققتها) @break
                                            @case('latest') (آخر محاولة) @break
                                            @case('first_only') (المحاولة الأولى فقط) @break
                                        @endswitch
                                    </div>
                                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $finalScore }}%</div>
                                    <div class="text-xs text-indigo-600">هذه هي درجتك الرسمية</div>
                                </div>
                            </div>
                            @endif

                            <!-- Performance Level -->
                            <div class="text-center">
                                @if($result->total_score >= 80)
                                    <div class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-6 py-3 rounded-full font-medium text-lg">
                                        <i class="fas fa-trophy"></i>
                                        أداء ممتاز
                                    </div>
                                @elseif($result->total_score >= 60)
                                    <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-6 py-3 rounded-full font-medium text-lg">
                                        <i class="fas fa-thumbs-up"></i>
                                        أداء جيد
                                    </div>
                                @elseif($result->total_score >= 40)
                                    <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-6 py-3 rounded-full font-medium text-lg">
                                        <i class="fas fa-hand-paper"></i>
                                        أداء مقبول
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 bg-red-100 text-red-800 px-6 py-3 rounded-full font-medium text-lg">
                                        <i class="fas fa-redo"></i>
                                        يحتاج تحسين
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Attempt History -->
                    @if($totalAttempts > 1)
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-history text-indigo-600"></i>
                            سجل المحاولات
                        </h4>
                        
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @foreach($allAttempts as $attempt)
                            <div class="flex items-center justify-between p-3 rounded-lg {{ $attempt->id === $result->id ? 'bg-indigo-50 border-2 border-indigo-200' : 'bg-gray-50' }} transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 {{ $attempt->id === $result->id ? 'bg-indigo-600' : 'bg-gray-400' }} text-white rounded-full flex items-center justify-center text-sm font-bold">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-800">
                                            المحاولة {{ $loop->iteration }}
                                            @if($attempt->id === $result->id)
                                                <span class="text-indigo-600">(الحالية)</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $attempt->created_at->format('m/d H:i') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-lg font-bold {{ $attempt->total_score >= 70 ? 'text-green-600' : ($attempt->total_score >= 50 ? 'text-orange-600' : 'text-red-600') }}">
                                        {{ $attempt->total_score }}%
                                    </div>
                                    @if($attempt->is_latest_attempt ?? false)
                                        <div class="text-xs text-blue-600 font-medium">آخر محاولة</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Enhanced 4-Roots Analysis -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center flex items-center justify-center gap-2">
                        <i class="fas fa-seedling text-green-600"></i>
                        تحليل الجُذور الأربعة
                    </h3>
                    
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        @php
                        $roots = [
                            'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'red', 'desc' => 'فهم الماهية والجوهر'],
                            'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'cyan', 'desc' => 'التفكير والتحليل'], 
                            'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'yellow', 'desc' => 'الربط والتكامل'],
                            'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'purple', 'desc' => 'التطبيق والإبداع']
                        ];
                        $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true);
                        @endphp

@php
// Sort roots by score (strongest first)
$sortedRootsByScore = collect($roots)->sortByDesc(function($root, $key) use ($scores) {
    return $scores[$key] ?? 0;
});
@endphp

@foreach($sortedRootsByScore as $key => $root)
@php $score = $scores[$key] ?? 0; @endphp
<div class="text-center p-6 bg-gradient-to-br from-{{ $root['color'] }}-50 to-{{ $root['color'] }}-100 rounded-2xl border border-{{ $root['color'] }}-200 hover:shadow-xl transition-all duration-300 group {{ $loop->first ? 'ring-4 ring-yellow-300 ring-opacity-50' : '' }}">
    @if($loop->first && $score > 0)
        <div class="absolute -top-2 -right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full shadow-lg">
            ⭐ نقطة قوتك
        </div>
    @endif
    
    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">{{ $root['icon'] }}</div>
    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $root['name'] }}</h4>
    <div class="text-3xl font-black text-{{ $root['color'] }}-600 mb-3">{{ $score }}%</div>
    
    <!-- Root Progress Bar -->
    <div class="w-full bg-white/60 rounded-full h-3 mb-3 shadow-inner">
        <div class="h-3 bg-gradient-to-r from-{{ $root['color'] }}-400 to-{{ $root['color'] }}-600 rounded-full transition-all duration-1000" 
             style="width: {{ $score }}%"></div>
    </div>
    
    <p class="text-sm text-{{ $root['color'] }}-700 font-medium mb-3">{{ $root['desc'] }}</p>
    
    <!-- Performance Level with Strength-Based Language -->
    <div class="mt-3">
        @if($loop->first && $score >= 60)
            <span class="text-xs bg-gradient-to-r from-yellow-200 to-yellow-300 text-yellow-900 px-3 py-1 rounded-full font-bold">✨ جذرك المتميز</span>
        @elseif($score >= 80)
            <span class="text-xs bg-green-200 text-green-800 px-3 py-1 rounded-full font-medium">ممتاز</span>
        @elseif($score >= 60)
            <span class="text-xs bg-blue-200 text-blue-800 px-3 py-1 rounded-full font-medium">جيد</span>
        @elseif($score >= 40)
            <span class="text-xs bg-orange-200 text-orange-800 px-3 py-1 rounded-full font-medium">ينمو</span>
        @else
            <span class="text-xs bg-purple-200 text-purple-800 px-3 py-1 rounded-full font-medium">في طور التطوير</span>
        @endif
    </div>
</div>
@endforeach
                    </div>

                    <!-- Juzoor Chart -->
                    <div class="flex justify-center mb-6">
                        <div class="bg-white/80 rounded-2xl p-6 shadow-lg border border-gray-200">
                            <x-juzoor-chart :scores="$result->scores" size="large" />
                        </div>
                    </div>

                    <!-- Strength-Based Root Analysis -->
<div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
    <h4 class="text-lg font-bold text-indigo-800 mb-4 flex items-center gap-2">
        <i class="fas fa-seedling"></i>
        خريطة قوتك في الجذور
    </h4>
    
    @php
    $sortedRoots = collect($scores)->sortByDesc(function($score, $key) { return $score; });
    $strongestRoot = $sortedRoots->keys()->first();
    $secondStrongest = $sortedRoots->keys()->skip(1)->first();
    $growthRoot = $sortedRoots->keys()->last();
    $strongestScore = $scores[$strongestRoot] ?? 0;
    $growthScore = $scores[$growthRoot] ?? 0;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Always Show Strength First -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border-2 border-green-200">
            <h5 class="font-bold text-green-700 mb-2 flex items-center gap-2">
                <i class="fas fa-crown text-yellow-500"></i>
                جذرك المتميز: {{ $roots[$strongestRoot]['name'] }}
            </h5>
            <p class="text-sm text-gray-700 leading-relaxed">
                @if($strongestScore >= 80)
                    🌟 متفوق! حققت {{ $strongestScore }}% في هذا الجذر. هذا يُظهر إتقاناً رائعاً يمكن الاعتماد عليه لدعم نمو الجذور الأخرى.
                @elseif($strongestScore >= 60)
                    ⭐ قوي! حققت {{ $strongestScore }}% في هذا الجذر. لديك أساس متين يمكن بناء المزيد عليه.
                @elseif($strongestScore >= 40)
                    💪 واعد! حققت {{ $strongestScore }}% في هذا الجذر. هذا يُظهر إمكانيات جيدة تحتاج لمزيد من التنمية.
                @else
                    🌱 بداية! كل خبير كان مبتدئاً يوماً ما. جذر {{ $roots[$strongestRoot]['name'] }} يُظهر بوادر نمو إيجابية.
                @endif
            </p>
        </div>

        <!-- Growth Opportunity (Not Weakness) -->
        @if($growthRoot !== $strongestRoot)
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-4 border-2 border-blue-200">
            <h5 class="font-bold text-blue-700 mb-2 flex items-center gap-2">
                <i class="fas fa-rocket"></i>
                فرصتك للنمو: {{ $roots[$growthRoot]['name'] }}
            </h5>
            <p class="text-sm text-gray-700 leading-relaxed">
                @if($growthScore >= 40)
                    🚀 في المسار الصحيح! مع التركيز على هذا الجذر، ستحقق قفزة نوعية في أدائك العام.
                @else
                    🌱 منطقة النمو الذهبية! هنا تكمن فرصتك الأكبر للتطوير. كل تحسن صغير سيحدث فرقاً كبيراً.
                @endif
            </p>
        </div>
        @endif
    </div>

    <!-- Personal Learning Path -->
    <div class="mt-4 p-4 bg-white/60 rounded-lg border border-purple-200">
        <h6 class="font-bold text-purple-700 mb-2 flex items-center gap-2">
            <i class="fas fa-route"></i>
            مسارك التعليمي الشخصي
        </h6>
        <p class="text-sm text-gray-700">
            استفد من قوتك في <strong>{{ $roots[$strongestRoot]['name'] }}</strong> لدعم نمو <strong>{{ $roots[$growthRoot]['name'] }}</strong>. 
            عندما تربط بين الجذور، يصبح التعلم أقوى وأعمق.
        </p>
    </div>
</div>
                </div>

                <!-- Enhanced Smart Report Section -->
                <div class="mb-8">
                    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <button onclick="toggleReport()" class="w-full px-8 py-6 bg-gradient-to-r from-purple-50 to-indigo-50 hover:from-purple-100 hover:to-indigo-100 transition-colors flex items-center justify-between text-left border-b border-purple-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-brain text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">🤖 التقرير الذكي</h3>
                                    <p class="text-sm text-gray-600">تحليل شخصي مفصل لـ {{ $studentName }}</p>
                                </div>
                            </div>
                            <svg id="report-arrow" class="w-6 h-6 text-purple-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div id="smart-report" class="hidden px-8 py-8 bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50">
                            @php
                                $totalScore = $result->total_score;
                                $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true);
                                $rootNames = ['jawhar' => 'الجَوهر', 'zihn' => 'الذِهن', 'waslat' => 'الوَصلات', 'roaya' => 'الرُؤية'];
                                $bestRoot = array_keys($scores, max($scores))[0];
                                $worstRoot = array_keys($scores, min($scores))[0];
                            @endphp
                            
                            <!-- Student Info Card -->
                            <div class="mb-6 p-6 bg-white/80 rounded-xl border border-white/50 shadow-lg">
                                <div class="grid md:grid-cols-3 gap-6 text-center">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">الطالب</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $studentName }}</p>
                                        @if($totalAttempts > 1)
                                            <p class="text-xs text-gray-500 mt-1">المحاولة {{ $currentAttemptNumber }} من {{ $totalAttempts }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">الاختبار</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $result->quiz->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $result->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">النتيجة</p>
                                        <p class="text-4xl font-bold {{ $totalScore >= 80 ? 'text-green-600' : ($totalScore >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $totalScore }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-6 text-gray-700">
                                <!-- Overall Performance -->
                                <!-- Strength-Based Detailed Roots Analysis -->
<div class="p-6 bg-white/80 rounded-xl shadow-lg">
    <h4 class="font-bold text-gray-900 mb-4 text-lg">🌱 خريطة جذورك التعليمية</h4>
    
    @php
    // Sort roots by score for strength-first display
    $sortedDetailedRoots = collect($scores)->sortByDesc(function($score) { return $score; });
    $rootIcons = ['jawhar' => '🎯', 'zihn' => '🧠', 'waslat' => '🔗', 'roaya' => '👁️'];
    $rootNames = ['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'];
    @endphp
    
    <div class="space-y-4">
        @foreach($sortedDetailedRoots as $root => $score)
        <div class="relative overflow-hidden rounded-xl border-2 transition-all duration-300 hover:shadow-lg
            {{ $loop->first ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-300' : 
               ($score >= 60 ? 'bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-300' : 
                'bg-gradient-to-r from-purple-50 to-pink-50 border-purple-300') }}">
            
            @if($loop->first)
                <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">
                    ⭐ الأقوى
                </div>
            @endif
            
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">{{ $rootIcons[$root] }}</span>
                        <div>
                            <h5 class="font-bold text-lg text-gray-800">{{ $rootNames[$root] }}</h5>
                            <p class="text-sm text-gray-600">
                                @if($loop->first)
                                    🌟 جذرك المميز
                                @elseif($score >= 60)
                                    💪 جذر قوي
                                @elseif($score >= 40)
                                    🌱 جذر نامي
                                @else
                                    🔥 جذر الإمكانيات المخفية
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold 
                            {{ $loop->first ? 'text-green-700' : 
                               ($score >= 60 ? 'text-blue-700' : 'text-purple-700') }}">
                            {{ $score }}%
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-white/60 rounded-full h-3 mb-3 shadow-inner">
                    <div class="h-3 rounded-full transition-all duration-1000
                        {{ $loop->first ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 
                           ($score >= 60 ? 'bg-gradient-to-r from-blue-400 to-indigo-500' : 
                            'bg-gradient-to-r from-purple-400 to-pink-500') }}" 
                         style="width: {{ $score }}%"></div>
                </div>
                
                <!-- Growth-Oriented Message -->
                <p class="text-sm leading-relaxed
                    {{ $loop->first ? 'text-green-800' : 
                       ($score >= 60 ? 'text-blue-800' : 'text-purple-800') }}">
                    @if($loop->first)
                        🎯 <strong>منطقة تفوقك!</strong> هذا الجذر يُظهر قدراتك الاستثنائية. استخدم هذه القوة كجسر للنمو في الجذور الأخرى.
                    @elseif($score >= 80)
                        ⚡ <strong>أداء متميز!</strong> تُظهر إتقاناً رائعاً في هذا الجذر. يمكنك الاعتماد عليه كنقطة قوة.
                    @elseif($score >= 60)
                        💫 <strong>أساس متين!</strong> لديك فهم جيد يحتاج لمزيد من الصقل والممارسة لتحقيق التميز.
                    @elseif($score >= 40)
                        🚀 <strong>في رحلة النمو!</strong> تُظهر تقدماً واضحاً. مع التركيز والممارسة، ستحقق قفزات مذهلة.
                    @else
                        💎 <strong>كنز مدفون!</strong> هنا تكمن إمكانياتك الأعظم. كل تحسن صغير سيحدث فرقاً كبيراً في رحلتك التعليمية.
                    @endif
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
                                
                                <!-- Roots Analysis -->
                                <div class="p-6 bg-white/80 rounded-xl shadow-lg">
                                    <h4 class="font-bold text-gray-900 mb-4 text-lg">🌱 تحليل مفصل للجذور الأربعة</h4>
                                    <div class="grid md:grid-cols-2 gap-4">
                                        @foreach($scores as $root => $score)
                                        <div class="flex items-center justify-between p-4 rounded-lg {{ $score >= 80 ? 'bg-green-50 border border-green-200' : ($score >= 60 ? 'bg-yellow-50 border border-yellow-200' : 'bg-red-50 border border-red-200') }}">
                                            <div class="flex items-center gap-3">
                                                <span class="text-2xl">
                                                    {{ ['jawhar' => '🎯', 'zihn' => '🧠', 'waslat' => '🔗', 'roaya' => '👁️'][$root] }}
                                                </span>
                                                <span class="font-medium">{{ $rootNames[$root] }}</span>
                                            </div>
                                            <span class="text-xl font-bold {{ $score >= 80 ? 'text-green-700' : ($score >= 60 ? 'text-yellow-700' : 'text-red-700') }}">
                                                {{ $score }}%
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Progress Analysis (if multiple attempts) -->
                                @if($totalAttempts > 1)
                                <div class="p-6 bg-white/80 rounded-xl shadow-lg">
                                    <h4 class="font-bold text-gray-900 mb-3 text-lg">📈 تحليل التقدم</h4>
                                    @php
                                    $improvement = $allAttempts->last()->total_score - $allAttempts->first()->total_score;
                                    @endphp
                                    <p class="leading-relaxed">
                                        @if($improvement > 0)
                                            📊 تحسن ملحوظ! ارتفعت درجتك من {{ $allAttempts->first()->total_score }}% إلى {{ $allAttempts->last()->total_score }}% 
                                            بتحسن قدره {{ $improvement }}%. هذا يدل على التزامك بالتعلم والتطوير.
                                        @elseif($improvement < 0)
                                            📉 انخفاض في الأداء من {{ $allAttempts->first()->total_score }}% إلى {{ $allAttempts->last()->total_score }}%. 
                                            لا تقلق، هذا أمر طبيعي في رحلة التعلم. راجع المفاهيم وحاول مرة أخرى.
                                        @else
                                            📊 أداء ثابت على {{ $allAttempts->first()->total_score }}%. للتحسن، ركز على فهم الأخطاء في المحاولات السابقة.
                                        @endif
                                    </p>
                                </div>
                                @endif
                                
                                <!-- Recommendations -->
                                <div class="p-6 bg-white/80 rounded-xl shadow-lg">
                                    <h4 class="font-bold text-gray-900 mb-4 text-lg">💡 توصيات مخصصة للتحسين</h4>
                                    <ul class="space-y-3">
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-600 text-xl">✓</span>
                                            <span><strong>نقطة القوة:</strong> {{ $rootNames[$bestRoot] }} - استمر في تطوير هذه المهارة واستخدمها لدعم الجذور الأخرى</span>
                                        </li>
                                        @if($scores[$worstRoot] < 60)
                                        <li class="flex items-start gap-3">
                                            <span class="text-red-600 text-xl">⚠</span>
                                            <span><strong>يحتاج تحسين:</strong> {{ $rootNames[$worstRoot] }} - خصص وقتاً إضافياً لفهم وممارسة مهارات هذا الجذر</span>
                                        </li>
                                        @endif
                                        <li class="flex items-start gap-3">
                                            <span class="text-blue-600 text-xl">🎯</span>
                                            <span><strong>رحلة النمو التالية:</strong>
                                                @if($totalScore >= 80)
                                                    تحدى نفسك بمستويات أصعب وساعد زملاءك في التعلم - أنت في طريقك للإتقان!
                                                @elseif($totalScore >= 60)
                                                    راجع الأسئلة التي تحتاج لمزيد من التفكير وتعلم من التجربة، ثم تدرب على أسئلة مشابهة - أنت تنمو!
                                                @else
                                                    اكتشف النص الأساسي مرة أخرى مع التركيز على المفاهيم الرئيسية، واطلب الدعم لتسريع نموك - كل خطوة تقربك من الهدف!
                                                @endif
                                            </span>
                                        </li>
                                        @if($totalAttempts > 1 && $currentAttemptNumber < 3)
                                        <li class="flex items-start gap-3">
                                            <span class="text-purple-600 text-xl">🔄</span>
                                            <span><strong>للممارسة:</strong> حاول الاختبار مرة أخرى بعد مراجعة نقاط الضعف - التكرار يقوي الفهم</span>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- مراجعة تفصيلية للأسئلة -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">مراجعة تفصيلية للأسئلة</h3>
                    <div class="space-y-3">
                        @foreach($result->answers as $index => $answer)
                        <div class="p-4 border-2 rounded-lg {{ $answer->is_correct ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }}">
                            <div class="flex items-start gap-3">
                                <span class="text-lg font-bold text-gray-500">{{ $index + 1 }}</span>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 mb-2">{!! $answer->question->question !!}</p>
                                    <div class="flex flex-wrap gap-2 text-sm">
                                        <span class="px-2 py-1 rounded-full {{ 
                                            $answer->question->root_type == 'jawhar' ? 'bg-red-100' : 
                                            ($answer->question->root_type == 'zihn' ? 'bg-cyan-100' : 
                                            ($answer->question->root_type == 'waslat' ? 'bg-yellow-100' : 'bg-purple-100')) 
                                        }}">
                                            {{ ['jawhar' => '🎯 جَوهر', 'zihn' => '🧠 ذِهن', 'waslat' => '🔗 وَصلات', 'roaya' => '👁️ رُؤية'][$answer->question->root_type] }}
                                        </span>
                                        <span class="px-2 py-1 rounded-full bg-gray-100">
                                            مستوى {{ $answer->question->depth_level }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-gray-700">
                                            إجابتك: <strong>{{ $answer->selected_answer }}</strong>
                                            @if($answer->is_correct)
                                                <span class="text-green-600 font-bold mr-2">✓ صحيح</span>
                                            @else
                                                <span class="text-red-600 font-bold mr-2">✗ خطأ</span>
                                                <br>
                                                <span class="text-gray-600">الإجابة الصحيحة: <strong>{{ $answer->question->correct_answer }}</strong></span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-center flex-wrap">
                    <a href="{{ route('quiz.take', $result->quiz) }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-xl font-bold transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <i class="fas fa-redo"></i>
                        إعادة الاختبار
                    </a>
                    
                    @if(Auth::check() && (Auth::user()->is_admin || Auth::user()->user_type === 'teacher'))
                    <a href="{{ route('quizzes.index') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-medium transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <i class="fas fa-list"></i>
                        عرض جميع الاختبارات
                    </a>
                    @endif
                    
                    @if($result->quiz->user_id === auth()->id())
                    <a href="{{ route('results.quiz', $result->quiz) }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-8 py-4 rounded-xl font-medium transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <i class="fas fa-chart-bar"></i>
                        جميع نتائج الاختبار
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleReport() {
    const report = document.getElementById('smart-report');
    const arrow = document.getElementById('report-arrow');
    
    if (report.classList.contains('hidden')) {
        report.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        report.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Add smooth animations on page load
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bg-gradient-to-br, .bg-gradient-to-r');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection