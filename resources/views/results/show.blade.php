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
                        <h3 class="text-xl text-white/90">{{ $result->quiz->title }}</h3>
                    </div>
                    @if(Auth::check() && (Auth::user()->id == $result->quiz->user_id || Auth::user()->is_admin))
                    <button onclick="generateReport()" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg transition flex items-center gap-2" title="توليد تقرير بالذكاء الاصطناعي">
                        <span class="text-2xl">🪄</span>
                        <span>تقرير سحري</span>
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
                                    <p class="font-medium text-gray-800 mb-2">{{ $answer->question->question }}</p>
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
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('quiz.take', $result->quiz) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition">
                        إعادة الاختبار
                    </a>
                    @auth
                        <a href="{{ route('quizzes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-bold transition">
                            عرض جميع الاختبارات
                        </a>
                    @endauth
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
async function generateReport() {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    button.disabled = true;
    
    try {
        const response = await fetch('{{ route("admin.ai.generateReport", $result->quiz) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                result_id: {{ $result->id }}
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