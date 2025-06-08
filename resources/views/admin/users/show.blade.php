{{-- File: resources/views/admin/users/show.blade.php --}}
@extends('layouts.app')

@section('content')
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<style>
    body, * {
        font-family: 'Tajawal', sans-serif !important;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }
    
    .info-row {
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .section-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 1.5rem;
    }
    
    .action-button {
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.2s ease;
        text-align: center;
        display: inline-block;
    }
    
    .action-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
@endpush

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-4">
            <a href="{{ route('admin.users.index') }}" class="text-purple-600 hover:text-purple-800 font-medium flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                العودة للقائمة
            </a>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- User Profile Card --}}
            <div class="lg:col-span-1">
                <div class="section-card">
                    {{-- Profile Header --}}
                    <div class="profile-header -m-6 mb-6">
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-white text-4xl font-bold shadow-xl">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                            <h2 class="text-2xl font-bold mt-4">{{ $user->name }}</h2>
                            <p class="text-purple-100">{{ $user->email }}</p>
                        </div>
                    </div>

                    {{-- User Status --}}
                    <div class="text-center mb-6">
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                                <i class="fas fa-check-circle"></i> حساب نشط
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-semibold">
                                <i class="fas fa-times-circle"></i> حساب معطل
                            </span>
                        @endif
                    </div>

                    {{-- Basic Info --}}
                    <div class="space-y-0">
                        <div class="info-row">
                            <span class="text-gray-600">النوع:</span>
                            <span class="font-semibold flex items-center gap-2">
                                {{ $user->role_display }}
                                @if($user->is_admin)
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Admin</span>
                                @endif
                            </span>
                        </div>
                        
                        @if($user->school_name)
                            <div class="info-row">
                                <span class="text-gray-600">المدرسة:</span>
                                <span class="font-semibold">{{ $user->school_name }}</span>
                            </div>
                        @endif
                        
                        @if($user->grade_level)
                            <div class="info-row">
                                <span class="text-gray-600">الصف:</span>
                                <span class="font-semibold">الصف {{ $user->grade_level }}</span>
                            </div>
                        @endif
                        
                        <div class="info-row">
                            <span class="text-gray-600">تاريخ التسجيل:</span>
                            <span class="font-semibold">{{ $user->created_at->format('Y/m/d') }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="text-gray-600">آخر دخول:</span>
                            <span class="font-semibold">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'لم يسجل دخول' }}
                            </span>
                        </div>
                        
                        {{-- Social Accounts --}}
                        @if($user->google_id || $user->facebook_id)
                            <div class="info-row">
                                <span class="text-gray-600">حسابات مرتبطة:</span>
                                <div class="flex gap-2">
                                    @if($user->google_id)
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">
                                            <i class="fab fa-google"></i> Google
                                        </span>
                                    @endif
                                    @if($user->facebook_id)
                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                            <i class="fab fa-facebook"></i> Facebook
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="action-button block w-full bg-blue-500 hover:bg-blue-600 text-white">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل المستخدم
                        </a>
                        
                        @if($user->canBeImpersonated())
                            <a href="{{ route('admin.users.impersonate', $user) }}" 
                               class="action-button block w-full bg-purple-500 hover:bg-purple-600 text-white"
                               onclick="return confirm('الدخول كـ {{ $user->name }}؟')">
                                <i class="fas fa-user-secret ml-2"></i>
                                الدخول كهذا المستخدم
                            </a>
                        @endif
                        
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="action-button w-full bg-red-500 hover:bg-red-600 text-white"
                                        onclick="return confirm('حذف هذا المستخدم؟')">
                                    <i class="fas fa-trash ml-2"></i>
                                    حذف المستخدم
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- User Activity --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Statistics Cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="stat-card">
                        <div class="text-3xl font-bold text-blue-600 mb-1">{{ $user->quizzes_count }}</div>
                        <div class="text-sm text-gray-600">اختبار تم إنشاؤه</div>
                    </div>
                    <div class="stat-card">
                        <div class="text-3xl font-bold text-green-600 mb-1">{{ $user->results_count }}</div>
                        <div class="text-sm text-gray-600">اختبار مكتمل</div>
                    </div>
                    <div class="stat-card">
                        <div class="text-3xl font-bold text-purple-600 mb-1">
                            {{ $user->results->count() > 0 ? number_format($user->results->avg('total_score'), 1) : 0 }}%
                        </div>
                        <div class="text-sm text-gray-600">متوسط النتائج</div>
                    </div>
                    <div class="stat-card">
                        <div class="text-3xl font-bold text-orange-600 mb-1">
                            {{ $user->quizzes->sum(function($quiz) { return $quiz->questions->count(); }) }}
                        </div>
                        <div class="text-sm text-gray-600">سؤال تم إنشاؤه</div>
                    </div>
                </div>

                {{-- Recent Quizzes --}}
                @if($user->user_type === 'teacher' || $user->is_admin)
                    <div class="section-card">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-file-alt text-purple-500"></i>
                            آخر الاختبارات المنشأة
                        </h3>
                        @if($recentQuizzes->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentQuizzes as $quiz)
                                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div>
                                            <h4 class="font-semibold">{{ $quiz->title }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $quiz->subject }} - الصف {{ $quiz->grade_level }}
                                                <span class="mr-2">• {{ $quiz->questions->count() }} سؤال</span>
                                            </p>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm text-gray-500">{{ $quiz->created_at->diffForHumans() }}</p>
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-xs font-medium">{{ $quiz->pin }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">لم يتم إنشاء أي اختبارات بعد</p>
                        @endif
                    </div>
                @endif

               {{-- Recent Results --}}
<div class="section-card">
    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-chart-line text-green-500"></i>
        آخر النتائج
    </h3>
    @if($recentResults->count() > 0)
        <div class="space-y-3">
            @foreach($recentResults as $result)
                @if($result->quiz) {{-- Check if quiz exists --}}
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div>
                            <h4 class="font-semibold">{{ $result->quiz->title }}</h4>
                            <p class="text-sm">
                                النتيجة: 
                                <span class="font-bold {{ $result->total_score >= 80 ? 'text-green-600' : ($result->total_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($result->total_score, 1) }}%
                                </span>
                            </p>
                        </div>
                        <div class="text-left">
                            <p class="text-sm text-gray-500">{{ $result->created_at->diffForHumans() }}</p>
                            <a href="{{ route('results.show', $result) }}" 
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center py-8">لم يتم إكمال أي اختبارات بعد</p>
    @endif
</div>
            </div>
        </div>
    </div>
</div>
@endsection