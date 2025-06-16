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
    $currentAttemptNumber = $result->attempt_number;
    $finalScore = \App\Models\Result::getFinalScore($result->quiz_id, $result->user_id);
}

function analyzePerformance(scores, totalScore, attemptNumber, totalAttempts) {
    const roots = {
        'jawhar': 'جَوهر',
        'zihn': 'ذِهن', 
        'waslat': 'وَصلات',
        'roaya': 'رُؤية'
    };

    // Find strongest and weakest areas
    let strongest = null;
    let weakest = null;
    let maxScore = -1;
    let minScore = 101;

    for (const [key, score] of Object.entries(scores)) {
        if (score > maxScore) {
            maxScore = score;
            strongest = key;
        }
        if (score < minScore) {
            minScore = score;
            weakest = key;
        }
    }

    let analysis = '<div class="space-y-4">';

    // Overall Performance Assessment
    analysis += '<div class="mb-4">';
    analysis += '<h5 class="font-bold text-purple-700 mb-2">📊 تقييم الأداء العام</h5>';
    
    if (totalScore >= 90) {
        analysis += '<p class="text-gray-700">🌟 أداء متميز جداً! لقد حققت نتيجة ممتازة تدل على فهم عميق وشامل للمادة.</p>';
    } else if (totalScore >= 80) {
        analysis += '<p class="text-gray-700">⭐ أداء متميز! أظهرت مستوى جيد جداً من الفهم مع وجود مجال للتطوير.</p>';
    } else if (totalScore >= 70) {
        analysis += '<p class="text-gray-700">👍 أداء جيد! لديك أساس قوي مع إمكانية للتحسن في بعض المجالات.</p>';
    } else if (totalScore >= 60) {
        analysis += '<p class="text-gray-700">📈 أداء مقبول. هناك حاجة لمزيد من التركيز والممارسة لتطوير مهاراتك.</p>';
    } else {
        analysis += '<p class="text-gray-700">💪 تحتاج إلى مزيد من الدراسة والممارسة. لا تستسلم، كل تحدي فرصة للتعلم!</p>';
    }
    analysis += '</div>';

    // Strengths Analysis
    if (maxScore >= 70) {
        analysis += '<div class="mb-4">';
        analysis += '<h5 class="font-bold text-green-700 mb-2">✨ نقاط القوة</h5>';
        analysis += `<p class="text-gray-700">تتميز في جذر <strong>${roots[strongest]}</strong> بنسبة ${maxScore}%. `;
        
        if (strongest === 'jawhar') {
            analysis += 'هذا يدل على قدرتك الممتازة على فهم المفاهيم الأساسية وجوهر المواضيع.';
        } else if (strongest === 'zihn') {
            analysis += 'هذا يظهر قوة في التفكير التحليلي والنقدي وحل المشكلات.';
        } else if (strongest === 'waslat') {
            analysis += 'هذا يعكس مهارتك في ربط المفاهيم وإيجاد العلاقات بين الأفكار المختلفة.';
        } else if (strongest === 'roaya') {
            analysis += 'هذا يبرز قدرتك على التطبيق العملي والتفكير الإبداعي.';
        }
        analysis += '</p></div>';
    }

    // Areas for Improvement
    if (minScore < 70) {
        analysis += '<div class="mb-4">';
        analysis += '<h5 class="font-bold text-orange-700 mb-2">🎯 مجالات التطوير</h5>';
        analysis += `<p class="text-gray-700">يحتاج جذر <strong>${roots[weakest]}</strong> إلى تركيز أكبر (${minScore}%). `;
        
        if (weakest === 'jawhar') {
            analysis += 'ننصح بمراجعة المفاهيم الأساسية والتعريفات الأساسية للمادة.';
        } else if (weakest === 'zihn') {
            analysis += 'اعمل على تطوير مهارات التحليل والتفكير النقدي من خلال حل المزيد من المسائل.';
        } else if (weakest === 'waslat') {
            analysis += 'ركز على فهم العلاقات بين المفاهيم وكيفية ترابط الأفكار المختلفة.';
        } else if (weakest === 'roaya') {
            analysis += 'تدرب على التطبيق العملي للمفاهيم وإيجاد حلول إبداعية للمشكلات.';
        }
        analysis += '</p></div>';
    }

    // Multiple Attempts Analysis
    if (totalAttempts > 1) {
        analysis += '<div class="mb-4">';
        analysis += '<h5 class="font-bold text-blue-700 mb-2">🔄 تحليل المحاولات</h5>';
        if (attemptNumber > 1) {
            analysis += `<p class="text-gray-700">هذه محاولتك رقم ${attemptNumber} من أصل ${totalAttempts}. `;
            analysis += 'الممارسة المتكررة تساعد على تحسين الفهم وترسيخ المعلومات.';
        } else {
            analysis += '<p class="text-gray-700">هذه محاولتك الأولى. يمكنك المحاولة مرة أخرى لتحسين نتيجتك.';
        }
        analysis += '</p></div>';
    }

    // Recommendations
    analysis += '<div class="mb-4">';
    analysis += '<h5 class="font-bold text-indigo-700 mb-2">💡 توصيات للتحسين</h5>';
    analysis += '<ul class="text-gray-700 space-y-1 mr-4">';
    
    if (totalScore < 80) {
        analysis += '<li>• راجع الأسئلة الخاطئة وافهم الأخطاء</li>';
        analysis += '<li>• ركز على الجذر الأضعف في دراستك القادمة</li>';
    }
    
    analysis += '<li>• استخدم تقنيات متنوعة للدراسة (مرئية، سمعية، عملية)</li>';
    analysis += '<li>• اطلب المساعدة من المعلم في النقاط الصعبة</li>';
    
    if (totalAttempts > 1 && attemptNumber < totalAttempts) {
        analysis += '<li>• حاول مرة أخرى بعد المراجعة لتحسين نتيجتك</li>';
    }
    
    analysis += '</ul></div>';

    analysis += '</div>';
    return analysis; else {
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

$isLatestAttempt = $result->is_latest_attempt;
$showFinalScore = $totalAttempts > 1 && $finalScore != $result->total_score;
@endphp

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Enhanced Header -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-8">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <a href="{{ url()->previous() }}" 
                               class="p-2 bg-white/20 hover:bg-white/30 rounded-xl transition-colors">
                                <i class="fas fa-arrow-right text-white"></i>
                            </a>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-black text-white mb-2">
                                    📊 نتائج الاختبار
                                </h1>
                                <h2 class="text-xl md:text-2xl text-white/90 font-medium">
                                    {{ $result->quiz->title }}
                                </h2>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-6 text-white/80 text-sm flex-wrap">
                            @if($result->guest_name)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $result->guest_name }}</span>
                                </div>
                            @endif
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
        </div>

        <!-- Score Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Main Score Card -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">النتائج</h3>
                    
                    <!-- Current Attempt Score -->
                    <div class="text-center mb-6">
                        <div class="mb-4">
                            <div class="text-sm text-gray-600 mb-2">
                                @if($totalAttempts > 1)
                                    درجة هذه المحاولة ({{ $currentAttemptNumber }})
                                @else
                                    النتيجة الإجمالية
                                @endif
                            </div>
                            <div class="text-6xl font-black mb-3 {{ $result->total_score >= 90 ? 'text-green-600' : ($result->total_score >= 70 ? 'text-blue-600' : ($result->total_score >= 50 ? 'text-orange-600' : 'text-red-600')) }}">
                                {{ $result->total_score }}%
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full max-w-md mx-auto">
                                <div class="w-full bg-gray-200 rounded-full h-4 shadow-inner">
                                    <div class="h-4 rounded-full bg-gradient-to-r {{ $result->total_score >= 90 ? 'from-green-400 to-green-600' : ($result->total_score >= 70 ? 'from-blue-400 to-blue-600' : ($result->total_score >= 50 ? 'from-orange-400 to-orange-600' : 'from-red-400 to-red-600')) }} transition-all duration-1000 shadow-sm" 
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
                                @switch($result->quiz->scoring_method)
                                    @case('average')
                                        (متوسط جميع المحاولات)
                                        @break
                                    @case('highest') 
                                        (أعلى درجة حققتها)
                                        @break
                                    @case('latest')
                                        (آخر محاولة)
                                        @break
                                    @case('first_only')
                                        (المحاولة الأولى فقط)
                                        @break
                                @endswitch
                            </div>
                            <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $finalScore }}%</div>
                            <div class="text-xs text-indigo-600">هذه هي درجتك الرسمية</div>
                        </div>
                    </div>
                    @endif

                    <!-- Performance Level -->
                    <div class="text-center">
                        @if($result->total_score >= 90)
                            <div class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full font-medium">
                                <i class="fas fa-trophy"></i>
                                أداء ممتاز
                            </div>
                        @elseif($result->total_score >= 70)
                            <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium">
                                <i class="fas fa-thumbs-up"></i>
                                أداء جيد
                            </div>
                        @elseif($result->total_score >= 50)
                            <div class="inline-flex items-center gap-2 bg-orange-100 text-orange-800 px-4 py-2 rounded-full font-medium">
                                <i class="fas fa-hand-paper"></i>
                                أداء مقبول
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 bg-red-100 text-red-800 px-4 py-2 rounded-full font-medium">
                                <i class="fas fa-redo"></i>
                                يحتاج تحسين
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Attempt History -->
            @if($totalAttempts > 1)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
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
                            @if($attempt->is_latest_attempt)
                                <div class="text-xs text-blue-600 font-medium">آخر محاولة</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Quiz Settings Info -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-600">
                        @if($result->quiz->max_attempts)
                            <div>🔢 الحد الأقصى: {{ $result->quiz->max_attempts }} محاولات</div>
                        @else
                            <div>♾️ محاولات غير محدودة</div>
                        @endif
                        
                        <div class="mt-1">
                            📊 طريقة الاحتساب: 
                            @switch($result->quiz->scoring_method)
                                @case('average') متوسط المحاولات @break
                                @case('highest') أعلى درجة @break
                                @case('latest') آخر محاولة @break
                                @case('first_only') المحاولة الأولى @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Smart AI Report Section -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 overflow-hidden mb-8">
            <button onclick="toggleSmartReport()" class="w-full px-8 py-6 bg-gradient-to-r from-purple-50 to-indigo-50 hover:from-purple-100 hover:to-indigo-100 transition-colors flex items-center justify-between text-left border-b border-purple-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-robot text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">🤖 التقرير الذكي</h3>
                        <p class="text-sm text-gray-600">تحليل شخصي مدعوم بالذكاء الاصطناعي</p>
                    </div>
                </div>
                <svg id="smart-report-arrow" class="w-6 h-6 text-purple-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <div id="smart-report-content" class="hidden">
                <div class="p-8 bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50">
                    <!-- Static Report Content -->
                    <div id="report-analysis">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- AI Analysis Text -->
                            <div class="lg:col-span-2">
                                <div class="bg-white/80 rounded-xl p-6 shadow-sm border border-white/50">
                                    <h4 class="text-lg font-bold text-purple-800 mb-4 flex items-center gap-2">
                                        <i class="fas fa-brain"></i>
                                        تحليل الأداء الشخصي
                                    </h4>
                                    <div id="ai-analysis-text" class="prose prose-purple max-w-none text-gray-700 leading-relaxed">
                                        <!-- AI generated content will be inserted here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Insights -->
                            <div class="space-y-4">
                                <!-- Strength Area -->
                                <div class="bg-white/80 rounded-xl p-4 shadow-sm border border-white/50">
                                    <h5 class="font-bold text-green-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-star"></i>
                                        نقطة قوتك
                                    </h5>
                                    <div id="strength-area" class="text-sm text-gray-700">
                                        <!-- Will be populated by JS -->
                                    </div>
                                </div>

                                <!-- Improvement Area -->
                                <div class="bg-white/80 rounded-xl p-4 shadow-sm border border-white/50">
                                    <h5 class="font-bold text-orange-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-target"></i>
                                        للتحسين
                                    </h5>
                                    <div id="improvement-area" class="text-sm text-gray-700">
                                        <!-- Will be populated by JS -->
                                    </div>
                                </div>

                                <!-- Progress Indicator -->
                                @if($totalAttempts > 1)
                                <div class="bg-white/80 rounded-xl p-4 shadow-sm border border-white/50">
                                    <h5 class="font-bold text-blue-700 mb-2 flex items-center gap-2">
                                        <i class="fas fa-chart-line"></i>
                                        تطور الأداء
                                    </h5>
                                    <div class="text-sm text-gray-700">
                                        @php
                                        $improvement = $allAttempts->last()->total_score - $allAttempts->first()->total_score;
                                        @endphp
                                        @if($improvement > 0)
                                            <div class="flex items-center gap-2 text-green-600">
                                                <i class="fas fa-arrow-up"></i>
                                                <span>تحسن بمقدار {{ $improvement }}%</span>
                                            </div>
                                        @elseif($improvement < 0)
                                            <div class="flex items-center gap-2 text-orange-600">
                                                <i class="fas fa-arrow-down"></i>
                                                <span>انخفاض بمقدار {{ abs($improvement) }}%</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 text-blue-600">
                                                <i class="fas fa-equals"></i>
                                                <span>أداء ثابت</span>
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-500 mt-1">
                                            من {{ $allAttempts->first()->total_score }}% إلى {{ $allAttempts->last()->total_score }}%
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Remove Generate Report Button -->
                            </div>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="report-error" class="hidden text-center py-8">
                        <div class="text-red-600">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>عذراً، لم نتمكن من عرض التقرير في الوقت الحالي</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced 4-Roots Analysis -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center flex items-center justify-center gap-2">
                <i class="fas fa-seedling text-green-600"></i>
                تحليل الجُذور الأربعة
            </h3>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                $roots = [
                    'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'color' => 'blue', 'desc' => 'فهم الماهية والجوهر'],
                    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'purple', 'desc' => 'التفكير والتحليل'], 
                    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'green', 'desc' => 'الربط والتكامل'],
                    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'orange', 'desc' => 'التطبيق والإبداع']
                ];
                $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true);
                @endphp

                @foreach($roots as $key => $root)
                @php $score = $scores[$key] ?? 0; @endphp
                <div class="text-center p-6 bg-gradient-to-br from-{{ $root['color'] }}-50 to-{{ $root['color'] }}-100 rounded-2xl border border-{{ $root['color'] }}-200 hover:shadow-lg transition-all duration-300 group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">{{ $root['icon'] }}</div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $root['name'] }}</h4>
                    <div class="text-3xl font-black text-{{ $root['color'] }}-600 mb-3">{{ $score }}%</div>
                    
                    <!-- Root Progress Bar -->
                    <div class="w-full bg-white/60 rounded-full h-3 mb-3 shadow-inner">
                        <div class="h-3 bg-gradient-to-r from-{{ $root['color'] }}-400 to-{{ $root['color'] }}-600 rounded-full transition-all duration-1000" 
                             style="width: {{ $score }}%"></div>
                    </div>
                    
                    <p class="text-sm text-{{ $root['color'] }}-700 font-medium">{{ $root['desc'] }}</p>
                    
                    <!-- Performance Level -->
                    <div class="mt-3">
                        @if($score >= 80)
                            <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded-full">ممتاز</span>
                        @elseif($score >= 60)
                            <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full">جيد</span>
                        @elseif($score >= 40)
                            <span class="text-xs bg-orange-200 text-orange-800 px-2 py-1 rounded-full">مقبول</span>
                        @else
                            <span class="text-xs bg-red-200 text-red-800 px-2 py-1 rounded-full">يحتاج تطوير</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Insights and Recommendations -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                <h4 class="text-lg font-bold text-indigo-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-lightbulb"></i>
                    نصائح للتحسين
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                    $sortedRoots = collect($scores)->sortBy(function($score, $key) { return $score; });
                    $weakestRoot = $sortedRoots->keys()->first();
                    $strongestRoot = $sortedRoots->keys()->last();
                    @endphp

                    @if($scores[$weakestRoot] < 60)
                    <div class="bg-white/60 rounded-lg p-4">
                        <h5 class="font-bold text-orange-700 mb-2">🎯 ركز على تطوير {{ $roots[$weakestRoot]['name'] }}</h5>
                        <p class="text-sm text-gray-700">
                            هذا الجذر حصل على {{ $scores[$weakestRoot] }}% ويحتاج إلى تركيز أكبر.
                        </p>
                    </div>
                    @endif

                    @if($scores[$strongestRoot] >= 80)
                    <div class="bg-white/60 rounded-lg p-4">
                        <h5 class="font-bold text-green-700 mb-2">⭐ نقطة قوتك في {{ $roots[$strongestRoot]['name'] }}</h5>
                        <p class="text-sm text-gray-700">
                            ممتاز! حصلت على {{ $scores[$strongestRoot] }}% في هذا الجذر.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 justify-center">
            @if($isRegisteredUser && $result->quiz->max_attempts && $totalAttempts < $result->quiz->max_attempts)
            <a href="{{ route('quiz.take', $result->quiz) }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-redo"></i>
                محاولة جديدة ({{ $totalAttempts }}/{{ $result->quiz->max_attempts }})
            </a>
            @elseif($isRegisteredUser && !$result->quiz->max_attempts)
            <a href="{{ route('quiz.take', $result->quiz) }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-redo"></i>
                محاولة جديدة
            </a>
            @endif

            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-home"></i>
                العودة للوحة التحكم
            </a>

            @if($result->quiz->user_id === auth()->id())
            <a href="{{ route('results.quiz', $result->quiz) }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                <i class="fas fa-chart-bar"></i>
                جميع نتائج الاختبار
            </a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.animate-slide-in {
    animation: slideIn 0.6s ease-out;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.score-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Enhanced responsive design */
@media (max-width: 768px) {
    .text-6xl { font-size: 3rem; }
    .text-4xl { font-size: 2rem; }
    .text-3xl { font-size: 1.8rem; }
}

/* Custom scrollbar for attempt history */
.overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animations
    const cards = document.querySelectorAll('.bg-white\\/80');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate-slide-in');
        }, index * 100);
    });

    // Add score pulse animation
    const mainScore = document.querySelector('.text-6xl');
    if (mainScore) {
        mainScore.classList.add('score-pulse');
        setTimeout(() => {
            mainScore.classList.remove('score-pulse');
        }, 3000);
    }

    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Populate quick insights
    populateQuickInsights();
});

// Smart Report Functions
function toggleSmartReport() {
    const content = document.getElementById('smart-report-content');
    const arrow = document.getElementById('smart-report-arrow');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
        
        // Auto-generate report on first open
        if (!document.getElementById('ai-analysis-text').innerHTML.trim()) {
            generateSmartReport();
        }
    } else {
        content.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}

function generateSmartReport() {
    // Show loading state
    document.getElementById('report-loading').classList.remove('hidden');
    document.getElementById('report-analysis').classList.add('hidden');
    document.getElementById('report-error').classList.add('hidden');
    
    // Prepare data for AI analysis
    const reportData = {
        result_id: {{ $result->id }},
        quiz_id: {{ $result->quiz_id }},
        total_score: {{ $result->total_score }},
        attempt_number: {{ $currentAttemptNumber }},
        total_attempts: {{ $totalAttempts }},
        scores: @json($result->scores)@if($totalAttempts > 1),
        all_scores: @json($allAttempts->pluck('total_score')->toArray())@endif
    };

    // Make AJAX request to generate report
    fetch('{{ route("admin.ai.generate-report", $result->quiz) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(reportData)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('report-loading').classList.add('hidden');
        
        if (data.success) {
            document.getElementById('ai-analysis-text').innerHTML = data.report;
            document.getElementById('report-analysis').classList.remove('hidden');
        } else {
            document.getElementById('report-error').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error generating report:', error);
        document.getElementById('report-loading').classList.add('hidden');
        document.getElementById('report-error').classList.remove('hidden');
    });
}

