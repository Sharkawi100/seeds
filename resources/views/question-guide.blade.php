```blade
@extends('layouts.app')

@section('title', 'دليل إنشاء الأسئلة - نموذج جُذور')

@section('content')
<!-- Hero Section with Modern Gradient -->
<section class="relative py-20 bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900 text-white overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600 rounded-full filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600 rounded-full filter blur-3xl opacity-20 animate-pulse animation-delay-2000"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="animate-fade-in-down">
            <h1 class="text-5xl md:text-6xl font-black mb-6">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-200 via-purple-200 to-pink-200">
                    دليل إنشاء الأسئلة الذكية
                </span>
            </h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto text-gray-100 font-light">
                تعلم كيف تصمم أسئلة تنمي جميع جذور المعرفة لدى طلابك في كل المراحل التعليمية
            </p>
        </div>
    </div>
</section>

<!-- Quick Navigation -->
<section class="py-8 bg-white shadow-md sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4">
            <a href="#action-verbs" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                الأفعال المفتاحية
            </a>
            <a href="#examples" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                أمثلة عملية
            </a>
            <a href="#builder" class="px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl font-bold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                مولد الأسئلة
            </a>
            <a href="#tips" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-bold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                نصائح ذهبية
            </a>
        </div>
    </div>
</section>

<!-- Action Verbs Section -->
<section id="action-verbs" class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">الأفعال المفتاحية لكل جذر</h2>
            <p class="text-xl text-gray-700">استخدم هذه الأفعال لصياغة أسئلة قوية ومحددة</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Jawhar -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-800 rounded-3xl blur-xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative bg-white rounded-3xl shadow-xl p-6 hover:shadow-2xl transition-all hover:-translate-y-2 border border-red-200">
                    <div class="text-center mb-6">
                        <span class="text-5xl">🎯</span>
                        <h3 class="text-2xl font-bold text-red-800 mt-3">جَوهر</h3>
                        <p class="text-gray-700 text-sm">ما هو؟</p>
                    </div>
                    <div class="space-y-2">
                        @php
                        $jawharVerbs = ['عرِّف', 'صِف', 'حدِّد', 'سمِّ', 'اذكُر', 'عدِّد', 'صنِّف', 'ميِّز'];
                        @endphp
                        @foreach($jawharVerbs as $verb)
                        <div class="bg-gradient-to-r from-red-100 to-red-200 rounded-xl px-4 py-3 text-center font-bold text-red-800 hover:from-red-200 hover:to-red-300 transition-all cursor-pointer">
                            {{ $verb }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Zihn -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl blur-xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative bg-white rounded-3xl shadow-xl p-6 hover:shadow-2xl transition-all hover:-translate-y-2 border border-blue-200">
                    <div class="text-center mb-6">
                        <span class="text-5xl">🧠</span>
                        <h3 class="text-2xl font-bold text-blue-800 mt-3">ذِهن</h3>
                        <p class="text-gray-700 text-sm">كيف يعمل؟</p>
                    </div>
                    <div class="space-y-2">
                        @php
                        $zihnVerbs = ['حلِّل', 'اشرَح', 'فسِّر', 'برِّر', 'استنتِج', 'قارِن', 'ناقِش', 'برهِن'];
                        @endphp
                        @foreach($zihnVerbs as $verb)
                        <div class="bg-gradient-to-r from-blue-100 to-blue-200 rounded-xl px-4 py-3 text-center font-bold text-blue-800 hover:from-blue-200 hover:to-blue-300 transition-all cursor-pointer">
                            {{ $verb }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Waslat -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-amber-600 to-amber-800 rounded-3xl blur-xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative bg-white rounded-3xl shadow-xl p-6 hover:shadow-2xl transition-all hover:-translate-y-2 border border-amber-200">
                    <div class="text-center mb-6">
                        <span class="text-5xl">🔗</span>
                        <h3 class="text-2xl font-bold text-amber-800 mt-3">وَصلات</h3>
                        <p class="text-gray-700 text-sm">كيف يرتبط؟</p>
                    </div>
                    <div class="space-y-2">
                        @php
                        $waslatVerbs = ['اربِط', 'وحِّد', 'كامِل', 'نسِّق', 'اجمَع', 'ألِّف', 'ادمِج', 'انسِج'];
                        @endphp
                        @foreach($waslatVerbs as $verb)
                        <div class="bg-gradient-to-r from-amber-100 to-amber-200 rounded-xl px-4 py-3 text-center font-bold text-amber-800 hover:from-amber-200 hover:to-amber-300 transition-all cursor-pointer">
                            {{ $verb }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Roaya -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-800 rounded-3xl blur-xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative bg-white rounded-3xl shadow-xl p-6 hover:shadow-2xl transition-all hover:-translate-y-2 border border-purple-200">
                    <div class="text-center mb-6">
                        <span class="text-5xl">👁️</span>
                        <h3 class="text-2xl font-bold text-purple-800 mt-3">رُؤية</h3>
                        <p class="text-gray-700 text-sm">كيف نستخدمه؟</p>
                    </div>
                    <div class="space-y-2">
                        @php
                        $roayaVerbs = ['طبِّق', 'ابتكِر', 'صمِّم', 'اخترِع', 'طوِّر', 'استخدِم', 'أنشِئ', 'احلُل'];
                        @endphp
                        @foreach($roayaVerbs as $verb)
                        <div class="bg-gradient-to-r from-purple-100 to-purple-200 rounded-xl px-4 py-3 text-center font-bold text-purple-800 hover:from-purple-200 hover:to-purple-300 transition-all cursor-pointer">
                            {{ $verb }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Practical Examples by Educational Level -->
<section id="examples" class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">أمثلة عملية حسب المرحلة التعليمية</h2>
            <p class="text-xl text-gray-700">نماذج أسئلة لكل جذر في المستويات الثلاثة</p>
        </div>

        <!-- Educational Level Tabs -->
        <div class="flex flex-wrap justify-center gap-4 mb-10">
            <button onclick="showLevel('primary')" id="primary-tab" 
                    class="level-tab active px-8 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl font-bold text-lg shadow-lg transform hover:scale-105 transition-all">
                <i class="fas fa-child ml-2"></i>
                المرحلة الابتدائية (6-12)
            </button>
            <button onclick="showLevel('middle')" id="middle-tab" 
                    class="level-tab px-8 py-4 bg-gray-300 text-gray-800 rounded-2xl font-bold text-lg hover:bg-gray-400 transition-all">
                <i class="fas fa-user-graduate ml-2"></i>
                المرحلة الإعدادية (12-15)
            </button>
            <button onclick="showLevel('secondary')" id="secondary-tab" 
                    class="level-tab px-8 py-4 bg-gray-300 text-gray-800 rounded-2xl font-bold text-lg hover:bg-gray-400 transition-all">
                <i class="fas fa-graduation-cap ml-2"></i>
                المرحلة الثانوية (15-18)
            </button>
        </div>

        <!-- Primary School Content -->
        <div id="primary-content" class="level-content">
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Jawhar Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🎯</span> جَوهر - المرحلة الابتدائية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1 (بسيط)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>عرِّف:</strong> ما هو الماء؟</li>
                                <li>• <strong>سمِّ:</strong> ما اسم كوكبنا؟</li>
                                <li>• <strong>عدِّد:</strong> اذكر فصول السنة الأربعة</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2 (متوسط)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>صِف:</strong> كيف يبدو القمر في أطواره المختلفة؟</li>
                                <li>• <strong>حدِّد:</strong> ما الفرق بين الثدييات والطيور؟</li>
                                <li>• <strong>صنِّف:</strong> رتب الحيوانات التالية حسب بيئتها</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3 (متقدم)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>ميِّز:</strong> ما الخصائص المميزة للنباتات الصحراوية؟</li>
                                <li>• <strong>عرِّف:</strong> اشرح مفهوم السلسلة الغذائية بالتفصيل</li>
                                <li>• <strong>حدِّد:</strong> ما مكونات الدورة المائية في الطبيعة؟</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Zihn Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🧠</span> ذِهن - المرحلة الابتدائية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1 (بسيط)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>اشرَح:</strong> لماذا نحتاج إلى النوم؟</li>
                                <li>• <strong>فسِّر:</strong> كيف تساعدنا الشمس؟</li>
                                <li>• <strong>حلِّل:</strong> ماذا يحدث عندما نزرع بذرة؟</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2 (متوسط)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>قارِن:</strong> ما الفرق بين النهار والليل؟</li>
                                <li>• <strong>برِّر:</strong> لماذا يجب علينا توفير الماء؟</li>
                                <li>• <strong>استنتِج:</strong> ماذا يحدث للنباتات بدون ضوء؟</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3 (متقدم)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>حلِّل:</strong> كيف تؤثر الفصول على حياة الحيوانات؟</li>
                                <li>• <strong>ناقِش:</strong> أهمية إعادة التدوير للبيئة</li>
                                <li>• <strong>برهِن:</strong> كيف يساعد التعاون على النجاح؟</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Waslat Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🔗</span> وَصلات - المرحلة الابتدائية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1 (بسيط)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>اربِط:</strong> ما علاقة المطر بنمو النباتات؟</li>
                                <li>• <strong>اجمَع:</strong> كيف ترتبط الحواس الخمس معاً؟</li>
                                <li>• <strong>وحِّد:</strong> ما العلاقة بين الغذاء والطاقة؟</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2 (متوسط)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>كامِل:</strong> كيف تعمل أعضاء الجسم معاً؟</li>
                                <li>• <strong>نسِّق:</strong> ما العلاقة بين التلوث والأمراض؟</li>
                                <li>• <strong>ادمِج:</strong> كيف ترتبط القراءة بالتعلم؟</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3 (متقدم)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>ألِّف:</strong> اربط بين دورة الماء ودورة الحياة</li>
                                <li>• <strong>انسِج:</strong> كيف تترابط المواد الدراسية المختلفة؟</li>
                                <li>• <strong>اربِط:</strong> ما علاقة التكنولوجيا بتطور المجتمع؟</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Roaya Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">👁️</span> رُؤية - المرحلة الابتدائية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1 (بسيط)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>طبِّق:</strong> استخدم ما تعلمته عن النظافة في يومك</li>
                                <li>• <strong>صمِّم:</strong> ارسم لوحة عن فصل الربيع</li>
                                <li>• <strong>أنشِئ:</strong> اصنع نموذجاً بسيطاً للنظام الشمسي</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2 (متوسط)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>ابتكِر:</strong> اخترع طريقة لتوفير الكهرباء في المنزل</li>
                                <li>• <strong>طوِّر:</strong> حسِّن طريقة ترتيب غرفتك</li>
                                <li>• <strong>احلُل:</strong> اقترح حلاً لمشكلة النفايات في المدرسة</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3 (متقدم)</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>اخترِع:</strong> صمم لعبة تعليمية لزملائك الأصغر</li>
                                <li>• <strong>طوِّر:</strong> ضع خطة لحديقة مدرسية صديقة للبيئة</li>
                                <li>• <strong>ابتكِر:</strong> أنشئ مشروعاً لخدمة مجتمعك المحلي</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle School Content (Hidden) -->
        <div id="middle-content" class="level-content hidden">
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Jawhar Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🎯</span> جَوهر - المرحلة الإعدادية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>عرِّف:</strong> ما هو التمثيل الضوئي؟</li>
                                <li>• <strong>حدِّد:</strong> عناصر الجدول الدوري</li>
                                <li>• <strong>صنِّف:</strong> أنواع الصخور في القشرة الأرضية</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>صِف:</strong> مكونات الخلية النباتية والحيوانية</li>
                                <li>• <strong>ميِّز:</strong> بين الأحماض والقواعد</li>
                                <li>• <strong>عرِّف:</strong> النظرية الذرية الحديثة</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>حدِّد:</strong> خصائص الموجات الكهرومغناطيسية</li>
                                <li>• <strong>صِف:</strong> آليات التطور البيولوجي</li>
                                <li>• <strong>عرِّف:</strong> قوانين نيوتن للحركة بالتفصيل</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Zihn Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🧠</span> ذِهن - المرحلة الإعدادية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>حلِّل:</strong> أسباب حدوث الزلازل</li>
                                <li>• <strong>فسِّر:</strong> كيف تعمل الدورة الدموية؟</li>
                                <li>• <strong>قارِن:</strong> بين التنفس الهوائي واللاهوائي</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>برِّر:</strong> أهمية طبقة الأوزون</li>
                                <li>• <strong>استنتِج:</strong> تأثير الاحتباس الحراري</li>
                                <li>• <strong>ناقِش:</strong> دور الإنزيمات في الهضم</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>برهِن:</strong> قانون حفظ الطاقة</li>
                                <li>• <strong>حلِّل:</strong> آليات المناعة في الجسم</li>
                                <li>• <strong>فسِّر:</strong> نظرية الانفجار العظيم</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Waslat Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🔗</span> وَصلات - المرحلة الإعدادية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>اربِط:</strong> بين الغذاء والطاقة في الجسم</li>
                                <li>• <strong>وحِّد:</strong> العلاقة بين الرياضيات والفيزياء</li>
                                <li>• <strong>اجمَع:</strong> كيف تترابط أجهزة الجسم؟</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>كامِل:</strong> بين التفاعلات الكيميائية والحياة</li>
                                <li>• <strong>نسِّق:</strong> دور التكنولوجيا في العلوم</li>
                                <li>• <strong>ادمِج:</strong> العلاقة بين البيئة والصحة</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>ألِّف:</strong> نموذجاً يربط بين العلوم المختلفة</li>
                                <li>• <strong>انسِج:</strong> شبكة مفاهيم للنظم البيئية</li>
                                <li>• <strong>اربِط:</strong> بين الوراثة والتطور</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Roaya Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">👁️</span> رُؤية - المرحلة الإعدادية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>طبِّق:</strong> قوانين الفيزياء في الحياة اليومية</li>
                                <li>• <strong>صمِّم:</strong> تجربة علمية بسيطة</li>
                                <li>• <strong>استخدِم:</strong> الجدول الدوري في التنبؤ</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>ابتكِر:</strong> حلاً لمشكلة بيئية</li>
                                <li>• <strong>طوِّر:</strong> نموذجاً لتوفير الطاقة</li>
                                <li>• <strong>أنشِئ:</strong> مشروعاً علمياً مبتكراً</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>اخترِع:</strong> جهازاً يحل مشكلة علمية</li>
                                <li>• <strong>طوِّر:</strong> نظرية جديدة بناءً على ملاحظاتك</li>
                                <li>• <strong>احلُل:</strong> تحدياً علمياً معقداً</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary School Content (Hidden) -->
        <div id="secondary-content" class="level-content hidden">
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Jawhar Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🎯</span> جَوهر - المرحلة الثانوية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>عرِّف:</strong> مفهوم الحمض النووي RNA</li>
                                <li>• <strong>حدِّد:</strong> قوانين الديناميكا الحرارية</li>
                                <li>• <strong>صنِّف:</strong> أنواع التفاعلات الكيميائية</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>صِف:</strong> آليات التعبير الجيني</li>
                                <li>• <strong>ميِّز:</strong> بين النسبية الخاصة والعامة</li>
                                <li>• <strong>عرِّف:</strong> الكيمياء العضوية المتقدمة</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>حدِّد:</strong> مبادئ ميكانيكا الكم</li>
                                <li>• <strong>صِف:</strong> البيولوجيا الجزيئية المتقدمة</li>
                                <li>• <strong>عرِّف:</strong> نظرية الأوتار الفائقة</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Zihn Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🧠</span> ذِهن - المرحلة الثانوية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>حلِّل:</strong> آليات التحفيز الإنزيمي</li>
                                <li>• <strong>فسِّر:</strong> ظاهرة التداخل الموجي</li>
                                <li>• <strong>قارِن:</strong> بين الانقسام الميوزي والميتوزي</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>برِّر:</strong> أهمية التنوع الجيني</li>
                                <li>• <strong>استنتِج:</strong> تطبيقات النانوتكنولوجي</li>
                                <li>• <strong>ناقِش:</strong> نظريات أصل الكون</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>برهِن:</strong> معادلات ماكسويل</li>
                                <li>• <strong>حلِّل:</strong> الشبكات العصبية الاصطناعية</li>
                                <li>• <strong>فسِّر:</strong> التشابك الكمي</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Waslat Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">🔗</span> وَصلات - المرحلة الثانوية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>اربِط:</strong> بين الفيزياء والطب الحديث</li>
                                <li>• <strong>وحِّد:</strong> مفاهيم الكيمياء والبيولوجيا</li>
                                <li>• <strong>اجمَع:</strong> العلوم الأساسية والتطبيقية</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>كامِل:</strong> بين الذكاء الاصطناعي والعلوم</li>
                                <li>• <strong>نسِّق:</strong> التكامل بين العلوم البينية</li>
                                <li>• <strong>ادمِج:</strong> النظريات العلمية المعاصرة</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>ألِّف:</strong> نظرية موحدة للعلوم</li>
                                <li>• <strong>انسِج:</strong> شبكة مفاهيم متقدمة</li>
                                <li>• <strong>اربِط:</strong> بين الأبحاث متعددة التخصصات</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Roaya Examples -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-5">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="text-3xl">👁️</span> رُؤية - المرحلة الثانوية
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="border-r-4 border-emerald-600 pr-4 bg-emerald-100 p-4 rounded-xl">
                            <h4 class="font-bold text-emerald-800 mb-3">المستوى 1</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>طبِّق:</strong> مبادئ الهندسة الوراثية</li>
                                <li>• <strong>صمِّم:</strong> نظاماً للطاقة المتجددة</li>
                                <li>• <strong>استخدِم:</strong> تقنيات التحليل المتقدمة</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-blue-600 pr-4 bg-blue-100 p-4 rounded-xl">
                            <h4 class="font-bold text-blue-800 mb-3">المستوى 2</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>ابتكِر:</strong> تطبيقاً علمياً مبتكراً</li>
                                <li>• <strong>طوِّر:</strong> بروتوكولاً بحثياً جديداً</li>
                                <li>• <strong>أنشِئ:</strong> نموذجاً حاسوبياً متقدماً</li>
                            </ul>
                        </div>
                        <div class="border-r-4 border-purple-600 pr-4 bg-purple-100 p-4 rounded-xl">
                            <h4 class="font-bold text-purple-800 mb-3">المستوى 3</h4>
                            <ul class="space-y-2 text-gray-800">
                                <li>• <strong>اخترِع:</strong> تقنية ثورية جديدة</li>
                                <li>• <strong>طوِّر:</strong> براءة اختراع علمية</li>
                                <li>• <strong>احلُل:</strong> مشكلة علمية عالمية</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Question Builder -->
<section id="builder" class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">🛠️ مولد الأسئلة التفاعلي</h2>
            <p class="text-xl text-gray-700">اختر المعايير لتوليد أمثلة مخصصة لأسئلتك</p>
        </div>

        <div class="bg-gradient-to-br from-gray-100 to-white rounded-3xl shadow-2xl p-8 border border-gray-200">
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <!-- Root Selector -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-3">اختر الجذر</label>
                    <select id="root-selector" class="w-full px-4 py-4 border-2 border-gray-400 rounded-2xl focus:border-purple-600 focus:ring-4 focus:ring-purple-200 transition-all text-lg bg-white text-gray-800">
                        <option value="jawhar">🎯 جَوهر - الأساس</option>
                        <option value="zihn">🧠 ذِهن - التحليل</option>
                        <option value="waslat">🔗 وَصلات - الربط</option>
                        <option value="roaya">👁️ رُؤية - التطبيق</option>
                    </select>
                </div>

                <!-- Level Selector -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-3">اختر المستوى</label>
                    <select id="level-selector" class="w-full px-4 py-4 border-2 border-gray-400 rounded-2xl focus:border-purple-600 focus:ring-4 focus:ring-purple-200 transition-all text-lg bg-white text-gray-800">
                        <option value="1">المستوى 1 - بسيط</option>
                        <option value="2">المستوى 2 - متوسط</option>
                        <option value="3">المستوى 3 - متقدم</option>
                    </select>
                </div>

                <!-- Subject Selector -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-3">اختر المادة</label>
                    <select id="subject-selector" class="w-full px-4 py-4 border-2 border-gray-400 rounded-2xl focus:border-purple-600 focus:ring-4 focus:ring-purple-200 transition-all text-lg bg-white text-gray-800">
                        <option value="science">العلوم</option>
                        <option value="arabic">اللغة العربية</option>
                        <option value="math">الرياضيات</option>
                        <option value="history">التاريخ</option>
                        <option value="general">عام</option>
                    </select>
                </div>
            </div>

            <button onclick="generateQuestions()" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-5 px-8 rounded-2xl hover:shadow-xl transform hover:-translate-y-0.5 transition-all text-lg flex items-center justify-center gap-3">
                <i class="fas fa-magic text-2xl"></i>
                <span>توليد أمثلة على الأسئلة</span>
            </button>

            <!-- Generated Questions Display -->
            <div id="generated-questions" class="mt-8 hidden">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">أمثلة الأسئلة المولدة:</h3>
                <div id="questions-list" class="space-y-4">
                    <!-- Questions will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tips Section -->
<section id="tips" class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">💡 نصائح ذهبية لكتابة الأسئلة</h2>
            <p class="text-xl text-gray-700">إرشادات عملية لصياغة أسئلة فعالة ومؤثرة</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $tips = [
                [
                    'icon' => '🎯',
                    'title' => 'الوضوح والدقة',
                    'description' => 'اجعل السؤال واضحاً ومحدداً، تجنب الغموض والعبارات المزدوجة المعنى',
                    'color' => 'red'
                ],
                [
                    'icon' => '🎨',
                    'title' => 'التنوع والإبداع',
                    'description' => 'نوّع في صيغ الأسئلة واستخدم سياقات مختلفة لإثارة اهتمام الطلاب',
                    'color' => 'purple'
                ],
                [
                    'icon' => '📊',
                    'title' => 'التدرج في الصعوبة',
                    'description' => 'ابدأ بالأسئلة السهلة وتدرج نحو الأصعب لبناء ثقة الطالب',
                    'color' => 'blue'
                ],
                [
                    'icon' => '🌍',
                    'title' => 'الربط بالواقع',
                    'description' => 'اربط الأسئلة بخبرات الطلاب اليومية وبيئتهم المحيطة',
                    'color' => 'emerald'
                ],
                [
                    'icon' => '⚖️',
                    'title' => 'التوازن بين الجذور',
                    'description' => 'احرص على توزيع متوازن للأسئلة على جميع الجذور الأربعة',
                    'color' => 'amber'
                ],
                [
                    'icon' => '🔄',
                    'title' => 'المراجعة والتحسين',
                    'description' => 'راجع أسئلتك باستمرار وحسّنها بناءً على أداء الطلاب',
                    'color' => 'indigo'
                ]
            ];
            @endphp

            @foreach($tips as $tip)
            <div class="group">
                <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2 border border-{{ $tip['color'] }}-200">
                    <div class="text-5xl mb-4">{{ $tip['icon'] }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $tip['title'] }}</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $tip['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 text-white relative overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-700/30 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-700/30 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6">جاهز لإنشاء اختبارات ذكية؟</h2>
        <p class="text-xl text-gray-200 mb-10 leading-relaxed">
            ابدأ الآن في تصميم أسئلة تنمي جميع جذور المعرفة لدى طلابك
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('quizzes.create') }}" class="group relative inline-flex items-center gap-3 bg-white text-gray-900 font-bold py-5 px-10 rounded-2xl overflow-hidden transition-all transform hover:scale-105 hover:shadow-2xl">
                <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-purple-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <i class="fas fa-plus-circle relative z-10 group-hover:text-white transition-colors text-xl"></i>
                <span class="relative z-10 group-hover:text-white transition-colors text-lg">إنشاء اختبار جديد</span>
            </a>
            <a href="{{ route('juzoor.model') }}" class="inline-flex items-center gap-3 bg-gray-800 border-2 border-gray-600 text-gray-100 font-bold py-5 px-10 rounded-2xl hover:bg-gray-700 hover:border-gray-500 transition-all text-lg">
                <i class="fas fa-book text-xl"></i>
                <span>المزيد عن النموذج</span>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Educational level switcher
function showLevel(level) {
    // Update tabs
    document.querySelectorAll('.level-tab').forEach(tab => {
        tab.classList.remove('active', 'bg-gradient-to-r', 'from-emerald-600', 'to-teal-600', 'text-white', 'shadow-lg', 'transform', 'scale-105');
        tab.classList.add('bg-gray-300', 'text-gray-800');
    });
    
    document.getElementById(`${level}-tab`).classList.remove('bg-gray-300', 'text-gray-800');
    document.getElementById(`${level}-tab`).classList.add('active', 'bg-gradient-to-r', 'from-emerald-600', 'to-teal-600', 'text-white', 'shadow-lg', 'transform', 'scale-105');
    
    // Update content
    document.querySelectorAll('.level-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.getElementById(`${level}-content`).classList.remove('hidden');
}

// Question generator
const questionTemplates = {
    jawhar: {
        1: {
            science: ["ما هو {topic}؟", "عرِّف {topic}", "اذكر أنواع {topic}", "صِف شكل {topic}"],
            arabic: ["ما معنى كلمة {topic}؟", "عدِّد أنواع {topic}", "حدِّد عناصر {topic}"],
            math: ["ما هو {topic}؟", "عرِّف {topic} في الرياضيات", "اذكر خصائص {topic}"],
            history: ["من هو {topic}؟", "متى حدث {topic}؟", "أين وقع {topic}؟"],
            general: ["عرِّف {topic}", "ما المقصود بـ {topic}؟", "حدِّد مفهوم {topic}"]
        },
        2: {
            science: ["صِف مكونات {topic} بالتفصيل", "عرِّف {topic} وأهميته", "حدِّد الفرق بين {topic} و{related}"],
            arabic: ["عرِّف {topic} مع ذكر أمثلة", "صِف خصائص {topic} الأدبية", "حدِّد أنواع {topic} واستخداماتها"],
            math: ["عرِّف {topic} واذكر قوانينه", "صِف العلاقة بين {topic} و{related}", "حدِّد شروط {topic}"],
            history: ["صِف أحداث {topic} الرئيسية", "عرِّف {topic} في سياقه التاريخي", "حدِّد أسباب {topic}"],
            general: ["عرِّف {topic} بشكل شامل", "صِف مراحل {topic}", "حدِّد معايير {topic}"]
        },
        3: {
            science: ["عرِّف {topic} مع شرح آلياته المعقدة", "صِف {topic} من منظور علمي متقدم", "حدِّد جميع جوانب {topic}"],
            arabic: ["عرِّف {topic} في السياق الأدبي والثقافي", "صِف تطور {topic} عبر العصور", "حدِّد مدارس {topic} المختلفة"],
            math: ["عرِّف {topic} مع البراهين الرياضية", "صِف تطبيقات {topic} المتقدمة", "حدِّد {topic} في الأنظمة المعقدة"],
            history: ["عرِّف {topic} وتأثيراته الحضارية", "صِف {topic} من منظور تاريخي شامل", "حدِّد أبعاد {topic} المختلفة"],
            general: ["قدم تعريفاً شاملاً ومتكاملاً لـ {topic}", "صِف {topic} من جميع الزوايا", "حدِّد {topic} في إطاره الشامل"]
        }
    },
    zihn: {
        1: {
            science: ["كيف يعمل {topic}؟", "لماذا يحدث {topic}؟", "اشرح عملية {topic}"],
            arabic: ["كيف يؤثر {topic}؟", "لماذا نستخدم {topic}؟", "حلِّل بنية {topic}"],
            math: ["كيف نحسب {topic}؟", "لماذا نحتاج {topic}؟", "اشرح خطوات {topic}"],
            history: ["لماذا حدث {topic}؟", "كيف تطور {topic}؟", "حلِّل أسباب {topic}"],
            general: ["اشرح كيف يعمل {topic}", "حلِّل عملية {topic}", "فسِّر آلية {topic}"]
        },
        2: {
            science: ["حلِّل العمليات المعقدة في {topic}", "قارن بين آليات {topic} المختلفة", "استنتج قوانين {topic}"],
            arabic: ["حلِّل البنية الداخلية لـ {topic}", "قارن بين أساليب {topic}", "فسِّر تأثير {topic} على المعنى"],
            math: ["برهن صحة {topic}", "حلِّل العلاقات في {topic}", "استنتج قواعد {topic}"],
            history: ["حلِّل العوامل المؤثرة في {topic}", "قارن بين نتائج {topic}", "فسِّر تداعيات {topic}"],
            general: ["حلِّل {topic} تحليلاً عميقاً", "قارن بين جوانب {topic}", "استنتج مبادئ {topic}"]
        },
        3: {
            science: ["حلِّل {topic} على المستوى الجزيئي/الذري", "برِّر النظريات المتعلقة بـ {topic}", "استنتج التطبيقات المستقبلية لـ {topic}"],
            arabic: ["حلِّل {topic} من منظور نقدي متقدم", "قارن بين النظريات حول {topic}", "فسِّر {topic} في ضوء المناهج الحديثة"],
            math: ["برهن {topic} باستخدام طرق متقدمة", "حلِّل {topic} في الأنظمة غير الخطية", "استنتج تعميمات {topic}"],
            history: ["حلِّل {topic} من منظور متعدد الأبعاد", "قارن بين التفسيرات التاريخية لـ {topic}", "استنتج الدروس من {topic}"],
            general: ["قدم تحليلاً نقدياً شاملاً لـ {topic}", "حلِّل {topic} من زوايا متعددة", "استنتج النظريات من {topic}"]
        }
    },
    waslat: {
        1: {
            science: ["ما علاقة {topic} بـ {related}؟", "اربط بين {topic} والحياة اليومية", "كيف يرتبط {topic} بالبيئة؟"],
            arabic: ["اربط بين {topic} والثقافة", "ما علاقة {topic} باللغة؟", "وحِّد بين {topic} والأدب"],
            math: ["اربط {topic} بالعلوم الأخرى", "ما علاقة {topic} بالحياة العملية؟", "كيف يرتبط {topic} بالتكنولوجيا؟"],
            history: ["اربط {topic} بالأحداث المعاصرة", "ما علاقة {topic} بالحاضر؟", "كيف أثر {topic} على الثقافة؟"],
            general: ["اربط {topic} بمجالات أخرى", "وحِّد بين {topic} والمفاهيم المشابهة", "اجمع بين {topic} وتطبيقاته"]
        },
        2: {
            science: ["كامل بين {topic} ونظريات أخرى", "نسِّق بين {topic} والتقنيات الحديثة", "ألِّف بين {topic} والاستدامة"],
            arabic: ["اربط {topic} بالمدارس الأدبية المختلفة", "وحِّد بين {topic} والنظريات النقدية", "كامل بين {topic} والسياق الثقافي"],
            math: ["نسِّق بين {topic} والنماذج الرياضية", "اربط {topic} بالذكاء الاصطناعي", "وحِّد بين {topic} والفيزياء"],
            history: ["ألِّف بين {topic} والتحولات الاجتماعية", "اربط {topic} بالنظريات السياسية", "نسِّق بين {topic} والاقتصاد"],
            general: ["كامل بين {topic} والمفاهيم المعقدة", "نسِّق بين جوانب {topic} المختلفة", "ألِّف رؤية شاملة حول {topic}"]
        },
        3: {
            science: ["ألِّف نظرية تربط {topic} بمجالات متعددة", "كامل بين {topic} والأبحاث المتقدمة", "نسِّق رؤية شمولية لـ {topic}"],
            arabic: ["ألِّف بين {topic} والفلسفات المختلفة", "كامل بين {topic} والدراسات البينية", "اربط {topic} بالنظريات العالمية"],
            math: ["نسِّق بين {topic} ونظرية الأنظمة", "ألِّف بين {topic} والنمذجة المعقدة", "اربط {topic} بالأبعاد المتعددة"],
            history: ["ألِّف سردية شاملة تربط {topic} بالتاريخ العالمي", "كامل بين {topic} ونظريات التاريخ", "نسِّق رؤية حضارية لـ {topic}"],
            general: ["قدم تصوراً متكاملاً يربط {topic} بكل شيء", "ألِّف نظرية شاملة حول {topic}", "اربط {topic} بالمنظومة الكونية"]
        }
    },
    roaya: {
        1: {
            science: ["كيف تستخدم {topic} في حياتك؟", "صمِّم تجربة بسيطة عن {topic}", "طبِّق {topic} في موقف يومي"],
            arabic: ["استخدم {topic} في كتابة نص", "طبِّق قواعد {topic}", "ابتكر مثالاً على {topic}"],
            math: ["استخدم {topic} لحل مشكلة", "طبِّق {topic} في موقف عملي", "صمِّم نشاطاً يستخدم {topic}"],
            history: ["كيف نستفيد من درس {topic}؟", "طبِّق مبادئ {topic} اليوم", "استخدم {topic} في فهم الحاضر"],
            general: ["طبِّق {topic} في مجالك", "استخدم {topic} لحل مشكلة", "ابتكر تطبيقاً لـ {topic}"]
        },
        2: {
            science: ["طوِّر تطبيقاً يستخدم {topic}", "صمِّم مشروعاً قائماً على {topic}", "ابتكر حلاً لمشكلة باستخدام {topic}"],
            arabic: ["ابتكر نصاً أدبياً يوظف {topic}", "طوِّر أسلوباً جديداً في {topic}", "صمِّم مشروعاً ثقافياً حول {topic}"],
            math: ["طوِّر نموذجاً رياضياً لـ {topic}", "صمِّم برنامجاً يطبق {topic}", "ابتكر لعبة تعليمية عن {topic}"],
            history: ["طوِّر مشروعاً يحيي {topic}", "صمِّم متحفاً افتراضياً عن {topic}", "ابتكر وسيلة لتعليم {topic}"],
            general: ["طوِّر مشروعاً متكاملاً حول {topic}", "صمِّم حلاً مبتكراً باستخدام {topic}", "ابتكر منتجاً يطبق {topic}"]
        },
        3: {
            science: ["اخترع تقنية جديدة تعتمد على {topic}", "طوِّر نظرية تطبيقية لـ {topic}", "صمِّم مستقبل {topic}"],
            arabic: ["ابتكر مدرسة أدبية جديدة في {topic}", "طوِّر منهجية نقدية لـ {topic}", "صمِّم مشروعاً حضارياً يوظف {topic}"],
            math: ["اخترع تطبيقاً متقدماً لـ {topic}", "طوِّر خوارزمية جديدة تستخدم {topic}", "صمِّم نظاماً معقداً يطبق {topic}"],
            history: ["طوِّر رؤية مستقبلية مستوحاة من {topic}", "صمِّم نموذجاً للتنمية يستفيد من {topic}", "ابتكر حلولاً للتحديات المعاصرة من {topic}"],
            general: ["اخترع مفهوماً جديداً يطور {topic}", "طوِّر نظاماً متكاملاً يطبق {topic}", "صمِّم المستقبل باستخدام {topic}"]
        }
    }
};

function generateQuestions() {
    const root = document.getElementById('root-selector').value;
    const level = document.getElementById('level-selector').value;
    const subject = document.getElementById('subject-selector').value;
    
    const templates = questionTemplates[root][level][subject];
    const topics = {
        science: ['الضوء', 'الطاقة', 'الخلية', 'الجاذبية', 'التفاعل الكيميائي'],
        arabic: ['الشعر', 'النحو', 'البلاغة', 'القصة', 'المقال'],
        math: ['المعادلات', 'الهندسة', 'الاحتمالات', 'التفاضل', 'المصفوفات'],
        history: ['الثورة الصناعية', 'الحضارة الإسلامية', 'الحرب العالمية', 'النهضة', 'الاستعمار'],
        general: ['التعلم', 'القيادة', 'الإبداع', 'التواصل', 'التخطيط']
    };
    
    const relatedTopics = {
        science: ['الصوت', 'المادة', 'الأنسجة', 'القوة', 'المحلول'],
        arabic: ['النثر', 'الصرف', 'النقد', 'الرواية', 'الخطبة'],
        math: ['الدوال', 'الجبر', 'الإحصاء', 'التكامل', 'المتجهات'],
        history: ['الثورة الزراعية', 'الحضارة الفرعونية', 'الحرب الباردة', 'التنوير', 'الاستقلال'],
        general: ['التعليم', 'الإدارة', 'الابتكار', 'التفاوض', 'التنفيذ']
    };
    
    const selectedTopics = topics[subject];
    const selectedRelated = relatedTopics[subject];
    
    let questionsHTML = '';
    templates.forEach((template, index) => {
        const topic = selectedTopics[index % selectedTopics.length];
        const related = selectedRelated[index % selectedRelated.length];
        const question = template.replace(/{topic}/g, topic).replace(/{related}/g, related);
        
        // Determine color based on root
        const colors = {
            jawhar: 'red',
            zihn: 'blue',
            waslat: 'amber',
            roaya: 'purple'
        };
        const color = colors[root];
        
        questionsHTML += `
            <div class="bg-gradient-to-r from-${color}-100 to-${color}-200 rounded-2xl p-6 hover:shadow-lg transition-all transform hover:-translate-y-1">
                <div class="flex items-start gap-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-${color}-600 to-${color}-700 text-white rounded-full flex items-center justify-center font-bold shadow-lg">
                        ${index + 1}
                    </span>
                    <p class="text-gray-800 flex-1 text-lg font-medium">${question}</p>
                </div>
            </div>
        `;
    });
    
    document.getElementById('questions-list').innerHTML = questionsHTML;
    document.getElementById('generated-questions').classList.remove('hidden');
    
    // Scroll to generated questions
    document.getElementById('generated-questions').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const offset = 100; // Account for sticky nav
            const targetPosition = target.offsetTop - offset;
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Initialize with animations
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to sections
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });
});
</script>
@endpush

@push('styles')
<style>
/* Animations */
@keyframes fade-in-down {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-down {
    animation: fade-in-down 0.8s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

/* Sticky navigation shadow */
.sticky {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Smooth transitions */
* {
    transition-property: transform, box-shadow, opacity, background-color;
    transition-duration: 300ms;
    transition-timing-function: ease-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    background: #f3f4f6;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #7c3aed, #6366f1);
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #6d28d9, #4f46e5);
}

/* Level tab styles */
.level-tab {
    position: relative;
    overflow: hidden;
}

.level-tab::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.level-tab:hover::before {
    left: 100%;
}

/* Hover effects */
.group:hover {
    z-index: 10;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    section {
        page-break-inside: avoid;
    }
}
</style>
@endpush
```