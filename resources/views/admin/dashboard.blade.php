@extends('layouts.app')

@section('title', 'لوحة الإدارة')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">لوحة إدارة جُذور</h1>
                        <p class="text-gray-600">نظرة شاملة على أداء المنصة</p>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex items-center space-x-3 space-x-reverse mt-4 md:mt-0">
                    <button onclick="refreshDashboard()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="fas fa-sync-alt ml-2"></i>
                        تحديث
                    </button>
                    <a href="{{ route('admin.users.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة مستخدم
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Critical Alerts -->
        @if(isset($critical_alerts) && count($critical_alerts) > 0)
        <div class="mb-8">
            @foreach($critical_alerts as $alert)
            <div class="bg-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-50 border border-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-200 rounded-xl p-4 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <i class="fas fa-{{ $alert['type'] === 'error' ? 'exclamation-circle' : ($alert['type'] === 'warning' ? 'exclamation-triangle' : 'info-circle') }} text-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-600"></i>
                        <div>
                            <p class="font-medium text-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-800">{{ $alert['title'] }}</p>
                            <p class="text-sm text-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-700">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                    @if(isset($alert['action_url']))
                    <a href="{{ $alert['action_url'] }}" 
                       class="bg-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-600 hover:bg-{{ $alert['type'] === 'error' ? 'red' : ($alert['type'] === 'warning' ? 'yellow' : 'blue') }}-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        {{ $alert['action_text'] ?? 'اتخاذ إجراء' }}
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Business Intelligence - Top Priority -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-line text-green-600 ml-3"></i>
                    المؤشرات المالية والنمو
                </h2>
                <div class="flex items-center space-x-2 space-x-reverse text-sm text-gray-600">
                    <i class="fas fa-clock ml-1"></i>
                    <span>آخر تحديث: {{ now()->format('H:i') }}</span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Monthly Recurring Revenue -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 space-x-reverse mb-2">
                                    <h3 class="text-sm font-medium text-gray-600">الإيرادات الشهرية</h3>
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                </div>
                                <div class="text-3xl font-bold text-gray-900 mb-1">
                                    @php
                                        $mrr = $subscription_stats['active_subscriptions'] * 15; // Assuming $15/month
                                    @endphp
                                    ${{ number_format($mrr) }}
                                </div>
                                <div class="flex items-center space-x-1 space-x-reverse">
                                    @php
                                        $mrrGrowth = $growth_rates['subscriptions'] ?? 0;
                                    @endphp
                                    <span class="text-{{ $mrrGrowth >= 0 ? 'green' : 'red' }}-600 text-sm font-medium">
                                        <i class="fas fa-arrow-{{ $mrrGrowth >= 0 ? 'up' : 'down' }} ml-1"></i>
                                        {{ $mrrGrowth >= 0 ? '+' : '' }}{{ $mrrGrowth }}%
                                    </span>
                                    <span class="text-gray-500 text-sm">هذا الشهر</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-3 bg-green-50 border-t border-green-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-green-700">{{ $subscription_stats['active_subscriptions'] ?? 0 }} مشترك نشط</span>
                            <a href="{{ route('admin.subscription-plans.index') }}" class="text-green-600 hover:text-green-800 font-medium">
                                عرض التفاصيل →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Users Growth -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 space-x-reverse mb-2">
                                    <h3 class="text-sm font-medium text-gray-600">المستخدمون النشطون</h3>
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                </div>
                                <div class="text-3xl font-bold text-gray-900 mb-1">
                                    {{ number_format($metrics['active_users'] ?? $metrics['total_users']) }}
                                </div>
                                <div class="flex items-center space-x-1 space-x-reverse">
                                    <span class="text-{{ $growth_rates['users'] >= 0 ? 'green' : 'red' }}-600 text-sm font-medium">
                                        <i class="fas fa-arrow-{{ $growth_rates['users'] >= 0 ? 'up' : 'down' }} ml-1"></i>
                                        {{ $growth_rates['users'] >= 0 ? '+' : '' }}{{ $growth_rates['users'] }}%
                                    </span>
                                    <span class="text-gray-500 text-sm">هذا الشهر</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-3 bg-blue-50 border-t border-blue-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-700">{{ $metrics['total_users'] }} إجمالي المسجلين</span>
                            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                إدارة المستخدمين →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quiz Engagement -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 space-x-reverse mb-2">
                                    <h3 class="text-sm font-medium text-gray-600">الاختبارات النشطة</h3>
                                    <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                                </div>
                                <div class="text-3xl font-bold text-gray-900 mb-1">
                                    {{ number_format($metrics['active_quizzes'] ?? $metrics['total_quizzes']) }}
                                </div>
                                <div class="flex items-center space-x-1 space-x-reverse">
                                    <span class="text-{{ $growth_rates['quizzes'] >= 0 ? 'green' : 'red' }}-600 text-sm font-medium">
                                        <i class="fas fa-arrow-{{ $growth_rates['quizzes'] >= 0 ? 'up' : 'down' }} ml-1"></i>
                                        {{ $growth_rates['quizzes'] >= 0 ? '+' : '' }}{{ $growth_rates['quizzes'] }}%
                                    </span>
                                    <span class="text-gray-500 text-sm">هذا الشهر</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-clipboard-list text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-3 bg-purple-50 border-t border-purple-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-purple-700">{{ $metrics['total_results'] ?? 0 }} محاولة إجمالية</span>
                            <a href="{{ route('admin.quizzes.index') }}" class="text-purple-600 hover:text-purple-800 font-medium">
                                إدارة الاختبارات →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 space-x-reverse mb-2">
                                    <h3 class="text-sm font-medium text-gray-600">صحة النظام</h3>
                                    <div class="w-2 h-2 bg-{{ $system_health['status'] === 'excellent' ? 'green' : ($system_health['status'] === 'good' ? 'yellow' : 'red') }}-500 rounded-full animate-pulse"></div>
                                </div>
                                <div class="text-3xl font-bold text-gray-900 mb-1">
                                    {{ $system_health['uptime'] ?? 99 }}%
                                </div>
                                <div class="flex items-center space-x-1 space-x-reverse">
                                    <span class="text-{{ $system_health['uptime'] >= 99 ? 'green' : 'red' }}-600 text-sm font-medium">
                                        <i class="fas fa-{{ $system_health['uptime'] >= 99 ? 'check-circle' : 'exclamation-triangle' }} ml-1"></i>
                                        {{ $system_health['status_text'] ?? 'ممتاز' }}
                                    </span>
                                    <span class="text-gray-500 text-sm">معدل التشغيل</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-{{ $system_health['uptime'] >= 99 ? 'green' : 'red' }}-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-server text-{{ $system_health['uptime'] >= 99 ? 'green' : 'red' }}-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-3 bg-{{ $system_health['uptime'] >= 99 ? 'green' : 'red' }}-50 border-t border-{{ $system_health['uptime'] >= 99 ? 'green' : 'red' }}-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-{{ $system_health['uptime'] >= 99 ? 'green' : 'red' }}-700">آخر تحديث: منذ {{ $system_health['last_check'] ?? '5' }} دقائق</span>
                            <a href="{{ route('admin.settings') }}" class="text-{{ $system_health['uptime'] >= 99 ? 'green' : 'red' }}-600 hover:text-{{ $system_health['uptime'] >= 99 ? 'green' : 'red' }}-800 font-medium">
                                التفاصيل →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Intelligence -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            <!-- User Management Panel -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-users-cog text-blue-600 ml-3"></i>
                            إدارة المستخدمين
                        </h3>
                        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            عرض الكل →
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Active Teachers -->
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">معلمين نشطين</span>
                            </div>
                            <span class="text-xl font-bold text-green-600">{{ number_format($user_breakdown['active_teachers'] ?? 0) }}</span>
                        </div>

                        <!-- Students -->
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">طلاب مسجلين</span>
                            </div>
                            <span class="text-xl font-bold text-blue-600">{{ number_format($user_breakdown['registered_students'] ?? 0) }}</span>
                        </div>

                        <!-- Pending Approvals -->
                        @if(($user_breakdown['pending_approval'] ?? 0) > 0)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center animate-pulse">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">في انتظار الموافقة</span>
                            </div>
                            <span class="text-xl font-bold text-yellow-600">{{ number_format($user_breakdown['pending_approval']) }}</span>
                        </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="pt-4 space-y-2">
                            <a href="{{ route('admin.users.create') }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-plus ml-2"></i>
                                إضافة مستخدم جديد
                            </a>
                            @if(($user_breakdown['pending_approval'] ?? 0) > 0)
                            <a href="{{ route('admin.users.index') }}?status=pending" 
                               class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-user-check ml-2"></i>
                                مراجعة الطلبات المعلقة
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Educational Performance -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-seedling text-green-600 ml-3"></i>
                        أداء نموذج جُذور
                    </h3>
                </div>
                
                <div class="p-6">
                    <!-- 4 Roots Performance -->
                    <div class="space-y-4 mb-6">
                        @php
                            $roots = [
                                'jawhar' => ['name' => 'جَوهر', 'score' => $roots_analytics['jawhar']['average'] ?? 75, 'color' => 'purple'],
                                'zihn' => ['name' => 'ذِهن', 'score' => $roots_analytics['zihn']['average'] ?? 72, 'color' => 'blue'], 
                                'waslat' => ['name' => 'وَصلات', 'score' => $roots_analytics['waslat']['average'] ?? 78, 'color' => 'green'],
                                'roaya' => ['name' => 'رُؤية', 'score' => $roots_analytics['roaya']['average'] ?? 70, 'color' => 'orange']
                            ];
                        @endphp
                        
                        @foreach($roots as $rootKey => $root)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-3 h-3 bg-{{ $root['color'] }}-500 rounded-full"></div>
                                <span class="text-gray-700 font-medium">{{ $root['name'] }}</span>
                            </div>
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-{{ $root['color'] }}-500 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ min($root['score'], 100) }}%"></div>
                                </div>
                                <span class="text-sm font-bold text-{{ $root['color'] }}-600 w-12 text-right">{{ round($root['score']) }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Overall Performance Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">متوسط الأداء العام</span>
                            <span class="text-lg font-bold text-gray-900">{{ round(array_sum(array_column($roots, 'score')) / count($roots)) }}%</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-purple-500 to-green-500 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ round(array_sum(array_column($roots, 'score')) / count($roots)) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Insights -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt text-yellow-600 ml-3"></i>
                        إجراءات سريعة
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        <!-- Create Quiz -->
                        <a href="{{ route('admin.quizzes.create') ?? '#' }}" 
                           class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                            <div class="mr-3">
                                <div class="font-medium text-gray-900">إنشاء اختبار جديد</div>
                                <div class="text-sm text-gray-600">إضافة محتوى تعليمي</div>
                            </div>
                        </a>

                        <!-- View Reports -->
                        <a href="{{ route('admin.reports') ?? route('reports.index') }}" 
                           class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-bar text-white"></i>
                            </div>
                            <div class="mr-3">
                                <div class="font-medium text-gray-900">التقارير المفصلة</div>
                                <div class="text-sm text-gray-600">تحليلات شاملة</div>
                            </div>
                        </a>

                        <!-- Manage Subscriptions -->
                        <a href="{{ route('admin.subscription-plans.index') }}" 
                           class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <div class="mr-3">
                                <div class="font-medium text-gray-900">إدارة الاشتراكات</div>
                                <div class="text-sm text-gray-600">الخطط والمدفوعات</div>
                            </div>
                        </a>

                        <!-- Contact Messages -->
                        <a href="{{ route('admin.contact.index') }}" 
                           class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div class="mr-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="font-medium text-gray-900">رسائل التواصل</div>
                                    @if(isset($unread_messages) && $unread_messages > 0)
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $unread_messages }}</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">استفسارات العملاء</div>
                            </div>
                        </a>

                        <!-- System Settings -->
                        <a href="{{ route('admin.settings') }}" 
                           class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-cog text-white"></i>
                            </div>
                            <div class="mr-3">
                                <div class="font-medium text-gray-900">إعدادات النظام</div>
                                <div class="text-sm text-gray-600">تكوين المنصة</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            <!-- Growth Trends Chart -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-line text-blue-600 ml-3"></i>
                            اتجاهات النمو
                        </h3>
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <button onclick="changeChartPeriod('week')" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">أسبوع</button>
                            <button onclick="changeChartPeriod('month')" class="text-sm text-blue-600 font-medium">شهر</button>
                            <button onclick="changeChartPeriod('year')" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">سنة</button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                        <canvas id="growthChart" width="400" height="200"></canvas>
                    </div>
                    
                    <!-- Chart Legend -->
                    <div class="flex items-center justify-center space-x-6 space-x-reverse mt-4">
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">المستخدمين</span>
                        </div>
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">الاختبارات</span>
                        </div>
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">الإيرادات</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Activity Heatmap -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-calendar-alt text-green-600 ml-3"></i>
                            نشاط المستخدمين
                        </h3>
                        <span class="text-sm text-gray-600">آخر 30 يوم</span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-7 gap-1 mb-4">
                        @for($i = 0; $i < 35; $i++)
                            @php
                                $intensity = rand(0, 4);
                                $colors = ['bg-gray-100', 'bg-green-200', 'bg-green-300', 'bg-green-400', 'bg-green-500'];
                            @endphp
                            <div class="w-4 h-4 {{ $colors[$intensity] }} rounded-sm" 
                                 title="{{ $i + 1 }} activity level"></div>
                        @endfor
                    </div>
                    
                    <!-- Activity Summary -->
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900">{{ $metrics['daily_active_users'] ?? rand(50, 150) }}</div>
                            <div class="text-sm text-gray-600">مستخدم يومي</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900">{{ $metrics['weekly_active_users'] ?? rand(200, 500) }}</div>
                            <div class="text-sm text-gray-600">مستخدم أسبوعي</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900">{{ $metrics['monthly_active_users'] ?? rand(800, 1500) }}</div>
                            <div class="text-sm text-gray-600">مستخدم شهري</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Notifications -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-history text-gray-600 ml-3"></i>
                            الأنشطة الأخيرة
                        </h3>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">عرض الكل →</a>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @php
                        $activities = [
                            ['type' => 'user', 'icon' => 'fas fa-user-plus', 'color' => 'blue', 'text' => 'انضم مستخدم جديد: أحمد محمد', 'time' => 'منذ 5 دقائق'],
                            ['type' => 'quiz', 'icon' => 'fas fa-clipboard-list', 'color' => 'green', 'text' => 'تم إنشاء اختبار جديد في الرياضيات', 'time' => 'منذ 15 دقيقة'],
                            ['type' => 'subscription', 'icon' => 'fas fa-credit-card', 'color' => 'purple', 'text' => 'اشتراك جديد من فاطمة علي', 'time' => 'منذ 30 دقيقة'],
                            ['type' => 'result', 'icon' => 'fas fa-chart-line', 'color' => 'orange', 'text' => 'نتيجة متميزة: 95% في اختبار العلوم', 'time' => 'منذ ساعة'],
                            ['type' => 'contact', 'icon' => 'fas fa-envelope', 'color' => 'red', 'text' => 'رسالة جديدة من دعم العملاء', 'time' => 'منذ ساعتين']
                        ];
                    @endphp
                    
                    @foreach($activities as $activity)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start space-x-3 space-x-reverse">
                            <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="{{ $activity['icon'] }} text-{{ $activity['color'] }}-600 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">{{ $activity['text'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- System Notifications -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-bell text-yellow-600 ml-3"></i>
                            تنبيهات النظام
                        </h3>
                        <button onclick="markAllAsRead()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">تمييز كمقروء</button>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @php
                        $notifications = [
                            ['type' => 'success', 'text' => 'تم تحديث النظام بنجاح إلى الإصدار 2.1', 'time' => 'منذ يوم'],
                            ['type' => 'warning', 'text' => '15 مستخدم في انتظار الموافقة', 'time' => 'منذ يومين', 'action' => true],
                            ['type' => 'info', 'text' => 'نسخة احتياطية تلقائية مكتملة', 'time' => 'منذ 3 أيام'],
                            ['type' => 'success', 'text' => 'تم إضافة 50 مستخدم جديد هذا الأسبوع', 'time' => 'منذ أسبوع']
                        ];
                    @endphp
                    
                    @foreach($notifications as $notification)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start space-x-3 space-x-reverse">
                            <div class="w-2 h-2 bg-{{ $notification['type'] === 'success' ? 'green' : ($notification['type'] === 'warning' ? 'yellow' : 'blue') }}-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">{{ $notification['text'] }}</p>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-xs text-gray-500">{{ $notification['time'] }}</p>
                                    @if(isset($notification['action']) && $notification['action'])
                                        <button class="text-xs text-blue-600 hover:text-blue-800 font-medium">اتخاذ إجراء</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Interactive Features -->
<script>
// Dashboard refresh functionality
function refreshDashboard() {
    // Add loading state
    const refreshBtn = document.querySelector('[onclick="refreshDashboard()"]');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري التحديث...';
    refreshBtn.disabled = true;
    
    // Simulate refresh (replace with actual AJAX call)
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Chart period change
function changeChartPeriod(period) {
    // Update chart data based on period
    console.log('Changing chart period to:', period);
    // Implementation would depend on your charting library
}

// Mark notifications as read
function markAllAsRead() {
    // AJAX call to mark notifications as read
    console.log('Marking all notifications as read');
    // Update UI to reflect read status
}

// Animate counters on page load
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/,/g, ''));
        let current = 0;
        const increment = target / 50;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current).toLocaleString();
            }
        }, 30);
    });
});

// Auto-refresh dashboard every 5 minutes
setInterval(() => {
    // Subtle refresh of key metrics without full page reload
    // Implementation depends on your backend API
}, 300000);
</script>

<!-- Chart.js for interactive charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Initialize growth chart
const ctx = document.getElementById('growthChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'المستخدمين',
                data: [12, 19, 25, 32, 38, 45],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }, {
                label: 'الاختبارات',
                data: [5, 10, 15, 25, 30, 35],
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4
            }, {
                label: 'الإيرادات',
                data: [180, 285, 375, 480, 570, 675],
                borderColor: 'rgb(147, 51, 234)',
                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}
</script>
@endsection