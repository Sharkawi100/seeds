@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">إنشاء اختبار جديد</h1>
            <p class="text-gray-600">صمم اختبارًا تفاعليًا باستخدام نموذج جُذور التعليمي</p>
            <a href="{{ route('juzoor.model') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800 underline">
                📖 تعرف على نموذج جُذور وكيفية عمله
            </a>
        </div>

        <form action="{{ route('quizzes.store') }}" method="POST" id="quizForm">
            @csrf
            
            <!-- Basic Info Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-600 rounded-full p-3 ml-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </span>
                    المعلومات الأساسية
                </h2>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">عنوان الاختبار</label>
                        <input type="text" name="title" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition" placeholder="مثال: اختبار قواعد اللغة العربية" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المادة الدراسية</label>
                        <select name="subject" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition" required>
                            <option value="">اختر المادة</option>
                            <option value="arabic">🌍 اللغة العربية</option>
                            <option value="english">🌎 اللغة الإنجليزية</option>
                            <option value="hebrew">🌏 اللغة العبرية</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الصف الدراسي</label>
                        <select name="grade_level" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition" required>
                            <option value="">اختر الصف</option>
                            <optgroup label="🏫 المرحلة الابتدائية">
                                @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">الصف {{ ['الأول', 'الثاني', 'الثالث', 'الرابع', 'الخامس', 'السادس'][$i-1] }}</option>
                                @endfor
                            </optgroup>
                            <optgroup label="🎓 المرحلة الإعدادية">
                                @for($i = 7; $i <= 9; $i++)
                                <option value="{{ $i }}">الصف {{ ['السابع', 'الثامن', 'التاسع'][$i-7] }}</option>
                                @endfor
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Creation Method -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">طريقة إنشاء الأسئلة</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <button type="button" onclick="setCreationMethod('ai')" class="creation-method active p-6 border-2 rounded-xl text-center hover:shadow-lg transition" data-method="ai">
                        <span class="text-3xl block mb-2">🤖</span>
                        <h3 class="font-bold">توليد بالذكاء الاصطناعي</h3>
                        <p class="text-sm text-gray-600 mt-2">دع الذكاء الاصطناعي ينشئ الأسئلة</p>
                    </button>
                    
                    <button type="button" onclick="setCreationMethod('manual')" class="creation-method p-6 border-2 rounded-xl text-center hover:shadow-lg transition" data-method="manual">
                        <span class="text-3xl block mb-2">✍️</span>
                        <h3 class="font-bold">إضافة يدوية</h3>
                        <p class="text-sm text-gray-600 mt-2">أضف الأسئلة بنفسك</p>
                    </button>
                    
                    <button type="button" onclick="setCreationMethod('hybrid')" class="creation-method p-6 border-2 rounded-xl text-center hover:shadow-lg transition" data-method="hybrid">
                        <span class="text-3xl block mb-2">🔄</span>
                        <h3 class="font-bold">مزيج</h3>
                        <p class="text-sm text-gray-600 mt-2">ابدأ بالذكاء الاصطناعي ثم عدّل</p>
                    </button>
                </div>
                <input type="hidden" name="creation_method" id="creation_method" value="ai">
            </div>
            
            <!-- AI Settings (shown for AI and Hybrid) -->
            <div id="ai-settings" class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="bg-green-100 text-green-600 rounded-full p-3 ml-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </span>
                    إعدادات الجذور للذكاء الاصطناعي
                </h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    @php
                    $roots = [
                        'jawhar' => ['name' => 'جَوهر', 'desc' => 'الجوهر والأساس', 'emoji' => '🎯', 'bg' => 'bg-red-50', 'border' => 'border-red-300'],
                        'zihn' => ['name' => 'ذِهن', 'desc' => 'العقل والتفكير', 'emoji' => '🧠', 'bg' => 'bg-blue-50', 'border' => 'border-blue-300'],
                        'waslat' => ['name' => 'وَصلات', 'desc' => 'الروابط والعلاقات', 'emoji' => '🔗', 'bg' => 'bg-yellow-50', 'border' => 'border-yellow-300'],
                        'roaya' => ['name' => 'رُؤية', 'desc' => 'الرؤية والتطبيق', 'emoji' => '👁️', 'bg' => 'bg-purple-50', 'border' => 'border-purple-300']
                    ];
                    @endphp
                    
                    @foreach($roots as $key => $root)
                    <div class="{{ $root['bg'] }} {{ $root['border'] }} border-2 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <span class="text-3xl ml-3">{{ $root['emoji'] }}</span>
                            <div class="flex-1">
                                <h3 class="font-bold text-lg">{{ $root['name'] }}</h3>
                                <p class="text-sm text-gray-600">{{ $root['desc'] }}</p>
                            </div>
                            <div class="group relative">
                                <span class="text-gray-400 hover:text-gray-600 cursor-help">ℹ️</span>
                                <div class="absolute left-0 bottom-full mb-2 hidden group-hover:block w-64 p-3 bg-gray-800 text-white text-sm rounded-lg shadow-lg z-10">
                                    @if($key == 'jawhar')
                                        يركز على التعريفات والمفاهيم الأساسية - "ما هو؟"
                                    @elseif($key == 'zihn')
                                        يحلل الآليات والعمليات - "كيف يعمل؟"
                                    @elseif($key == 'waslat')
                                        يكتشف العلاقات والروابط - "كيف يرتبط؟"
                                    @else
                                        يطبق المعرفة عملياً - "كيف نستخدمه؟"
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4 text-center mb-3">
                            <div class="font-medium text-sm text-gray-700">المستوى</div>
                            <div class="font-medium text-sm text-gray-700">عدد الأسئلة</div>
                            <div></div>
                        </div>
                        
                        @foreach(['سطحي' => 1, 'متوسط' => 2, 'عميق' => 3] as $levelName => $levelValue)
                        <div class="grid grid-cols-3 gap-4 items-center mb-2">
                            <div class="text-sm">
                                @if($levelValue == 1) 🟡 @elseif($levelValue == 2) 🟠 @else 🟢 @endif
                                {{ $levelName }}
                            </div>
                            <div>
                                <input type="number" 
                                       name="roots[{{ $key }}][levels][{{ $levelValue }}]" 
                                       min="0" 
                                       max="10" 
                                       value="{{ $levelValue == 2 ? 1 : 0 }}"
                                       class="root-level-input w-full px-2 py-1 text-center rounded-lg border {{ $root['border'] }} focus:ring-2"
                                       data-root="{{ $key }}">
                            </div>
                            <div class="text-xs text-gray-500">
                                سؤال/أسئلة
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="mt-4 pt-4 border-t {{ $root['border'] }}">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">المجموع:</span>
                                <span class="total-questions font-bold text-lg" data-root="{{ $key }}">1</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Preview Chart -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">معاينة توزيع الأسئلة</h2>
                <div id="chart-container">
                    <x-juzoor-chart :scores="['jawhar' => 25, 'zihn' => 25, 'waslat' => 25, 'roaya' => 25]" size="medium" />
                </div>
                <p class="text-center text-gray-600 mt-4">سيتم تحديث الرسم البياني بناءً على اختياراتك</p>
            </div>
            
            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" id="submitBtn" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transform hover:scale-105">
                    <span class="ml-2">✨</span>
                    <span id="submitText">توليد الاختبار بالذكاء الاصطناعي</span>
                    <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-20 bg-white transition-opacity"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.creation-method {
    border-color: #e5e7eb;
}
.creation-method.active {
    border-color: #3b82f6;
    background-color: #eff6ff;
}
.creation-method:hover {
    border-color: #3b82f6;
}
</style>

<script>
// Creation method selection
function setCreationMethod(method) {
    document.querySelectorAll('.creation-method').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-method="${method}"]`).classList.add('active');
    document.getElementById('creation_method').value = method;
    
    // Update UI based on method
    const aiSettings = document.getElementById('ai-settings');
    const submitBtn = document.getElementById('submitText');
    
    if (method === 'manual') {
        aiSettings.style.display = 'none';
        submitBtn.textContent = 'متابعة لإضافة الأسئلة';
    } else if (method === 'ai') {
        aiSettings.style.display = 'block';
        submitBtn.textContent = 'توليد الاختبار بالذكاء الاصطناعي';
    } else {
        aiSettings.style.display = 'block';
        submitBtn.textContent = 'توليد وتعديل الأسئلة';
    }
}

// Calculate totals and update chart
function updateTotals() {
    const roots = ['jawhar', 'zihn', 'waslat', 'roaya'];
    const totals = {};
    let grandTotal = 0;
    
    roots.forEach(root => {
        let total = 0;
        document.querySelectorAll(`input[data-root="${root}"]`).forEach(input => {
            total += parseInt(input.value) || 0;
        });
        totals[root] = total;
        grandTotal += total;
        
        // Update total display
        const totalElement = document.querySelector(`.total-questions[data-root="${root}"]`);
        if (totalElement) {
            totalElement.textContent = total;
        }
    });
    
    // Calculate percentages for chart
    const percentages = {};
    roots.forEach(root => {
        percentages[root] = grandTotal > 0 ? Math.round((totals[root] / grandTotal) * 100) : 0;
    });
    
    updateChart(percentages);
}

// Update chart visualization
function updateChart(percentages) {
    const chartContainer = document.getElementById('chart-container');
    // Re-render chart with new percentages
    const chartHTML = `
        <div class="relative w-64 h-64 mx-auto">
            <svg viewBox="0 0 400 400" class="w-full h-full">
                <!-- Background circles -->
                <circle cx="200" cy="200" r="150" fill="none" stroke="#e5e7eb" stroke-width="1" />
                <circle cx="200" cy="200" r="100" fill="none" stroke="#e5e7eb" stroke-width="1" />
                <circle cx="200" cy="200" r="50" fill="none" stroke="#e5e7eb" stroke-width="1" />
                
                <!-- Axis lines -->
                <line x1="200" y1="50" x2="200" y2="350" stroke="#e5e7eb" stroke-width="1" />
                <line x1="50" y1="200" x2="350" y2="200" stroke="#e5e7eb" stroke-width="1" />
                
                <!-- Data polygon -->
                <polygon points="${200},${200 - (percentages.jawhar / 100 * 150)} ${200 + (percentages.zihn / 100 * 150)},${200} ${200},${200 + (percentages.waslat / 100 * 150)} ${200 - (percentages.roaya / 100 * 150)},${200}"
                         fill="rgba(59, 130, 246, 0.2)" 
                         stroke="rgb(59, 130, 246)" 
                         stroke-width="2" />
                
                <!-- Data points -->
                <circle cx="200" cy="${200 - (percentages.jawhar / 100 * 150)}" r="6" fill="#ff6b6b" />
                <circle cx="${200 + (percentages.zihn / 100 * 150)}" cy="200" r="6" fill="#4ecdc4" />
                <circle cx="200" cy="${200 + (percentages.waslat / 100 * 150)}" r="6" fill="#f7b731" />
                <circle cx="${200 - (percentages.roaya / 100 * 150)}" cy="200" r="6" fill="#5f27cd" />
            </svg>
            
            <!-- Labels -->
            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-2 text-center">
                <span class="text-2xl">🎯</span>
                <div class="text-sm font-bold">جَوهر</div>
                <div class="text-xs text-gray-600">${percentages.jawhar}%</div>
            </div>
            
            <div class="absolute top-1/2 right-0 transform translate-x-2 -translate-y-1/2 text-center">
                <span class="text-2xl">🧠</span>
                <div class="text-sm font-bold">ذِهن</div>
                <div class="text-xs text-gray-600">${percentages.zihn}%</div>
            </div>
            
            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-2 text-center">
                <span class="text-2xl">🔗</span>
                <div class="text-sm font-bold">وَصلات</div>
                <div class="text-xs text-gray-600">${percentages.waslat}%</div>
            </div>
            
            <div class="absolute top-1/2 left-0 transform -translate-x-2 -translate-y-1/2 text-center">
                <span class="text-2xl">👁️</span>
                <div class="text-sm font-bold">رُؤية</div>
                <div class="text-xs text-gray-600">${percentages.roaya}%</div>
            </div>
        </div>
    `;
    chartContainer.innerHTML = chartHTML;
}

// Event listeners
document.querySelectorAll('.root-level-input').forEach(input => {
    input.addEventListener('input', updateTotals);
});

// Initialize
updateTotals();
</script>
@endsection