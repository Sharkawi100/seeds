@extends('layouts.app')

@section('title', 'نتيجة الاختبار')

@section('content')
@php
// Handle both array and object formats for $result
$resultData = is_array($result) ? $result : $result->toArray();

// Calculate result info for guest
$studentName = $resultData['guest_name'] ?? 'طالب ضيف';
$quizTitle = $resultData['quiz']['title'] ?? 'اختبار محذوف';
$totalScore = $resultData['total_score'] ?? 0;
$scores = is_array($resultData['scores'] ?? null) ? $resultData['scores'] : json_decode($resultData['scores'] ?? '{}', true);
$createdAt = $resultData['created_at'] ?? now();
$gradeLevel = $resultData['quiz']['grade_level'] ?? null;

// Define 4 roots
$roots = [
    'jawhar' => ['name' => 'جَوهر', 'icon' => '💎', 'color' => 'blue', 'desc' => 'الماهية - ما هو؟'],
    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'purple', 'desc' => 'العقل - كيف يعمل؟'],
    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'green', 'desc' => 'الروابط - كيف يتصل؟'],
    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'orange', 'desc' => 'البصيرة - كيف نستخدمه؟']
];
@endphp

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white/90 backdrop-blur-lg overflow-hidden shadow-2xl rounded-3xl border border-white/20">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 via-blue-600 to-purple-600 p-8">
                <div class="text-center">
                    <div class="mb-4">
                        <h1 class="text-4xl md:text-5xl font-black text-white mb-2">
                            🎯 نتيجة الاختبار
                        </h1>
                        <h2 class="text-xl md:text-2xl text-white/90 font-medium">
                            {{ $quizTitle }}
                        </h2>
                    </div>
                    
                    <div class="flex items-center justify-center gap-6 text-white/80 text-sm flex-wrap">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user"></i>
                            <span>{{ $studentName }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar"></i>
                            <span>{{ is_string($createdAt) ? $createdAt : $createdAt->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($gradeLevel)
                        <div class="flex items-center gap-2">
                            <i class="fas fa-layer-group"></i>
                            <span>الصف {{ $gradeLevel }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Score Display -->
            <div class="p-8 text-center border-b border-gray-100">
                <div class="mb-6">
                    <div class="text-6xl md:text-7xl font-black mb-2 text-{{ $totalScore >= 90 ? 'green' : ($totalScore >= 70 ? 'blue' : ($totalScore >= 50 ? 'yellow' : 'red')) }}-600">
                        {{ $totalScore }}%
                    </div>
                    <div class="text-xl text-gray-600 font-medium">
                        @if($totalScore >= 90)
                            🌟 ممتاز! أداء رائع
                        @elseif($totalScore >= 70)
                            👍 جيد جداً! استمر في التقدم
                        @elseif($totalScore >= 50)
                            📈 جيد، يمكنك التحسن أكثر
                        @else
                            💪 لا تيأس، المحاولة القادمة ستكون أفضل
                        @endif
                    </div>
                </div>
            </div>

            <!-- 4 Roots Performance -->
            <div class="p-8">
                <h3 class="text-2xl font-bold text-center text-gray-900 mb-8">
                    📊 أداؤك في الجذور الأربعة
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    @foreach($roots as $key => $root)
                    @php
                        $score = $scores[$key] ?? 0;
                    @endphp
                    <div class="bg-gradient-to-br from-{{ $root['color'] }}-50 to-{{ $root['color'] }}-100 rounded-2xl p-6 border border-{{ $root['color'] }}-200 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="text-3xl">{{ $root['icon'] }}</div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900">{{ $root['name'] }}</h4>
                                    <p class="text-sm text-gray-600">{{ $root['desc'] }}</p>
                                </div>
                            </div>
                            <div class="text-3xl font-black text-{{ $root['color'] }}-600">
                                {{ $score }}%
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                            <div class="h-4 bg-gradient-to-r from-{{ $root['color'] }}-400 to-{{ $root['color'] }}-600 rounded-full transition-all duration-1000 ease-out" 
                                 style="width: {{ $score }}%"></div>
                        </div>
                        
                        <!-- Performance Message -->
                        <div class="mt-3 text-sm text-{{ $root['color'] }}-700 font-medium">
                            @if($score >= 90)
                                🌟 إتقان ممتاز!
                            @elseif($score >= 70)
                                👍 أداء جيد جداً
                            @elseif($score >= 50)
                                📈 يمكن التحسن
                            @else
                                💪 يحتاج إلى مراجعة
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Detailed Performance Analysis -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-200">
                    <h4 class="text-lg font-bold text-gray-900 mb-4 text-center">
                        📈 تحليل أدائك
                    </h4>
                    
                    @php
                        $rootScores = array_values($scores);
                        $averageRootScore = count($rootScores) > 0 ? array_sum($rootScores) / count($rootScores) : 0;
                        $strongestRoot = collect($roots)->keys()->reduce(function($carry, $key) use ($scores) {
                            return ($scores[$key] ?? 0) > ($scores[$carry] ?? 0) ? $key : $carry;
                        }, 'jawhar');
                        $weakestRoot = collect($roots)->keys()->reduce(function($carry, $key) use ($scores) {
                            return ($scores[$key] ?? 0) < ($scores[$carry] ?? 100) ? $key : $carry;
                        }, 'jawhar');
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div class="bg-white rounded-xl p-4 border border-gray-100">
                            <div class="text-2xl mb-2">🎯</div>
                            <div class="text-sm text-gray-600 mb-1">متوسط الجذور</div>
                            <div class="text-xl font-bold text-blue-600">{{ round($averageRootScore) }}%</div>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 border border-gray-100">
                            <div class="text-2xl mb-2">⭐</div>
                            <div class="text-sm text-gray-600 mb-1">أقوى جذر</div>
                            <div class="text-lg font-bold text-green-600">{{ $roots[$strongestRoot]['name'] }}</div>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 border border-gray-100">
                            <div class="text-2xl mb-2">📚</div>
                            <div class="text-sm text-gray-600 mb-1">يحتاج تركيز</div>
                            <div class="text-lg font-bold text-orange-600">{{ $roots[$weakestRoot]['name'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center gap-4 mt-8">
                    <a href="{{ route('home') }}" 
                       class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-redo mr-2"></i>
                        حل اختبار آخر
                    </a>
                    
                    <button onclick="window.print()" 
                            class="bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        طباعة النتيجة
                    </button>
                </div>

                <!-- Tips Section -->
                <div class="mt-8 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                    <h4 class="text-lg font-bold text-gray-900 mb-4 text-center">
                        💡 نصائح للتحسن
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="bg-white rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-lg">{{ $roots['jawhar']['icon'] }}</span>
                                <span class="font-bold text-blue-600">{{ $roots['jawhar']['name'] }}</span>
                            </div>
                            <p class="text-gray-600">راجع التعريفات والمفاهيم الأساسية</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-lg">{{ $roots['zihn']['icon'] }}</span>
                                <span class="font-bold text-purple-600">{{ $roots['zihn']['name'] }}</span>
                            </div>
                            <p class="text-gray-600">تدرب على التحليل والتفكير النقدي</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-lg">{{ $roots['waslat']['icon'] }}</span>
                                <span class="font-bold text-green-600">{{ $roots['waslat']['name'] }}</span>
                            </div>
                            <p class="text-gray-600">اربط المفاهيم مع بعضها البعض</p>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-lg">{{ $roots['roaya']['icon'] }}</span>
                                <span class="font-bold text-orange-600">{{ $roots['roaya']['name'] }}</span>
                            </div>
                            <p class="text-gray-600">فكر في التطبيقات العملية للمعرفة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    
    .print-section, .print-section * {
        visibility: visible;
    }
    
    .print-section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    .no-print {
        display: none !important;
    }
}

.bg-gradient-to-br {
    background-attachment: fixed;
}
</style>
@endpush
@endsection