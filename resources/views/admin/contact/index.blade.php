@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Title & Description -->
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                            رسائل التواصل
                        </h1>
                    </div>
                    <p class="text-gray-600 text-lg">إدارة استفسارات المستخدمين والرد عليها بكفاءة</p>
                </div>

                <!-- Quick Stats -->
                <div class="flex gap-4">
                    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-2xl px-6 py-4 min-w-[120px]">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-500 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-red-700">{{ $messages->where('is_read', false)->count() }}</div>
                                <div class="text-xs text-red-600 font-medium">غير مقروءة</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl px-6 py-4 min-w-[120px]">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-blue-700">{{ $messages->count() }}</div>
                                <div class="text-xs text-blue-600 font-medium">إجمالي الرسائل</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-2xl px-6 py-4 min-w-[120px]">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-600 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                @php
                                    $cancellationCount = $messages->filter(function($msg) {
                                        return $msg->isCancellationMessage();
                                    })->count();
                                @endphp
                                <div class="text-2xl font-bold text-red-700">{{ $cancellationCount }}</div>
                                <div class="text-xs text-red-600 font-medium">إلغاء اشتراك</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Advanced Filters & Search -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                    </svg>
                    البحث والتصفية
                </h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Enhanced Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">البحث في الرسائل</label>
                        <div class="relative">
                            <input type="text" 
                                   id="searchMessages" 
                                   placeholder="ابحث بالاسم، البريد، الموضوع أو المحتوى..."
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 focus:bg-white">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                        <select id="categoryFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-gray-50 focus:bg-white transition-all duration-200">
                            <option value="">جميع الفئات</option>
                            <option value="1">دعم فني</option>
                            <option value="2">تسجيل معلم</option>
                            <option value="3">الاشتراكات</option>
                            <option value="4">ميزة جديدة</option>
                            <option value="5">شراكة</option>
                            <option value="6">أخرى</option>
                            <option value="cancellation">إلغاء اشتراك</option>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select id="statusFilter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-gray-50 focus:bg-white transition-all duration-200">
                            <option value="">جميع الحالات</option>
                            <option value="unread">غير مقروءة</option>
                            <option value="read">مقروءة</option>
                        </select>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button onclick="markAllAsRead()" class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        تحديد الكل كمقروء
                    </button>
                    
                    <button onclick="filterCancellations()" class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                        </svg>
                        إلغاءات الاشتراك فقط
                    </button>
                    
                    <button onclick="refreshMessages()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                        </svg>
                        تحديث
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages List -->
        @if($messages->isEmpty())
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-16 text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">لا توجد رسائل</h3>
                    <p class="text-gray-500 text-lg">لم يتم استلام أي رسائل حتى الآن</p>
                </div>
            </div>
        @else
            <div class="space-y-4" id="messagesList">
                @foreach($messages as $message)
                @php
                    $categories = [
                        1 => ['name' => 'دعم فني', 'color' => 'bg-blue-100 text-blue-800', 'icon' => '🔧'],
                        2 => ['name' => 'تسجيل معلم', 'color' => 'bg-green-100 text-green-800', 'icon' => '👨‍🏫'],
                        3 => ['name' => 'الاشتراكات', 'color' => 'bg-purple-100 text-purple-800', 'icon' => '💳'],
                        4 => ['name' => 'ميزة جديدة', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '✨'],
                        5 => ['name' => 'شراكة', 'color' => 'bg-indigo-100 text-indigo-800', 'icon' => '🤝'],
                        6 => ['name' => 'أخرى', 'color' => 'bg-gray-100 text-gray-800', 'icon' => '📝']
                    ];

                    if ($message->isCancellationMessage()) {
                        $category = ['name' => 'إلغاء اشتراك', 'color' => 'bg-red-100 text-red-800', 'icon' => '🚫'];
                        $dataCategory = 'cancellation';
                    } else {
                        $category = $categories[$message->category_id ?? 6] ?? $categories[6];
                        $dataCategory = $message->category_id ?? '';
                    }
                @endphp

                <div class="message-card bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 {{ !$message->is_read ? 'ring-2 ring-amber-200' : '' }}"
                     data-category="{{ $dataCategory }}"
                     data-status="{{ $message->is_read ? 'read' : 'unread' }}">
                    
                    <!-- Card Header -->
                    <div class="p-6 {{ !$message->is_read ? 'bg-gradient-to-r from-amber-50 to-orange-50' : 'bg-gray-50' }} rounded-t-2xl border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 space-x-reverse">
                                <!-- Avatar -->
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-lg">{{ mb_substr($message->name, 0, 1) }}</span>
                                </div>
                                
                                <!-- Message Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <!-- Category Badge -->
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $category['color'] }}">
                                            <span>{{ $category['icon'] }}</span>
                                            {{ $category['name'] }}
                                        </span>
                                        
                                        <!-- Unread Badge -->
                                        @if(!$message->is_read)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                                جديدة
                                            </span>
                                        @endif

                                        <!-- Cancellation Priority Badge -->
                                        @if($message->isCancellationMessage())
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-red-500 text-white">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                عاجل
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Sender Details -->
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-4 text-sm">
                                            <span class="font-semibold text-gray-900">{{ $message->name }}</span>
                                            <span class="text-gray-500">{{ $message->email }}</span>
                                        </div>
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message->created_at->format('Y/m/d H:i') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Subject -->
                                    @if($message->subject)
                                    <div class="mt-3">
                                        <h4 class="font-semibold text-gray-900 text-lg">{{ $message->subject }}</h4>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2">
                                @if(!$message->is_read)
                                    <form action="{{ route('admin.contact.mark-read', $message) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            تم القراءة
                                        </button>
                                    </form>
                                @endif
                                
                                <button onclick="toggleMessage({{ $message->id }})" 
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 message-toggle-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="message-toggle-text">عرض التفاصيل</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message Content (Hidden by default) -->
                    <div id="message-content-{{ $message->id }}" class="hidden">
                        <div class="p-6">
                            <!-- Subscription Cancellation Details -->
                            @if($message->isCancellationMessage() && $message->subscription)
                                <div class="bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-200 rounded-2xl p-6 mb-6">
                                    <div class="flex items-start gap-4">
                                        <div class="p-3 bg-red-500 rounded-xl">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h5 class="font-bold text-red-900 text-lg mb-4">تفاصيل إلغاء الاشتراك</h5>
                                            <div class="grid md:grid-cols-2 gap-4">
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-red-600 font-medium">الخطة:</span>
                                                        <span class="font-semibold text-red-800">{{ $message->subscription->plan_name ?? 'غير محددة' }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-red-600 font-medium">تاريخ الإلغاء:</span>
                                                        <span class="font-semibold text-red-800">{{ $message->subscription->cancelled_at?->format('Y-m-d H:i') ?? 'غير محدد' }}</span>
                                                    </div>
                                                </div>
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-red-600 font-medium">تاريخ البدء:</span>
                                                        <span class="font-semibold text-red-800">{{ $message->subscription->current_period_start->format('Y-m-d') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-red-600 font-medium">تاريخ الانتهاء:</span>
                                                        <span class="font-semibold text-red-800">{{ $message->subscription->current_period_end->format('Y-m-d') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Message Content -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 mb-6">
                                <h6 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message->isCancellationMessage() ? 'سبب الإلغاء:' : 'محتوى الرسالة:' }}
                                </h6>
                                <div class="prose prose-gray max-w-none">
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap text-lg">{{ $message->message }}</p>
                                </div>
                            </div>
                            
                            <!-- Response Actions -->
                            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-200">
                                <a href="mailto:{{ $message->email }}?subject=رد على: {{ $message->subject ?? 'استفسارك' }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    رد بالبريد
                                </a>
                                
                                <button onclick="copyEmail('{{ $message->email }}')"
                                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path>
                                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path>
                                    </svg>
                                    نسخ البريد
                                </button>

                                @if($message->isCancellationMessage())
                                    <button onclick="showRetentionTips('{{ $message->email }}')"
                                            class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                        محاولة الاستبقاء
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchMessages');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const messageCards = document.querySelectorAll('.message-card');
    
    // Enhanced search and filter functionality
    function filterMessages() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const selectedStatus = statusFilter.value;
        
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const cardText = card.textContent.toLowerCase();
            const cardCategory = card.getAttribute('data-category');
            const cardStatus = card.getAttribute('data-status');
            
            const matchesSearch = !searchTerm || cardText.includes(searchTerm);
            const matchesCategory = !selectedCategory || cardCategory === selectedCategory;
            const matchesStatus = !selectedStatus || cardStatus === selectedStatus;
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                card.style.display = 'block';
                card.style.animation = 'fadeInUp 0.3s ease-out';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show no results message if needed
        updateNoResultsMessage(visibleCount);
    }
    
    function updateNoResultsMessage(count) {
        const messagesList = document.getElementById('messagesList');
        let noResultsMsg = document.getElementById('noResultsMessage');
        
        if (count === 0 && messageCards.length > 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'noResultsMessage';
                noResultsMsg.className = 'bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center';
                noResultsMsg.innerHTML = `
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد رسائل مطابقة</h3>
                    <p class="text-gray-500">جرب تغيير معايير البحث أو المرشحات</p>
                `;
                messagesList.appendChild(noResultsMsg);
            }
            noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }
    
    // Debounced search for better performance
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterMessages, 300);
    });
    
    categoryFilter.addEventListener('change', filterMessages);
    statusFilter.addEventListener('change', filterMessages);
});

// Enhanced toggle message functionality
function toggleMessage(messageId) {
    const content = document.getElementById('message-content-' + messageId);
    const card = content.closest('.message-card');
    const icon = card.querySelector('.message-toggle-icon');
    const text = card.querySelector('.message-toggle-text');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        content.style.animation = 'slideDown 0.3s ease-out';
        icon.innerHTML = `<path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878a3 3 0 00-3-3m7.071 7.071L21 21m-2.929-2.929L15.657 15.657m0 0a9.97 9.97 0 01-3.827-.873L9.878 9.878"></path>`;
        text.textContent = 'إخفاء التفاصيل';
        
        // Smooth scroll to content
        content.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        content.classList.add('hidden');
        icon.innerHTML = `<path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>`;
        text.textContent = 'عرض التفاصيل';
    }
}

// Enhanced copy email functionality
async function copyEmail(email) {
    try {
        await navigator.clipboard.writeText(email);
        showToast('تم نسخ البريد الإلكتروني بنجاح', 'success');
    } catch (err) {
        showToast('فشل في نسخ البريد الإلكتروني', 'error');
    }
}

// Enhanced toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-6 right-6 px-6 py-4 rounded-2xl shadow-2xl z-50 transition-all duration-300 transform translate-x-full backdrop-blur-sm ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    const icons = {
        success: '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>',
        error: '<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>',
        info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>'
    };
    
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                ${icons[type]}
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Quick action functions
function markAllAsRead() {
    showToast('جاري تحديث جميع الرسائل...', 'info');
    // Implementation would go here
}

