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

                        @foreach($roots as $key => $root)
                        @php $score = $scores[$key] ?? 0; @endphp
                        <div class="text-center p-6 bg-gradient-to-br from-{{ $root['color'] }}-50 to-{{ $root['color'] }}-100 rounded-2xl border border-{{ $root['color'] }}-200 hover:shadow-xl transition-all duration-300 group">
                            <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">{{ $root['icon'] }}</div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $root['name'] }}</h4>
                            <div class="text-3xl font-black text-{{ $root['color'] }}-600 mb-3">{{ $score }}%</div>
                            
                            <!-- Root Progress Bar -->
                            <div class="w-full bg-white/60 rounded-full h-3 mb-3 shadow-inner">
                                <div class="h-3 bg-gradient-to-r from-{{ $root['color'] }}-400 to-{{ $root['color'] }}-600 rounded-full transition-all duration-1000" 
                                     style="width: {{ $score }}%"></div>
                            </div>
                            
                            <p class="text-sm text-{{ $root['color'] }}-700 font-medium mb-3">{{ $root['desc'] }}</p>
                            
                            <!-- Performance Level -->
                            <div class="mt-3">
                                @if($score >= 80)
                                    <span class="text-xs bg-green-200 text-green-800 px-3 py-1 rounded-full font-medium">ممتاز</span>
                                @elseif($score >= 60)
                                    <span class="text-xs bg-blue-200 text-blue-800 px-3 py-1 rounded-full font-medium">جيد</span>
                                @elseif($score >= 40)
                                    <span class="text-xs bg-orange-200 text-orange-800 px-3 py-1 rounded-full font-medium">مقبول</span>
                                @else
                                    <span class="text-xs bg-red-200 text-red-800 px-3 py-1 rounded-full font-medium">يحتاج تطوير</span>
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

                    <!-- Root Analysis Summary -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                        <h4 class="text-lg font-bold text-indigo-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-lightbulb"></i>
                            تحليل الأداء حسب الجذور
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                            $sortedRoots = collect($scores)->sortByDesc(function($score, $key) { return $score; });
                            $strongestRoot = $sortedRoots->keys()->first();
                            $weakestRoot = $sortedRoots->keys()->last();
                            @endphp

                            @if($scores[$strongestRoot] >= 70)
                            <div class="bg-white/60 rounded-lg p-4">
                                <h5 class="font-bold text-green-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-star"></i>
                                    نقطة قوتك: {{ $roots[$strongestRoot]['name'] }}
                                </h5>
                                <p class="text-sm text-gray-700">
                                    ممتاز! حصلت على {{ $scores[$strongestRoot] }}% في هذا الجذر.
                                </p>
                            </div>
                            @endif

                            @if($scores[$weakestRoot] < 60)
                            <div class="bg-white/60 rounded-lg p-4">
                                <h5 class="font-bold text-orange-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-target"></i>
                                    للتطوير: {{ $roots[$weakestRoot]['name'] }}
                                </h5>
                                <p class="text-sm text-gray-700">
                                    يحتاج تركيز أكبر - حصلت على {{ $scores[$weakestRoot] }}% في هذا الجذر.
                                </p>
                            </div>
                            @endif
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
                                <div class="p-6 bg-white/80 rounded-xl border-r-4 {{ $totalScore >= 80 ? 'border-green-500' : ($totalScore >= 60 ? 'border-yellow-500' : 'border-red-500') }} shadow-lg">
                                    <h4 class="font-bold text-gray-900 mb-3 text-lg">📊 تقييم الأداء العام</h4>
                                    <p class="leading-relaxed">
                                        @if($totalScore >= 80)
                                            🌟 أداء متميز يعكس إتقاناً عالياً للمادة. {{ $studentName }} يُظهر فهماً عميقاً وقدرة على تطبيق المفاهيم بكفاءة عالية. هذا المستوى يدل على استعداد ممتاز للتقدم إلى مراحل أكثر تعقيداً.
                                        @elseif($totalScore >= 60)
                                            ⭐ أداء جيد مع إمكانية التحسين. {{ $studentName }} لديه أساس قوي يحتاج إلى تعزيز في بعض الجوانب. مع التركيز على نقاط الضعف، يمكن تحقيق تحسن ملحوظ.
                                        @else
                                            💪 يحتاج إلى دعم إضافي. مراجعة المفاهيم الأساسية ضرورية لبناء فهم أقوى. لا تيأس، كل تحدي فرصة للتعلم والنمو!
                                        @endif
                                    </p>
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
                                            <span><strong>الخطوة التالية:</strong>
                                                @if($totalScore >= 80)
                                                    تحدى نفسك بمستويات أصعب وساعد زملاءك في التعلم
                                                @elseif($totalScore >= 60)
                                                    راجع الأسئلة الخاطئة بعناية وافهم أسباب الخطأ، ثم تدرب على أسئلة مشابهة
                                                @else
                                                    أعد دراسة النص الأساسي مع التركيز على المفاهيم الرئيسية، واطلب المساعدة عند الحاجة
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