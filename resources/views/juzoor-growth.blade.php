@extends('layouts.guest')

@section('title', 'رحلة النمو في جُذور')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-purple-600 to-blue-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-black mb-6">رحلة النمو مع جُذور</h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                شاهد كيف تتحول البذرة الصغيرة إلى شجرة معرفة قوية
            </p>
            <div class="mt-8">
                <a href="{{ route('juzoor.model') }}" class="inline-flex items-center gap-2 text-white/80 hover:text-white">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة لنموذج جُذور</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Growth Animation Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">مراحل النمو المعرفي</h2>
            <p class="text-xl text-gray-600">انقر على كل مرحلة لمشاهدة التطور</p>
        </div>

        <!-- Interactive Growth Stages -->
        <div class="relative">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <!-- Stage 1: Seed -->
                <div class="growth-stage cursor-pointer" onclick="showStage(1)">
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-2 text-center">
                        <div class="text-6xl mb-4">🌱</div>
                        <h3 class="text-xl font-bold mb-2">البذرة</h3>
                        <p class="text-gray-600 text-sm">البداية - معرفة بسيطة</p>
                    </div>
                </div>

                <!-- Stage 2: Sprout -->
                <div class="growth-stage cursor-pointer" onclick="showStage(2)">
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-2 text-center">
                        <div class="text-6xl mb-4">🌿</div>
                        <h3 class="text-xl font-bold mb-2">النبتة</h3>
                        <p class="text-gray-600 text-sm">النمو - ترابط المفاهيم</p>
                    </div>
                </div>

                <!-- Stage 3: Plant -->
                <div class="growth-stage cursor-pointer" onclick="showStage(3)">
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-2 text-center">
                        <div class="text-6xl mb-4">🌾</div>
                        <h3 class="text-xl font-bold mb-2">النبات</h3>
                        <p class="text-gray-600 text-sm">التعمق - فهم شامل</p>
                    </div>
                </div>

                <!-- Stage 4: Tree -->
                <div class="growth-stage cursor-pointer" onclick="showStage(4)">
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-2 text-center">
                        <div class="text-6xl mb-4">🌳</div>
                        <h3 class="text-xl font-bold mb-2">الشجرة</h3>
                        <p class="text-gray-600 text-sm">الإتقان - إبداع وابتكار</p>
                    </div>
                </div>
            </div>

            <!-- Stage Details -->
            <div id="stage-details" class="bg-white rounded-3xl shadow-xl p-8 md:p-12 hidden">
                <div id="stage-content"></div>
            </div>
        </div>

        <!-- Visual Growth Timeline -->
        <div class="mt-16">
            <h3 class="text-2xl font-bold text-center mb-8">المسار الزمني للنمو</h3>
            <div class="relative">
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-green-300 to-green-600"></div>
                
                <div class="space-y-12">
                    <!-- Week 1 -->
                    <div class="flex items-center">
                        <div class="flex-1 text-left pr-8">
                            <h4 class="font-bold text-lg">الأسبوع الأول</h4>
                            <p class="text-gray-600">تعرف على المفاهيم الأساسية</p>
                        </div>
                        <div class="w-8 h-8 bg-green-500 rounded-full border-4 border-white shadow-lg z-10"></div>
                        <div class="flex-1 text-right pl-8">
                            <div class="bg-red-100 rounded-lg p-3 inline-block">
                                <span class="text-2xl">🎯</span> جَوهر: 40%
                            </div>
                        </div>
                    </div>

                    <!-- Week 2 -->
                    <div class="flex items-center">
                        <div class="flex-1 text-left pr-8">
                            <div class="bg-teal-100 rounded-lg p-3 inline-block">
                                <span class="text-2xl">🧠</span> ذِهن: 35%
                            </div>
                        </div>
                        <div class="w-8 h-8 bg-green-500 rounded-full border-4 border-white shadow-lg z-10"></div>
                        <div class="flex-1 text-right pl-8">
                            <h4 class="font-bold text-lg">الأسبوع الثاني</h4>
                            <p class="text-gray-600">بدء التحليل والفهم العميق</p>
                        </div>
                    </div>

                    <!-- Week 3 -->
                    <div class="flex items-center">
                        <div class="flex-1 text-left pr-8">
                            <h4 class="font-bold text-lg">الأسبوع الثالث</h4>
                            <p class="text-gray-600">ربط المفاهيم ببعضها</p>
                        </div>
                        <div class="w-8 h-8 bg-green-500 rounded-full border-4 border-white shadow-lg z-10"></div>
                        <div class="flex-1 text-right pl-8">
                            <div class="bg-yellow-100 rounded-lg p-3 inline-block">
                                <span class="text-2xl">🔗</span> وَصلات: 60%
                            </div>
                        </div>
                    </div>

                    <!-- Week 4 -->
                    <div class="flex items-center">
                        <div class="flex-1 text-left pr-8">
                            <div class="bg-purple-100 rounded-lg p-3 inline-block">
                                <span class="text-2xl">👁️</span> رُؤية: 75%
                            </div>
                        </div>
                        <div class="w-8 h-8 bg-green-500 rounded-full border-4 border-white shadow-lg z-10"></div>
                        <div class="flex-1 text-right pl-8">
                            <h4 class="font-bold text-lg">الأسبوع الرابع</h4>
                            <p class="text-gray-600">التطبيق والإبداع</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">شاهد النموذج في العمل</h2>
            <p class="text-xl text-gray-600">فيديو توضيحي لرحلة الطالب مع جُذور</p>
        </div>

        <!-- YouTube Video Embed -->
        <div class="relative pb-[56.25%] rounded-2xl overflow-hidden shadow-2xl">
            <iframe 
                class="absolute top-0 left-0 w-full h-full"
                src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                title="نموذج جُذور التعليمي"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>

        <div class="mt-8 text-center">
            <p class="text-gray-600 mb-4">هل أنت جاهز لبدء رحلتك؟</p>
            <a href="{{ route('register') }}" class="inline-block bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-3 px-8 rounded-xl hover:shadow-lg transform hover:scale-105 transition-all">
                ابدأ الآن مجاناً
            </a>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">لماذا النمو مع جُذور مختلف؟</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">نمو متوازن</h3>
                <p class="text-gray-600">تطوير جميع جوانب المعرفة بشكل متوازن</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-eye text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">رؤية واضحة</h3>
                <p class="text-gray-600">تتبع مرئي لمستوى التقدم في كل جذر</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trophy text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">إنجازات ملموسة</h3>
                <p class="text-gray-600">احتفل بكل مرحلة من مراحل النمو</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Stage content data
const stageData = {
    1: {
        title: 'مرحلة البذرة 🌱',
        description: 'في هذه المرحلة، يبدأ الطالب رحلته مع المعرفة الأساسية',
        roots: [
            { name: 'جَوهر', icon: '🎯', level: '20%', desc: 'تعلم التعريفات الأساسية' },
            { name: 'ذِهن', icon: '🧠', level: '15%', desc: 'بداية الفهم البسيط' },
            { name: 'وَصلات', icon: '🔗', level: '10%', desc: 'ملاحظة الروابط الأولية' },
            { name: 'رُؤية', icon: '👁️', level: '5%', desc: 'التعرف على الإمكانيات' }
        ]
    },
    2: {
        title: 'مرحلة النبتة 🌿',
        description: 'تبدأ المعرفة بالنمو وتظهر الروابط بين المفاهيم',
        roots: [
            { name: 'جَوهر', icon: '🎯', level: '40%', desc: 'فهم أعمق للمفاهيم' },
            { name: 'ذِهن', icon: '🧠', level: '35%', desc: 'تحليل وتفكير نقدي' },
            { name: 'وَصلات', icon: '🔗', level: '30%', desc: 'ربط المفاهيم ببعضها' },
            { name: 'رُؤية', icon: '👁️', level: '25%', desc: 'بداية التطبيق العملي' }
        ]
    },
    3: {
        title: 'مرحلة النبات 🌾',
        description: 'نضج المعرفة وتعمق الفهم في جميع الجذور',
        roots: [
            { name: 'جَوهر', icon: '🎯', level: '65%', desc: 'إتقان المفاهيم الأساسية' },
            { name: 'ذِهن', icon: '🧠', level: '60%', desc: 'تحليل متقدم ونقد بناء' },
            { name: 'وَصلات', icon: '🔗', level: '55%', desc: 'رؤية شاملة للموضوع' },
            { name: 'رُؤية', icon: '👁️', level: '50%', desc: 'حل المشكلات بإبداع' }
        ]
    },
    4: {
        title: 'مرحلة الشجرة 🌳',
        description: 'الإتقان الكامل والقدرة على الإبداع والابتكار',
        roots: [
            { name: 'جَوهر', icon: '🎯', level: '85%', desc: 'خبرة عميقة في المجال' },
            { name: 'ذِهن', icon: '🧠', level: '80%', desc: 'تفكير استراتيجي متقدم' },
            { name: 'وَصلات', icon: '🔗', level: '75%', desc: 'ربط متعدد التخصصات' },
            { name: 'رُؤية', icon: '👁️', level: '70%', desc: 'ابتكار حلول جديدة' }
        ]
    }
};

function showStage(stageNumber) {
    const stage = stageData[stageNumber];
    const detailsDiv = document.getElementById('stage-details');
    const contentDiv = document.getElementById('stage-content');
    
    // Build content HTML
    let rootsHTML = stage.roots.map(root => `
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">${root.icon}</span>
                    <span class="font-bold">${root.name}</span>
                </div>
                <span class="text-sm text-gray-600">${root.level}</span>
            </div>
            <div class="bg-gray-200 rounded-full h-3 mb-2">
                <div class="bg-gradient-to-r ${
                    root.name === 'جَوهر' ? 'from-red-400 to-red-600' :
                    root.name === 'ذِهن' ? 'from-teal-400 to-teal-600' :
                    root.name === 'وَصلات' ? 'from-yellow-400 to-yellow-600' :
                    'from-purple-400 to-purple-600'
                } h-3 rounded-full transition-all duration-1000" style="width: ${root.level}"></div>
            </div>
            <p class="text-sm text-gray-600">${root.desc}</p>
        </div>
    `).join('');
    
    contentDiv.innerHTML = `
        <div class="text-center mb-8">
            <h3 class="text-3xl font-bold mb-4">${stage.title}</h3>
            <p class="text-xl text-gray-600">${stage.description}</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            ${rootsHTML}
        </div>
    `;
    
    // Show details
    detailsDiv.classList.remove('hidden');
    detailsDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Animate progress bars
    setTimeout(() => {
        detailsDiv.querySelectorAll('.bg-gradient-to-r').forEach(bar => {
            bar.style.width = bar.style.width;
        });
    }, 100);
}

// Highlight active stage on hover
document.querySelectorAll('.growth-stage').forEach((stage, index) => {
    stage.addEventListener('mouseenter', () => {
        stage.querySelector('div').classList.add('scale-105');
    });
    stage.addEventListener('mouseleave', () => {
        stage.querySelector('div').classList.remove('scale-105');
    });
});
</script>
@endpush