function populateQuickInsights() {
    const scores = @json($result->scores);
    const roots = {
        'jawhar': 'جَوهر',
        'zihn': 'ذِهن', 
        'waslat': 'وَصلات',
        'roaya': 'رُؤية'
    };

    // Find strongest and weakest areas
    let strongest = null;
    let weakest = null;
    let maxScore = -1;
    let minScore = 101;

    for (const [key, score] of Object.entries(scores)) {
        if (score > maxScore) {
            maxScore = score;
            strongest = key;
        }
        if (score < minScore) {
            minScore = score;
            weakest = key;
        }
    }

    // Populate strength area
    if (strongest && maxScore >= 70) {
        document.getElementById('strength-area').innerHTML = `
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="font-medium">${roots[strongest]}</span>
            </div>
            <div class="text-xs text-gray-600">حصلت على ${maxScore}% في هذا الجذر</div>
        `;
    } else {
        document.getElementById('strength-area').innerHTML = `
            <div class="text-gray-600">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-seedling text-green-500"></i>
                    <span class="font-medium">في طور النمو</span>
                </div>
                <div class="text-xs">استمر في التطوير</div>
            </div>
        `;
    }

    // Populate improvement area
    if (weakest && minScore < 70) {
        document.getElementById('improvement-area').innerHTML = `
            <div class="flex items-center gap-2 mb-2">
                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                <span class="font-medium">${roots[weakest]}</span>
            </div>
            <div class="text-xs text-gray-600">يحتاج تركيز أكبر (${minScore}%)</div>
        `;
    } else {
        document.getElementById('improvement-area').innerHTML = `
            <div class="text-gray-600">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="font-medium">أداء متوازن</span>
                </div>
                <div class="text-xs">استمر في التميز</div>
            </div>
        `;
    }
}
</script>
@endpush