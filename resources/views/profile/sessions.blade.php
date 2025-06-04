@extends('layouts.app')

@section('title', 'الجلسات النشطة')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">الجلسات النشطة</h2>
                
                <div class="space-y-4">
                    @forelse($sessions as $session)
                        <div class="border rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <div class="font-medium">
                                    <i class="fas fa-desktop ml-2"></i>
                                    {{ $session->browser ?? 'متصفح غير معروف' }} - {{ $session->platform ?? 'نظام غير معروف' }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $session->ip_address ?? 'IP غير معروف' }}
                                    • 
                                    {{ $session->logged_in_at ? $session->logged_in_at->diffForHumans() : 'وقت غير معروف' }}
                                </div>
                            </div>
                            
                            @if($session->id == session('login_record_id'))
                                <span class="text-green-600 text-sm font-medium">
                                    <i class="fas fa-check-circle ml-1"></i>
                                    الجلسة الحالية
                                </span>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-600 text-center py-8">لا توجد جلسات نشطة</p>
                    @endforelse
                </div>
                
                <div class="mt-6">
                    <a href="{{ route('profile.edit') }}" class="text-purple-600 hover:text-purple-700">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة إلى الملف الشخصي
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection