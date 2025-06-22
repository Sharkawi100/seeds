@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-3xl p-8 text-white mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
            
            <div class="relative">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center text-4xl font-bold backdrop-blur">
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }}</h1>
                        <p class="text-purple-100 text-lg">{{ Auth::user()->email }}</p>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">
                                {{ Auth::user()->user_type === 'teacher' ? 'معلم' : (Auth::user()->user_type === 'admin' ? 'مدير' : 'طالب') }}
                            </span>
                            @if(Auth::user()->hasActiveSubscription())
                                <span class="bg-green-400 px-3 py-1 rounded-full text-sm font-medium text-green-900">
                                    💎 مشترك
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Subscription Status -->
                @if(Auth::user()->user_type === 'teacher')
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-crown text-purple-600"></i>
                            </div>
                            حالة الاشتراك
                        </h2>
                        
                        @if(Auth::user()->hasActiveSubscription())
                            <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-green-900">اشتراك نشط</h3>
                                        <p class="text-green-700">{{ Auth::user()->subscription?->plan_name ?? 'Pro Teacher' }}</p>
                                    </div>
                                    <div class="text-3xl">💎</div>
                                </div>
                                
                                @if(Auth::user()->subscription_expires_at)
                                    <div class="text-sm text-green-600 mb-4">
                                        <i class="fas fa-calendar-alt ml-2"></i>
                                        ينتهي في: {{ Auth::user()->subscription_expires_at->format('Y/m/d H:i') }}
                                    </div>
                                @endif
                                
                                <div class="flex gap-3">
                                    <a href="{{ route('subscription.manage') }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        إدارة الاشتراك
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-purple-900">ترقية حسابك</h3>
                                        <p class="text-purple-700">احصل على مميزات الذكاء الاصطناعي</p>
                                    </div>
                                    <div class="text-3xl">⭐</div>
                                </div>
                                
                                <a href="{{ route('subscription.upgrade') }}" 
                                   class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center gap-2">
                                    <i class="fas fa-rocket"></i>
                                    اشترك الآن
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Usage Statistics -->
                @if(Auth::user()->user_type === 'teacher')
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-bar text-blue-600"></i>
                            </div>
                            إحصائيات الاستخدام الشهري
                        </h2>
                        
                        @php
                            $quota = Auth::user()->monthlyQuota;
                            $limits = Auth::user()->getCurrentQuotaLimits();
                        @endphp
                        
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="text-center p-6 bg-blue-50 rounded-xl">
                                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $quota->quiz_count ?? 0 }}</div>
                                <div class="text-blue-600 font-medium mb-1">اختبارات منشأة</div>
                                <div class="text-sm text-blue-500">من {{ $limits['monthly_quiz_limit'] }} متاح</div>
                                <div class="w-full bg-blue-200 rounded-full h-2 mt-3">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $limits['monthly_quiz_limit'] > 0 ? (($quota->quiz_count ?? 0) / $limits['monthly_quiz_limit']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="text-center p-6 bg-purple-50 rounded-xl">
                                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $quota->ai_text_requests ?? 0 }}</div>
                                <div class="text-purple-600 font-medium mb-1">نصوص مولدة</div>
                                <div class="text-sm text-purple-500">من {{ $limits['monthly_ai_text_limit'] }} متاح</div>
                                <div class="w-full bg-purple-200 rounded-full h-2 mt-3">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $limits['monthly_ai_text_limit'] > 0 ? (($quota->ai_text_requests ?? 0) / $limits['monthly_ai_text_limit']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div class="text-center p-6 bg-green-50 rounded-xl">
                                <div class="text-3xl font-bold text-green-600 mb-2">{{ $quota->ai_quiz_requests ?? 0 }}</div>
                                <div class="text-green-600 font-medium mb-1">أسئلة مولدة</div>
                                <div class="text-sm text-green-500">من {{ $limits['monthly_ai_quiz_limit'] }} متاح</div>
                                <div class="w-full bg-green-200 rounded-full h-2 mt-3">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $limits['monthly_ai_quiz_limit'] > 0 ? (($quota->ai_quiz_requests ?? 0) / $limits['monthly_ai_quiz_limit']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-green-600"></i>
                        </div>
                        النشاط الأخير
                    </h2>
                    
                    @php
                        $recentQuizzes = Auth::user()->quizzes()->latest()->take(5)->get();
                    @endphp
                    
                    @if($recentQuizzes->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentQuizzes as $quiz)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center text-white font-bold">
                                            {{ $quiz->questions->count() }}
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900">{{ $quiz->title }}</h3>
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                <span><i class="fas fa-calendar ml-1"></i>{{ $quiz->created_at->format('Y/m/d') }}</span>
                                                <span><i class="fas fa-users ml-1"></i>{{ $quiz->results->count() }} مشارك</span>
                                                <span class="px-2 py-1 bg-{{ $quiz->is_active ? 'green' : 'gray' }}-100 text-{{ $quiz->is_active ? 'green' : 'gray' }}-700 rounded-full">
                                                    {{ $quiz->is_active ? 'نشط' : 'معطل' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('quizzes.show', $quiz) }}" 
                                       class="text-purple-600 hover:text-purple-800 font-medium">
                                        عرض
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-file-alt text-4xl mb-4"></i>
                            <p>لا توجد اختبارات منشأة بعد</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">إجراءات سريعة</h3>
                    <div class="space-y-3">
                        @if(Auth::user()->user_type === 'teacher')
                            <a href="{{ route('quizzes.create') }}" 
                               class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors">
                                <i class="fas fa-plus text-purple-600"></i>
                                <span class="font-medium text-purple-700">إنشاء اختبار جديد</span>
                            </a>
                            
                            <a href="{{ route('quizzes.index') }}" 
                               class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors">
                                <i class="fas fa-list text-blue-600"></i>
                                <span class="font-medium text-blue-700">اختباراتي</span>
                            </a>
                            
                            <a href="{{ route('results.index') }}" 
                               class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-xl transition-colors">
                                <i class="fas fa-chart-line text-green-600"></i>
                                <span class="font-medium text-green-700">النتائج والتحليلات</span>
                            </a>
                        @endif
                        
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                            <i class="fas fa-cog text-gray-600"></i>
                            <span class="font-medium text-gray-700">إعدادات الحساب</span>
                        </a>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">معلومات الحساب</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">تاريخ الانضمام:</span>
                            <span class="font-medium">{{ Auth::user()->created_at->format('Y/m/d') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">آخر تسجيل دخول:</span>
                            <span class="font-medium">{{ Auth::user()->last_login_at?->format('Y/m/d H:i') ?? 'غير متاح' }}</span>
                        </div>
                        
                        @if(Auth::user()->school_name)
                            <div class="flex justify-between">
                                <span class="text-gray-600">المدرسة:</span>
                                <span class="font-medium">{{ Auth::user()->school_name }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">حالة الحساب:</span>
                            <span class="font-medium text-green-600">نشط</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection