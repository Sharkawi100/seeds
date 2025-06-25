@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">رسائل التواصل</h1>
                    <p class="text-gray-600 mt-1">إدارة استفسارات المستخدمين والرد عليها</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg px-4 py-2 border border-gray-200">
                        <div class="text-xs text-gray-500">غير مقروءة</div>
                        <div class="text-lg font-semibold text-red-600">{{ $messages->where('is_read', false)->count() }}</div>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 border border-gray-200">
                        <div class="text-xs text-gray-500">المجموع</div>
                        <div class="text-lg font-semibold text-gray-900">{{ $messages->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-lg border border-gray-200 mb-6 p-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               id="searchMessages" 
                               placeholder="البحث في الرسائل..."
                               class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="sm:w-48">
                    <select id="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200">
                        <option value="">جميع الفئات</option>
                        <option value="1">دعم فني</option>
                        <option value="2">تسجيل معلم</option>
                        <option value="3">الاشتراكات</option>
                        <option value="4">ميزة جديدة</option>
                        <option value="5">شراكة</option>
                        <option value="6">أخرى</option>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div class="sm:w-32">
                    <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-1 focus:ring-emerald-200">
                        <option value="">الكل</option>
                        <option value="unread">غير مقروءة</option>
                        <option value="read">مقروءة</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Messages List -->
        @if($messages->isEmpty())
            <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد رسائل</h3>
                <p class="text-gray-500">لم يتم استلام أي رسائل حتى الآن</p>
            </div>
        @else
            <div class="space-y-3" id="messagesList">
                @foreach($messages as $message)
                <div class="message-card bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 {{ !$message->is_read ? 'border-r-4 border-r-amber-500' : '' }}"
                     data-category="{{ $message->category_id ?? '' }}"
                     data-status="{{ $message->is_read ? 'read' : 'unread' }}">
                    
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <!-- Category Badge -->
                                    @php
                                    $categories = [
                                        1 => ['name' => 'دعم فني', 'color' => 'bg-blue-100 text-blue-800'],
                                        2 => ['name' => 'تسجيل معلم', 'color' => 'bg-green-100 text-green-800'],
                                        3 => ['name' => 'الاشتراكات', 'color' => 'bg-purple-100 text-purple-800'],
                                        4 => ['name' => 'ميزة جديدة', 'color' => 'bg-yellow-100 text-yellow-800'],
                                        5 => ['name' => 'شراكة', 'color' => 'bg-indigo-100 text-indigo-800'],
                                        6 => ['name' => 'أخرى', 'color' => 'bg-gray-100 text-gray-800']
                                    ];
                                    $category = $categories[$message->category_id ?? 6] ?? $categories[6];
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category['color'] }}">
                                        {{ $category['name'] }}
                                    </span>
                                    
                                    @if(!$message->is_read)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full ml-1"></span>
                                            جديدة
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Sender Info -->
                                <div class="flex items-center gap-4 text-sm">
                                    <div class="font-semibold text-gray-900">{{ $message->name }}</div>
                                    <div class="text-gray-500">{{ $message->email }}</div>
                                    <div class="text-gray-400">{{ $message->created_at->format('Y/m/d H:i') }}</div>
                                </div>
                                
                                <!-- Subject -->
                                @if($message->subject)
                                <div class="font-medium text-gray-900 mt-2">{{ $message->subject }}</div>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                @if(!$message->is_read)
                                    <form action="{{ route('admin.contact.mark-read', $message) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-md hover:bg-emerald-100 transition-colors">
                                            <i class="fas fa-check ml-1"></i>
                                            تم القراءة
                                        </button>
                                    </form>
                                @endif
                                
                                <button onclick="toggleMessage({{ $message->id }})" 
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-eye message-toggle-icon"></i>
                                    <span class="message-toggle-text mr-1">عرض</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Message Content (Hidden by default) -->
                        <div id="message-content-{{ $message->id }}" class="hidden">
                            <div class="border-t border-gray-100 pt-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $message->message }}</p>
                                </div>
                                
                                <!-- Response Actions -->
                                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100">
                                    <a href="mailto:{{ $message->email }}?subject=رد على: {{ $message->subject ?? 'استفسارك' }}" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-700 border border-transparent rounded-md hover:bg-amber-800 transition-colors">
                                        <i class="fas fa-reply ml-2"></i>
                                        رد بالبريد
                                    </a>
                                    
                                    <button onclick="copyEmail('{{ $message->email }}')"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-copy ml-2"></i>
                                        نسخ البريد
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchMessages');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const messageCards = document.querySelectorAll('.message-card');
    
    // Search and Filter functionality
    function filterMessages() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const selectedStatus = statusFilter.value;
        
        messageCards.forEach(card => {
            const cardText = card.textContent.toLowerCase();
            const cardCategory = card.getAttribute('data-category');
            const cardStatus = card.getAttribute('data-status');
            
            const matchesSearch = cardText.includes(searchTerm);
            const matchesCategory = !selectedCategory || cardCategory === selectedCategory;
            const matchesStatus = !selectedStatus || cardStatus === selectedStatus;
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterMessages);
    categoryFilter.addEventListener('change', filterMessages);
    statusFilter.addEventListener('change', filterMessages);
});

// Toggle message content
function toggleMessage(messageId) {
    const content = document.getElementById('message-content-' + messageId);
    const icon = content.parentElement.querySelector('.message-toggle-icon');
    const text = content.parentElement.querySelector('.message-toggle-text');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.className = 'fas fa-eye-slash message-toggle-icon';
        text.textContent = 'إخفاء';
    } else {
        content.classList.add('hidden');
        icon.className = 'fas fa-eye message-toggle-icon';
        text.textContent = 'عرض';
    }
}

// Copy email to clipboard
function copyEmail(email) {
    navigator.clipboard.writeText(email).then(() => {
        // Show toast notification
        showToast('تم نسخ البريد الإلكتروني', 'success');
    }).catch(() => {
        showToast('فشل في نسخ البريد الإلكتروني', 'error');
    });
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-600 text-white' :
        type === 'error' ? 'bg-red-600 text-white' :
        'bg-blue-600 text-white'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-times' : 'fa-info'} ml-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Auto-refresh for new messages (every 30 seconds)
setInterval(() => {
    const unreadCount = document.querySelectorAll('[data-status="unread"]').length;
    // You could implement AJAX refresh here if needed
}, 30000);
</script>

<style>
/* Smooth animations */
.message-card {
    transition: all 0.2s ease;
}

.message-card:hover {
    transform: translateY(-1px);
}

/* Custom scrollbar for message content */
.message-card {
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb #f9fafb;
}

.message-card::-webkit-scrollbar {
    width: 6px;
}

.message-card::-webkit-scrollbar-track {
    background: #f9fafb;
    border-radius: 3px;
}

.message-card::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 3px;
}

.message-card::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}
</style>
@endsection