@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="py-12" x-data="{ activeTab: 'overview', showAvatarModal: false }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-t-lg shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Avatar Section -->
                    <div class="relative flex-shrink-0">
                        <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur flex items-center justify-center overflow-hidden shadow-2xl">
                            @if(auth()->user()->avatar)
                                @if(str_starts_with(auth()->user()->avatar, 'http'))
                                    <img src="{{ auth()->user()->avatar }}" 
                                         alt="Avatar" 
                                         class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                         alt="Avatar" 
                                         class="w-full h-full object-cover">
                                @endif
                            @else
                                <span class="text-5xl text-white font-bold">
                                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <button @click="showAvatarModal = true" 
                                class="absolute bottom-0 left-0 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition transform hover:scale-110">
                            <i class="fas fa-camera text-gray-600"></i>
                        </button>
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex-1 text-center md:text-right">
                        <h1 class="text-3xl font-bold text-white mb-2">{{ auth()->user()->name }}</h1>
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 text-white/90">
                            <p class="flex items-center">
                                @if(auth()->user()->user_type == 'teacher')
                                    <i class="fas fa-chalkboard-teacher ml-2"></i> معلم
                                @elseif(auth()->user()->user_type == 'admin')
                                    <i class="fas fa-user-shield ml-2"></i> مدير
                                @else
                                    <i class="fas fa-user-graduate ml-2"></i> طالب
                                @endif
                            </p>
                            <p class="flex items-center">
                                <i class="fas fa-envelope ml-2"></i> {{ auth()->user()->email }}
                            </p>
                            @if(auth()->user()->school_name)
                                <p class="flex items-center">
                                    <i class="fas fa-school ml-2"></i> {{ auth()->user()->school_name }}
                                </p>
                            @endif
                            @if(auth()->user()->grade_level)
                                <p class="flex items-center">
                                    <i class="fas fa-layer-group ml-2"></i> الصف {{ auth()->user()->grade_level }}
                                </p>
                            @endif
                        </div>
                        
                        <!-- Login Provider Badge -->
                        @if(auth()->user()->auth_provider === 'google')
                        <div class="mt-3 inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur rounded-full text-sm">
                            <i class="fab fa-google ml-2"></i> تسجيل عبر Google
                        </div>
                    @endif
                    </div>
                    
                    <!-- Profile Completion -->
                    <div class="bg-white/10 backdrop-blur rounded-lg p-6 min-w-[200px]">
                        <p class="text-white/90 text-sm mb-2">اكتمال الملف الشخصي</p>
                        <div class="text-3xl font-bold text-white mb-3">{{ auth()->user()->profile_completion ?? 0 }}%</div>
                        <div class="w-full bg-white/30 rounded-full h-3 overflow-hidden">
                            <div class="bg-white rounded-full h-3 transition-all duration-500 shadow-sm" 
                                 style="width: {{ auth()->user()->profile_completion ?? 0 }}%"></div>
                        </div>
                        @if(auth()->user()->profile_completion < 100)
                            <a href="{{ route('profile.completion') }}" 
                               class="mt-3 inline-flex items-center text-sm text-white/90 hover:text-white transition">
                                <i class="fas fa-tasks ml-2"></i> أكمل ملفك
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="bg-white shadow-xl rounded-b-lg">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button @click="activeTab = 'overview'" 
                            :class="{ 'border-purple-500 text-purple-600': activeTab === 'overview' }"
                            class="px-6 py-4 border-b-2 font-medium text-sm transition hover:text-purple-600 flex items-center">
                        <i class="fas fa-chart-line ml-2"></i> نظرة عامة
                    </button>
                    <button @click="activeTab = 'info'" 
                            :class="{ 'border-purple-500 text-purple-600': activeTab === 'info' }"
                            class="px-6 py-4 border-b-2 font-medium text-sm transition hover:text-purple-600 flex items-center">
                        <i class="fas fa-info-circle ml-2"></i> المعلومات الشخصية
                    </button>
                    <button @click="activeTab = 'security'" 
                            :class="{ 'border-purple-500 text-purple-600': activeTab === 'security' }"
                            class="px-6 py-4 border-b-2 font-medium text-sm transition hover:text-purple-600 flex items-center">
                        <i class="fas fa-shield-alt ml-2"></i> الأمان
                    </button>
                    @if(auth()->user()->user_type == 'student')
                        <button @click="activeTab = 'progress'" 
                                :class="{ 'border-purple-500 text-purple-600': activeTab === 'progress' }"
                                class="px-6 py-4 border-b-2 font-medium text-sm transition hover:text-purple-600 flex items-center">
                            <i class="fas fa-trophy ml-2"></i> التقدم
                        </button>
                    @endif
                </nav>
            </div>
            
            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200">
                    @if(auth()->user()->user_type == 'teacher' || auth()->user()->user_type == 'admin')
                        <!-- Teacher/Admin Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-blue-600 text-sm font-medium">الاختبارات المنشأة</p>
                                        <p class="text-3xl font-bold text-blue-800 mt-2">{{ auth()->user()->quizzes->count() }}</p>
                                    </div>
                                    <div class="bg-blue-200/50 p-3 rounded-xl">
                                        <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-600 text-sm font-medium">إجمالي الأسئلة</p>
                                        <p class="text-3xl font-bold text-green-800 mt-2">
                                            {{ auth()->user()->quizzes->sum(function($quiz) { 
                                                return $quiz->questions->count(); 
                                            }) }}
                                        </p>
                                    </div>
                                    <div class="bg-green-200/50 p-3 rounded-xl">
                                        <i class="fas fa-question-circle text-2xl text-green-600"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-purple-600 text-sm font-medium">الطلاب المشاركون</p>
                                        <p class="text-3xl font-bold text-purple-800 mt-2">
                                            {{ \App\Models\Result::whereIn('quiz_id', auth()->user()->quizzes->pluck('id'))->distinct('user_id')->count('user_id') }}
                                        </p>
                                    </div>
                                    <div class="bg-purple-200/50 p-3 rounded-xl">
                                        <i class="fas fa-users text-2xl text-purple-600"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-yellow-600 text-sm font-medium">متوسط النتائج</p>
                                        <p class="text-3xl font-bold text-yellow-800 mt-2">
                                            {{ round(\App\Models\Result::whereIn('quiz_id', auth()->user()->quizzes->pluck('id'))->avg('total_score') ?? 0) }}%
                                        </p>
                                    </div>
                                    <div class="bg-yellow-200/50 p-3 rounded-xl">
                                        <i class="fas fa-chart-bar text-2xl text-yellow-600"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Quizzes -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-clock ml-2 text-gray-600"></i> الاختبارات الأخيرة
                            </h3>
                            <div class="space-y-3">
                                @forelse(auth()->user()->quizzes()->latest()->take(5)->get() as $quiz)
                                    <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h4 class="font-medium text-gray-800">{{ $quiz->title }}</h4>
                                                <div class="flex gap-4 mt-1 text-sm text-gray-600">
                                                    <span><i class="fas fa-book ml-1"></i> {{ $quiz->subject }}</span>
                                                    <span><i class="fas fa-layer-group ml-1"></i> الصف {{ $quiz->grade_level }}</span>
                                                    <span><i class="fas fa-question ml-1"></i> {{ $quiz->questions->count() }} سؤال</span>
                                                </div>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-lg font-bold text-purple-600">{{ $quiz->pin }}</p>
                                                <p class="text-xs text-gray-500">{{ $quiz->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">لا توجد اختبارات بعد</p>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <!-- Student Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-blue-600 text-sm font-medium">الاختبارات المكتملة</p>
                                        <p class="text-3xl font-bold text-blue-800 mt-2">{{ auth()->user()->results->count() }}</p>
                                    </div>
                                    <div class="bg-blue-200/50 p-3 rounded-xl">
                                        <i class="fas fa-clipboard-check text-2xl text-blue-600"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-600 text-sm font-medium">متوسط النتائج</p>
                                        <p class="text-3xl font-bold text-green-800 mt-2">
                                            {{ round(auth()->user()->results->avg('total_score') ?? 0) }}%
                                        </p>
                                    </div>
                                    <div class="bg-green-200/50 p-3 rounded-xl">
                                        <i class="fas fa-percentage text-2xl text-green-600"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-purple-600 text-sm font-medium">أفضل جذر</p>
                                        @php
                                            $rootAverages = [
                                                'jawhar' => auth()->user()->results->avg('scores.jawhar') ?? 0,
                                                'zihn' => auth()->user()->results->avg('scores.zihn') ?? 0,
                                                'waslat' => auth()->user()->results->avg('scores.waslat') ?? 0,
                                                'roaya' => auth()->user()->results->avg('scores.roaya') ?? 0,
                                            ];
                                            $bestRoot = array_search(max($rootAverages), $rootAverages);
                                            $rootNames = [
                                                'jawhar' => 'جَوهر',
                                                'zihn' => 'ذِهن',
                                                'waslat' => 'وَصلات',
                                                'roaya' => 'رُؤية'
                                            ];
                                        @endphp
                                        <p class="text-2xl font-bold text-purple-800 mt-2">{{ $rootNames[$bestRoot] ?? '-' }}</p>
                                    </div>
                                    <div class="bg-purple-200/50 p-3 rounded-xl">
                                        <i class="fas fa-star text-2xl text-purple-600"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-yellow-600 text-sm font-medium">مستوى التقدم</p>
                                        <p class="text-2xl font-bold text-yellow-800 mt-2">
                                            @if(auth()->user()->results->count() >= 10) 
                                                متقدم
                                            @elseif(auth()->user()->results->count() >= 5)
                                                متوسط
                                            @else
                                                مبتدئ
                                            @endif
                                        </p>
                                    </div>
                                    <div class="bg-yellow-200/50 p-3 rounded-xl">
                                        <i class="fas fa-medal text-2xl text-yellow-600"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Results -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-history ml-2 text-gray-600"></i> النتائج الأخيرة
                            </h3>
                            <div class="space-y-3">
                                @forelse(auth()->user()->results()->latest()->take(5)->get() as $result)
                                    <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h4 class="font-medium text-gray-800">{{ $result->quiz->title }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ $result->created_at->diffForHumans() }}</p>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-2xl font-bold text-purple-600">{{ $result->total_score }}%</p>
                                                <a href="{{ route('results.show', $result) }}" 
                                                   class="text-xs text-purple-600 hover:text-purple-700">
                                                    عرض التفاصيل <i class="fas fa-arrow-left"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">لم تكمل أي اختبارات بعد</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Personal Info Tab -->
                <div x-show="activeTab === 'info'" style="display: none;" x-transition:enter="transition ease-out duration-200">
                    @include('profile.partials.update-profile-information-form')
                </div>
                
                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" style="display: none;" x-transition:enter="transition ease-out duration-200">
                    @include('profile.partials.update-password-form')
                    
                    <!-- Login History -->
                    <div class="mt-8 bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-history ml-2"></i> سجل تسجيل الدخول
                        </h3>
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">آخر تسجيل دخول</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'الآن' }}
                                        </p>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-globe ml-1"></i> {{ auth()->user()->last_login_ip ?? request()->ip() }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-sign-in-alt ml-1"></i> عدد مرات الدخول: {{ auth()->user()->login_count }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Tab (Students Only) -->
                @if(auth()->user()->user_type == 'student')
                <div x-show="activeTab === 'progress'" style="display: none;" x-transition:enter="transition ease-out duration-200">
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-chart-line ml-2"></i> تقدمك في الجذور الأربعة
                        </h3>
                        
                        <!-- Juzoor Progress Chart -->
                        @if(auth()->user()->results->count() > 0)
                            <div class="bg-white rounded-xl p-6 shadow-lg">
                                @php
                                    $userScores = [
                                        'jawhar' => round(auth()->user()->results->avg('scores.jawhar') ?? 0),
                                        'zihn' => round(auth()->user()->results->avg('scores.zihn') ?? 0),
                                        'waslat' => round(auth()->user()->results->avg('scores.waslat') ?? 0),
                                        'roaya' => round(auth()->user()->results->avg('scores.roaya') ?? 0),
                                    ];
                                @endphp
                                <x-juzoor-chart :scores="$userScores" size="large" />
                            </div>
                            
                            <!-- Performance by Root -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                                @foreach($userScores as $root => $score)
                                    <div class="bg-white rounded-lg p-4 shadow-sm text-center">
                                        <p class="text-sm text-gray-600 mb-1">{{ $rootNames[$root] }}</p>
                                        <p class="text-2xl font-bold text-purple-600">{{ $score }}%</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-xl p-12 text-center">
                                <i class="fas fa-chart-pie text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">لا توجد بيانات كافية لعرض التقدم</p>
                                <p class="text-sm text-gray-400 mt-2">أكمل بعض الاختبارات لرؤية تقدمك</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Avatar Upload Modal -->
    <div x-show="showAvatarModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showAvatarModal = false"></div>
            
            <div class="relative bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-camera ml-2"></i> تحديث الصورة الشخصية
                </h3>
                
                <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            اختر صورة جديدة
                        </label>
                        <input type="file" 
                               name="avatar" 
                               accept="image/*" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                        <p class="mt-1 text-sm text-gray-500">
                            JPG, PNG أو GIF. الحجم الأقصى 2MB
                        </p>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" 
                                @click="showAvatarModal = false"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            رفع الصورة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide success messages
    setTimeout(() => {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endpush