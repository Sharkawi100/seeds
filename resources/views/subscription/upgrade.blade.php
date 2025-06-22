@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                أطلق العنان للذكاء الاصطناعي
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                وفر ساعات من الوقت في إنشاء الاختبارات والنصوص التعليمية باستخدام تقنية الذكاء الاصطناعي المتطورة
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach($plans as $plan)
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-4 border-purple-200">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-8 text-white text-center">
                        <h3 class="text-2xl font-bold mb-2">{{ $plan->name }}</h3>
                        <div class="text-5xl font-bold mb-2">${{ $plan->price_monthly }}</div>
                        <div class="text-purple-100">شهرياً</div>
                    </div>

                    <!-- Features -->
                    <div class="p-8">
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 ml-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700 font-medium">{{ $plan->monthly_quiz_limit }} اختبار شهرياً</span>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 ml-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700 font-medium">توليد النصوص بالذكاء الاصطناعي</span>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 ml-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700 font-medium">توليد الاختبارات بالذكاء الاصطناعي</span>
                            </div>
                        </div>

                        <!-- Subscribe Button -->
                        <form action="{{ route('subscription.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-4 px-6 rounded-2xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                ابدأ الاشتراك الآن
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection