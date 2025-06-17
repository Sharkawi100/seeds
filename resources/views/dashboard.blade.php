{{-- Update resources/views/dashboard.blade.php --}}
@extends('layouts.app')
{{-- Perspective Switching for Admins --}}
@if(Auth::user()->is_admin)
    <div class="mb-6">
        @if(session('viewing_as') === 'teacher')
            {{-- Admin viewing as teacher --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-2 ml-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-800">عرض منظور المعلم</h3>
                        <p class="text-blue-600 text-sm">أنت تشاهد المنصة كما يراها المعلمون</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('switch.admin') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            العودة للإدارة
                        </button>
                    </form>
                </div>
            </div>
        @else
            {{-- Admin in normal mode - show teacher view option --}}
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-gray-100 rounded-full p-2 ml-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">منظور الإدارة</h3>
                        <p class="text-gray-600 text-sm">يمكنك تجربة منظور المعلم لفهم تجربة المستخدم</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('switch.teacher') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            عرض كمعلم
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endif
@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->is_admin)
                {{-- Admin-specific welcome with dark background --}}
                <div class="bg-black/60 backdrop-blur-lg rounded-3xl p-8 mb-8 border border-white/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-5xl font-bold text-white mb-2">🛡️ لوحة تحكم المسؤول</h1>
                            <p class="text-xl text-gray-300">مرحباً {{ Auth::user()->name }} - لديك صلاحيات كاملة</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-400 mb-1">آخر دخول</div>
                            <div class="text-lg text-gray-300">{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'أول مرة' }}</div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Regular welcome header for teachers and students --}}
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-white mb-2">🎮 مرحباً {{ Auth::user()->name }}!</h1>
                    @if(Auth::user()->is_admin || Auth::user()->user_type === 'teacher')
                        <p class="text-xl text-gray-300">جاهز لتحدي جديد في عالم جُذور التعليمي؟</p>
                    @else
                        <p class="text-xl text-gray-300">جاهز للتعلم والنمو مع جُذور؟</p>
                    @endif
                </div>
            @endif

            {{-- Include role-specific dashboard --}}
            @if(Auth::user()->is_admin)
    @include('dashboard.admin')
    {{-- Also show teacher features for admins --}}
@endif

@if(Auth::user()->is_admin || Auth::user()->user_type === 'teacher')
    @include('dashboard.teacher')
@elseif(Auth::user()->user_type === 'student')
    @include('dashboard.student')
@endif
        </div>
    </div>
</div>
@endsection