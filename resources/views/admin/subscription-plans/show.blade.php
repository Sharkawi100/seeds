@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.subscription-plans.index') }}" 
           class="text-gray-600 hover:text-gray-800 ml-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">{{ $subscriptionPlan->name }}</h1>
    </div>

    <!-- Plan Details Card -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-purple-50 rounded-xl">
                <div class="text-3xl font-bold text-purple-600">${{ $subscriptionPlan->price_monthly }}</div>
                <div class="text-purple-600 font-medium">شهرياً</div>
            </div>
            <div class="text-center p-6 bg-blue-50 rounded-xl">
                <div class="text-3xl font-bold text-blue-600">{{ $subscriptionPlan->monthly_quiz_limit }}</div>
                <div class="text-blue-600 font-medium">اختبار شهرياً</div>
            </div>
            <div class="text-center p-6 bg-green-50 rounded-xl">
                <div class="text-3xl font-bold text-green-600">{{ $subscriptionPlan->monthly_ai_text_limit }}</div>
                <div class="text-green-600 font-medium">توليد نصوص</div>
            </div>
            <div class="text-center p-6 bg-orange-50 rounded-xl">
                <div class="text-3xl font-bold text-orange-600">{{ $subscriptionPlan->monthly_ai_quiz_limit }}</div>
                <div class="text-orange-600 font-medium">توليد أسئلة</div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">إجمالي الاشتراكات</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_subscriptions'] }}</p>
                </div>
                <div class="text-gray-400 text-2xl">📊</div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">اشتراكات نشطة</p>
                    <p class="text-2xl font-bold text-green-900">{{ $stats['active_subscriptions'] }}</p>
                </div>
                <div class="text-green-500 text-2xl">💎</div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">الإيرادات الشهرية</p>
                    <p class="text-2xl font-bold text-blue-900">${{ number_format($stats['monthly_revenue'], 2) }}</p>
                </div>
                <div class="text-blue-500 text-2xl">💰</div>
            </div>
        </div>
    </div>

    <!-- Active Subscribers -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">المشتركون النشطون</h2>
        </div>
        
        @if($activeSubscriptions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">المستخدم</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">تاريخ البدء</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">تاريخ التجديد</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الاستخدام</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($activeSubscriptions as $subscription)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold">
                                        {{ mb_substr($subscription->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $subscription->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $subscription->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-900">
                                {{ $subscription->current_period_start->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4 text-gray-900">
                                {{ $subscription->current_period_end->format('Y/m/d') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $quota = $subscription->user->monthlyQuota;
                                @endphp
                                <div class="text-sm">
                                    <div>{{ $quota->quiz_count ?? 0 }}/{{ $subscriptionPlan->monthly_quiz_limit }} اختبار</div>
                                    <div>{{ $quota->ai_text_requests ?? 0 }}/{{ $subscriptionPlan->monthly_ai_text_limit }} نص</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $subscription->user) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">عرض</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($activeSubscriptions->hasPages())
                <div class="p-6">
                    {{ $activeSubscriptions->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="text-4xl mb-4">📋</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد اشتراكات نشطة</h3>
                <p class="text-gray-500">لم يشترك أي مستخدم في هذه الخطة بعد</p>
            </div>
        @endif
    </div>
</div>
@endsection