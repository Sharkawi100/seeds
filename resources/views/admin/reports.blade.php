@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-black/60 backdrop-blur-lg rounded-3xl p-8 mb-8 border border-white/20">
                <h1 class="text-4xl font-bold text-white mb-2">📊 التقارير والإحصائيات</h1>
                <p class="text-xl text-gray-300">تحليل شامل لأداء المنصة</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-black/50 backdrop-blur rounded-2xl p-6 text-white">
                    <div class="text-5xl font-black mb-2">{{ \App\Models\Quiz::count() }}</div>
                    <div class="text-lg opacity-90">إجمالي الاختبارات</div>
                </div>
                <div class="bg-black/50 backdrop-blur rounded-2xl p-6 text-white">
                    <div class="text-5xl font-black mb-2">{{ \App\Models\Result::count() }}</div>
                    <div class="text-lg opacity-90">إجمالي النتائج</div>
                </div>
                <div class="bg-black/50 backdrop-blur rounded-2xl p-6 text-white">
                    <div class="text-5xl font-black mb-2">{{ \App\Models\User::where('user_type', 'teacher')->count() }}</div>
                    <div class="text-lg opacity-90">عدد المعلمين</div>
                </div>
                <div class="bg-black/50 backdrop-blur rounded-2xl p-6 text-white">
                    <div class="text-5xl font-black mb-2">{{ \App\Models\User::where('user_type', 'student')->count() }}</div>
                    <div class="text-lg opacity-90">عدد الطلاب</div>
                </div>
            </div>

            <!-- Detailed Reports Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- User Growth -->
                <div class="bg-black/50 backdrop-blur rounded-2xl p-6">
                    <h2 class="text-2xl font-bold text-white mb-6">📈 نمو المستخدمين</h2>
                    <div class="space-y-4">
                        @foreach(range(6, 0) as $i)
                        @php
                            $date = now()->subDays($i);
                            $count = \App\Models\User::whereDate('created_at', $date)->count();
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-gray-300">{{ $date->format('Y-m-d') }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-32 bg-gray-700 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full" 
                                         style="width: {{ $count > 0 ? min($count * 20, 100) : 0 }}%"></div>
                                </div>
                                <span class="text-white font-bold">{{ $count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quiz Activity -->
                <div class="bg-black/50 backdrop-blur rounded-2xl p-6">
                    <h2 class="text-2xl font-bold text-white mb-6">🎮 نشاط الاختبارات</h2>
                    <div class="space-y-4">
                        @foreach(\App\Models\Quiz::with('user')->latest()->take(5)->get() as $quiz)
                        <div class="flex items-center justify-between p-3 bg-black/40 rounded-lg">
                            <div>
                                <p class="text-white font-medium">{{ $quiz->title }}</p>
                                <p class="text-gray-400 text-sm">{{ $quiz->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-white font-bold">{{ $quiz->results->count() }}</p>
                                <p class="text-gray-400 text-xs">محاولة</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

               <!-- Root Performance -->
<div class="bg-black/50 backdrop-blur rounded-2xl p-6">
    <h2 class="text-2xl font-bold text-white mb-6">🌱 أداء الجذور</h2>
    @php
        $rootAverages = [
            'jawhar' => \App\Models\Result::all()->avg(function($r) { 
                return $r->scores['jawhar'] ?? 0; 
            }) ?? 0,
            'zihn' => \App\Models\Result::all()->avg(function($r) { 
                return $r->scores['zihn'] ?? 0; 
            }) ?? 0,
            'waslat' => \App\Models\Result::all()->avg(function($r) { 
                return $r->scores['waslat'] ?? 0; 
            }) ?? 0,
            'roaya' => \App\Models\Result::all()->avg(function($r) { 
                return $r->scores['roaya'] ?? 0; 
            }) ?? 0,
        ];
    @endphp
    <div class="space-y-4">
        @foreach($rootAverages as $root => $avg)
        <div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-white font-medium">
                    {{ ['jawhar' => 'جَوهر', 'zihn' => 'ذِهن', 'waslat' => 'وَصلات', 'roaya' => 'رُؤية'][$root] }}
                </span>
                <span class="text-white font-bold">{{ number_format($avg, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-3">
                <div class="bg-gradient-to-r {{ 
                    $root == 'jawhar' ? 'from-red-400 to-red-600' : 
                    ($root == 'zihn' ? 'from-teal-400 to-teal-600' : 
                    ($root == 'waslat' ? 'from-yellow-400 to-yellow-600' : 'from-purple-400 to-purple-600')) 
                }} h-3 rounded-full" style="width: {{ $avg }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

                <!-- Pending Teachers -->
                <div class="bg-black/50 backdrop-blur rounded-2xl p-6">
                    <h2 class="text-2xl font-bold text-white mb-6">⏳ معلمون بانتظار الموافقة</h2>
                    @php
                        $pendingTeachers = \App\Models\User::where('user_type', 'teacher')
                            ->where('is_approved', false)
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp
                    @if($pendingTeachers->count() > 0)
                        <div class="space-y-3">
                            @foreach($pendingTeachers as $teacher)
                            <div class="flex items-center justify-between p-3 bg-yellow-500/20 rounded-lg border border-yellow-500/40">
                                <div>
                                    <p class="text-white font-medium">{{ $teacher->name }}</p>
                                    <p class="text-gray-300 text-sm">{{ $teacher->school_name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-300 text-xs">{{ $teacher->created_at->diffForHumans() }}</p>
                                    <a href="{{ route('admin.users.edit', $teacher) }}" class="text-yellow-400 hover:text-yellow-300 text-sm">
                                        مراجعة
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-center py-8">لا يوجد معلمون بانتظار الموافقة</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection