@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة خطط الاشتراك</h1>
            <p class="text-gray-600">إدارة خطط الاشتراك وعرض إحصائيات المشتركين</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.subscription-plans.users') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                </svg>
                عرض المشتركين
            </a>
            <a href="{{ route('admin.subscription-plans.create') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة خطة جديدة
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        @php
            $totalPlans = $plans->count();
            $activePlans = $plans->where('is_active', true)->count();
            $totalSubscriptions = \App\Models\Subscription::count();
            $activeSubscriptions = \App\Models\Subscription::where('status', 'active')->count();
        @endphp
        
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">إجمالي الخطط</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPlans }}</p>
                </div>
                <div class="text-purple-500 text-2xl">📋</div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">خطط نشطة</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activePlans }}</p>
                </div>
                <div class="text-green-500 text-2xl">✅</div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">إجمالي الاشتراكات</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSubscriptions }}</p>
                </div>
                <div class="text-blue-500 text-2xl">👥</div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">اشتراكات نشطة</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeSubscriptions }}</p>
                </div>
                <div class="text-orange-500 text-2xl">💎</div>
            </div>
        </div>
    </div>

    <!-- Plans Grid -->
    @if($plans->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border {{ $plan->is_active ? 'border-green-200' : 'border-gray-200' }}">
                    <!-- Plan Header -->
                    <div class="bg-gradient-to-r {{ $plan->is_active ? 'from-green-500 to-emerald-600' : 'from-gray-400 to-gray-500' }} p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold">{{ $plan->name }}</h3>
                            @if($plan->is_active)
                                <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">نشط</span>
                            @else
                                <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">معطل</span>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">${{ $plan->price_monthly }}</div>
                            <div class="text-sm opacity-90">شهرياً</div>
                        </div>
                    </div>

                    <!-- Plan Features -->
                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">الاختبارات الشهرية:</span>
                                <span class="font-bold text-gray-900">{{ $plan->monthly_quiz_limit }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">توليد النصوص:</span>
                                <span class="font-bold text-gray-900">{{ $plan->monthly_ai_text_limit }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">توليد الأسئلة:</span>
                                <span class="font-bold text-gray-900">{{ $plan->monthly_ai_quiz_limit }}</span>
                            </div>
                        </div>

                        <!-- Plan Stats -->
                        @php
                            $planSubscriptions = $plan->subscriptions()->count();
                            $planActiveSubscriptions = $plan->subscriptions()->where('status', 'active')->count();
                        @endphp
                        
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $planActiveSubscriptions }}</div>
                                <div class="text-sm text-gray-600">مشترك نشط</div>
                                <div class="text-xs text-gray-500 mt-1">من أصل {{ $planSubscriptions }} إجمالي</div>
                            </div>
                        </div>

                        <!-- Variant ID -->
                        <div class="text-xs text-gray-500 mb-4 text-center">
                            Variant ID: {{ $plan->lemon_squeezy_variant_id }}
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('admin.subscription-plans.show', $plan) }}" 
                               class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-4 rounded-lg transition-colors text-center">
                                عرض التفاصيل
                            </a>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('admin.subscription-plans.edit', $plan) }}" 
                                   class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-center">
                                    تعديل
                                </a>
                                
                                <form action="{{ route('admin.subscription-plans.toggle', $plan) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full {{ $plan->is_active ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} font-medium py-2 px-4 rounded-lg transition-colors">
                                        {{ $plan->is_active ? 'إلغاء تفعيل' : 'تفعيل' }}
                                    </button>
                                </form>
                            </div>
                            
                            @if($plan->subscriptions()->where('status', 'active')->count() === 0)
                                <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الخطة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors">
                                        حذف الخطة
                                    </button>
                                </form>
                            @else
                                <div class="text-center text-xs text-gray-500 py-2">
                                    لا يمكن حذف خطة لديها اشتراكات نشطة
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="text-6xl mb-6">📋</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">لا توجد خطط اشتراك</h3>
            <p class="text-gray-600 mb-8">ابدأ بإنشاء أول خطة اشتراك للمعلمين</p>
            <a href="{{ route('admin.subscription-plans.create') }}" 
               class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إنشاء خطة جديدة
            </a>
        </div>
    @endif
</div>

@if(session('success'))
    <script>
        setTimeout(() => {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = '{{ session("success") }}';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }, 100);
    </script>
@endif

@if(session('error'))
    <script>
        setTimeout(() => {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = '{{ session("error") }}';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }, 100);
    </script>
@endif
@endsection