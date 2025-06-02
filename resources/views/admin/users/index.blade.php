@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">إدارة المستخدمين</h2>
                    <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        إضافة مستخدم جديد
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">الاسم</th>
                                <th class="px-4 py-2">البريد الإلكتروني</th>
                                <th class="px-4 py-2">النوع</th>
                                <th class="px-4 py-2">تاريخ التسجيل</th>
                                <th class="px-4 py-2">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $user->id }}</td>
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">
                                    @if($user->is_admin)
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded">مدير</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">مستخدم</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $user->created_at->format('Y/m/d') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 ml-2">تعديل</a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection