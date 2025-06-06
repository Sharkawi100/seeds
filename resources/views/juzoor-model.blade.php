@extends('layouts.app')
@push('styles')
<style>
    * {
        font-family: 'Tajawal', sans-serif !important;
    }
</style>
@endpush
@section('title', 'نموذج جُذور التعليمي')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 bg-gradient-to-br from-purple-600 to-blue-600 text-white overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-400 rounded-full filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-400 rounded-full filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-black mb-6">نموذج جُذور التعليمي</h1>
        <p class="text-xl md:text-2xl max-w-3xl mx-auto opacity-90">
            أربعة جذور للمعرفة تنمو معاً لبناء فهم شامل ومتوازن
        </p>
    </div>
</section>

<!-- Academic Resources Section -->
<section class="py-16 bg-gradient-to-br from-purple-50 to-blue-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-3 bg-white/80 backdrop-blur-sm px-6 py-3 rounded-full shadow-md mb-4">
                <i class="fas fa-graduation-cap text-purple-600 text-xl"></i>
                <span class="text-purple-600 font-bold text-sm uppercase tracking-wider">موارد أكاديمية</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-gray-800 mb-4">
                الأساس النظري لنموذج جُذور
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                اطلع على البحث الأكاديمي الكامل الذي يشرح النموذج التربوي بالتفصيل
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Arabic PDF -->
            <div class="group">
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-br from-purple-600 to-purple-800 p-8 text-center">
                        <i class="fas fa-file-pdf text-6xl text-white mb-4"></i>
                        <h3 class="text-2xl font-bold text-white mb-2">النسخة العربية</h3>
                        <p class="text-purple-200">البحث الكامل باللغة العربية</p>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col gap-3">
                            <a href="{{ asset('docs/juzoor-ar.pdf') }}" target="_blank" 
                               class="flex items-center justify-center gap-3 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold py-3 px-6 rounded-xl transition-all">
                                <i class="fas fa-eye"></i>
                                <span>عرض المستند</span>
                            </a>
                            <a href="{{ asset('docs/juzoor-ar.pdf') }}" download 
                               class="flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all">
                                <i class="fas fa-download"></i>
                                <span>تحميل PDF</span>
                            </a>
                        </div>
                        <div class="mt-4 text-center text-sm text-gray-500">
                            <i class="fas fa-file-alt ml-1"></i>
                            حجم الملف: 2.4 ميجابايت
                        </div>
                    </div>
                </div>
            </div>

            <!-- English PDF -->
            <div class="group">
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-8 text-center">
                        <i class="fas fa-file-pdf text-6xl text-white mb-4"></i>
                        <h3 class="text-2xl font-bold text-white mb-2">English Version</h3>
                        <p class="text-blue-200">Complete research in English</p>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col gap-3">
                            <a href="{{ asset('docs/juzoor-en.pdf') }}" target="_blank" 
                               class="flex items-center justify-center gap-3 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-3 px-6 rounded-xl transition-all">
                                <i class="fas fa-eye"></i>
                                <span>View Document</span>
                            </a>
                            <a href="{{ asset('docs/juzoor-en.pdf') }}" download 
                               class="flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-6 rounded-xl transition-all">
                                <i class="fas fa-download"></i>
                                <span>Download PDF</span>
                            </a>
                        </div>
                        <div class="mt-4 text-center text-sm text-gray-500">
                            <i class="fas fa-file-alt ml-1"></i>
                            File size: 2.4 MB
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-12 text-center">
            <div class="inline-flex items-center gap-4 bg-white/80 backdrop-blur-sm px-6 py-4 rounded-2xl shadow-md">
                <i class="fas fa-info-circle text-purple-600 text-xl"></i>
                <p class="text-gray-700">
                    هذه الأوراق البحثية توضح الأساس النظري والفلسفي لنموذج جُذور التربوي
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Model Explorer -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-black text-gray-800 mb-4">استكشف نموذج جُذور التفاعلي</h2>
            <p class="text-xl text-gray-600">انقر على أي جذر لاستكشاف التفاصيل</p>
        </div>

        <!-- Interactive Root Diagram -->
        <div class="relative max-w-4xl mx-auto">
            <div class="bg-gray-50 rounded-3xl p-8 md:p-16">
                <!-- Central Seed -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                    <div class="w-32 h-32 md:w-40 md:h-40 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex flex-col items-center justify-center shadow-2xl animate-pulse cursor-pointer hover:scale-110 transition-transform" onclick="showCentralConcept()">
                        <span class="text-5xl md:text-6xl mb-2">🌱</span>
                        <span class="text-white font-bold text-sm">البذرة</span>
                    </div>
                </div>

                <!-- Root Cards positioned around center -->
                <div class="grid grid-cols-2 gap-8 md:gap-16">
                    <!-- Jawhar - Top Left -->
                    <div class="text-right">
                        <div id="jawhar-card" class="group cursor-pointer transform hover:scale-105 transition-all duration-300" onclick="showRootDetails('jawhar')">
                            <div class="bg-white rounded-2xl shadow-xl p-6 border-3 border-red-200 hover:border-red-400 hover:shadow-2xl">
                                <div class="flex items-start gap-4">
                                    <div class="text-5xl">🎯</div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-red-600 mb-1">جَوهر</h3>
                                        <p class="text-lg font-semibold text-gray-700">ما هو؟</p>
                                        <p class="text-sm text-gray-500 mt-2">انقر للتفاصيل</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Zihn - Top Right -->
                    <div class="text-left">
                        <div id="zihn-card" class="group cursor-pointer transform hover:scale-105 transition-all duration-300" onclick="showRootDetails('zihn')">
                            <div class="bg-white rounded-2xl shadow-xl p-6 border-3 border-teal-200 hover:border-teal-400 hover:shadow-2xl">
                                <div class="flex items-start gap-4 flex-row-reverse">
                                    <div class="text-5xl">🧠</div>
                                    <div class="text-right">
                                        <h3 class="text-2xl font-bold text-teal-600 mb-1">ذِهن</h3>
                                        <p class="text-lg font-semibold text-gray-700">كيف يعمل؟</p>
                                        <p class="text-sm text-gray-500 mt-2">انقر للتفاصيل</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Waslat - Bottom Left -->
                    <div class="text-right">
                        <div id="waslat-card" class="group cursor-pointer transform hover:scale-105 transition-all duration-300" onclick="showRootDetails('waslat')">
                            <div class="bg-white rounded-2xl shadow-xl p-6 border-3 border-yellow-200 hover:border-yellow-400 hover:shadow-2xl">
                                <div class="flex items-start gap-4">
                                    <div class="text-5xl">🔗</div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-yellow-600 mb-1">وَصلات</h3>
                                        <p class="text-lg font-semibold text-gray-700">كيف يرتبط؟</p>
                                        <p class="text-sm text-gray-500 mt-2">انقر للتفاصيل</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roaya - Bottom Right -->
                    <div class="text-left">
                        <div id="roaya-card" class="group cursor-pointer transform hover:scale-105 transition-all duration-300" onclick="showRootDetails('roaya')">
                            <div class="bg-white rounded-2xl shadow-xl p-6 border-3 border-purple-200 hover:border-purple-400 hover:shadow-2xl">
                                <div class="flex items-start gap-4 flex-row-reverse">
                                    <div class="text-5xl">👁️</div>
                                    <div class="text-right">
                                        <h3 class="text-2xl font-bold text-purple-600 mb-1">رُؤية</h3>
                                        <p class="text-lg font-semibold text-gray-700">كيف نستخدمه؟</p>
                                        <p class="text-sm text-gray-500 mt-2">انقر للتفاصيل</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Root Details Panel -->
        <div id="root-details" class="mt-12 hidden">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 max-w-4xl mx-auto">
                <div id="root-content">
                    <!-- Content will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Depth Levels Visualization -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-gray-800 mb-4">ثلاثة مستويات من العمق</h2>
            <p class="text-xl text-gray-600">كل جذر ينمو عبر ثلاثة مستويات من الفهم</p>
        </div>

        <div class="max-w-5xl mx-auto">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Level 1 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                            <div class="w-12 h-12 bg-green-400 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                1
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-center mt-4 mb-4">المستوى السطحي</h3>
                        <div class="text-center text-6xl mb-4">🌱</div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-gray-600">فهم أساسي ومباشر</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-gray-600">تطبيقات بسيطة</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="text-gray-600">معرفة أولية</span>
                            </div>
                        </div>
                        <div class="mt-6 bg-green-50 rounded-xl p-4 text-center">
                            <span class="text-3xl font-bold text-green-600">33%</span>
                            <p class="text-sm text-gray-600 mt-1">من العمق الكلي</p>
                        </div>
                    </div>
                </div>

                <!-- Level 2 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                2
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-center mt-4 mb-4">المستوى المتوسط</h3>
                        <div class="text-center text-6xl mb-4">🌿</div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-blue-500"></i>
                                <span class="text-gray-600">تحليل تفصيلي</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-blue-500"></i>
                                <span class="text-gray-600">ربط متعدد الأوجه</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-blue-500"></i>
                                <span class="text-gray-600">فهم أعمق</span>
                            </div>
                        </div>
                        <div class="mt-6 bg-blue-50 rounded-xl p-4 text-center">
                            <span class="text-3xl font-bold text-blue-600">66%</span>
                            <p class="text-sm text-gray-600 mt-1">من العمق الكلي</p>
                        </div>
                    </div>
                </div>

                <!-- Level 3 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                3
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-center mt-4 mb-4">المستوى العميق</h3>
                        <div class="text-center text-6xl mb-4">🌳</div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-purple-500"></i>
                                <span class="text-gray-600">إتقان شامل</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-purple-500"></i>
                                <span class="text-gray-600">تركيب مبتكر</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-purple-500"></i>
                                <span class="text-gray-600">حلول إبداعية</span>
                            </div>
                        </div>
                        <div class="mt-6 bg-purple-50 rounded-xl p-4 text-center">
                            <span class="text-3xl font-bold text-purple-600">100%</span>
                            <p class="text-sm text-gray-600 mt-1">من العمق الكلي</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Progress -->
            <div class="mt-12 bg-white rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold text-center mb-8">مثال على نمو المعرفة</h3>
                <div class="relative h-32">
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-gray-200 rounded-full"></div>
                    
                    <!-- Progress indicators -->
                    <div class="absolute bottom-0 left-0 w-1/3 h-2 bg-green-500 rounded-full"></div>
                    <div class="absolute bottom-0 left-1/3 w-1/3 h-2 bg-blue-500 rounded-full"></div>
                    <div class="absolute bottom-0 left-2/3 w-1/3 h-2 bg-purple-600 rounded-full"></div>
                    
                    <!-- Milestone markers -->
                    <div class="absolute bottom-0 left-0 transform -translate-x-1/2">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mb-2"></div>
                            <span class="text-5xl mb-2">🌱</span>
                            <span class="text-sm font-bold">بداية</span>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-0 left-1/3 transform -translate-x-1/2">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mb-2"></div>
                            <span class="text-5xl mb-2">🌿</span>
                            <span class="text-sm font-bold">نمو</span>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-0 left-2/3 transform -translate-x-1/2">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-blue-500 rounded-full mb-2"></div>
                            <span class="text-5xl mb-2">🌲</span>
                            <span class="text-sm font-bold">تطور</span>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-0 right-0 transform translate-x-1/2">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 bg-purple-600 rounded-full mb-2"></div>
                            <span class="text-5xl mb-2">🌳</span>
                            <span class="text-sm font-bold">إتقان</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Practical Examples Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-gray-800 mb-4">أمثلة عملية من الواقع</h2>
            <p class="text-xl text-gray-600">شاهد كيف يعمل نموذج جُذور في مواد مختلفة</p>
        </div>

        <!-- Subject Tabs -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button onclick="showExample('science')" class="example-tab active bg-purple-600 text-white px-6 py-3 rounded-xl font-bold transition-all">
                العلوم 🔬
            </button>
            <button onclick="showExample('arabic')" class="example-tab bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-300 transition-all">
                اللغة العربية ✍️
            </button>
            <button onclick="showExample('math')" class="example-tab bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-300 transition-all">
                الرياضيات 🔢
            </button>
        </div>

        <!-- Example Content -->
        <div id="example-content" class="max-w-5xl mx-auto">
            <!-- Science Example (Default) -->
            <div id="science-example" class="example-panel">
                <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl p-8 md:p-12">
                    <h3 class="text-3xl font-bold text-center mb-8">موضوع: دورة الماء في الطبيعة</h3>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🎯</span>
                                <h4 class="text-xl font-bold text-red-600">جَوهر - ما هي دورة الماء؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• ما هي دورة الماء؟</li>
                                <li>• ما هي مراحل دورة الماء؟</li>
                                <li>• عرّف التبخر والتكاثف</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🧠</span>
                                <h4 class="text-xl font-bold text-teal-600">ذِهن - كيف تعمل؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• كيف تتبخر المياه من المحيطات؟</li>
                                <li>• لماذا تتكون السحب في السماء؟</li>
                                <li>• ما الذي يسبب هطول الأمطار؟</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🔗</span>
                                <h4 class="text-xl font-bold text-yellow-600">وَصلات - كيف ترتبط؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• ما علاقة دورة الماء بالطقس؟</li>
                                <li>• كيف تؤثر على الزراعة؟</li>
                                <li>• ما الصلة بين دورة الماء والحياة؟</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">👁️</span>
                                <h4 class="text-xl font-bold text-purple-600">رُؤية - كيف نستفيد؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• كيف نحافظ على المياه؟</li>
                                <li>• صمم نظام لجمع مياه الأمطار</li>
                                <li>• اقترح حلولاً لمشكلة الجفاف</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Arabic Example (Hidden) -->
            <div id="arabic-example" class="example-panel hidden">
                <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-3xl p-8 md:p-12">
                    <h3 class="text-3xl font-bold text-center mb-8">موضوع: القصة القصيرة</h3>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🎯</span>
                                <h4 class="text-xl font-bold text-red-600">جَوهر - ما هي القصة؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• ما هي عناصر القصة؟</li>
                                <li>• من هي الشخصيات الرئيسية؟</li>
                                <li>• أين ومتى تدور الأحداث؟</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🧠</span>
                                <h4 class="text-xl font-bold text-teal-600">ذِهن - كيف بُنيت؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• كيف تطورت الأحداث؟</li>
                                <li>• لماذا تصرفت الشخصيات بهذا الشكل؟</li>
                                <li>• ما الصراع في القصة؟</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🔗</span>
                                <h4 class="text-xl font-bold text-yellow-600">وَصلات - ما العلاقات؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• ما علاقة القصة بالواقع؟</li>
                                <li>• كيف ترتبط بقصص أخرى؟</li>
                                <li>• ما الرموز المستخدمة؟</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">👁️</span>
                                <h4 class="text-xl font-bold text-purple-600">رُؤية - ماذا نتعلم؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• ما العبرة من القصة؟</li>
                                <li>• اكتب نهاية بديلة</li>
                                <li>• كيف تطبق الدرس في حياتك؟</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Math Example (Hidden) -->
            <div id="math-example" class="example-panel hidden">
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-8 md:p-12">
                    <h3 class="text-3xl font-bold text-center mb-8">موضوع: الكسور</h3>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🎯</span>
                                <h4 class="text-xl font-bold text-red-600">جَوهر - ما هو الكسر؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• ما هو الكسر؟</li>
                                <li>• ما الفرق بين البسط والمقام؟</li>
                                <li>• ما أنواع الكسور؟</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🧠</span>
                                <h4 class="text-xl font-bold text-teal-600">ذِهن - كيف نحسب؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• كيف نجمع الكسور؟</li>
                                <li>• لماذا نوحد المقامات؟</li>
                                <li>• كيف نبسط الكسور؟</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🔗</span>
                                <h4 class="text-xl font-bold text-yellow-600">وَصلات - كيف ترتبط؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• ما العلاقة بين الكسور والنسب؟</li>
                                <li>• كيف ترتبط بالأعداد العشرية؟</li>
                                <li>• ما الصلة بالقسمة؟</li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">👁️</span>
                                <h4 class="text-xl font-bold text-purple-600">رُؤية - أين نستخدمها؟</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li>• كيف نقسم البيتزا بالعدل؟</li>
                                <li>• حساب الخصومات في التسوق</li>
                                <li>• استخدم الكسور في وصفة طعام</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Key Advantages -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-gray-800 mb-4">لماذا نموذج جُذور مختلف؟</h2>
            <p class="text-xl text-gray-600">مزايا تجعل التعلم أكثر طبيعية وفعالية</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $advantages = [
                [
                    'icon' => 'fa-random',
                    'title' => 'مرونة كاملة',
                    'desc' => 'يمكن للطلاب البدء من أي جذر والتقدم في أي اتجاه',
                    'color' => 'purple'
                ],
                [
                    'icon' => 'fa-users',
                    'title' => 'شمولية',
                    'desc' => 'يستوعب أنماط التعلم المختلفة والقدرات المتنوعة',
                    'color' => 'blue'
                ],
                [
                    'icon' => 'fa-smile',
                    'title' => 'تحفيز إيجابي',
                    'desc' => 'يحتفي بالنمو التدريجي ومسارات النجاح المتعددة',
                    'color' => 'green'
                ],
                [
                    'icon' => 'fa-globe',
                    'title' => 'صلة بالواقع',
                    'desc' => 'يركز على التطبيق العملي بجانب المعرفة النظرية',
                    'color' => 'yellow'
                ],
                [
                    'icon' => 'fa-link',
                    'title' => 'تعلم مترابط',
                    'desc' => 'يعزز فهم العلاقات بين المواضيع المختلفة',
                    'color' => 'red'
                ],
                [
                    'icon' => 'fa-seedling',
                    'title' => 'نمو طبيعي',
                    'desc' => 'يحترم إيقاع التعلم الفردي لكل طالب',
                    'color' => 'teal'
                ]
            ];
            @endphp

            @foreach($advantages as $advantage)
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all">
                <div class="w-16 h-16 bg-{{ $advantage['color'] }}-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas {{ $advantage['icon'] }} text-2xl text-{{ $advantage['color'] }}-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">{{ $advantage['title'] }}</h3>
                <p class="text-gray-600">{{ $advantage['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Implementation Guide -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-gray-800 mb-4">كيف تطبق نموذج جُذور؟</h2>
            <p class="text-xl text-gray-600">دليل عملي للمعلمين مع أمثلة حقيقية</p>
        </div>

        <div class="max-w-6xl mx-auto">
            <!-- Steps Overview -->
            <div class="grid md:grid-cols-4 gap-6 mb-16">
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl mx-auto mb-4">
                        1
                    </div>
                    <h3 class="font-bold mb-2">اختر الموضوع</h3>
                    <p class="text-sm text-gray-600">حدد المفهوم المراد تدريسه</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl mx-auto mb-4">
                        2
                    </div>
                    <h3 class="font-bold mb-2">صمم الأسئلة</h3>
                    <p class="text-sm text-gray-600">وزع الأسئلة على الجذور الأربعة</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl mx-auto mb-4">
                        3
                    </div>
                    <h3 class="font-bold mb-2">طبق وقيّم</h3>
                    <p class="text-sm text-gray-600">نفذ الاختبار وتابع النتائج</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-600 rounded-full flex items-center justify-center text-white font-bold text-xl mx-auto mb-4">
                        4
                    </div>
                    <h3 class="font-bold mb-2">خصص التعلم</h3>
                    <p class="text-sm text-gray-600">وجه كل طالب حسب احتياجاته</p>
                </div>
            </div>

            <!-- Question Examples Section -->
            <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl p-8 md:p-12 mb-12">
                <h3 class="text-3xl font-bold text-center mb-8">بنك أسئلة جُذور التطبيقي</h3>
                
                <!-- Age Group Selector -->
                <div class="flex justify-center gap-4 mb-8">
                    <button onclick="showQuestionSet('kids')" id="kids-btn" class="question-btn active bg-purple-600 text-white px-6 py-3 rounded-xl font-bold">
                        🧒 أطفال (6-12)
                    </button>
                    <button onclick="showQuestionSet('teens')" id="teens-btn" class="question-btn bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-300">
                        👦 مراهقون (13-18)
                    </button>
                    <button onclick="showQuestionSet('adults')" id="adults-btn" class="question-btn bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-300">
                        👨 بالغون (18+)
                    </button>
                </div>

                <!-- Kids Questions -->
                <div id="kids-questions" class="question-set">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Jawhar Questions -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🎯</span>
                                <h4 class="text-xl font-bold text-red-600">جَوهر - أسئلة للأطفال</h4>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h5 class="font-bold text-green-600 mb-2">المستوى 1:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• ما اسم كوكبنا الذي نعيش عليه؟</li>
                                        <li>• عدِّد فصول السنة الأربعة</li>
                                        <li>• صِف لون السماء في النهار</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-blue-600 mb-2">المستوى 2:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• عرِّف الصداقة بكلماتك الخاصة</li>
                                        <li>• ما الفرق بين الحيوانات والنباتات؟</li>
                                        <li>• صِف مكونات الهواء الذي نتنفسه</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-purple-600 mb-2">المستوى 3:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• عرِّف دورة الماء في الطبيعة بالتفصيل</li>
                                        <li>• ما خصائص الكائنات الحية الخمس؟</li>
                                        <li>• صِف طبقات الأرض الثلاث</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Zihn Questions -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🧠</span>
                                <h4 class="text-xl font-bold text-teal-600">ذِهن - أسئلة للأطفال</h4>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h5 class="font-bold text-green-600 mb-2">المستوى 1:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• لماذا نحتاج إلى النوم؟</li>
                                        <li>• كيف تطير الطائرة الورقية؟</li>
                                        <li>• اشرح لماذا نغسل أيدينا</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-blue-600 mb-2">المستوى 2:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• كيف ينمو النبات من البذرة؟</li>
                                        <li>• لماذا يتغير شكل القمر؟</li>
                                        <li>• قارن بين الصيف والشتاء</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-purple-600 mb-2">المستوى 3:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• حلِّل كيف يصنع النبات غذاءه</li>
                                        <li>• فسِّر سبب حدوث قوس قزح</li>
                                        <li>• استنتج لماذا تهاجر الطيور</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Waslat Questions -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🔗</span>
                                <h4 class="text-xl font-bold text-yellow-600">وَصلات - أسئلة للأطفال</h4>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h5 class="font-bold text-green-600 mb-2">المستوى 1:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• ما علاقة الشمس بالنباتات؟</li>
                                        <li>• اربط بين المطر والمظلة</li>
                                        <li>• كيف ترتبط الأسنان بالطعام؟</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-blue-600 mb-2">المستوى 2:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• اربط بين التمارين والصحة</li>
                                        <li>• ما علاقة النحل بالعسل والأزهار؟</li>
                                        <li>• وحِّد بين أجزاء الجسم ووظائفها</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-purple-600 mb-2">المستوى 3:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• كيف ترتبط دورة الماء بالطقس؟</li>
                                        <li>• اربط بين التلوث وصحة الإنسان</li>
                                        <li>• وحِّد بين الغذاء والطاقة والنمو</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Roaya Questions -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">👁️</span>
                                <h4 class="text-xl font-bold text-purple-600">رُؤية - أسئلة للأطفال</h4>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h5 class="font-bold text-green-600 mb-2">المستوى 1:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• ارسم منظراً طبيعياً جميلاً</li>
                                        <li>• طبِّق قواعد النظافة في يومك</li>
                                        <li>• استخدم الأشكال لصنع صورة</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-blue-600 mb-2">المستوى 2:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• صمِّم لعبة جديدة لأصدقائك</li>
                                        <li>• ابتكر طريقة لتوفير الماء</li>
                                        <li>• طوِّر خطة لحديقة صغيرة</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-purple-600 mb-2">المستوى 3:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• اخترع أداة تساعد كبار السن</li>
                                        <li>• صمِّم حملة لحماية البيئة</li>
                                        <li>• ابتكر قصة تعلم درساً مهماً</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teens Questions (Hidden) -->
                <div id="teens-questions" class="question-set hidden">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Similar structure with teen-appropriate questions -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🎯</span>
                                <h4 class="text-xl font-bold text-red-600">جَوهر - أسئلة للمراهقين</h4>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h5 class="font-bold text-green-600 mb-2">المستوى 1:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• عرِّف الديمقراطية</li>
                                        <li>• ما هي الطاقة المتجددة؟</li>
                                        <li>• حدِّد أنواع الصخور الثلاثة</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-blue-600 mb-2">المستوى 2:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• صِف بنية الذرة بالتفصيل</li>
                                        <li>• عرِّف النظام البيئي ومكوناته</li>
                                        <li>• حدِّد خصائص الموجات الصوتية</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-purple-600 mb-2">المستوى 3:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• عرِّف نظرية التطور وآلياتها</li>
                                        <li>• صِف تركيب الحمض النووي DNA</li>
                                        <li>• حدِّد مبادئ الاقتصاد الكلي</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Continue with other roots... -->
                    </div>
                </div>

                <!-- Adults Questions (Hidden) -->
                <div id="adults-questions" class="question-set hidden">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Similar structure with adult-appropriate questions -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">🎯</span>
                                <h4 class="text-xl font-bold text-red-600">جَوهر - أسئلة للبالغين</h4>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h5 class="font-bold text-green-600 mb-2">المستوى 1:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• عرِّف الذكاء الاصطناعي</li>
                                        <li>• ما هي البلوك تشين؟</li>
                                        <li>• حدِّد أنواع الاستثمار</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-blue-600 mb-2">المستوى 2:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• صِف نظريات القيادة الحديثة</li>
                                        <li>• عرِّف التحول الرقمي للمؤسسات</li>
                                        <li>• حدِّد عناصر الاستدامة البيئية</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-bold text-purple-600 mb-2">المستوى 3:</h5>
                                    <ul class="space-y-1 text-sm text-gray-700">
                                        <li>• عرِّف نظرية الأنظمة المعقدة</li>
                                        <li>• صِف آليات الحوكمة المؤسسية</li>
                                        <li>• حدِّد أبعاد الأمن السيبراني</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Continue with other roots... -->
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="grid md:grid-cols-3 gap-6 mt-12">
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-purple-100">
                    <div class="text-3xl mb-4 text-center">🎯</div>
                    <h4 class="font-bold text-lg mb-3 text-center">ابدأ بسيطاً</h4>
                    <p class="text-gray-600 text-center">جرب النموذج مع موضوع واحد أولاً قبل التوسع</p>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-blue-100">
                    <div class="text-3xl mb-4 text-center">⚖️</div>
                    <h4 class="font-bold text-lg mb-3 text-center">وازن الجذور</h4>
                    <p class="text-gray-600 text-center">احرص على توزيع متساوٍ للأسئلة على الجذور الأربعة</p>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-green-100">
                    <div class="text-3xl mb-4 text-center">📈</div>
                    <h4 class="font-bold text-lg mb-3 text-center">تابع النمو</h4>
                    <p class="text-gray-600 text-center">استخدم التقارير لتحديد احتياجات كل طالب</p>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="text-center mt-12">
                <a href="{{ route('question.guide') }}" 
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-magic ml-2"></i>
                    <span>دليل إنشاء الأسئلة المتقدم</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-20 bg-gradient-to-br from-purple-600 to-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6">جاهز لتطبيق نموذج جُذور؟</h2>
        <p class="text-xl mb-8 opacity-90">ابدأ رحلة تعليمية جديدة تنمو فيها المعرفة بشكل طبيعي ومتوازن</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-white text-purple-600 font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                <i class="fas fa-user-plus ml-2"></i>
                إنشاء حساب معلم
            </a>
            <a href="{{ route('home') }}" 
               class="bg-transparent border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-purple-600 transition-all">
                <i class="fas fa-home ml-2"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Animation for blob */
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

/* Border width utility */
.border-3 {
    border-width: 3px;
}

/* Example tabs */
.example-tab {
    position: relative;
    overflow: hidden;
}

.example-tab.active {
    background: linear-gradient(135deg, #7c3aed, #3b82f6);
    color: white;
}

.example-panel {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hover effects */
.group:hover .text-5xl {
    animation: pulse 1s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}
</style>
@endpush

@push('scripts')
<script>
// Root details data
const rootDetails = {
    jawhar: {
        title: 'جَوهر - جذر الأساس',
        emoji: '🎯',
        color: 'red',
        content: `
            <div class="text-center mb-8">
                <span class="text-6xl">🎯</span>
                <h2 class="text-3xl font-bold text-red-600 mt-4">جَوهر - جذر الأساس</h2>
                <p class="text-xl text-gray-600 mt-2">فهم "ما هو؟" - الماهية والتعريف</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">المهارات الأساسية:</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>التعريف الدقيق:</strong> القدرة على تحديد المفاهيم بوضوح
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>تحديد المكونات:</strong> معرفة العناصر الأساسية لأي موضوع
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>التمييز والتصنيف:</strong> القدرة على الفصل بين المفاهيم المتشابهة
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">أمثلة على الأسئلة:</h3>
                    <div class="bg-red-50 rounded-xl p-6 space-y-3">
                        <p>• ما هو التمثيل الضوئي؟</p>
                        <p>• عرّف مفهوم الديمقراطية</p>
                        <p>• ما هي مكونات الذرة؟</p>
                        <p>• ما الفرق بين النثر والشعر؟</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-gradient-to-r from-red-100 to-orange-100 rounded-xl p-6">
                <p class="text-center text-lg">
                    <strong>الهدف:</strong> بناء أساس معرفي قوي يمكن البناء عليه في الجذور الأخرى
                </p>
            </div>
        `
    },
    zihn: {
        title: 'ذِهن - جذر التحليل',
        emoji: '🧠',
        color: 'teal',
        content: `
            <div class="text-center mb-8">
                <span class="text-6xl">🧠</span>
                <h2 class="text-3xl font-bold text-teal-600 mt-4">ذِهن - جذر التحليل</h2>
                <p class="text-xl text-gray-600 mt-2">فهم "كيف يعمل؟" - الآليات والعمليات</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">المهارات الأساسية:</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>التحليل العميق:</strong> تفكيك العمليات المعقدة إلى خطوات
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>فهم السببية:</strong> إدراك العلاقات بين الأسباب والنتائج
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>التفكير النقدي:</strong> تقييم المعلومات والحجج بموضوعية
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">أمثلة على الأسئلة:</h3>
                    <div class="bg-teal-50 rounded-xl p-6 space-y-3">
                        <p>• كيف تحول النباتات ضوء الشمس إلى غذاء؟</p>
                        <p>• لماذا تحدث الفصول الأربعة؟</p>
                        <p>• كيف يؤثر العرض والطلب على الأسعار؟</p>
                        <p>• ما آلية عمل الجهاز المناعي؟</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-gradient-to-r from-teal-100 to-cyan-100 rounded-xl p-6">
                <p class="text-center text-lg">
                    <strong>الهدف:</strong> تطوير القدرة على فهم العمليات والأنظمة المعقدة
                </p>
            </div>
        `
    },
    waslat: {
        title: 'وَصلات - جذر الربط',
        emoji: '🔗',
        color: 'yellow',
        content: `
            <div class="text-center mb-8">
                <span class="text-6xl">🔗</span>
                <h2 class="text-3xl font-bold text-yellow-600 mt-4">وَصلات - جذر الربط</h2>
                <p class="text-xl text-gray-600 mt-2">فهم "كيف يرتبط؟" - العلاقات والروابط</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">المهارات الأساسية:</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>الربط بين المفاهيم:</strong> رؤية العلاقات غير الواضحة
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>التفكير الشمولي:</strong> فهم الصورة الكبرى والسياق
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>التكامل المعرفي:</strong> دمج المعارف من مجالات مختلفة
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">أمثلة على الأسئلة:</h3>
                    <div class="bg-yellow-50 rounded-xl p-6 space-y-3">
                        <p>• ما العلاقة بين التغير المناخي والهجرة؟</p>
                        <p>• كيف أثرت الثورة الصناعية على الأدب؟</p>
                        <p>• ما الصلة بين الرياضيات والموسيقى؟</p>
                        <p>• كيف ترتبط اللغة بالهوية الثقافية؟</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-gradient-to-r from-yellow-100 to-orange-100 rounded-xl p-6">
                <p class="text-center text-lg">
                    <strong>الهدف:</strong> بناء شبكات معرفية متكاملة وفهم التأثيرات المتبادلة
                </p>
            </div>
        `
    },
    roaya: {
        title: 'رُؤية - جذر التطبيق',
        emoji: '👁️',
        color: 'purple',
        content: `
            <div class="text-center mb-8">
                <span class="text-6xl">👁️</span>
                <h2 class="text-3xl font-bold text-purple-600 mt-4">رُؤية - جذر التطبيق</h2>
                <p class="text-xl text-gray-600 mt-2">فهم "كيف نستخدمه؟" - التطبيق والإبداع</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">المهارات الأساسية:</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>التطبيق العملي:</strong> استخدام المعرفة في مواقف حقيقية
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>حل المشكلات:</strong> إيجاد حلول مبتكرة للتحديات
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <strong>الإبداع والابتكار:</strong> توليد أفكار جديدة وأصيلة
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">أمثلة على الأسئلة:</h3>
                    <div class="bg-purple-50 rounded-xl p-6 space-y-3">
                        <p>• صمم حلاً لمشكلة نقص المياه في منطقتك</p>
                        <p>• كيف يمكن تطبيق مبادئ الفيزياء في الرياضة؟</p>
                        <p>• اقترح تطبيقاً جديداً للذكاء الاصطناعي</p>
                        <p>• ابتكر طريقة لتعليم الكسور للأطفال</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-gradient-to-r from-purple-100 to-indigo-100 rounded-xl p-6">
                <p class="text-center text-lg">
                    <strong>الهدف:</strong> تحويل المعرفة إلى قيمة عملية وحلول إبداعية
                </p>
            </div>
        `
    }
};

// Show root details
function showRootDetails(root) {
    const detailsPanel = document.getElementById('root-details');
    const contentDiv = document.getElementById('root-content');
    
    // Update content
    contentDiv.innerHTML = rootDetails[root].content;
    
    // Show panel with animation
    detailsPanel.classList.remove('hidden');
    detailsPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Highlight selected card
    document.querySelectorAll('[id$="-card"]').forEach(card => {
        card.querySelector('.bg-white').classList.remove('ring-4', 'ring-offset-4');
    });
    
    const selectedCard = document.getElementById(`${root}-card`);
    selectedCard.querySelector('.bg-white').classList.add('ring-4', 'ring-offset-4', `ring-${rootDetails[root].color}-400`);
}

// Show central concept
function showCentralConcept() {
    const detailsPanel = document.getElementById('root-details');
    const contentDiv = document.getElementById('root-content');
    
    contentDiv.innerHTML = `
        <div class="text-center">
            <span class="text-6xl">🌱</span>
            <h2 class="text-3xl font-bold text-green-600 mt-4 mb-6">البذرة المعرفية</h2>
            
            <div class="max-w-3xl mx-auto space-y-6 text-lg text-gray-700">
                <p>
                    في نموذج جُذور، كل معلومة أو مفهوم هو بمثابة بذرة يمكن أن تنمو في أربعة اتجاهات مختلفة.
                </p>
                
                <div class="bg-green-50 rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4">المبادئ الأساسية:</h3>
                    <ul class="space-y-3 text-right">
                        <li>• كل بذرة معرفية لها إمكانية النمو في جميع الاتجاهات</li>
                        <li>• لا يوجد ترتيب إلزامي للنمو - كل طالب له مساره</li>
                        <li>• القوة تكمن في تشابك الجذور وتكاملها</li>
                        <li>• النمو عملية مستمرة وليس لها نهاية محددة</li>
                    </ul>
                </div>
                
                <p>
                    عندما تزرع بذرة المعرفة وتنمو جذورها في الاتجاهات الأربعة، تتحول إلى شجرة معرفية قوية ومتكاملة.
                </p>
            </div>
        </div>
    `;
    
    detailsPanel.classList.remove('hidden');
    detailsPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Example switcher
function showExample(subject) {
    // Update tab styles
    document.querySelectorAll('.example-tab').forEach(tab => {
        tab.classList.remove('active', 'bg-purple-600', 'text-white');
        tab.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    event.target.classList.add('active', 'bg-purple-600', 'text-white');
    
    // Hide all panels
    document.querySelectorAll('.example-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Show selected panel
    document.getElementById(`${subject}-example`).classList.remove('hidden');
}

// Smooth scroll for internal links
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>
@endpush