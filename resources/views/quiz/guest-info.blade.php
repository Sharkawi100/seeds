@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-900">مرحباً بك!</h2>
                <p class="text-gray-600 mt-2">{{ $quiz->title }}</p>
            </div>

            <form action="{{ route('quiz.guest-start', $quiz) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            اسمك <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="guest_name" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="أدخل اسمك الكامل"
                               required
                               autofocus>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            المدرسة / الصف <span class="text-gray-400">(اختياري)</span>
                        </label>
                        <input type="text" 
                               name="school_class" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مثال: مدرسة النور - الصف السادس">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full mt-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-bold hover:shadow-lg transform hover:scale-105 transition">
                    ابدأ الاختبار
                </button>
            </form>
        </div>
    </div>
</div>
@endsection