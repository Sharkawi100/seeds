@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.subscription-plans.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">مستخدمي الاشتراكات</h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">اشتراكات نشطة</p>
                    <p class="text-2xl font-bold text-green-900">{{ $stats['active'] }}</p>
                </div>
                <div class="text-green-500 text-2xl">💎</div>
            </div>
        </div>
        
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">اشتراكات منتهية</p>
                    <p class="text-2xl font-bold text-red-900">{{ $stats['expired'] }}</p>
                </div>
                <div class="text-red-500 text-2xl">⏰</div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">اشتراكات ملغية</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $stats['cancelled'] }}</p>
                </div>
                <div class="text-yellow-500 text-2xl">🚫</div>
            </div>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">إجمالي الإيرادات</p>
                    <p class="text-2xl font-bold text-blue-900">${{ number_format($stats['revenue'], 2) }}</p>
                </div>
                <div class="text-blue-500 text-2xl">💰</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="status" class="rounded-xl border-gray-200 focus:border-purple-500 px-4 py-3">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
            </select>
            
            <select name="plan" class="rounded-xl border-gray-200 focus:border-purple-500 px-4 py-3">
                <option value="">جميع الخطط</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl">
                تطبيق
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">المستخدم</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الخطة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">تاريخ البدء</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">تاريخ الانتهاء</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الاستخدام الشهري</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($subscriptions as $subscription)
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
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900">{{ $subscription->plan_name }}</span>
                            <div class="text-sm text-gray-500">${{ $subscription->subscriptionPlan?->price_monthly ?? 'N/A' }}/شهر</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($subscription->status == 'active')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">💎 نشط</span>
                            @elseif($subscription->status == 'cancelled')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">🚫 ملغي</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">⏸️ {{ $subscription->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{ $subscription->current_period_start->format('Y/m/d') }}
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{ $subscription->current_period_end->format('Y/m/d') }}
                            @if($subscription->current_period_end->isPast())
                                <span class="text-red-500 text-sm">(منتهي)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $quota = $subscription->user->monthlyQuota;
                                $limits = $subscription->user->getCurrentQuotaLimits();
                            @endphp
                            <div class="text-sm">
                                <div>اختبارات: {{ $quota->quiz_count ?? 0 }}/{{ $limits['monthly_quiz_limit'] }}</div>
                                <div>نصوص: {{ $quota->ai_text_requests ?? 0 }}/{{ $limits['monthly_ai_text_limit'] }}</div>
                                <div>أسئلة: {{ $quota->ai_quiz_requests ?? 0 }}/{{ $limits['monthly_ai_quiz_limit'] }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $subscription->user) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">عرض</a>
                                <a href="{{ route('admin.users.impersonate', $subscription->user) }}" 
                                   class="text-purple-600 hover:text-purple-800 font-medium">دخول</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($subscriptions->hasPages())
        <div class="mt-8">
            {{ $subscriptions->links() }}
        </div>
    @endif
</div>
@endsection