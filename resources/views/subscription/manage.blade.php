@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">إدارة الاشتراك</h1>
    
    <!-- Subscription Status -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        @if($user->hasActiveSubscription() && $subscription)
            @if($subscription->isCancelled())
                <!-- Cancelled but still active -->
                <div class="flex items-center justify-between p-6 bg-yellow-50 rounded-xl border border-yellow-200">
                    <div>
                        <h3 class="font-bold text-yellow-900">{{ $subscription->plan_name ?? 'اشتراك ملغي' }}</h3>
                        <p class="text-yellow-700">تم إلغاء الاشتراك في: {{ $subscription->cancelled_at->format('Y/m/d H:i') }}</p>
                        <p class="text-sm text-yellow-600"><strong>الخدمة متاحة حتى:</strong> {{ $subscription->current_period_end->format('Y/m/d H:i') }}</p>
                        <p class="text-sm text-yellow-600 font-medium">{{ $subscription->daysRemaining() }} يوم متبقي</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl text-yellow-600 mb-2">⚠️</div>
                        <p class="text-yellow-700 font-medium">ملغي</p>
                    </div>
                </div>
            @else
                <!-- Active subscription -->
                <div class="flex items-center justify-between p-6 bg-green-50 rounded-xl border border-green-200">
                    <div>
                        <h3 class="font-bold text-green-900">{{ $subscription->plan_name ?? 'اشتراك نشط' }}</h3>
                        <p class="text-green-700">الحالة: {{ $subscription->status ?? 'نشط' }}</p>
                        <p class="text-sm text-green-600"><strong>تاريخ البدء:</strong> {{ $subscription->current_period_start->format('Y/m/d') }}</p>
                        <p class="text-sm text-green-600"><strong>تاريخ الانتهاء:</strong> {{ $subscription->current_period_end->format('Y/m/d H:i') }}</p>
                        <p class="text-sm text-green-600 font-medium">{{ $subscription->daysRemaining() }} يوم متبقي</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl text-green-600 mb-2">✅</div>
                        <p class="text-green-700 font-medium">نشط</p>
                    </div>
                </div>

                <!-- Benefits Reminder -->
                <div class="mt-6 p-6 bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl border border-purple-200">
                    <h3 class="font-bold text-purple-900 mb-4">🎯 مميزات اشتراكك الحالي</h3>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-green-500">✓</span>
                            <span class="text-sm text-gray-700">توليد نصوص تعليمية بالذكاء الاصطناعي</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-green-500">✓</span>
                            <span class="text-sm text-gray-700">إنشاء أسئلة تلقائياً من النصوص</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-green-500">✓</span>
                            <span class="text-sm text-gray-700">تحليل الجُذور الأربعة للطلاب</span>
                        </div>
                    </div>
                </div>

                <!-- Cancellation Section -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-start gap-4">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 mb-2">هل تفكر في إلغاء الاشتراك؟</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                ستفقد إمكانية استخدام الذكاء الاصطناعي نهائياً. الخدمة ستبقى متاحة حتى {{ $subscription->current_period_end->format('Y/m/d') }} فقط.
                            </p>
                        </div>
                        <button onclick="toggleCancelSection()" 
                                id="cancelButton"
                                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            إلغاء الاشتراك
                        </button>
                    </div>
                </div>
            @endif
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
    @if($user->hasActiveSubscription())
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
                <div class="text-xs text-blue-600 mt-1">
                    {{ round((($quota->quiz_count ?? 0) / $limits['monthly_quiz_limit']) * 100) }}% مستخدم
                </div>
            </div>
            
            <div class="bg-purple-50 p-6 rounded-xl">
                <h3 class="font-bold text-purple-900 mb-2">توليد النصوص</h3>
                <div class="text-2xl font-bold text-purple-600">
                    {{ $quota->ai_text_requests ?? 0 }} / {{ $limits['monthly_ai_text_limit'] }}
                </div>
                <div class="text-xs text-purple-600 mt-1">
                    وفرت {{ ($quota->ai_text_requests ?? 0) * 10 }} دقيقة من وقتك
                </div>
            </div>
            
            <div class="bg-green-50 p-6 rounded-xl">
                <h3 class="font-bold text-green-900 mb-2">توليد الأسئلة</h3>
                <div class="text-2xl font-bold text-green-600">
                    {{ $quota->ai_quiz_requests ?? 0 }} / {{ $limits['monthly_ai_quiz_limit'] }}
                </div>
                <div class="text-xs text-green-600 mt-1">
                    وفرت {{ ($quota->ai_quiz_requests ?? 0) * 15 }} دقيقة من وقتك
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Enhanced Cancellation Section - Inline within Page -->
    @if($user->hasActiveSubscription() && $subscription && !$subscription->isCancelled())
    <div id="cancelSection" class="hidden">
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">انتظر! فكر مرة أخرى قبل الإلغاء</h3>
            
            <!-- Pros vs Cons Cards - Side by Side -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <!-- Cons Card (What you'll lose) -->
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6">
                    <div class="text-center mb-4">
                        <div class="text-4xl mb-2">💔</div>
                        <h4 class="font-bold text-red-800 text-lg">ماذا ستفقد عند الإلغاء</h4>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 text-xl">✗</span>
                            <span class="text-sm text-red-700">لن تتمكن من توليد نصوص تعليمية بالذكاء الاصطناعي</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 text-xl">✗</span>
                            <span class="text-sm text-red-700">لن تتمكن من إنشاء أسئلة تلقائياً</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 text-xl">✗</span>
                            @php
                                $quota = $user->monthlyQuota;
                                $timeSaved = ($quota->ai_text_requests ?? 0) * 10 + ($quota->ai_quiz_requests ?? 0) * 15;
                            @endphp
                            <span class="text-sm text-red-700">ستفقد <strong>{{ $timeSaved }} دقيقة</strong> وفرتها هذا الشهر</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 text-xl">✗</span>
                            <span class="text-sm text-red-700">ستعود لإنشاء الاختبارات يدوياً (يستغرق ساعات)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 text-xl">✗</span>
                            <span class="text-sm text-red-700">ستفقد التحديثات والمميزات الجديدة</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-red-500 text-xl">✗</span>
                            <span class="text-sm text-red-700">طلابك سيفقدون التجربة التفاعلية المتطورة</span>
                        </li>
                    </ul>
                </div>

                <!-- Pros Card (What you can do instead) -->
                <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6">
                    <div class="text-center mb-4">
                        <div class="text-4xl mb-2">💡</div>
                        <h4 class="font-bold text-green-800 text-lg">اقتراحات أفضل من الإلغاء</h4>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 text-xl">✓</span>
                            <span class="text-sm text-green-700">تواصل معنا لحل أي مشكلة تقنية مجاناً</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 text-xl">✓</span>
                            <span class="text-sm text-green-700">استمتع بالمميزات الجديدة التي نضيفها شهرياً</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 text-xl">✓</span>
                            <span class="text-sm text-green-700">وفر <strong>15$</strong> شهرياً مقابل توفير ساعات من وقتك</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 text-xl">✓</span>
                            <span class="text-sm text-green-700">كن من المعلمين الرائدين في استخدام التكنولوجيا</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 text-xl">✓</span>
                            <span class="text-sm text-green-700">امنح طلابك تجربة تعليمية متقدمة ومميزة</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 text-xl">✓</span>
                            <span class="text-sm text-green-700">استمر في الاستفادة من تحليل الجُذور الأربعة</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Alternative Solutions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                    <span>🤝</span>
                    <span>بدلاً من الإلغاء، جرب هذه الحلول:</span>
                </h4>
                <div class="grid md:grid-cols-2 gap-3 text-sm text-blue-700">
                    <div>• راسلنا عبر الدعم لمساعدتك</div>
                    <div>• اطلب تدريباً مجانياً على المميزات</div>
                    <div>• شارك ملاحظاتك لتحسين الخدمة</div>
                    <div>• جرب ميزة واحدة كل يوم لمدة أسبوع</div>
                </div>
            </div>

            <!-- Still want to cancel section -->
            <div class="border-t pt-6">
                <div class="text-center mb-4">
                    <p class="text-gray-600 font-medium">إذا كنت مصر على الإلغاء، ساعدنا في التحسين:</p>
                </div>
                
                <form action="{{ route('subscription.cancel') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">سبب الإلغاء (مطلوب):</label>
                        <textarea name="cancellation_reason" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                  placeholder="مثال: مكلف، لا أحتاجه، مشاكل تقنية، غير راضي عن الخدمة، لا أفهم كيفية الاستخدام..."
                                  required></textarea>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3 text-sm text-yellow-800">
                            <div class="text-yellow-600 text-xl">⚠️</div>
                            <div>
                                <strong>تذكير أخير:</strong> الخدمة ستبقى متاحة حتى {{ $subscription->current_period_end->format('Y/m/d H:i') }} ولن يتم استرداد المبلغ. يمكنك إعادة الاشتراك في أي وقت.
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" 
                                onclick="hideCancelSection()"
                                class="flex-1 bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition-colors">
                            أريد البقاء مشترك 💚
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-red-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition-colors">
                            إلغاء نهائي 💔
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function toggleCancelSection() {
    const section = document.getElementById('cancelSection');
    const button = document.getElementById('cancelButton');
    
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        button.textContent = 'إخفاء';
        button.classList.remove('bg-red-600', 'hover:bg-red-700');
        button.classList.add('bg-gray-600', 'hover:bg-gray-700');
    } else {
        section.classList.add('hidden');
        button.textContent = 'إلغاء الاشتراك';
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        button.classList.add('bg-red-600', 'hover:bg-red-700');
    }
}

function hideCancelSection() {
    const section = document.getElementById('cancelSection');
    const button = document.getElementById('cancelButton');
    
    section.classList.add('hidden');
    button.textContent = 'إلغاء الاشتراك';
    button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
    button.classList.add('bg-red-600', 'hover:bg-red-700');
    
    // Scroll back to top of subscription section
    document.querySelector('h1').scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>
@endsection