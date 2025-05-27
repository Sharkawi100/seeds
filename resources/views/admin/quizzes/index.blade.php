@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">جميع الاختبارات</h2>
                
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">العنوان</th>
                            <th class="px-4 py-2">المستخدم</th>
                            <th class="px-4 py-2">الأسئلة</th>
                            <th class="px-4 py-2">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quizzes as $quiz)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $quiz->title }}</td>
                            <td class="px-4 py-2">{{ $quiz->user->name ?? 'غير معروف' }}</td>
                            <td class="px-4 py-2">{{ $quiz->questions_count }}</td>
                            <td class="px-4 py-2">{{ $quiz->created_at->format('Y/m/d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="mt-4">
                    {{ $quizzes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection