@extends('layouts.app')

@section('title', 'إعدادات النظام')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">إعدادات النظام</h1>
                        <p class="text-gray-600 mt-1">إدارة إعدادات المنصة والنظام</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-arrow-left ml-2"></i>
                        العودة للوحة التحكم
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Settings Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">الإعدادات</h3>
                        <nav class="space-y-2">
                            <a href="#general" class="settings-tab active" data-tab="general">
                                <i class="fas fa-cog ml-2"></i>
                                الإعدادات العامة
                            </a>
                            <a href="#ai" class="settings-tab" data-tab="ai">
                                <i class="fas fa-robot ml-2"></i>
                                إعدادات الذكاء الاصطناعي
                            </a>
                            <a href="#system" class="settings-tab" data-tab="system">
                                <i class="fas fa-server ml-2"></i>
                                إعدادات النظام
                            </a>
                            <a href="#backup" class="settings-tab" data-tab="backup">
                                <i class="fas fa-database ml-2"></i>
                                النسخ الاحتياطي
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- System Health -->
                <div class="bg-white shadow rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">حالة النظام</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">الخدمة</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">
                                    <i class="fas fa-check ml-1"></i>متاحة
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">قاعدة البيانات</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">
                                    <i class="fas fa-check ml-1"></i>طبيعية
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">معدل التشغيل</span>
                                <span class="font-bold text-green-600">99.9%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-2">
                
                <!-- General Settings Tab -->
                <div id="general-tab" class="settings-content bg-white shadow rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">الإعدادات العامة</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <!-- Platform Settings -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">اسم المنصة</label>
                            <input type="text" value="منصة جُذور التعليمية" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وصف المنصة</label>
                            <textarea rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md">نموذج تعليمي مبتكر لتنمية جميع جوانب التعلم</textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اللغة الافتراضية</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="ar" selected>العربية</option>
                                    <option value="en">English</option>
                                    <option value="he">עברית</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">المنطقة الزمنية</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="Asia/Jerusalem" selected>آسيا/القدس</option>
                                    <option value="UTC">UTC</option>
                                </select>
                            </div>
                        </div>
                        
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            حفظ التغييرات
                        </button>
                    </div>
                </div>

                <!-- AI Settings Tab -->
                <div id="ai-tab" class="settings-content bg-white shadow rounded-lg hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">إعدادات الذكاء الاصطناعي</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-600 ml-2"></i>
                                <span class="text-blue-800 font-medium">حالة اتصال Claude API: متصل</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نموذج الذكاء الاصطناعي</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="claude-3-5-sonnet-20241022" selected>Claude 3.5 Sonnet</option>
                                <option value="claude-3-opus-20240229">Claude 3 Opus</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">حد الاستخدام اليومي</label>
                                <input type="number" value="100" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">المهلة الزمنية (ثانية)</label>
                                <input type="number" value="30" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                            تحديث إعدادات AI
                        </button>
                    </div>
                </div>

                <!-- System Settings Tab -->
                <div id="system-tab" class="settings-content bg-white shadow rounded-lg hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">إعدادات النظام</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وضع الصيانة</label>
                                <div class="flex items-center">
                                    <input type="checkbox" class="rounded">
                                    <span class="mr-2 text-sm text-gray-600">تفعيل وضع الصيانة</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تفعيل التسجيل</label>
                                <div class="flex items-center">
                                    <input type="checkbox" class="rounded" checked>
                                    <span class="mr-2 text-sm text-gray-600">السماح بالتسجيل الجديد</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مستوى السجلات</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="error">أخطاء فقط</option>
                                <option value="warning">تحذيرات وأخطاء</option>
                                <option value="info" selected>معلومات عامة</option>
                                <option value="debug">تفصيلي</option>
                            </select>
                        </div>
                        
                        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            تطبيق إعدادات النظام
                        </button>
                    </div>
                </div>

                <!-- Backup Settings Tab -->
                <div id="backup-tab" class="settings-content bg-white shadow rounded-lg hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">النسخ الاحتياطي</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-green-800">آخر نسخة احتياطية: {{ now()->subHours(6)->diffForHumans() }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تكرار النسخ الاحتياطي</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="daily" selected>يومياً</option>
                                <option value="weekly">أسبوعياً</option>
                                <option value="monthly">شهرياً</option>
                            </select>
                        </div>
                        
                        <div class="flex gap-4">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                <i class="fas fa-download ml-2"></i>
                                إنشاء نسخة احتياطية الآن
                            </button>
                            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                                <i class="fas fa-upload ml-2"></i>
                                استعادة من نسخة احتياطية
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<style>
.settings-tab {
    display: block;
    padding: 12px 16px;
    text-decoration: none;
    color: #6b7280;
    border-radius: 8px;
    transition: all 0.2s;
}

.settings-tab:hover {
    background-color: #f3f4f6;
    color: #374151;
}

.settings-tab.active {
    background-color: #3b82f6;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.settings-tab');
    const contents = document.querySelectorAll('.settings-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Hide all content
            contents.forEach(c => c.classList.add('hidden'));
            
            // Activate clicked tab
            this.classList.add('active');
            
            // Show corresponding content
            const tabName = this.getAttribute('data-tab');
            document.getElementById(tabName + '-tab').classList.remove('hidden');
        });
    });
});
</script>
@endsection