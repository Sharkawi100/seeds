@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-8">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="text-gray-600 hover:text-gray-800 ml-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">إدارة اشتراك: {{ $user->name }}</h1>
        </div>

        <!-- Current Status -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">الحالة الحالية</h2>
            
            <div class="flex items-center justify-between p-6 {{ $user->hasActiveSubscription() ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }} rounded-xl">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        @if($user->hasActiveSubscription())
                            <span class="text-2xl">💎</span>
                            <h3 class="font-bold text-green-900">اشتراك نشط</h3>
                        @else
                            <span class="text-2xl">🎓</span>
                            <h3 class="font-bold text-gray-900">بدون اشتراك</h3>
                        @endif
                    </div>
                    
                    @if($currentSubscription)
                        <p class="text-sm text-gray-600">الخطة: {{ $currentSubscription->plan_name }}</p>
                        <p class="text-sm text-gray-600">ينتهي في: {{ $user->subscription_expires_at?->format('Y/m/d H:i') }}</p>
                    @else
                        <p class="text-sm text-gray-600">لم يشترك المستخدم في أي خطة</p>
                    @endif
                </div>
                
                <div class="text-center">
                    @if($user->hasActiveSubscription())
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center text-white text-2xl">✓</div>
                    @else
                        <div class="w-16 h-16 bg-gray-400 rounded-full flex items-center justify-center text-white text-2xl">✗</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Usage Stats -->
        @if($user->hasActiveSubscription())
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">إحصائيات الاستخدام الشهري</h2>
                
                @php
                    $quota = $user->monthlyQuota;
                    $limits = $user->getCurrentQuotaLimits();
                @endphp
                
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $quota->quiz_count ?? 0 }}</div>
                        <div class="text-sm text-blue-600">من {{ $limits['monthly_quiz_limit'] }} اختبار</div>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $quota->ai_text_requests ?? 0 }}</div>
                        <div class="text-sm text-purple-600">من {{ $limits['monthly_ai_text_limit'] }} نص</div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $quota->ai_quiz_requests ?? 0 }}</div>
                        <div class="text-sm text-green-600">من {{ $limits['monthly_ai_quiz_limit'] }} سؤال</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Management Form -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">إدارة الاشتراك</h2>
            
            <form action="{{ route('admin.subscription-plans.update-user', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3">نوع الإجراء</label>
                    <div class="space-y-3">
                        @if($user->hasActiveSubscription())
                            <label class="flex items-center p-4 border border-yellow-200 rounded-xl cursor-pointer hover:bg-yellow-50">
                                <input type="radio" name="action" value="update" class="ml-3" checked>
                                <div>
                                    <div class="font-medium text-yellow-800">تحديث الاشتراك</div>
                                    <div class="text-sm text-yellow-600">تغيير الخطة أو تاريخ الانتهاء</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-red-200 rounded-xl cursor-pointer hover:bg-red-50">
                                <input type="radio" name="action" value="revoke" class="ml-3">
                                <div>
                                    <div class="font-medium text-red-800">إلغاء الاشتراك</div>
                                    <div class="text-sm text-red-600">إيقاف الاشتراك نهائياً</div>
                                </div>
                            </label>
                        @else
                            <label class="flex items-center p-4 border border-green-200 rounded-xl cursor-pointer hover:bg-green-50">
                                <input type="radio" name="action" value="grant" class="ml-3" checked>
                                <div>
                                    <div class="font-medium text-green-800">منح اشتراك</div>
                                    <div class="text-sm text-green-600">تفعيل اشتراك للمستخدم</div>
                                </div>
                            </label>
                        @endif
                    </div>
                </div>

                <!-- Plan Selection -->
                <div class="mb-6" id="plan-selection">
                    <label class="block text-sm font-bold text-gray-700 mb-3">اختيار الخطة</label>
                    <select name="plan_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ $currentSubscription?->plan_id == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} - ${{ $plan->price_monthly }}/شهر 
                                ({{ $plan->monthly_quiz_limit }} اختبار، {{ $plan->monthly_ai_text_limit }} نص)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Expiration Date -->
                <div class="mb-6" id="expiration-date">
                    <label class="block text-sm font-bold text-gray-700 mb-3">تاريخ انتهاء الاشتراك</label>
                    <input type="datetime-local" 
                           name="expires_at" 
                           value="{{ $user->subscription_expires_at?->format('Y-m-d\TH:i') ?? now()->addMonth()->format('Y-m-d\TH:i') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const actionRadios = document.querySelectorAll('input[name="action"]');
    const planSelection = document.getElementById('plan-selection');
    const expirationDate = document.getElementById('expiration-date');
    
    function toggleFields() {
        const selectedAction = document.querySelector('input[name="action"]:checked').value;
        
        if (selectedAction === 'revoke') {
            planSelection.style.display = 'none';
            expirationDate.style.display = 'none';
        } else {
            planSelection.style.display = 'block';
            expirationDate.style.display = 'block';
        }
    }
    
    actionRadios.forEach(radio => {
        radio.addEventListener('change', toggleFields);
    });
    
    toggleFields(); // Initial state
});
</script>
@endsection