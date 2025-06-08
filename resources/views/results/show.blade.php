@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">نتائج الاختبار</h2>
                        <a href="{{ route('quizzes.show', $result->quiz) }}" class="text-xl text-white/90 hover:text-white transition-colors">
                            {{ $result->quiz->title }}
                        </a>                        @if($result->guest_name)
                            <p class="text-white/80 mt-1">الطالب: {{ $result->guest_name }}</p>
                        @endif
                    </div>
                    @if(Auth::check() && ((int)Auth::user()->id === (int)$result->quiz->user_id || Auth::user()->is_admin))
<button onclick="generateTeacherReport({{ $result->quiz->id }})" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg transition flex items-center gap-2">
    <span class="text-2xl">🤖</span>
    <span>تحليل أداء الصف</span>
</button>
@endif
                </div>
            </div>

            <div class="p-6">
                <!-- Overall Score -->
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">النتيجة الإجمالية</h3>
                    <div class="text-6xl font-bold {{ $result->total_score >= 80 ? 'text-green-600' : ($result->total_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $result->total_score }}%
                    </div>
                </div>

                <!-- Root Scores -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-700 mb-4 text-center">نتائج الجذور</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @foreach($result->scores as $root => $score)
                        <div class="text-center p-4 rounded-xl {{ 
                            $root == 'jawhar' ? 'bg-red-50' : 
                            ($root == 'zihn' ? 'bg-cyan-50' : 
                            ($root == 'waslat' ? 'bg-yellow-50' : 'bg-purple-50')) 
                        }}">
                            <div class="text-3xl mb-2">
                                {{ ['jawhar' => '🎯', 'zihn' => '🧠', 'waslat' => '🔗', 'roaya' => '👁️'][$root] }}
                            </div>
                            <h4 class="font-bold text-gray-700">{{ ['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'][$root] }}</h4>
                            <p class="text-2xl font-bold {{ 
                                $score >= 80 ? 'text-green-600' : 
                                ($score >= 60 ? 'text-yellow-600' : 'text-red-600') 
                            }}">{{ $score }}%</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- Juzoor Chart -->
                    <div class="flex justify-center">
                        <x-juzoor-chart :scores="$result->scores" size="medium" />
                    </div>
                </div>

                <!-- Detailed Answers -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">تفاصيل الإجابات</h3>
                    <div class="space-y-3">
                        @foreach($result->answers as $index => $answer)
                        <div class="p-4 border-2 rounded-lg {{ $answer->is_correct ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }}">
                            <div class="flex items-start gap-3">
                                <span class="text-lg font-bold text-gray-500">{{ $index + 1 }}</span>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 mb-2">{!! $answer->question->question !!}</p>                                    <div class="flex flex-wrap gap-2 text-sm">
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
<!-- Smart Report Section -->
<div class="mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <button onclick="toggleReport()" class="w-full px-6 py-4 bg-gray-50 hover:bg-gray-100 transition-colors flex items-center justify-between text-left">
            <h3 class="text-xl font-bold text-gray-700 flex items-center gap-2">
                <span class="text-2xl">📊</span>
                التقرير الذكي
            </h3>
            <svg id="report-arrow" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div id="smart-report" class="hidden px-6 py-6 bg-gradient-to-br from-gray-50 to-white">
            @php
                $totalScore = $result->total_score;
                $scores = $result->scores;
                $studentName = $result->user ? $result->user->name : ($result->guest_name ?? 'طالب ضيف');
                $rootNames = ['jawhar' => 'الجَوهر', 'zihn' => 'الذِهن', 'waslat' => 'الوَصلات', 'roaya' => 'الرُؤية'];
                $bestRoot = array_keys($scores, max($scores))[0];
                $worstRoot = array_keys($scores, min($scores))[0];
            @endphp
            
            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                <div class="grid md:grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-600">الطالب</p>
                        <p class="text-lg font-bold text-gray-900">{{ $studentName }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">الاختبار</p>
                        <p class="text-lg font-bold text-gray-900">{{ $result->quiz->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">النتيجة</p>
                        <p class="text-3xl font-bold {{ $totalScore >= 80 ? 'text-green-600' : ($totalScore >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $totalScore }}%
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4 text-gray-700">
                <div class="p-4 bg-white rounded-lg border-r-4 {{ $totalScore >= 80 ? 'border-green-500' : ($totalScore >= 60 ? 'border-yellow-500' : 'border-red-500') }}">
                    <h4 class="font-bold text-gray-900 mb-2">الأداء العام</h4>
                    <p>
                        @if($totalScore >= 80)
                            أداء متميز يعكس إتقاناً عالياً للمادة. الطالب يُظهر فهماً عميقاً وقدرة على تطبيق المفاهيم بكفاءة.
                        @elseif($totalScore >= 60)
                            أداء جيد مع إمكانية التحسين. الطالب لديه أساس قوي يحتاج إلى تعزيز في بعض الجوانب.
                        @else
                            يحتاج إلى دعم إضافي. مراجعة المفاهيم الأساسية ضرورية لبناء فهم أقوى.
                        @endif
                    </p>
                </div>
                
                <div class="p-4 bg-white rounded-lg">
                    <h4 class="font-bold text-gray-900 mb-3">تحليل الجذور</h4>
                    <div class="grid md:grid-cols-2 gap-3">
                        @foreach($scores as $root => $score)
                        <div class="flex items-center justify-between p-2 rounded {{ $score >= 80 ? 'bg-green-50' : ($score >= 60 ? 'bg-yellow-50' : 'bg-red-50') }}">
                            <span class="font-medium">{{ $rootNames[$root] }}</span>
                            <span class="font-bold {{ $score >= 80 ? 'text-green-700' : ($score >= 60 ? 'text-yellow-700' : 'text-red-700') }}">
                                {{ $score }}%
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="p-4 bg-white rounded-lg">
                    <h4 class="font-bold text-gray-900 mb-2">التوصيات</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2">
                            <span class="text-green-600">✓</span>
                            <span>نقطة القوة: {{ $rootNames[$bestRoot] }} - استمر في تطوير هذه المهارة</span>
                        </li>
                        @if($scores[$worstRoot] < 60)
                        <li class="flex items-start gap-2">
                            <span class="text-red-600">!</span>
                            <span>يحتاج تحسين: {{ $rootNames[$worstRoot] }} - ركز على تمارين هذا الجذر</span>
                        </li>
                        @endif
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">←</span>
                            <span>الخطوة التالية: 
                                @if($totalScore >= 80)
                                    تحدى نفسك بمستويات أصعب
                                @elseif($totalScore >= 60)
                                    راجع الأسئلة الخاطئة وافهم أسباب الخطأ
                                @else
                                    أعد دراسة النص الأساسي مع التركيز على المفاهيم الرئيسية
                                @endif
                            </span>
                        </li>
                    </ul>
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
</script>
                <!-- Action Buttons -->
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('quiz.take', $result->quiz) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition">
                        إعادة الاختبار
                    </a>
                    @if(Auth::check() && (Auth::user()->is_admin || Auth::user()->user_type === 'teacher'))
    <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
        <i class="fas fa-list"></i>
        عرض جميع الاختبارات
    </a>
@endif
                </div>
            </div>
        </div>

        <!-- AI Generated Report Section -->
        <div id="reportSection" class="hidden mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6">
                <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="text-3xl">✨</span>
                    التقرير التفصيلي - مولد بالذكاء الاصطناعي
                </h3>
            </div>
            <div class="p-6">
                <div id="reportContent" class="prose max-w-none text-gray-700 leading-relaxed"></div>
            </div>
        </div>
    </div>
</div>

<script>
async function generateTeacherReport(quizId) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/roots/admin/ai/quiz/${quizId}/report`, {            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                type: 'class_analysis'
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('reportContent').innerHTML = data.report.replace(/\n/g, '<br>');
            document.getElementById('reportSection').classList.remove('hidden');
            document.getElementById('reportSection').scrollIntoView({ behavior: 'smooth' });
        } else {
            alert('حدث خطأ في توليد التقرير');
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
        console.error(error);
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}
</script>
@endsection