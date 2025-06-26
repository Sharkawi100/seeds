@extends('layouts.app')

@section('title', 'نموذج الجُذور الأربعة - دليل تفاعلي')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50" x-data="juzoorModel()">
    
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-400 to-purple-500 text-white py-20">
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-6 backdrop-blur-sm">
                <svg class="w-12 h-12 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2L3 9h2v8h4v-6h2v6h4V9h2l-7-7z"/>
                </svg>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-4">ما هو نموذج الجُذور؟</h1>
            <p class="text-xl md:text-2xl opacity-90">رحلة تفاعلية لفهم كيف نتعلم ونفكر</p>
        </div>
        
        <!-- Floating Animation Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-bounce"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-yellow-300/20 rounded-full animate-pulse"></div>
        <div class="absolute top-1/2 left-1/4 w-8 h-8 bg-green-300/30 rounded-full animate-ping"></div>
    </div>

    <!-- Introduction Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="order-2 md:order-1">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">نموذج الجُذور الأربعة</h2>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            هو طريقة مبتكرة لفهم كيف نتعلم ونفكر. كل جذر يساعدك على استكشاف جانب مختلف من المعرفة
                        </p>
                    </div>
                    <div class="order-1 md:order-2">
                        <!-- Four Roots Diagram -->
                        <div class="relative">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Jawhar Root -->
                                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl p-6 text-white transform hover:scale-105 transition-transform cursor-pointer"
                                     @click="showRoot('jawhar')">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                            🎯
                                        </div>
                                        <h3 class="font-bold text-lg">جَوهر</h3>
                                        <p class="text-sm opacity-90">ما هو؟</p>
                                    </div>
                                </div>

                                <!-- Zihn Root -->
                                <div class="bg-gradient-to-br from-cyan-400 to-blue-500 rounded-2xl p-6 text-white transform hover:scale-105 transition-transform cursor-pointer"
                                     @click="showRoot('zihn')">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                            🧠
                                        </div>
                                        <h3 class="font-bold text-lg">ذِهن</h3>
                                        <p class="text-sm opacity-90">كيف يعمل؟</p>
                                    </div>
                                </div>

                                <!-- Waslat Root -->
                                <div class="bg-gradient-to-br from-pink-400 to-red-500 rounded-2xl p-6 text-white transform hover:scale-105 transition-transform cursor-pointer"
                                     @click="showRoot('waslat')">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                            🔗
                                        </div>
                                        <h3 class="font-bold text-lg">وَصلات</h3>
                                        <p class="text-sm opacity-90">كيف يرتبط؟</p>
                                    </div>
                                </div>

                                <!-- Roaya Root -->
                                <div class="bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl p-6 text-white transform hover:scale-105 transition-transform cursor-pointer"
                                     @click="showRoot('roaya')">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                            👁️
                                        </div>
                                        <h3 class="font-bold text-lg">رُؤية</h3>
                                        <p class="text-sm opacity-90">كيف نستخدمه؟</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Central Seed -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-16 h-16 bg-white rounded-full shadow-lg flex items-center justify-center border-4 border-green-200">
                                    <span class="text-2xl">🌱</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Individual Roots Sections -->
    <div class="space-y-0">
        
        <!-- Jawhar Root -->
        <section class="py-16 bg-gradient-to-r from-yellow-50 to-orange-50" id="jawhar-section">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div>
                            <div class="inline-flex items-center bg-yellow-500 text-white px-4 py-2 rounded-full mb-6">
                                <span class="text-xl mr-2">🎯</span>
                                <span class="font-bold">الجذر الأول: جَوهر</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">جذر الجَوهر يساعدك على فهم ما هو الشيء</h2>
                            
                            <div class="bg-white rounded-xl p-6 shadow-lg border-r-4 border-yellow-500">
                                <div class="flex items-start space-x-reverse space-x-4">
                                    <div class="text-4xl">🤔</div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-green-600 mb-2">سؤال: ما هو الماء؟</h3>
                                        <p class="text-gray-700">الجواب: الماء سائل شفاف نشربه</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-yellow-100 rounded-lg border-r-4 border-yellow-400">
                                <p class="text-gray-700 italic">"عندما تعرف ما هو الشيء، تكون قد بدأت رحلة التعلم"</p>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <div class="relative inline-block">
                                <div class="w-64 h-64 bg-gradient-to-br from-yellow-200 to-orange-300 rounded-full flex items-center justify-center">
                                    <div class="text-6xl animate-bounce">📚</div>
                                </div>
                                <div class="absolute -top-4 -right-4 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center animate-pulse">
                                    <span class="text-2xl">💡</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Zihn Root -->
        <section class="py-16 bg-gradient-to-r from-cyan-50 to-blue-50" id="zihn-section">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="order-2 md:order-1">
                            <div class="text-center">
                                <div class="relative inline-block">
                                    <div class="w-64 h-64 bg-gradient-to-br from-cyan-200 to-blue-300 rounded-full flex items-center justify-center">
                                        <div class="text-6xl">🦸‍♂️</div>
                                    </div>
                                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-blue-400 rounded-full flex items-center justify-center animate-spin">
                                        <span class="text-2xl">⚙️</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-1 md:order-2">
                            <div class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded-full mb-6">
                                <span class="text-xl mr-2">🧠</span>
                                <span class="font-bold">الجذر الثاني: ذِهن</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">جذر الذِهن يساعدك على فهم كيف يعمل الشيء</h2>
                            
                            <div class="bg-white rounded-xl p-6 shadow-lg border-r-4 border-blue-500">
                                <div class="flex items-start space-x-reverse space-x-4">
                                    <div class="text-4xl">🤔</div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-green-600 mb-2">سؤال: كيف يحدث المطر؟</h3>
                                        <p class="text-gray-700">الجواب: الماء يتبخر، يصبح سحاباً، ثم يهطل مطراً</p>
                                    </div>
                                    <div class="text-3xl">🌧️</div>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-blue-100 rounded-lg border-r-4 border-blue-400">
                                <p class="text-gray-700 italic">"عندما تفهم كيف يعمل الشيء، تصبح أكثر ذكاءً"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Waslat Root -->
        <section class="py-16 bg-gradient-to-r from-pink-50 to-red-50" id="waslat-section">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div>
                            <div class="inline-flex items-center bg-red-500 text-white px-4 py-2 rounded-full mb-6">
                                <span class="text-xl mr-2">🔗</span>
                                <span class="font-bold">الجذر الثالث: وَصلات</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">جذر الوَصلات يساعدك على فهم كيف يرتبط الشيء بأشياء أخرى</h2>
                            
                            <div class="bg-white rounded-xl p-6 shadow-lg border-r-4 border-red-500">
                                <div class="flex items-start space-x-reverse space-x-4">
                                    <div class="text-4xl">🤔</div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-green-600 mb-2">سؤال: كيف يرتبط المطر بالنباتات؟</h3>
                                        <p class="text-gray-700">الجواب: المطر يسقي النباتات، النباتات تنمو، وتعطينا الأكسجين والغذاء</p>
                                    </div>
                                    <div class="text-3xl">🌱</div>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-red-100 rounded-lg border-r-4 border-red-400">
                                <p class="text-gray-700 italic">"عندما تكتشف الروابط، تفهم العالم أكثر"</p>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <div class="relative inline-block">
                                <div class="w-64 h-64 bg-gradient-to-br from-pink-200 to-red-300 rounded-full flex items-center justify-center">
                                    <div class="text-6xl">🧩</div>
                                </div>
                                <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-red-400 rounded-full flex items-center justify-center animate-bounce">
                                    <span class="text-2xl">🔗</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Roaya Root -->
        <section class="py-16 bg-gradient-to-r from-green-50 to-emerald-50" id="roaya-section">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="order-2 md:order-1">
                            <div class="text-center">
                                <div class="relative inline-block">
                                    <div class="w-64 h-64 bg-gradient-to-br from-green-200 to-emerald-300 rounded-full flex items-center justify-center">
                                        <div class="text-6xl">💡</div>
                                    </div>
                                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-green-400 rounded-full flex items-center justify-center animate-pulse">
                                        <span class="text-2xl">🚀</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-1 md:order-2">
                            <div class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-full mb-6">
                                <span class="text-xl mr-2">👁️</span>
                                <span class="font-bold">الجذر الرابع: رُؤية</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-6">جذر الرُؤية يساعدك على فهم كيف نستخدم الشيء في حياتنا</h2>
                            
                            <div class="bg-white rounded-xl p-6 shadow-lg border-r-4 border-green-500">
                                <div class="flex items-start space-x-reverse space-x-4">
                                    <div class="text-4xl">🤔</div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-green-600 mb-2">سؤال: كيف نستخدم المطر في حياتنا؟</h3>
                                        <p class="text-gray-700">الجواب: نشرب منه، نسقي الزروع، نملأ الآبار</p>
                                    </div>
                                    <div class="text-3xl">🏃‍♂️</div>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-green-100 rounded-lg border-r-4 border-green-400">
                                <p class="text-gray-700 italic">"عندما تعرف كيف تستخدم الشيء، تصبح مبدعاً"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Integration Section -->
    <section class="py-20 bg-gradient-to-br from-indigo-100 to-purple-100">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto text-center">
                <h2 class="text-4xl font-bold text-gray-800 mb-8">الجُذور الأربعة تعمل معاً! 🌳</h2>
                <p class="text-xl text-gray-600 mb-12">عندما تستخدم الجُذور الأربعة معاً، تصبح أذكى وأقوى!</p>
                
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="text-center">
                        <div class="relative inline-block">
                            <!-- Tree with hands -->
                            <div class="w-80 h-80 relative">
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2">
                                    <!-- Hands -->
                                    <div class="w-40 h-20 bg-gradient-to-r from-amber-200 to-orange-200 rounded-t-full flex items-end justify-center">
                                        <!-- Tree trunk -->
                                        <div class="w-8 h-16 bg-amber-600 rounded-t-lg"></div>
                                    </div>
                                </div>
                                <!-- Tree crown -->
                                <div class="absolute top-8 left-1/2 transform -translate-x-1/2 w-64 h-48 bg-gradient-to-br from-green-300 to-green-500 rounded-full flex items-center justify-center">
                                    <div class="text-4xl">🌳</div>
                                </div>
                                <!-- Sun -->
                                <div class="absolute top-0 right-4 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center animate-pulse">
                                    <span class="text-3xl">☀️</span>
                                </div>
                                <!-- Cloud -->
                                <div class="absolute top-4 left-4 w-20 h-12 bg-gray-300 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right space-y-6">
                        <h3 class="text-2xl font-bold text-red-600 mb-6">مثال شامل عن "الشمس":</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-end space-x-reverse space-x-4 p-4 bg-yellow-100 rounded-lg">
                                <div>
                                    <h4 class="font-bold text-yellow-700">🎯 جَوهر: "ما هي؟"</h4>
                                    <p class="text-gray-700">نجم يضيء ويدفئ</p>
                                </div>
                                <div class="text-3xl">🎯</div>
                            </div>
                            
                            <div class="flex items-center justify-end space-x-reverse space-x-4 p-4 bg-blue-100 rounded-lg">
                                <div>
                                    <h4 class="font-bold text-blue-700">🧠 ذِهن: "كيف تعمل؟"</h4>
                                    <p class="text-gray-700">تحرق الغازات وترسل الضوء</p>
                                </div>
                                <div class="text-3xl">🧠</div>
                            </div>
                            
                            <div class="flex items-center justify-end space-x-reverse space-x-4 p-4 bg-red-100 rounded-lg">
                                <div>
                                    <h4 class="font-bold text-red-700">🔗 وَصلات: "كيف ترتبط؟"</h4>
                                    <p class="text-gray-700">تساعد النباتات والحيوانات</p>
                                </div>
                                <div class="text-3xl">🔗</div>
                            </div>
                            
                            <div class="flex items-center justify-end space-x-reverse space-x-4 p-4 bg-green-100 rounded-lg">
                                <div>
                                    <h4 class="font-bold text-green-700">👁️ رُؤية: "كيف نستخدمها؟"</h4>
                                    <p class="text-gray-700">للطاقة والدفء والضوء</p>
                                </div>
                                <div class="text-3xl">👁️</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 p-6 bg-gradient-to-r from-purple-100 to-pink-100 rounded-xl border-r-4 border-purple-400">
                    <p class="text-lg text-gray-700 italic font-medium">"كلما استخدمت جذورك الأربعة، كلما نما تعلمك أكثر"</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Challenge Section -->
    <section class="py-20 bg-white" id="challenge-section">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">تحديك! 🏆</h2>
                    <p class="text-xl text-gray-600">الآن دورك! اختر شيئاً تحبه وفكر فيه بالجُذور الأربعة</p>
                </div>
                
                <div class="relative">
                    <!-- Central Character -->
                    <div class="text-center mb-8">
                        <div class="inline-block w-24 h-24 bg-gradient-to-br from-pink-300 to-red-400 rounded-full flex items-center justify-center">
                            <span class="text-3xl">🧠</span>
                        </div>
                    </div>
                    
                    <!-- Four Input Boxes -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-yellow-50 rounded-xl p-6 border-2 border-yellow-200 hover:border-yellow-400 transition-colors">
                            <h3 class="text-lg font-bold text-yellow-700 mb-3 flex items-center">
                                <span class="text-2xl mr-2">🎯</span>
                                جَوهر: "ما هو؟"
                            </h3>
                            <textarea 
                                x-model="challenge.jawhar"
                                placeholder="اكتب تعريفاً للشيء الذي اخترته..."
                                class="w-full h-24 p-3 border border-yellow-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-yellow-400"
                            ></textarea>
                        </div>
                        
                        <div class="bg-blue-50 rounded-xl p-6 border-2 border-blue-200 hover:border-blue-400 transition-colors">
                            <h3 class="text-lg font-bold text-blue-700 mb-3 flex items-center">
                                <span class="text-2xl mr-2">🧠</span>
                                ذِهن: "كيف يعمل؟"
                            </h3>
                            <textarea 
                                x-model="challenge.zihn"
                                placeholder="اشرح كيف يعمل هذا الشيء..."
                                class="w-full h-24 p-3 border border-blue-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-400"
                            ></textarea>
                        </div>
                        
                        <div class="bg-red-50 rounded-xl p-6 border-2 border-red-200 hover:border-red-400 transition-colors">
                            <h3 class="text-lg font-bold text-red-700 mb-3 flex items-center">
                                <span class="text-2xl mr-2">🔗</span>
                                وَصلات: "كيف يرتبط؟"
                            </h3>
                            <textarea 
                                x-model="challenge.waslat"
                                placeholder="كيف يرتبط بأشياء أخرى؟..."
                                class="w-full h-24 p-3 border border-red-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-red-400"
                            ></textarea>
                        </div>
                        
                        <div class="bg-green-50 rounded-xl p-6 border-2 border-green-200 hover:border-green-400 transition-colors">
                            <h3 class="text-lg font-bold text-green-700 mb-3 flex items-center">
                                <span class="text-2xl mr-2">👁️</span>
                                رُؤية: "كيف نستخدمه؟"
                            </h3>
                            <textarea 
                                x-model="challenge.roaya"
                                placeholder="كيف يمكن استخدامه في الحياة؟..."
                                class="w-full h-24 p-3 border border-green-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-green-400"
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="text-center mt-8">
                        <button 
                            @click="completeChallenge()"
                            :disabled="!canComplete"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span class="text-xl mr-2">🎉</span>
                            أكملت التحدي!
                        </button>
                    </div>
                    
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p><strong>أمثلة مساعدة:</strong> يمكنك اختيار: الكتاب، الهاتف، الطعام، الرياضة...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Modal -->
    <div x-show="showSuccess" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         style="display: none;">
        <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">أحسنت!</h3>
            <p class="text-gray-600 mb-6">لقد أكملت تحدي الجُذور الأربعة بنجاح!</p>
            <button @click="showSuccess = false" 
                    class="px-6 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors">
                ممتاز!
            </button>
        </div>
    </div>

    <!-- Final Section -->
    <section class="py-20 bg-gradient-to-br from-green-400 to-blue-500 text-white">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl font-bold mb-6">أحسنت! 🌟</h2>
                        <p class="text-xl opacity-90 mb-8">الآن أصبحت خبيراً في نموذج الجُذور الأربعة!</p>
                        
                        <div class="space-y-4 text-right">
                            <h3 class="text-xl font-bold mb-4">نصائح للمستقبل:</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-end space-x-reverse space-x-2">
                                    <span>"استخدم الجُذور في دروسك"</span>
                                    <span class="text-xl">📚</span>
                                </div>
                                <div class="flex items-center justify-end space-x-reverse space-x-2">
                                    <span>"فكر بها عند حل المشاكل"</span>
                                    <span class="text-xl">🧩</span>
                                </div>
                                <div class="flex items-center justify-end space-x-reverse space-x-2">
                                    <span>"شاركها مع أصدقائك"</span>
                                    <span class="text-xl">👥</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="relative inline-block">
                            <!-- Tree with books -->
                            <div class="w-80 h-80 relative">
                                <!-- Tree with built-in library -->
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-32 h-48 bg-gradient-to-b from-green-400 to-green-600 rounded-t-full flex flex-col justify-end items-center pb-4">
                                    <!-- Book shelves in tree -->
                                    <div class="space-y-2">
                                        <div class="w-24 h-2 bg-amber-700 rounded"></div>
                                        <div class="w-24 h-2 bg-amber-700 rounded"></div>
                                        <div class="w-24 h-2 bg-amber-700 rounded"></div>
                                    </div>
                                </div>
                                <!-- Happy character -->
                                <div class="absolute bottom-0 right-8 text-4xl animate-bounce">👧</div>
                                <!-- Cat -->
                                <div class="absolute bottom-0 left-8 text-3xl">🐱</div>
                                <!-- Stars -->
                                <div class="absolute top-4 left-4 text-2xl animate-pulse">⭐</div>
                                <div class="absolute top-8 right-4 text-xl animate-pulse">✨</div>
                                <div class="absolute top-16 left-1/2 text-lg animate-pulse">💫</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 p-6 bg-white/10 rounded-xl backdrop-blur-sm">
                    <p class="text-lg italic font-medium">"تذكر: كلما استخدمت جذورك، كلما نما عقلك وأصبحت أذكى!"</p>
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-bold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <span class="text-xl mr-2">🚀</span>
                        ابدأ رحلتك مع جُذور
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function juzoorModel() {
    return {
        showSuccess: false,
        challenge: {
            jawhar: '',
            zihn: '',
            waslat: '',
            roaya: ''
        },
        
        get canComplete() {
            return this.challenge.jawhar.trim() !== '' && 
                   this.challenge.zihn.trim() !== '' && 
                   this.challenge.waslat.trim() !== '' && 
                   this.challenge.roaya.trim() !== '';
        },
        
        showRoot(rootName) {
            const element = document.getElementById(rootName + '-section');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        },
        
        completeChallenge() {
            if (this.canComplete) {
                this.showSuccess = true;
                // Add confetti effect
                this.createConfetti();
            }
        },
        
        createConfetti() {
            // Simple confetti effect
            const colors = ['#f43f5e', '#3b82f6', '#10b981', '#f59e0b'];
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.top = '-10px';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.borderRadius = '50%';
                    confetti.style.animation = 'fall 3s linear forwards';
                    confetti.style.zIndex = '9999';
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 3000);
                }, i * 50);
            }
        }
    }
}
</script>

<style>
@keyframes fall {
    to {
        transform: translateY(100vh) rotate(360deg);
    }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-wiggle {
    animation: wiggle 1s ease-in-out infinite;
}

@keyframes wiggle {
    0%, 100% { transform: rotate(-3deg); }
    50% { transform: rotate(3deg); }
}
</style>
@endsection