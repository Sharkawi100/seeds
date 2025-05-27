@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-green-600 to-blue-600 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">نموذج جُذور التعليمي 🌱</h1>
            <p class="text-xl md:text-2xl opacity-90">إطار تعليمي مبتكر يُشبه نمو النبات</p>
        </div>
    </div>

    <!-- Introduction -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <h2 class="text-3xl font-bold text-center mb-8">ما هو نموذج جُذور؟</h2>
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div class="space-y-4 text-lg">
                    <p>نموذج جُذور هو إطار تعليمي يحول كل معلومة إلى بذرة يمكن أن تنمو في أربعة اتجاهات متكاملة.</p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <span class="text-green-500 ml-2">✓</span>
                            كل معلومة هي بذرة يمكن أن تنمو في اتجاهات متعددة
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 ml-2">✓</span>
                            التعلم الحقيقي يحدث عندما تتشابك الجذور وتتعمق
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 ml-2">✓</span>
                            لا يوجد فشل، فقط مستويات مختلفة من النمو
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <div class="relative">
                        <x-juzoor-chart size="large" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Four Roots -->
    <div class="max-w-6xl mx-auto px-4 pb-16">
        <h2 class="text-3xl font-bold text-center mb-12">الجذور الأربعة</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-red-500">
                <div class="text-center mb-4">
                    <span class="text-5xl">🎯</span>
                    <h3 class="text-2xl font-bold mt-3 text-red-600">جَوهر</h3>
                    <p class="text-gray-600 mt-2">"ما هو؟"</p>
                </div>
                <p class="text-sm">يركز على التعريفات والمكونات الأساسية للمفهوم</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-cyan-500">
                <div class="text-center mb-4">
                    <span class="text-5xl">🧠</span>
                    <h3 class="text-2xl font-bold mt-3 text-cyan-600">ذِهن</h3>
                    <p class="text-gray-600 mt-2">"كيف يعمل؟"</p>
                </div>
                <p class="text-sm">يحلل الآليات والعمليات والأسباب والنتائج</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-yellow-500">
                <div class="text-center mb-4">
                    <span class="text-5xl">🔗</span>
                    <h3 class="text-2xl font-bold mt-3 text-yellow-600">وَصلات</h3>
                    <p class="text-gray-600 mt-2">"كيف يرتبط؟"</p>
                </div>
                <p class="text-sm">يكتشف العلاقات والروابط مع مفاهيم أخرى</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-purple-500">
                <div class="text-center mb-4">
                    <span class="text-5xl">👁️</span>
                    <h3 class="text-2xl font-bold mt-3 text-purple-600">رُؤية</h3>
                    <p class="text-gray-600 mt-2">"كيف نستخدمه؟"</p>
                </div>
                <p class="text-sm">يطبق المعرفة عملياً ويشجع الإبداع</p>
            </div>
        </div>
    </div>

    <!-- Examples Section -->
    <div class="bg-white py-16">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">أمثلة من المواد الدراسية</h2>
            
            <!-- Tabs -->
            <div class="flex justify-center mb-8">
                <button onclick="showExample('arabic')" class="tab-btn active px-6 py-3 bg-blue-600 text-white rounded-r-lg">اللغة العربية</button>
                <button onclick="showExample('english')" class="tab-btn px-6 py-3 bg-gray-200 text-gray-700">اللغة الإنجليزية</button>
                <button onclick="showExample('hebrew')" class="tab-btn px-6 py-3 bg-gray-200 text-gray-700 rounded-l-lg">اللغة العبرية</button>
            </div>

            <!-- Arabic Example -->
            <div id="arabic-example" class="example-content">
                <h3 class="text-2xl font-bold mb-6 text-center">موضوع: الفعل الماضي</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-red-50 p-6 rounded-xl">
                        <h4 class="font-bold text-red-600 mb-3">🎯 جَوهر</h4>
                        <p class="font-medium mb-2">ما هو الفعل الماضي؟</p>
                        <p>هو الفعل الذي يدل على حدث وقع وانتهى في الزمن الماضي. مثل: كَتَبَ، قَرَأَ، ذَهَبَ</p>
                    </div>
                    <div class="bg-cyan-50 p-6 rounded-xl">
                        <h4 class="font-bold text-cyan-600 mb-3">🧠 ذِهن</h4>
                        <p class="font-medium mb-2">كيف نصرف الفعل الماضي؟</p>
                        <p>يُبنى على الفتح مع (هو، هي، هما)، وعلى السكون مع (أنا، نحن، أنتَ، أنتِ، أنتم، أنتن، هم، هن)</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-xl">
                        <h4 class="font-bold text-yellow-600 mb-3">🔗 وَصلات</h4>
                        <p class="font-medium mb-2">كيف يرتبط بالأزمنة الأخرى؟</p>
                        <p>يرتبط بالفعل المضارع والأمر من نفس الجذر. كما يستخدم في بناء الجمل الخبرية</p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-xl">
                        <h4 class="font-bold text-purple-600 mb-3">👁️ رُؤية</h4>
                        <p class="font-medium mb-2">كيف نستخدمه في التعبير؟</p>
                        <p>اكتب قصة قصيرة عن رحلة قمت بها مستخدماً الأفعال الماضية</p>
                    </div>
                </div>
            </div>

            <!-- English Example -->
            <div id="english-example" class="example-content hidden">
                <h3 class="text-2xl font-bold mb-6 text-center">Topic: Present Perfect Tense</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-red-50 p-6 rounded-xl">
                        <h4 class="font-bold text-red-600 mb-3">🎯 جَوهر</h4>
                        <p class="font-medium mb-2">ما هو Present Perfect؟</p>
                        <p>زمن يربط الماضي بالحاضر. يتكون من: have/has + past participle</p>
                    </div>
                    <div class="bg-cyan-50 p-6 rounded-xl">
                        <h4 class="font-bold text-cyan-600 mb-3">🧠 ذِهن</h4>
                        <p class="font-medium mb-2">متى نستخدمه؟</p>
                        <p>للتجارب السابقة، الأحداث التي بدأت في الماضي ومستمرة، أو الأحداث المكتملة حديثاً</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-xl">
                        <h4 class="font-bold text-yellow-600 mb-3">🔗 وَصلات</h4>
                        <p class="font-medium mb-2">الفرق بينه وبين Past Simple؟</p>
                        <p>Past Simple للأحداث المنتهية في وقت محدد، بينما Present Perfect يركز على النتيجة أو الخبرة</p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-xl">
                        <h4 class="font-bold text-purple-600 mb-3">👁️ رُؤية</h4>
                        <p class="font-medium mb-2">تطبيق عملي</p>
                        <p>اكتب سيرتك الذاتية مستخدماً Present Perfect للإنجازات والخبرات</p>
                    </div>
                </div>
            </div>

            <!-- Hebrew Example -->
            <div id="hebrew-example" class="example-content hidden">
                <h3 class="text-2xl font-bold mb-6 text-center">נושא: פועל עבר (موضوع: الفعل الماضي)</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-red-50 p-6 rounded-xl">
                        <h4 class="font-bold text-red-600 mb-3">🎯 جَوهر</h4>
                        <p class="font-medium mb-2">ما هو الفعل الماضي في العبرية؟</p>
                        <p>פועל שמתאר פעולה שהתרחשה בעבר - فعل يصف حدثاً وقع في الماضي. مثل: כָּתַב، קָרָא، הָלַךְ</p>
                    </div>
                    <div class="bg-cyan-50 p-6 rounded-xl">
                        <h4 class="font-bold text-cyan-600 mb-3">🧠 ذِهن</h4>
                        <p class="font-medium mb-2">كيف يُصرف؟</p>
                        <p>يضاف للجذر لواحق حسب الضمير: תִּי (أنا)، תָּ (أنتَ)، תְּ (أنتِ)، נוּ (نحن)</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-xl">
                        <h4 class="font-bold text-yellow-600 mb-3">🔗 وَصلات</h4>
                        <p class="font-medium mb-2">التشابه مع العربية</p>
                        <p>كلاهما لغة سامية، نفس نظام الجذور الثلاثية، تصريف مشابه مع الضمائر</p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-xl">
                        <h4 class="font-bold text-purple-600 mb-3">👁️ رُؤية</h4>
                        <p class="font-medium mb-2">تطبيق</p>
                        <p>اكتب يومياتك بالعبرية مستخدماً الأفعال الماضية لوصف أحداث يومك</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison with Bloom -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">مقارنة مع تصنيف بلوم</h2>
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="border-l-4 border-blue-500 pr-6">
                    <h3 class="text-xl font-bold mb-4">تصنيف بلوم 📊</h3>
                    <ul class="space-y-3">
                        <li>🏔️ بنية هرمية تصاعدية</li>
                        <li>📈 6 مستويات متتالية</li>
                        <li>➡️ تقدم خطي من الأسفل للأعلى</li>
                        <li>🚫 "لم يصل للمستوى المطلوب"</li>
                    </ul>
                </div>
                <div class="border-l-4 border-green-500 pr-6">
                    <h3 class="text-xl font-bold mb-4">نموذج جُذور 🌱</h3>
                    <ul class="space-y-3">
                        <li>🕸️ بنية شبكية منتشرة</li>
                        <li>🌿 4 جذور × 3 مستويات عمق</li>
                        <li>🔄 نمو حر في أي اتجاه</li>
                        <li>✅ "ما زال في مرحلة النمو"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold mb-6">ابدأ رحلتك مع نموذج جُذور</h2>
            <p class="text-xl mb-8">صمم اختبارات تفاعلية تنمي جميع جوانب التعلم</p>
            <a href="{{ route('quizzes.create') }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transform hover:scale-105 transition">
                إنشاء اختبار جديد
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes grow-root {
        from { stroke-dasharray: 300; stroke-dashoffset: 300; }
        to { stroke-dasharray: 300; stroke-dashoffset: 0; }
    }
    
    .animate-fade-in { animation: fade-in 1s ease-out; }
    .animate-float { animation: float 3s ease-in-out infinite; }
    .animate-grow-root { animation: grow-root 2s ease-out forwards; }
    
    .tab-btn { transition: all 0.3s; }
    .tab-btn.active { background-color: #2563eb; color: white; }
    .example-content { transition: all 0.3s; }
</style>

<script>
function showExample(subject) {
    // Hide all examples
    document.querySelectorAll('.example-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    // Show selected example
    document.getElementById(subject + '-example').classList.remove('hidden');
    event.target.classList.add('active', 'bg-blue-600', 'text-white');
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
}
</script>
@endsection