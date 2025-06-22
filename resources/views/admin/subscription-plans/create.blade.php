@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-8">
            <a href="{{ route('admin.subscription-plans.index') }}" 
               class="text-gray-600 hover:text-gray-800 ml-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">إضافة خطة اشتراك جديدة</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form action="{{ route('admin.subscription-plans.store') }}" method="POST">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم الخطة</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Lemon Squeezy Variant ID</label>
                        <input type="text" name="lemon_squeezy_variant_id" value="{{ old('lemon_squeezy_variant_id') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               required>
                        @error('lemon_squeezy_variant_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">السعر الشهري ($)</label>
                        <input type="number" step="0.01" name="price_monthly" value="{{ old('price_monthly') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               required>
                        @error('price_monthly')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">حد الاختبارات الشهري</label>
                        <input type="number" name="monthly_quiz_limit" value="{{ old('monthly_quiz_limit', 40) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               required>
                        @error('monthly_quiz_limit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">حد توليد النصوص الشهري</label>
                        <input type="number" name="monthly_ai_text_limit" value="{{ old('monthly_ai_text_limit', 100) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               required>
                        @error('monthly_ai_text_limit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">حد توليد الأسئلة الشهري</label>
                        <input type="number" name="monthly_ai_quiz_limit" value="{{ old('monthly_ai_quiz_limit', 100) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               required>
                        @error('monthly_ai_quiz_limit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked 
                               class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm font-bold text-gray-700">خطة نشطة</span>
                    </label>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('admin.subscription-plans.index') }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                        حفظ الخطة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection