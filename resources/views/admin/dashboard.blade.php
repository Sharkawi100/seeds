@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-3xl font-bold">لوحة التحكم الإدارية</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-2xl font-bold">{{ $stats['total_users'] }}</div>
                <div class="text-gray-500">المستخدمون</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-2xl font-bold">{{ $stats['total_quizzes'] }}</div>
                <div class="text-gray-500">الاختبارات</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-2xl font-bold">{{ $stats['total_questions'] }}</div>
                <div class="text-gray-500">الأسئلة</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-2xl font-bold">{{ $stats['total_results'] }}</div>
                <div class="text-gray-500">النتائج</div>
            </div>
        </div>
    </div>
</div>
@endsection