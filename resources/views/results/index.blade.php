@extends('layouts.app')

@section('title', 'النشاط الأخير')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(Auth::user()->user_type === 'teacher' || Auth::user()->is_admin)
        <!-- Teacher Dashboard -->
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                            📊 النشاط الأخير
                        </h1>
                        <p class="text-xl text-gray-700">آخر النتائج والأنشطة في اختباراتك</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('reports.index') }}" 
                           class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-chart-line mr-2"></i>
                            التقارير التفصيلية
                        </a>
                        <a href="{{ route('quizzes.index') }}" 
                           class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            إدارة الاختبارات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Stats -->
        @if(isset($dashboardStats))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الاختبارات</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $dashboardStats['total_quizzes'] ?? 0 }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i>
                            {{ $dashboardStats['active_quizzes'] ?? 0 }} نشط
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl">
                        <i class="fas fa-clipboard-list text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">إجمالي النتائج</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $dashboardStats['total_results'] ?? 0 }}</p>
                        <p class="text-sm text-purple-600 mt-1">
                            <i class="fas fa-users mr-1"></i>
                            جميع المحاولات
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl">
                        <i class="fas fa-chart-bar text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">النشاط الأخير</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $dashboardStats['recent_activity'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-600 mt-1">
                            <i class="fas fa-clock mr-1"></i>
                            آخر 15 نتيجة
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-2xl">
                        <i class="fas fa-activity text-2xl text-indigo-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">النتائج اليوم</p>
                        @php
                            $todayResults = isset($results) ? $results->filter(function($result) {
                                return $result->created_at->isToday();
                            })->count() : 0;
                        @endphp
                        <p class="text-3xl font-bold text-gray-900">{{ $todayResults }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-calendar-day mr-1"></i>
                            محاولات اليوم
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl">
                        <i class="fas fa-calendar-check text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @else
        <!-- Student Dashboard -->
        <div class="mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8">
                <h1 class="text-4xl font-black text-gray-900 bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mb-2">
                    🎯 نتائجي
                </h1>
                <p class="text-xl text-gray-700">تتبع أدائك في الاختبارات</p>
            </div>
        </div>
        @endif

        <!-- Results Section -->
        @if(isset($results) && $results->count() > 0)
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <!-- Section Header -->
            <div class="bg-gradient-to-r from-gray-800 via-gray-900 to-black p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">
                        @if(Auth::user()->user_type === 'teacher' || Auth::user()->is_admin)
                            <i class="fas fa-clock mr-2"></i>
                            آخر النتائج
                        @else
                            <i class="fas fa-chart-line mr-2"></i>
                            نتائجي الأخيرة
                        @endif
                    </h2>
                    <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium">
                        {{ $results->count() }} 
                        @if(Auth::user()->user_type === 'teacher' || Auth::user()->is_admin)
                            نتيجة حديثة
                        @else
                            نتيجة
                        @endif
                    </span>
                </div>
            </div>

            <!-- Results Grid -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($results as $result)
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Result Header -->
                        <div class="p-4 border-b border-gray-100">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 truncate mb-1">
                                        {{ $result->quiz->title ?? 'اختبار محذوف' }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $result->user ? $result->user->name : ($result->guest_name ?? 'طالب ضيف') }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-black text-{{ $result->total_score >= 90 ? 'green' : ($result->total_score >= 70 ? 'blue' : ($result->total_score >= 50 ? 'yellow' : 'red')) }}-600">
                                        {{ $result->total_score }}%
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $result->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <!-- Quiz Info -->
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span>
                                    <i class="fas fa-book mr-1"></i>
                                    @if($result->quiz && $result->quiz->subject)
                                        {{ $result->quiz->subject->name }}
                                    @else
                                        {{ $result->quiz->subject ?? 'غير محدد' }}
                                    @endif
                                </span>
                                <span>
                                    <i class="fas fa-layer-group mr-1"></i>
                                    الصف {{ $result->quiz->grade_level ?? '?' }}
                                </span>
                                <span>
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $result->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Roots Performance -->
                        @php
                            $roots = [
                                'jawhar' => ['name' => 'جَوهر', 'icon' => '💎', 'color' => 'blue', 'desc' => 'الماهية'],
                                'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'color' => 'purple', 'desc' => 'العقل'],
                                'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'color' => 'green', 'desc' => 'الروابط'],
                                'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'color' => 'orange', 'desc' => 'البصيرة']
                            ];
                            $scores = is_array($result->scores) ? $result->scores : json_decode($result->scores ?? '{}', true);
                        @endphp

                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($roots as $key => $root)
                                <div class="text-center p-3 bg-{{ $root['color'] }}-50 rounded-xl border border-{{ $root['color'] }}-100 hover:bg-{{ $root['color'] }}-100 transition-colors">
                                    <div class="text-lg mb-1">{{ $root['icon'] }}</div>
                                    <div class="text-xs text-gray-600 mb-1 font-medium">{{ $root['name'] }}</div>
                                    <div class="text-sm font-bold text-{{ $root['color'] }}-600">
                                        {{ $scores[$key] ?? 0 }}%
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('results.show', $result) }}" 
                                   class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-center py-2.5 rounded-xl font-medium hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    عرض التفاصيل
                                </a>
                                
                                @if(Auth::user()->user_type === 'teacher' || Auth::user()->is_admin)
                                <a href="{{ route('results.quiz', $result->quiz) }}" 
                                   class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transform hover:-translate-y-0.5 transition-all duration-300 text-sm"
                                   title="جميع نتائج هذا الاختبار">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(Auth::user()->user_type === 'teacher' || Auth::user()->is_admin)
                <!-- Call to Action for More Results -->
                <div class="mt-8 text-center">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">تريد مشاهدة المزيد؟</h3>
                        <p class="text-gray-600 mb-4">للحصول على تحليل شامل وإحصائيات مفصلة لجميع الاختبارات</p>
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('reports.index') }}" 
                               class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                <i class="fas fa-chart-line mr-2"></i>
                                التقارير الشاملة
                            </a>
                            <a href="{{ route('quizzes.index') }}" 
                               class="bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                إدارة الاختبارات
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @else
        <!-- Empty State -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                </div>
                
                @if(Auth::user()->user_type === 'teacher' || Auth::user()->is_admin)
                <h3 class="text-2xl font-bold text-gray-700 mb-3">لا توجد نتائج حديثة</h3>
                <p class="text-gray-600 mb-6">لم يقم أي طالب بحل اختباراتك مؤخراً. ابدأ بإنشاء اختبار جديد أو شارك رمز الاختبار مع الطلاب.</p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('quizzes.create') }}" 
                       class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        إنشاء اختبار جديد
                    </a>
                    <a href="{{ route('quizzes.index') }}" 
                       class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        اختباراتي
                    </a>
                </div>
                @else
                <h3 class="text-2xl font-bold text-gray-700 mb-3">لا توجد نتائج</h3>
                <p class="text-gray-600 mb-6">لم تقم بحل أي اختبارات بعد. ابدأ بحل اختبار من خلال إدخال رمز الاختبار.</p>
                <a href="{{ route('home') }}" 
                   class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 inline-block">
                    <i class="fas fa-keyboard mr-2"></i>
                    إدخال رمز الاختبار
                </a>
                @endif
            </div>
        </div>
        @endif

        @if(Auth::user()->user_type === 'student' && isset($results) && $results->hasPages())
        <!-- Pagination for Students -->
        <div class="mt-8">
            {{ $results->links() }}
        </div>
        @endif

    </div>
</div>

@push('styles')
<style>
.bg-gradient-to-br {
    background-attachment: fixed;
}

.glass-morphism {
    backdrop-filter: blur(20px);
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

@media (max-width: 768px) {
    .grid-cols-1.lg\:grid-cols-2.xl\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
@endsection