@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-white mb-2">🎮 مرحباً {{ Auth::user()->name }}!</h1>
                <p class="text-xl text-gray-300">جاهز لتحدي جديد في عالم جُذور التعليمي؟</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-pink-500 to-violet-500 rounded-xl p-6 text-white transform hover:scale-105 transition">
                    <div class="text-4xl mb-2">🏆</div>
                    <div class="text-3xl font-bold">{{ Auth::user()->quizzes->count() }}</div>
                    <div class="text-sm opacity-90">اختبارات تم إنشاؤها</div>
                </div>
                
                <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl p-6 text-white transform hover:scale-105 transition">
                    <div class="text-4xl mb-2">⚡</div>
                    <div class="text-3xl font-bold">{{ Auth::user()->results->count() }}</div>
                    <div class="text-sm opacity-90">اختبارات مكتملة</div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl p-6 text-white transform hover:scale-105 transition">
                    <div class="text-4xl mb-2">🎯</div>
                    <div class="text-3xl font-bold">{{ Auth::user()->results->avg('total_score') ?? 0 }}%</div>
                    <div class="text-sm opacity-90">متوسط النتائج</div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-6 text-white transform hover:scale-105 transition">
                    <div class="text-4xl mb-2">🔥</div>
                    <div class="text-3xl font-bold">{{ Auth::user()->results->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                    <div class="text-sm opacity-90">نشاط هذا الأسبوع</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">🚀 إجراءات سريعة</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('quizzes.create') }}" class="group">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl p-6 text-center transform hover:scale-105 transition">
                            <div class="text-5xl mb-3">➕</div>
                            <h3 class="text-xl font-bold text-white">إنشاء اختبار جديد</h3>
                            <p class="text-gray-200 mt-2">صمم تحدي جديد بنموذج جُذور</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('quizzes.index') }}" class="group">
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-center transform hover:scale-105 transition">
                            <div class="text-5xl mb-3">📚</div>
                            <h3 class="text-xl font-bold text-white">اختباراتي</h3>
                            <p class="text-gray-200 mt-2">إدارة وتعديل اختباراتك</p>
                        </div>
                    </a>
                    
                    <a href="#" class="group">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl p-6 text-center transform hover:scale-105 transition">
                            <div class="text-5xl mb-3">🏅</div>
                            <h3 class="text-xl font-bold text-white">لوحة الصدارة</h3>
                            <p class="text-gray-200 mt-2">تنافس مع الآخرين</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Root Progress -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-white mb-6">🌱 تقدم الجذور</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @php
                    $roots = [
                        ['name' => 'جَوهر', 'emoji' => '🎯', 'color' => 'from-red-500 to-pink-500', 'progress' => 75],
                        ['name' => 'ذِهن', 'emoji' => '🧠', 'color' => 'from-cyan-500 to-blue-500', 'progress' => 60],
                        ['name' => 'وَصلات', 'emoji' => '🔗', 'color' => 'from-yellow-500 to-orange-500', 'progress' => 45],
                        ['name' => 'رُؤية', 'emoji' => '👁️', 'color' => 'from-purple-500 to-indigo-500', 'progress' => 80],
                    ];
                    @endphp
                    
                    @foreach($roots as $root)
                    <div class="text-center">
                        <div class="text-5xl mb-3">{{ $root['emoji'] }}</div>
                        <h3 class="text-white font-bold mb-2">{{ $root['name'] }}</h3>
                        <div class="bg-gray-700 rounded-full h-4 overflow-hidden">
                            <div class="bg-gradient-to-r {{ $root['color'] }} h-full transition-all duration-1000" style="width: {{ $root['progress'] }}%"></div>
                        </div>
                        <p class="text-gray-300 text-sm mt-2">{{ $root['progress'] }}%</p>
                    </div>
                    @endforeach
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-white mb-6 text-center">🌱 تقدم الجذور</h2>
                    @php
                        $averageScores = [
                            'jawhar' => 85,
                            'zihn' => 75, 
                            'waslat' => 60,
                            'roaya' => 65
                        ];
                    @endphp
                    <x-juzoor-chart :scores="$averageScores" size="large" />
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .transform:hover {
        animation: pulse 0.5s ease-in-out;
    }
</style>
@endsection