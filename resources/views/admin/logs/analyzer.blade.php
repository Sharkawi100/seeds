@extends('layouts.app')

@section('title', 'محلل سجلات الأخطاء')

@push('styles')
<style>
    .error-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .error-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .error-critical { border-left-color: #ef4444; }
    .error-high { border-left-color: #f59e0b; }
    .error-medium { border-left-color: #3b82f6; }
    .error-low { border-left-color: #10b981; }
    
    .recommendation-card {
        border-left: 4px solid #6366f1;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .code-block {
        background: #1e293b;
        color: #e2e8f0;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-bug text-red-500 ml-2"></i>
                        محلل سجلات الأخطاء
                    </h1>
                    <p class="text-gray-600">تحليل ذكي لأخطاء النظام والحلول المقترحة</p>
                </div>
                
                <div class="flex gap-3">
                    <form method="GET" class="flex gap-2">
                        <select name="hours" class="rounded-lg border-gray-300 text-sm">
                            <option value="1" {{ request('hours') == 1 ? 'selected' : '' }}>آخر ساعة</option>
                            <option value="6" {{ request('hours') == 6 ? 'selected' : '' }}>آخر 6 ساعات</option>
                            <option value="24" {{ request('hours', 24) == 24 ? 'selected' : '' }}>آخر 24 ساعة</option>
                            <option value="168" {{ request('hours') == 168 ? 'selected' : '' }}>آخر أسبوع</option>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-sm">
                            تحديث
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.logs.download') }}" 
                       class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 text-sm inline-flex items-center">
                        <i class="fas fa-download ml-2"></i>
                        تحميل السجل
                    </a>
                    
                    <form method="POST" action="{{ route('admin.logs.clear') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('هل أنت متأكد من مسح السجل؟ سيتم حفظ نسخة احتياطية.')"
                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm inline-flex items-center">
                            <i class="fas fa-trash ml-2"></i>
                            مسح السجل
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">إجمالي الأخطاء</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_errors']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-fire text-orange-600 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">أخطاء حرجة</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['critical_count'] + $summary['error_count']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">آخر خطأ</p>
                        <p class="text-lg font-bold text-gray-900">
                            @if($summary['last_error'])
                                {{ $summary['last_error']->diffForHumans() }}
                            @else
                                لا يوجد
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-file-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm text-gray-600">حجم ملف السجل</p>
                        <p class="text-lg font-bold text-gray-900">{{ $summary['file_size_mb'] }} ميجا</p>
                    </div>
                </div>
            </div>
        </div>

        @if(empty($errors) && empty($recommendations))
            <!-- No Errors -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">ممتاز! لا توجد أخطاء</h3>
                <p class="text-gray-600">النظام يعمل بشكل طبيعي خلال الفترة المحددة</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recommendations -->
                @if(!empty($recommendations))
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-500 ml-2"></i>
                            حلول مقترحة
                        </h2>
                        
                        <div class="space-y-4">
                            @foreach($recommendations as $rec)
                            <div class="recommendation-card rounded-lg p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ $rec['title'] }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($rec['priority'] === 'critical') bg-red-100 text-red-800
                                        @elseif($rec['priority'] === 'high') bg-orange-100 text-orange-800
                                        @elseif($rec['priority'] === 'medium') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $rec['count'] }} مرة
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm mb-3">{{ $rec['description'] }}</p>
                                <div class="bg-gray-50 rounded p-3">
                                    <p class="text-sm font-medium text-gray-700">💡 الحل:</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $rec['solution'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Error Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-list text-blue-500 ml-2"></i>
                            تفاصيل الأخطاء الأكثر تكراراً
                        </h2>
                        
                        <div class="space-y-4">
                            @forelse($errors as $errorGroup)
                            @php 
                                $error = $errorGroup['sample_error'];
                                $priorityClass = 'error-medium';
                                if($errorGroup['count'] > 50) $priorityClass = 'error-critical';
                                elseif($errorGroup['count'] > 20) $priorityClass = 'error-high';
                                elseif($errorGroup['count'] > 5) $priorityClass = 'error-medium';
                                else $priorityClass = 'error-low';
                            @endphp
                            
                            <div class="error-card {{ $priorityClass }} bg-white rounded-lg p-4 border border-gray-200">
                                <!-- Error Header -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                @if($error['level'] === 'CRITICAL' || $error['level'] === 'ERROR') bg-red-100 text-red-800
                                                @elseif($error['level'] === 'WARNING') bg-yellow-100 text-yellow-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ $error['level'] }}
                                            </span>
                                            <span class="mr-2 text-sm text-gray-500">{{ $error['exception_class'] }}</span>
                                        </div>
                                        <h3 class="font-semibold text-gray-900 text-sm leading-relaxed">
                                            {{ Str::limit($error['message'], 120) }}
                                        </h3>
                                    </div>
                                    <div class="text-left mr-4">
                                        <div class="text-2xl font-bold text-gray-900">{{ $errorGroup['count'] }}</div>
                                        <div class="text-xs text-gray-500">مرة</div>
                                    </div>
                                </div>

                                <!-- Error Meta -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3 text-sm">
                                    <div>
                                        <span class="text-gray-500">أول ظهور:</span>
                                        <div class="font-medium">{{ $errorGroup['first_seen']->format('H:i') }}</div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">آخر ظهور:</span>
                                        <div class="font-medium">{{ $errorGroup['last_seen']->diffForHumans() }}</div>
                                    </div>
                                    @if($error['file'])
                                    <div>
                                        <span class="text-gray-500">الملف:</span>
                                        <div class="font-medium">{{ $error['file'] }}:{{ $error['line_number'] }}</div>
                                    </div>
                                    @endif
                                    @if(!empty($errorGroup['affected_controllers']))
                                    <div>
                                        <span class="text-gray-500">Controller:</span>
                                        <div class="font-medium">{{ implode(', ', array_slice($errorGroup['affected_controllers'], 0, 2)) }}</div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Expandable Details -->
                                <details class="mt-3">
                                    <summary class="cursor-pointer text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        عرض التفاصيل الكاملة
                                    </summary>
                                    <div class="mt-3">
                                        <pre class="code-block rounded-lg p-4 text-xs overflow-x-auto">{{ $error['full_message'] }}</pre>
                                        
                                        @if(!empty($errorGroup['affected_views']))
                                        <div class="mt-3">
                                            <span class="text-sm font-medium text-gray-700">الملفات المتأثرة:</span>
                                            <div class="mt-1">
                                                @foreach($errorGroup['affected_views'] as $view)
                                                    <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs mr-1 mb-1">{{ $view }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </details>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-search text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500">لا توجد أخطاء في الفترة المحددة</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">إجراءات سريعة</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">مسح الكاش</h4>
                    <p class="text-blue-700 text-sm mb-3">مسح جميع ملفات الكاش المؤقتة</p>
                    <code class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">php artisan cache:clear</code>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="font-semibold text-green-900 mb-2">إعادة تحميل الكلاسات</h4>
                    <p class="text-green-700 text-sm mb-3">لحل مشاكل "Class not found"</p>
                    <code class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">composer dump-autoload</code>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-4">
                    <h4 class="font-semibold text-purple-900 mb-2">مسح كاش الروابط</h4>
                    <p class="text-purple-700 text-sm mb-3">لحل مشاكل الروابط</p>
                    <code class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">php artisan route:clear</code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh every 5 minutes if there are critical errors
    @if($summary['critical_count'] + $summary['error_count'] > 0)
    setTimeout(function() {
        window.location.reload();
    }, 300000); // 5 minutes
    @endif
</script>
@endpush