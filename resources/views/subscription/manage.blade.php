@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">إدارة الاشتراك</h1>
    
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        @if($user->hasActiveSubscription())
            <div class="flex items-center justify-between p-6 bg-green-50 rounded-xl border border-green-200">
                <div>
                    <h3 class="font-bold text-green-900">{{ $subscription->plan_name ?? 'اشتراك نشط' }}</h3>
                    <p class="text-green-700">الحالة: {{ $subscription->status ?? 'نشط' }}</p>
                    @if($user->subscription_expires_at)
                        <p class="text-sm text-green-600">ينتهي في: {{ $user->subscription_expires_at->format('Y/m/d') }}</p>
                    @endif
                </div>
                <div class="text-center">
                    <div class="text-3xl text-green-600 mb-2">✅</div>
                    <p class="text-green-700 font-medium">نشط</p>
                </div>
            </div>
        @else
            <div class="text-center p-8 bg-gray-50 rounded-xl">
                <div class="text-4xl mb-4">⭐</div>
                <h3 class="text-xl font-bold mb-2">ترقية إلى معلم محترف</h3>
                <p class="text-gray-600 mb-6">احصل على مميزات الذكاء الاصطناعي</p>
                <a href="{{ route('subscription.upgrade') }}" 
                   class="inline-flex items-center px-8 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700">
                    اشترك الآن
                </a>
            </div>
        @endif
    </div>

    <!-- Usage Stats -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6">إحصائيات الاستخدام الشهري</h2>
        @php
            $quota = $user->monthlyQuota;
            $limits = $user->getCurrentQuotaLimits();
        @endphp
        
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-blue-50 p-6 rounded-xl">
                <h3 class="font-bold text-blue-900 mb-2">الاختبارات</h3>
                <div class="text-2xl font-bold text-blue-600">
                    {{ $quota->quiz_count ?? 0 }} / {{ $limits['monthly_quiz_limit'] }}
                </div>
            </div>
            
            <div class="bg-purple-50 p-6 rounded-xl">
                <h3 class="font-bold text-purple-900 mb-2">توليد النصوص</h3>
                <div class="text-2xl font-bold text-purple-600">
                    {{ $quota->ai_text_requests ?? 0 }} / {{ $limits['monthly_ai_text_limit'] }}
                </div>
            </div>
            
            <div class="bg-green-50 p-6 rounded-xl">
                <h3 class="font-bold text-green-900 mb-2">توليد الأسئلة</h3>
                <div class="text-2xl font-bold text-green-600">
                    {{ $quota->ai_quiz_requests ?? 0 }} / {{ $limits['monthly_ai_quiz_limit'] }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection