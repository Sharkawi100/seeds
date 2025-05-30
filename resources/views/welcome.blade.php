<!-- File: resources/views/welcome.blade.php -->
@extends('layouts.guest')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 opacity-90"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Logo and Title -->
        <div class="mb-8 animate-fade-in-up">
            <h1 class="text-6xl md:text-8xl font-black mb-4">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600">جُذور</span>
            </h1>
            <p class="text-2xl md:text-3xl text-gray-700 font-bold">نموذج تعليمي مبتكر</p>
        </div>

        <!-- Tagline -->
        <p class="text-xl md:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto animate-fade-in-up animation-delay-200">
            حيث ينمو كل طالب بطريقته الخاصة - أربعة جذور للمعرفة، إمكانيات لا محدودة للنمو
        </p>

        <!-- Quick Actions -->
        <div class="flex flex-col md:flex-row gap-6 justify-center items-center mb-12 animate-fade-in-up animation-delay-400">
            <!-- PIN Entry Card -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-8 max-w-md w-full transform hover:scale-105 transition-all">
                <h3 class="text-2xl font-bold mb-4 text-gray-800">ادخل الاختبار مباشرة</h3>
                <form action="{{ route('quiz.enter-pin') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" 
                           name="pin" 
                           placeholder="أدخل رمز الاختبار"
                           class="w-full px-6 py-4 text-2xl text-center border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all"
                           required>
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        دخول الاختبار
                    </button>
                </form>
                <p class="text-sm text-gray-500 mt-4">احصل على رمز الاختبار من معلمك</p>
            </div>

            <!-- OR Divider -->
            <div class="hidden md:flex items-center">
                <div class="text-3xl font-bold text-gray-400 mx-8">أو</div>
            </div>

            <!-- CTA Buttons -->
            <div class="space-y-4">
                <a href="{{ route('register') }}" 
                   class="block bg-gradient-to-r from-green-500 to-teal-500 text-white font-bold py-4 px-12 rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all text-xl">
                    إنشاء حساب مجاني
                </a>
                <a href="#demo" 
                   class="block bg-white text-purple-600 font-bold py-4 px-12 rounded-xl border-2 border-purple-600 hover:bg-purple-50 transition-all text-xl">
                    جرّب نموذج تجريبي
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </div>
</section>

<!-- What is Juzoor Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-800 mb-4">ما هو نموذج جُذور؟</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                إطار تعليمي مبتكر يُشبه نمو النبات، حيث ينطلق المتعلم من البذرة (المعلومة الأساسية) وينمو في أربعة اتجاهات متكاملة
            </p>
        </div>

        <!-- Interactive Roots Visualization -->
        <div class="relative max-w-4xl mx-auto mb-16">
            <div class="grid grid-cols-2 gap-8">
                <!-- Jawhar -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-400 to-red-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-all"></div>
                    <div class="relative bg-white rounded-2xl shadow-lg p-8 border-2 border-red-200 hover:border-red-400 transition-all transform hover:-translate-y-2">
                        <div class="text-5xl mb-4">🎯</div>
                        <h3 class="text-2xl font-bold text-red-600 mb-2">جَوهر</h3>
                        <p class="text-lg text-gray-700 mb-4">ما هو؟</p>
                        <p class="text-gray-600">فهم الماهية والتعريف والمكونات الأساسية</p>
                        <ul class="mt-4 space-y-2 text-sm text-gray-500">
                            <li>• التعريف الدقيق</li>
                            <li>• تحديد المكونات</li>
                            <li>• التمييز بين المفاهيم</li>
                        </ul>
                    </div>
                </div>

                <!-- Zihn -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-teal-400 to-teal-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-all"></div>
                    <div class="relative bg-white rounded-2xl shadow-lg p-8 border-2 border-teal-200 hover:border-teal-400 transition-all transform hover:-translate-y-2">
                        <div class="text-5xl mb-4">🧠</div>
                        <h3 class="text-2xl font-bold text-teal-600 mb-2">ذِهن</h3>
                        <p class="text-lg text-gray-700 mb-4">كيف يعمل؟</p>
                        <p class="text-gray-600">فهم الآليات والعمليات والأسباب والنتائج</p>
                        <ul class="mt-4 space-y-2 text-sm text-gray-500">
                            <li>• التحليل العميق</li>
                            <li>• فهم العلاقات السببية</li>
                            <li>• شرح العمليات</li>
                        </ul>
                    </div>
                </div>

                <!-- Waslat -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-all"></div>
                    <div class="relative bg-white rounded-2xl shadow-lg p-8 border-2 border-yellow-200 hover:border-yellow-400 transition-all transform hover:-translate-y-2">
                        <div class="text-5xl mb-4">🔗</div>
                        <h3 class="text-2xl font-bold text-yellow-600 mb-2">وَصلات</h3>
                        <p class="text-lg text-gray-700 mb-4">كيف يرتبط؟</p>
                        <p class="text-gray-600">اكتشاف العلاقات والروابط مع مفاهيم أخرى</p>
                        <ul class="mt-4 space-y-2 text-sm text-gray-500">
                            <li>• الربط بين المفاهيم</li>
                            <li>• رؤية الصورة الكبيرة</li>
                            <li>• التكامل المعرفي</li>
                        </ul>
                    </div>
                </div>

                <!-- Roaya -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-purple-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-all"></div>
                    <div class="relative bg-white rounded-2xl shadow-lg p-8 border-2 border-purple-200 hover:border-purple-400 transition-all transform hover:-translate-y-2">
                        <div class="text-5xl mb-4">👁️</div>
                        <h3 class="text-2xl font-bold text-purple-600 mb-2">رُؤية</h3>
                        <p class="text-lg text-gray-700 mb-4">كيف نستخدمه؟</p>
                        <p class="text-gray-600">التطبيق العملي والإبداع والابتكار</p>
                        <ul class="mt-4 space-y-2 text-sm text-gray-500">
                            <li>• التطبيق العملي</li>
                            <li>• حل المشكلات</li>
                            <li>• الابتكار والإبداع</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Benefits -->
        <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-3xl p-12">
            <h3 class="text-3xl font-bold text-center mb-12 text-gray-800">لماذا جُذور مختلف؟</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">لا يوجد فشل</h4>
                    <p class="text-gray-600">فقط مستويات مختلفة من النمو - كل طالب ينمو بطريقته</p>
                </div>
                <div class="text-center">
                    <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">نمو متعدد الاتجاهات</h4>
                    <p class="text-gray-600">يمكن البدء من أي جذر والنمو في أي اتجاه</p>
                </div>
                <div class="text-center">
                    <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">تعلم شامل</h4>
                    <p class="text-gray-600">يغطي جميع جوانب المعرفة من الأساسيات للتطبيق</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Activity Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-black text-center mb-12 text-gray-800">النشاط الحالي</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Active Subjects -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                    <span class="text-3xl ml-3">📚</span>
                    المواد النشطة اليوم
                </h3>
                <div class="space-y-4">
                    @foreach($activeSubjects ?? [] as $subject)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium">{{ $subject['name'] }}</span>
                        <span class="text-sm text-gray-500">{{ $subject['count'] }} اختبار</span>
                    </div>
                    @endforeach
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium">اللغة العربية</span>
                        <span class="text-sm text-gray-500">12 اختبار</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium">الرياضيات</span>
                        <span class="text-sm text-gray-500">8 اختبارات</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium">العلوم</span>
                        <span class="text-sm text-gray-500">5 اختبارات</span>
                    </div>
                </div>
            </div>

            <!-- Growth Stats -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                    <span class="text-3xl ml-3">🌱</span>
                    نمو الطلاب هذا الأسبوع
                </h3>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium text-red-600">جَوهر</span>
                            <span class="text-sm text-gray-500">+15%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-red-400 to-red-600 h-3 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium text-teal-600">ذِهن</span>
                            <span class="text-sm text-gray-500">+22%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-teal-400 to-teal-600 h-3 rounded-full" style="width: 82%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium text-yellow-600">وَصلات</span>
                            <span class="text-sm text-gray-500">+18%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-3 rounded-full" style="width: 68%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium text-purple-600">رُؤية</span>
                            <span class="text-sm text-gray-500">+12%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-3 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                    <span class="text-3xl ml-3">📊</span>
                    إحصائيات سريعة
                </h3>
                <div class="space-y-6">
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <div class="text-3xl font-bold text-purple-600">{{ $stats['total_quizzes'] ?? '156' }}</div>
                        <div class="text-gray-600">اختبار تم إنشاؤه</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <div class="text-3xl font-bold text-blue-600">{{ $stats['total_attempts'] ?? '2,341' }}</div>
                        <div class="text-gray-600">محاولة هذا الشهر</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <div class="text-3xl font-bold text-green-600">{{ $stats['active_schools'] ?? '12' }}</div>
                        <div class="text-gray-600">مدرسة نشطة</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Demo Section -->
<section id="demo" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-gray-800 mb-4">جرّب النموذج بنفسك</h2>
            <p class="text-xl text-gray-600">اختبار تجريبي قصير لفهم كيفية عمل نموذج جُذور</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl p-8 md:p-12">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h3 class="text-2xl font-bold mb-4">اختبار تجريبي: النباتات</h3>
                        <p class="text-gray-600 mb-6">
                            اختبار قصير للصف الرابع حول موضوع النباتات يُظهر كيف تعمل الجذور الأربعة
                        </p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                10 أسئلة فقط
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                نتائج فورية مع شرح
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                بدون تسجيل
                            </li>
                        </ul>
                        <a href="{{ route('quiz.demo') }}" 
                           class="inline-block bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all">
                            ابدأ الاختبار التجريبي
                        </a>
                    </div>
                    <div class="relative">
                        <img src="/images/demo-preview.png" alt="معاينة الاختبار" class="rounded-2xl shadow-xl">
                        <div class="absolute -bottom-4 -right-4 bg-yellow-400 text-yellow-900 font-bold py-2 px-6 rounded-full transform rotate-6">
                            مجاني!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- For Educators Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-gray-800 mb-4">للمعلمين والمدارس</h2>
            <p class="text-xl text-gray-600">أدوات قوية لتطبيق نموذج جُذور في صفك</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all">
                <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold mb-2">توليد ذكي للأسئلة</h3>
                <p class="text-gray-600 text-sm">استخدم الذكاء الاصطناعي لإنشاء أسئلة متوازنة حسب الجذور الأربعة</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all">
                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold mb-2">تحليلات مفصلة</h3>
                <p class="text-gray-600 text-sm">تابع نمو طلابك في كل جذر واكتشف نقاط القوة والضعف</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all">
                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold mb-2">نتائج فورية</h3>
                <p class="text-gray-600 text-sm">احصل على تقارير مرئية فورية لأداء الطلاب</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all">
                <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold mb-2">متعدد اللغات</h3>
                <p class="text-gray-600 text-sm">يدعم العربية والإنجليزية والعبرية</p>
            </div>
        </div>

        <!-- CTA for Teachers -->
        <div class="mt-12 text-center">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl p-8 md:p-12 max-w-3xl mx-auto text-white">
                <h3 class="text-3xl font-bold mb-4">ابدأ رحلتك مع جُذور</h3>
                <p class="text-xl mb-8 opacity-90">انضم إلى مئات المعلمين الذين يستخدمون جُذور لتطوير أساليب التقييم</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" 
                       class="bg-white text-purple-600 font-bold py-4 px-8 rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        إنشاء حساب معلم مجاني
                    </a>
                    <a href="{{ route('contact') }}" 
                       class="border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-purple-600 transition-all">
                        احجز عرضاً تجريبياً
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-gray-800 mb-4">مبني على أسس تربوية صلبة</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="bg-gray-100 rounded-2xl p-8 mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">نظرية الذكاءات المتعددة</h3>
                <p class="text-gray-600">يراعي النموذج أنماط التعلم المختلفة ويقدّر جميع أنواع الذكاء</p>
            </div>

            <div class="text-center">
                <div class="bg-gray-100 rounded-2xl p-8 mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">التعلم البنائي</h3>
                <p class="text-gray-600">يبني الطلاب معرفتهم بشكل تدريجي من خلال الاستكشاف النشط</p>
            </div>

            <div class="text-center">
                <div class="bg-gray-100 rounded-2xl p-8 mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">التقييم التكويني</h3>
                <p class="text-gray-600">يركز على النمو المستمر بدلاً من التقييم النهائي فقط</p>
            </div>
        </div>

        <!-- Privacy & Ethics -->
        <div class="mt-16 bg-gray-50 rounded-3xl p-8 max-w-3xl mx-auto">
            <div class="flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-green-600 ml-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-800">الخصوصية والأمان</h3>
            </div>
            <div class="grid md:grid-cols-2 gap-6 text-center">
                <div>
                    <p class="text-gray-600">
                        <strong>حماية بيانات الطلاب:</strong> جميع النتائج مجهولة المصدر ومحمية
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">
                        <strong>أخلاقيات الذكاء الاصطناعي:</strong> نستخدم AI بمسؤولية لدعم المعلمين
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer CTA -->
<section class="py-20 bg-gradient-to-br from-purple-600 to-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6">مستعد لتجربة التعلم بطريقة جديدة؟</h2>
        <p class="text-xl mb-8 opacity-90">انضم إلى منصة جُذور وابدأ رحلة تعليمية فريدة</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-white text-purple-600 font-bold py-4 px-12 rounded-xl hover:shadow-xl transform hover:-translate-y-1 transition-all text-lg">
                ابدأ مجاناً
            </a>
            <a href="{{ route('about') }}" 
               class="border-2 border-white text-white font-bold py-4 px-12 rounded-xl hover:bg-white hover:text-purple-600 transition-all text-lg">
                اعرف المزيد
            </a>
        </div>
    </div>
</section>

<style>
/* Animation Classes */
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

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
}

.animation-delay-200 {
    animation-delay: 200ms;
}

.animation-delay-400 {
    animation-delay: 400ms;
}

/* Smooth scroll for anchor links */
html {
    scroll-behavior: smooth;
}

/* Hover effects */
.group:hover .group-hover\:opacity-40 {
    opacity: 0.4;
}

/* Additional custom styles */
.transform {
    transition: transform 0.3s ease;
}

/* RTL specific adjustments */
[dir="rtl"] .ml-3 {
    margin-left: 0;
    margin-right: 0.75rem;
}

[dir="rtl"] .ml-2 {
    margin-left: 0;
    margin-right: 0.5rem;
}
</style>

@push('scripts')
<script>
// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Add animation classes when elements come into view
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in-up');
        }
    });
}, observerOptions);

// Observe all sections
document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});
</script>
@endpush
@endsection