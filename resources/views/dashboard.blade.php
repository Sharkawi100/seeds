{{-- Update resources/views/dashboard.blade.php --}}
@extends('layouts.app')

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
                    @if(Auth::user()->user_type === 'teacher')
                        <p class="text-xl text-gray-300">جاهز لتحدي جديد في عالم جُذور التعليمي؟</p>
                    @else
                        <p class="text-xl text-gray-300">جاهز للتعلم والنمو مع جُذور؟</p>
                    @endif
                </div>
            @endif

            {{-- Include role-specific dashboard --}}
            @if(Auth::user()->is_admin)
                @include('dashboard.admin')
            @elseif(Auth::user()->user_type === 'teacher')
                @include('dashboard.teacher')
            @else
                @include('dashboard.student')
            @endif
        </div>
    </div>
</div>
@endsection