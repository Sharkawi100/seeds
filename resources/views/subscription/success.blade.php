@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-3xl shadow-2xl p-8 text-center">
            <div class="text-6xl text-green-500 mb-6">✅</div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">تم تفعيل الاشتراك بنجاح</h1>
            <p class="text-gray-600 mb-8">يمكنك الآن استخدام جميع مميزات الذكاء الاصطناعي</p>
            
            <div class="space-y-4">
                <a href="{{ route('quizzes.create') }}" class="block w-full bg-purple-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-purple-700">
                    إنشاء اختبار جديد
                </a>
                <a href="{{ route('quizzes.index') }}" class="block w-full bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-xl hover:bg-gray-300">
                    اختباراتي
                </a>
            </div>
        </div>
    </div>
</div>
@endsection