function filterCancellations() {
    document.getElementById('categoryFilter').value = 'cancellation';
    document.getElementById('categoryFilter').dispatchEvent(new Event('change'));
    showToast('تم تصفية رسائل إلغاء الاشتراك', 'info');
}

function refreshMessages() {
    showToast('جاري تحديث الرسائل...', 'info');
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

function showRetentionTips(email) {
    const tips = `
مرحباً،

نأسف لعلمنا برغبتك في إلغاء الاشتراك. نود أن نقدم لك بعض الحلول:

• دعم فني مجاني لحل أي مشاكل
• تدريب مخصص على استخدام المميزات
• خصم خاص 50% للشهر القادم
• إمكانية تجميد الاشتراك بدلاً من الإلغاء

هل يمكننا ترتيب مكالمة سريعة لمناقشة احتياجاتك؟

مع التقدير،
فريق جُذور
`;

    const mailtoLink = `mailto:${email}?subject=عرض خاص - لا تلغي اشتراكك&body=${encodeURIComponent(tips)}`;
    window.location.href = mailtoLink;
}

// Auto-refresh functionality
let autoRefreshInterval;

function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        // Check for new messages via AJAX
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                const newUnreadCount = newDoc.querySelectorAll('[data-status="unread"]').length;
                const currentUnreadCount = document.querySelectorAll('[data-status="unread"]').length;
                
                if (newUnreadCount > currentUnreadCount) {
                    showToast('رسائل جديدة متاحة', 'info');
                }
            })
            .catch(err => console.log('Auto-refresh failed:', err));
    }, 30000); // Check every 30 seconds
}

// Start auto-refresh when page loads
document.addEventListener('DOMContentLoaded', startAutoRefresh);

// Stop auto-refresh when page is hidden (performance optimization)
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        clearInterval(autoRefreshInterval);
    } else {
        startAutoRefresh();
    }
});
</script>

<!-- Enhanced CSS -->
<style>
/* Smooth animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 1000px;
    }
}

.message-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.message-card:hover {
    transform: translateY(-4px);
}

/* Custom scrollbar */
.message-card::-webkit-scrollbar {
    width: 6px;
}

.message-card::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.message-card::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.message-card::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Focus states for accessibility */
button:focus,
input:focus,
select:focus {
    outline: none;
    ring: 2px;
    ring-color: #3b82f6;
    ring-opacity: 0.5;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .message-card {
        margin: 0 -1rem;
        border-radius: 1rem;
    }
    
    .grid {
        grid-template-columns: 1fr;
    }
}

/* Print styles */
@media print {
    .message-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #e5e7eb;
    }
}
</style>
@endsection