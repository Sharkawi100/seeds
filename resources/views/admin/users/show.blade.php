@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">تفاصيل المستخدم</h2>
                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        تعديل
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">المعلومات الأساسية</h3>
                        <p><strong>الاسم:</strong> {{ $user->name }}</p>
                        <p><strong>البريد الإلكتروني:</strong> {{ $user->email }}</p>
                        <p><strong>النوع:</strong> 
                            @if($user->is_admin)
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded">مدير</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">مستخدم</span>
                            @endif
                        </p>
                        <p><strong>تاريخ التسجيل:</strong> {{ $user->created_at->format('Y/m/d') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-2">الإحصائيات</h3>
                        <p><strong>عدد الاختبارات:</strong> {{ $user->quizzes_count }}</p>
                        <p><strong>عدد النتائج:</strong> {{ $user->results_count }}</p>
                    </div>
                </div>
                
                @if($recentQuizzes->count() > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">آخر الاختبارات</h3>
                    <ul>
                        @foreach($recentQuizzes as $quiz)
                        <li>{{ $quiz->title }} - {{ $quiz->created_at->diffForHumans() }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="mt-6">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">العودة للقائمة</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection