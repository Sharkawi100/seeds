{{-- File: resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<style>
    body, * {
        font-family: 'Tajawal', sans-serif !important;
    }
    
    .admin-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 0 0 2rem 2rem;
        margin: -2rem -2rem 2rem -2rem;
    }
    
    .user-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #667eea;
    }
    
    .status-toggle {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .status-toggle:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .role-select {
        transition: all 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .role-select:hover:not(:disabled) {
        background-color: #f3f4f6;
    }
    
    .role-select:focus {
        outline: none;
        ring: 2px;
        ring-color: #667eea;
    }
    
    .action-btn {
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
    }
    
    .filter-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.5rem;
    }
    
    .stat-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }
</style>
@endpush

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
            <div class="p-8">
                {{-- Header --}}
                <div class="admin-header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">إدارة المستخدمين</h1>
                            <p class="text-purple-100">قم بإدارة جميع المستخدمين والصلاحيات من هنا</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.users.export') }}" 
                               class="bg-white/20 backdrop-blur hover:bg-white/30 text-white font-semibold py-2.5 px-5 rounded-xl transition flex items-center gap-2">
                                <i class="fas fa-download"></i>
                                تصدير CSV
                            </a>
                            <a href="{{ route('admin.users.create') }}" 
                               class="bg-white text-purple-600 hover:bg-purple-50 font-semibold py-2.5 px-5 rounded-xl transition flex items-center gap-2">
                                <i class="fas fa-plus-circle"></i>
                                إضافة مستخدم
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center">
                        <i class="fas fa-check-circle ml-3"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session()->has('impersonator'))
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="fas fa-user-secret ml-3"></i>
                            أنت تتصفح كـ {{ auth()->user()->name }}
                        </div>
                        <a href="{{ route('admin.stop-impersonation') }}" 
                           class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            العودة لحسابي
                        </a>
                    </div>
                @endif

                {{-- Filters --}}
                <div class="filter-card mb-8">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[250px]">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="البحث بالاسم أو البريد..."
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                                <i class="fas fa-search absolute right-3 top-3.5 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <select name="role" class="rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 px-4 py-3">
                            <option value="">جميع الأدوار</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>المدراء</option>
                            <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>المعلمين</option>
                            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>الطلاب</option>
                        </select>
                        
                        <select name="status" class="rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500 px-4 py-3">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطل</option>
                        </select>
                        
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2">
                            <i class="fas fa-filter"></i>
                            تطبيق
                        </button>
                        
                        @if(request()->hasAny(['search', 'role', 'status']))
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-medium transition flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                إلغاء
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Users Grid --}}
                <div class="grid gap-4">
                    @foreach($users as $user)
                    <div class="user-card bg-white rounded-xl p-6 flex items-center justify-between">
                        {{-- User Info --}}
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $user->name }}</h3>
                                <p class="text-gray-500">{{ $user->email }}</p>
                                <div class="flex items-center gap-3 mt-1">
                                    @if($user->google_id)
                                        <span class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">
                                            <i class="fab fa-google"></i> Google
                                        </span>
                                    @endif
                                                                        @if($user->school_name)
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-school ml-1"></i> {{ $user->school_name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Stats & Actions --}}
                        <div class="flex items-center gap-6">
                            {{-- Stats --}}
                            <div class="text-center">
                                <div class="stat-badge mb-1">{{ $user->quizzes_count }}</div>
                                <div class="text-xs text-gray-500">اختبارات</div>
                            </div>
                            
                            <div class="text-center">
                                <div class="stat-badge mb-1">{{ $user->results_count }}</div>
                                <div class="text-xs text-gray-500">نتائج</div>
                            </div>

                            {{-- Role --}}
                            <div class="text-center min-w-[120px]">
                                <select class="role-select w-full {{ $user->id === auth()->id() ? 'bg-gray-100' : '' }}" 
                                        data-user-id="{{ $user->id }}"
                                        data-current-type="{{ $user->user_type }}"
                                        data-is-admin="{{ $user->is_admin ? '1' : '0' }}"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="student" {{ $user->user_type == 'student' && !$user->is_admin ? 'selected' : '' }}>طالب</option>
                                    <option value="teacher" {{ $user->user_type == 'teacher' && !$user->is_admin ? 'selected' : '' }}>معلم</option>
                                    <option value="admin" {{ $user->is_admin ? 'selected' : '' }}>مدير</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="text-center">
                                <button class="status-toggle relative inline-flex h-8 w-14 items-center rounded-full transition-colors"
                                        data-user-id="{{ $user->id }}"
                                        data-active="{{ $user->is_active ? '1' : '0' }}"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                        style="background-color: {{ $user->is_active ? '#10b981' : '#ef4444' }}">
                                    <span class="inline-block h-6 w-6 transform rounded-full bg-white shadow-lg transition-transform"
                                          style="transform: translateX({{ $user->is_active ? '28px' : '4px' }})"></span>
                                </button>
                                <div class="text-xs mt-1 font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $user->is_active ? 'نشط' : 'معطل' }}
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="action-btn bg-green-100 text-green-600 hover:bg-green-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($user->canBeImpersonated())
                                    <a href="{{ route('admin.users.impersonate', $user) }}" 
                                       class="action-btn bg-purple-100 text-purple-600 hover:bg-purple-200"
                                       onclick="return confirm('الدخول كـ {{ $user->name }}؟')">
                                        <i class="fas fa-user-secret"></i>
                                    </a>
                                @endif
                                
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="action-btn bg-red-100 text-red-600 hover:bg-red-200"
                                                onclick="return confirm('حذف هذا المستخدم؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Status Toggle
document.querySelectorAll('.status-toggle').forEach(toggle => {
    toggle.addEventListener('click', async function() {
        if (this.disabled) return;
        
        const userId = this.dataset.userId;
        const isActive = this.dataset.active === '1';
        
        try {
            const response = await fetch(`{{ url('/admin/users') }}/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.dataset.active = data.is_active ? '1' : '0';
                this.style.backgroundColor = data.is_active ? '#10b981' : '#ef4444';
                this.querySelector('span').style.transform = `translateX(${data.is_active ? '28px' : '4px'})`;
                
                const statusText = this.nextElementSibling;
                statusText.textContent = data.is_active ? 'نشط' : 'معطل';
                statusText.className = `text-xs mt-1 font-medium ${data.is_active ? 'text-green-600' : 'text-red-600'}`;
                
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'حدث خطأ', 'error');
            }
        } catch (error) {
            console.error('Status toggle error:', error);
            showNotification('حدث خطأ في الاتصال', 'error');
        }
    });
});

// Role Update
document.querySelectorAll('.role-select').forEach(select => {
    select.addEventListener('change', async function() {
        if (this.disabled) return;
        
        const userId = this.dataset.userId;
        const newRole = this.value;
        const isAdmin = newRole === 'admin';
        const userType = newRole === 'admin' ? this.dataset.currentType : newRole;
        
        try {
            const response = await fetch(`{{ url('/admin/users') }}/${userId}/update-role`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_type: userType,
                    is_admin: isAdmin
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.dataset.currentType = userType;
                this.dataset.isAdmin = isAdmin ? '1' : '0';
                showNotification(data.message, 'success');
            } else {
                // Revert to previous value
                this.value = this.dataset.isAdmin === '1' ? 'admin' : this.dataset.currentType;
                showNotification(data.error || 'حدث خطأ', 'error');
            }
        } catch (error) {
            console.error('Role update error:', error);
            // Revert to previous value
            this.value = this.dataset.isAdmin === '1' ? 'admin' : this.dataset.currentType;
            showNotification('حدث خطأ في الاتصال', 'error');
        }
    });
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 left-4 px-6 py-3 rounded-xl shadow-lg font-medium z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => notification.classList.add('opacity-100'), 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endpush
@endsection