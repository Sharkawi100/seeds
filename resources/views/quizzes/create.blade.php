@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-8">
        <!-- Progress Steps -->
        <div class="mb-12">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <!-- Step 1 -->
                    <div class="step-item active" data-step="1">
                        <div class="relative">
                            <div class="step-circle">
                                <span class="text-2xl">📝</span>
                            </div>
                            <span class="step-label">المعلومات الأساسية</span>
                        </div>
                    </div>
                    
                    <!-- Connector -->
                    <div class="step-connector"></div>
                    
                    <!-- Step 2 -->
                    <div class="step-item" data-step="2">
                        <div class="relative">
                            <div class="step-circle">
                                <span class="text-2xl">📚</span>
                            </div>
                            <span class="step-label">النص التعليمي</span>
                        </div>
                    </div>
                    
                    <!-- Connector -->
                    <div class="step-connector"></div>
                    
                    <!-- Step 3 -->
                    <div class="step-item" data-step="3">
                        <div class="relative">
                            <div class="step-circle">
                                <span class="text-2xl">❓</span>
                            </div>
                            <span class="step-label">توليد الأسئلة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Title -->
        <div class="text-center mb-10 animate-fade-in">
            <h1 class="text-5xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">
                إنشاء اختبار جديد ✨
            </h1>
            <p class="text-xl text-gray-600">ابدأ رحلة تعليمية جديدة مع نموذج جُذور</p>
        </div>

        <!-- Hidden Quiz ID for later steps -->
        <input type="hidden" id="quiz-id" value="">

        <!-- Step 1: Basic Information -->
        <div id="step-1" class="step-content">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 animate-scale-in">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-3xl shadow-lg">
                        📋
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">المعلومات الأساسية</h2>
                        <p class="text-gray-600">ابدأ بتحديد معلومات الاختبار</p>
                    </div>
                </div>

                <form id="step-1-form">
                    @csrf
                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="lg:col-span-2">
                            <label class="block text-lg font-bold text-gray-700 mb-3">
                                عنوان الاختبار
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title"
                                   class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all"
                                   placeholder="مثال: اختبار الفصل الأول في اللغة العربية"
                                   required>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="block text-lg font-bold text-gray-700 mb-3">
                                المادة الدراسية
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="subject_id" 
                                        id="subject_id"
                                        class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 appearance-none cursor-pointer transition-all"
                                        required>
                                    <option value="">اختر المادة</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Grade -->
                        <div>
                            <label class="block text-lg font-bold text-gray-700 mb-3">
                                الصف الدراسي
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="grade_level" 
                                        id="grade_level"
                                        class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 appearance-none cursor-pointer transition-all"
                                        required>
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
                                    <optgroup label="🎯 المرحلة الثانوية">
                                        @for($i = 10; $i <= 12; $i++)
                                        <option value="{{ $i }}">الصف {{ ['العاشر', 'الحادي عشر', 'الثاني عشر'][$i-10] }}</option>
                                        @endfor
                                    </optgroup>
                                </select>
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Topic -->
                        <div class="lg:col-span-2">
                            <label class="block text-lg font-bold text-gray-700 mb-3">
                                موضوع الاختبار
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="topic" 
                                   id="topic"
                                   class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all"
                                   placeholder="مثال: الفصول الأربعة، الحضارة الإسلامية، قواعد اللغة..."
                                   required>
                            <p class="mt-2 text-sm text-gray-500">حدد الموضوع الذي سيتناوله النص والأسئلة</p>
                        </div>

                        <!-- Description -->
                        <div class="lg:col-span-2">
                            <label class="block text-lg font-bold text-gray-700 mb-3">وصف الاختبار</label>
                            <textarea name="description" 
                                      id="description"
                                      rows="3"
                                      class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all"
                                      placeholder="وصف مختصر للاختبار (اختياري)"></textarea>
                        </div>
                    </div>

                    <!-- Quiz Settings -->
                    <div class="mt-8 p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                            <span class="text-2xl">⚙️</span>
                            إعدادات الاختبار
                        </h3>
                        
                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Time Duration -->
                            <div>
                                <label class="block text-lg font-bold text-gray-700 mb-3">
                                    المدة الزمنية
                                    <span class="text-sm font-normal text-gray-500 mr-2">(بالدقائق)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="time_limit" 
                                           class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all"
                                           placeholder="0 = غير محدد"
                                           min="0"
                                           max="180">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <span class="text-2xl">⏰</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Passing Score -->
                            <div>
                                <label class="block text-lg font-bold text-gray-700 mb-3">
                                    درجة النجاح
                                    <span class="text-sm font-normal text-gray-500 mr-2">(%)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="passing_score" 
                                           class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all"
                                           placeholder="60"
                                           value="60"
                                           min="0"
                                           max="100">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <span class="text-2xl">🎯</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Checkbox Options -->
                            <div class="lg:col-span-2 space-y-4">
                                <label class="flex items-center gap-4 cursor-pointer p-4 bg-white rounded-xl hover:bg-gray-50 transition-all">
                                    <input type="checkbox" 
                                           name="shuffle_questions" 
                                           value="1"
                                           class="w-6 h-6 text-purple-600 rounded-lg focus:ring-purple-500">
                                    <div>
                                        <span class="text-lg font-medium text-gray-800">خلط ترتيب الأسئلة</span>
                                        <p class="text-sm text-gray-600">عرض الأسئلة بترتيب عشوائي مختلف لكل طالب</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-4 cursor-pointer p-4 bg-white rounded-xl hover:bg-gray-50 transition-all">
                                    <input type="checkbox" 
                                           name="shuffle_answers" 
                                           value="1"
                                           class="w-6 h-6 text-purple-600 rounded-lg focus:ring-purple-500">
                                    <div>
                                        <span class="text-lg font-medium text-gray-800">خلط خيارات الإجابة</span>
                                        <p class="text-sm text-gray-600">عرض خيارات الإجابة بترتيب عشوائي</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-4 cursor-pointer p-4 bg-white rounded-xl hover:bg-gray-50 transition-all">
                                    <input type="checkbox" 
                                           name="show_results" 
                                           value="1"
                                           checked
                                           class="w-6 h-6 text-purple-600 rounded-lg focus:ring-purple-500">
                                    <div>
                                        <span class="text-lg font-medium text-gray-800">عرض النتائج للطلاب</span>
                                        <p class="text-sm text-gray-600">السماح للطلاب بمشاهدة نتائجهم بعد إنهاء الاختبار</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit"
                                class="group relative px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                            <span class="flex items-center gap-3">
                                التالي
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Step 2: Educational Text -->
        <div id="step-2" class="step-content hidden">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 animate-scale-in">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center text-3xl shadow-lg">
                        📖
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">النص التعليمي</h2>
                        <p class="text-gray-600">اختر أو اكتب النص الذي ستبنى عليه الأسئلة</p>
                    </div>
                </div>

                <!-- Text Source Selection -->
                <div class="mb-8">
                    <div class="grid md:grid-cols-3 gap-6">
                        <button type="button" 
                                onclick="setTextSource('ai')"
                                class="text-source-card active"
                                data-source="ai">
                            <div class="card-icon bg-gradient-to-br from-purple-500 to-pink-600">
                                <span class="text-4xl">🤖</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mt-4">توليد بالذكاء الاصطناعي</h3>
                            <p class="text-gray-600 mt-2">دع الذكاء الاصطناعي يكتب نصاً تعليمياً مناسباً</p>
                        </button>
                        
                        <button type="button" 
                                onclick="setTextSource('manual')"
                                class="text-source-card"
                                data-source="manual">
                            <div class="card-icon bg-gradient-to-br from-green-500 to-teal-600">
                                <span class="text-4xl">✍️</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mt-4">كتابة يدوية</h3>
                            <p class="text-gray-600 mt-2">اكتب أو الصق نصاً جاهزاً</p>
                        </button>

                        <button type="button" 
                                onclick="setTextSource('none')"
                                class="text-source-card"
                                data-source="none">
                            <div class="card-icon bg-gradient-to-br from-blue-500 to-indigo-600">
                                <span class="text-4xl">💭</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mt-4">أسئلة مباشرة</h3>
                            <p class="text-gray-600 mt-2">أسئلة عن الموضوع بدون نص قراءة</p>
                        </button>
                    </div>
                    <input type="hidden" name="text_source" id="text_source" value="ai">
                </div>

                <!-- AI Text Options -->
                <div id="ai-text-options" class="mb-8 space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-lg font-bold text-gray-700 mb-3">نوع النص</label>
                            <select name="text_type" id="text_type"
                                    class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                <option value="story">📖 قصة</option>
                                <option value="article">📰 مقال</option>
                                <option value="dialogue">💬 حوار</option>
                                <option value="description">📝 نص وصفي</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-lg font-bold text-gray-700 mb-3">طول النص</label>
                            <select name="text_length" id="text_length"
                                    class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                <option value="short">قصير (50-100 كلمة)</option>
                                <option value="medium" selected>متوسط (150-250 كلمة)</option>
                                <option value="long">طويل (300-500 كلمة)</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="button" 
                            onclick="generateText()"
                            id="generate-text-btn"
                            class="w-full py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                        <span class="text-2xl">✨</span>
                        توليد النص
                    </button>
                </div>

                <!-- Text Editor -->
                <div class="educational-text-container">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-lg font-bold text-gray-700">النص التعليمي</label>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            <span id="word-count">0</span> كلمة
                        </span>
                    </div>
                    <textarea name="educational_text" id="educational_text"
                              class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all"
                              rows="12"
                              placeholder="اكتب أو الصق النص التعليمي هنا..."
                              oninput="updateWordCount()"></textarea>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" 
                            onclick="previousStep()"
                            class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-2xl font-bold text-lg transition-all flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        السابق
                    </button>
                    <button type="button" 
                            onclick="nextStep()"
                            id="step-2-next"
                            class="group relative px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                        <span class="flex items-center gap-3">
                            التالي
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 3: Question Generation -->
        <div id="step-3" class="step-content hidden">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 animate-scale-in">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center text-3xl shadow-lg">
                        🎯
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">إعدادات توليد الأسئلة</h2>
                        <p class="text-gray-600">حدد توزيع الأسئلة حسب نموذج جُذور</p>
                    </div>
                </div>

                <!-- Text Preview -->
                <div class="mb-8 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6">
                    <h4 class="text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span>👁️</span> معاينة النص
                    </h4>
                    <div id="text-preview" class="text-gray-700 leading-relaxed max-h-40 overflow-y-auto"></div>
                </div>

                <!-- Presets -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">قوالب جاهزة 🎨</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <button type="button" onclick="applyPreset('balanced')" 
                                class="preset-btn">
                            <span class="text-3xl mb-2">⚖️</span>
                            <span class="font-bold">متوازن</span>
                        </button>
                        <button type="button" onclick="applyPreset('comprehension')" 
                                class="preset-btn">
                            <span class="text-3xl mb-2">📚</span>
                            <span class="font-bold">فهم قرائي</span>
                        </button>
                        <button type="button" onclick="applyPreset('analytical')" 
                                class="preset-btn">
                            <span class="text-3xl mb-2">🔬</span>
                            <span class="font-bold">تحليلي</span>
                        </button>
                        <button type="button" onclick="applyPreset('creative')" 
                                class="preset-btn">
                            <span class="text-3xl mb-2">💡</span>
                            <span class="font-bold">إبداعي</span>
                        </button>
                    </div>
                </div>

                <!-- Root Distribution -->
                <form id="step-3-form">
                    <div class="grid lg:grid-cols-2 gap-6 mb-8">
                        @php
                        $roots = [
                            'jawhar' => ['name' => 'جَوهر', 'desc' => 'أسئلة مباشرة من النص', 'emoji' => '🎯', 'color' => 'from-red-500 to-orange-500'],
                            'zihn' => ['name' => 'ذِهن', 'desc' => 'تحليل وفهم العمليات', 'emoji' => '🧠', 'color' => 'from-cyan-500 to-blue-500'],
                            'waslat' => ['name' => 'وَصلات', 'desc' => 'ربط المعلومات', 'emoji' => '🔗', 'color' => 'from-yellow-500 to-amber-500'],
                            'roaya' => ['name' => 'رُؤية', 'desc' => 'تطبيق وإبداع', 'emoji' => '👁️', 'color' => 'from-purple-500 to-pink-500']
                        ];
                        @endphp
                        
                        @foreach($roots as $key => $root)
                        <div class="root-card" data-root="{{ $key }}">
                            <div class="root-header bg-gradient-to-r {{ $root['color'] }}">
                                <div class="flex items-center gap-3">
                                    <span class="text-4xl">{{ $root['emoji'] }}</span>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ $root['name'] }}</h3>
                                        <p class="text-white/80 text-sm">{{ $root['desc'] }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="root-body">
                                @foreach(['سطحي' => 1, 'متوسط' => 2, 'عميق' => 3] as $levelName => $levelValue)
                                <div class="level-row">
                                    <div class="level-info">
                                        <span class="level-badge level-{{ $levelValue }}">{{ $levelName }}</span>
                                    </div>
                                    <div class="level-control">
                                        <button type="button" 
                                                onclick="decrementLevel('{{ $key }}', {{ $levelValue }})"
                                                class="control-btn">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               name="roots[{{ $key }}][levels][{{ $levelValue }}][count]" 
                                               id="{{ $key }}-{{ $levelValue }}"
                                               min="0" 
                                               max="10" 
                                               value="0"
                                               class="level-input"
                                               onchange="updateTotals()">
                                        <input type="hidden" 
                                               name="roots[{{ $key }}][levels][{{ $levelValue }}][depth]" 
                                               value="{{ $levelValue }}">
                                        <button type="button" 
                                                onclick="incrementLevel('{{ $key }}', {{ $levelValue }})"
                                                class="control-btn">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                
                                <div class="root-total">
                                    <span class="text-lg font-bold">المجموع:</span>
                                    <span class="total-questions text-2xl font-black bg-gradient-to-r {{ $root['color'] }} bg-clip-text text-transparent" data-root="{{ $key }}">0</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Total Summary -->
                    <div class="bg-gradient-to-r from-purple-100 to-pink-100 rounded-2xl p-6 mb-8">
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-gray-800">إجمالي الأسئلة:</span>
                            <span id="grand-total" class="text-4xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">0</span>
                        </div>
                    </div>

                    <!-- Creation Method -->
                    <div class="mb-8">
                        <label class="block text-lg font-bold text-gray-700 mb-3">طريقة الإنشاء</label>
                        <select name="creation_method" id="creation_method"
                                class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                            <option value="ai">🤖 توليد تلقائي بالذكاء الاصطناعي</option>
                            <option value="hybrid">✨ توليد بالذكاء الاصطناعي مع إمكانية التعديل</option>
                            <option value="manual">✍️ إضافة يدوية للأسئلة</option>
                        </select>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex justify-between items-center">
                        <button type="button" 
                                onclick="previousStep()"
                                class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-2xl font-bold text-lg transition-all flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                            </svg>
                            السابق
                        </button>
                        
                        <button type="submit"
                                class="group relative px-10 py-5 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-2xl font-black text-xl hover:shadow-2xl transform hover:scale-105 transition-all">
                            <span class="flex items-center gap-3">
                                <span class="text-2xl">🚀</span>
                                إنشاء الاختبار
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loading-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-3xl shadow-2xl p-10 max-w-md w-full mx-4 text-center animate-bounce-in">
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full mb-6">
                    <span class="text-5xl animate-spin">🤖</span>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-3" id="loading-title">جاري المعالجة</h3>
                <p class="text-lg text-gray-600" id="loading-message">يتم معالجة طلبك...</p>
            </div>
            <div class="space-y-4">
                <div class="loading-step active">
                    <span class="loading-icon">⚡</span>
                    <span class="loading-text">معالجة البيانات</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-3xl shadow-2xl p-10 max-w-md w-full mx-4 text-center animate-bounce-in">
        <div class="mb-6">
            <span class="text-8xl">🎉</span>
        </div>
        <h2 class="text-3xl font-black text-gray-900 mb-4">تم إنشاء الاختبار بنجاح!</h2>
        <div class="bg-gradient-to-r from-purple-100 to-pink-100 rounded-2xl p-6 mb-6">
            <p class="text-lg text-gray-700 mb-2">رمز الدخول للطلاب</p>
            <p class="text-5xl font-black text-purple-600 tracking-wider" id="quiz-pin">XXXXX</p>
        </div>
        <div class="space-y-3">
            <button onclick="copyPIN()" class="w-full py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-2xl font-bold text-lg hover:shadow-xl transition-all">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    نسخ رمز الدخول
                </span>
            </button>
            <a href="#" id="view-quiz-btn" class="block w-full py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-2xl font-bold text-lg transition-all">
                عرض الاختبار
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Animation keyframes */
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scale-in {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes bounce-in {
    0% { opacity: 0; transform: scale(0.3); }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { opacity: 1; transform: scale(1); }
}

/* Animations */
.animate-blob { animation: blob 7s infinite; }
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-4000 { animation-delay: 4s; }
.animate-fade-in { animation: fade-in 0.6s ease-out; }
.animate-scale-in { animation: scale-in 0.4s ease-out; }
.animate-bounce-in { animation: bounce-in 0.6s ease-out; }

/* Progress Steps */
.step-item {
    position: relative;
    text-align: center;
}

.step-circle {
    width: 5rem;
    height: 5rem;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    transition: all 0.3s;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.step-item.active .step-circle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: scale(1.1);
    box-shadow: 0 10px 15px -3px rgba(102, 126, 234, 0.4);
}

.step-item.completed .step-circle {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.step-label {
    font-size: 1rem;
    color: #6b7280;
    font-weight: 600;
}

.step-item.active .step-label {
    color: #1f2937;
    font-weight: 700;
}

.step-connector {
    flex: 1;
    height: 3px;
    background: #e5e7eb;
    margin-top: 2.5rem;
}

/* Text Source Cards */
.text-source-card {
    padding: 2rem;
    border: 3px solid #e5e7eb;
    border-radius: 1.5rem;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
    background: white;
}

.text-source-card:hover {
    border-color: #a78bfa;
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.text-source-card.active {
    border-color: #7c3aed;
    background: linear-gradient(to bottom right, #f3e8ff, #fce7f3);
    transform: scale(1.05);
}

.card-icon {
    width: 5rem;
    height: 5rem;
    border-radius: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
}

/* Root Cards */
.root-card {
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
}

.root-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
}

.root-header {
    padding: 1.5rem;
    color: white;
}

.root-body {
    background: white;
    padding: 1.5rem;
}

.level-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 2px solid #f3f4f6;
}

.level-row:last-child {
    border-bottom: none;
}

.level-badge {
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.875rem;
}

.level-badge.level-1 {
    background: #fef3c7;
    color: #92400e;
}

.level-badge.level-2 {
    background: #fed7aa;
    color: #7c2d12;
}

.level-badge.level-3 {
    background: #d1fae5;
    color: #065f46;
}

.level-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.control-btn {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    border: 2px solid #e5e7eb;
    background: white;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    cursor: pointer;
}

.control-btn:hover {
    background: #f3f4f6;
    color: #1f2937;
    border-color: #d1d5db;
    transform: scale(1.1);
}

.level-input {
    width: 4rem;
    text-align: center;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 0.5rem;
    font-size: 1.125rem;
    font-weight: 600;
}

.root-total {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 3px solid #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Preset Buttons */
.preset-btn {
    padding: 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 1rem;
    background: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
}

.preset-btn:hover {
    background: linear-gradient(to bottom right, #f3e8ff, #fce7f3);
    border-color: #a78bfa;
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Loading Modal */
.loading-step {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 1rem;
    background: #f3f4f6;
}

.loading-step.active {
    background: linear-gradient(to right, #f3e8ff, #fce7f3);
}

.loading-icon {
    font-size: 1.5rem;
    display: inline-block;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .step-circle {
        width: 4rem;
        height: 4rem;
    }
    
    .step-label {
        font-size: 0.875rem;
    }
    
    .text-source-card {
        padding: 1.5rem;
    }
    
    .card-icon {
        width: 4rem;
        height: 4rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Global state
let currentStep = 1;
let textSource = 'ai';
let quizId = null;

// Step navigation
function nextStep() {
    if (validateStep(currentStep)) {
        if (currentStep < 3) {
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            currentStep++;
            document.getElementById(`step-${currentStep}`).classList.remove('hidden');
            updateStepIndicators();
            
            if (currentStep === 3) {
                updateTextPreview();
            }
        }
    }
}

function previousStep() {
    if (currentStep > 1) {
        document.getElementById(`step-${currentStep}`).classList.add('hidden');
        currentStep--;
        document.getElementById(`step-${currentStep}`).classList.remove('hidden');
        updateStepIndicators();
    }
}

function validateStep(step) {
    if (step === 1) {
        const title = document.getElementById('title').value.trim();
        const subjectId = document.getElementById('subject_id').value;
        const gradeLevel = document.getElementById('grade_level').value;
        const topic = document.getElementById('topic').value.trim();
        
        if (!title || !subjectId || !gradeLevel || !topic) {
            showNotification('يرجى ملء جميع الحقول المطلوبة', 'error');
            return false;
        }
    } else if (step === 2) {
        if (textSource === 'none') {
            // No text validation needed for direct questions
            return true;
        }
        
        const text = document.getElementById('educational_text').value.trim();
        if (!text || text.length < 50) {
            showNotification('النص يجب أن يكون 50 حرف على الأقل', 'error');
            return false;
        }
    }
    return true;
}

function updateStepIndicators() {
    document.querySelectorAll('.step-item').forEach((item, index) => {
        const stepNum = index + 1;
        if (stepNum < currentStep) {
            item.classList.add('completed');
            item.classList.remove('active');
        } else if (stepNum === currentStep) {
            item.classList.add('active');
            item.classList.remove('completed');
        } else {
            item.classList.remove('active', 'completed');
        }
    });
}

// Text source
function setTextSource(source) {
    textSource = source;
    document.getElementById('text_source').value = source;
    
    document.querySelectorAll('.text-source-card').forEach(card => {
        card.classList.toggle('active', card.dataset.source === source);
    });
    
    const aiOptions = document.getElementById('ai-text-options');
    const textEditor = document.querySelector('.educational-text-container');
    
    if (source === 'ai') {
        aiOptions.style.display = 'block';
        textEditor.style.display = 'block';
    } else if (source === 'manual') {
        aiOptions.style.display = 'none';
        textEditor.style.display = 'block';
    } else if (source === 'none') {
        aiOptions.style.display = 'none';
        textEditor.style.display = 'none';
        document.getElementById('educational_text').value = '';
        updateWordCount();
    }
}

// Step 1 form submission
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('step-1-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validateStep(1)) return;
        
        const formData = new FormData(this);
        
        console.log('Submitting Step 1 data:', Object.fromEntries(formData));
        
        try {
            const response = await fetch('{{ route("quizzes.create-step-1") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                quizId = data.quiz_id;
                document.getElementById('quiz-id').value = quizId;
                console.log('Quiz ID set to:', quizId);
                showNotification('تم حفظ المعلومات الأساسية بنجاح', 'success');
                nextStep();
            } else {
                console.error('Step 1 failed:', data);
                showNotification(data.message || 'حدث خطأ في حفظ البيانات', 'error');
            }
        } catch (error) {
            console.error('Step 1 error:', error);
            showNotification('حدث خطأ في الاتصال بالخادم', 'error');
        }
    });
    
    updateTotals();
});

// Generate text
async function generateText() {
    if (!quizId) {
        showNotification('خطأ: لم يتم العثور على معرف الاختبار', 'error');
        return;
    }
    
    const btn = document.getElementById('generate-text-btn');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="text-2xl animate-spin">⚡</span> جاري التوليد...';
    
    // Get subject name from selected option text
    const subjectSelect = document.getElementById('subject_id');
    const subjectText = subjectSelect.options[subjectSelect.selectedIndex].text;
    const subjectName = subjectText.replace(/🌍|🌎|🌏/, '').trim();
    
    try {
        const response = await fetch(`/quizzes/${quizId}/generate-text`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                topic: document.getElementById('topic').value,
                passage_topic: document.getElementById('topic').value, // Same as topic for now
                text_type: document.getElementById('text_type').value
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('educational_text').value = data.text;
            updateWordCount();
            showNotification('تم توليد النص بنجاح', 'success');
        } else {
            showNotification(data.message || 'فشل توليد النص', 'error');
        }
    } catch (error) {
        showNotification('حدث خطأ في الاتصال', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalContent;
    }
}

// Word count
function updateWordCount() {
    const text = document.getElementById('educational_text').value;
    const words = text.trim().split(/\s+/).filter(word => word.length > 0);
    document.getElementById('word-count').textContent = words.length;
}

// Text preview
function updateTextPreview() {
    if (textSource === 'none') {
        document.getElementById('text-preview').innerHTML = '<em class="text-gray-500">لا يوجد نص - سيتم إنشاء أسئلة مباشرة عن الموضوع</em>';
    } else {
        const text = document.getElementById('educational_text').value;
        document.getElementById('text-preview').textContent = text || 'لا يوجد نص';
    }
}

// Level controls
function incrementLevel(root, level) {
    const input = document.getElementById(`${root}-${level}`);
    const currentValue = parseInt(input.value) || 0;
    if (currentValue < 10) {
        input.value = currentValue + 1;
        updateTotals();
    }
}

function decrementLevel(root, level) {
    const input = document.getElementById(`${root}-${level}`);
    const currentValue = parseInt(input.value) || 0;
    if (currentValue > 0) {
        input.value = currentValue - 1;
        updateTotals();
    }
}

// Update totals
function updateTotals() {
    const roots = ['jawhar', 'zihn', 'waslat', 'roaya'];
    let grandTotal = 0;
    
    roots.forEach(root => {
        let total = 0;
        for (let level = 1; level <= 3; level++) {
            const input = document.getElementById(`${root}-${level}`);
            if (input) {
                total += parseInt(input.value) || 0;
            }
        }
        grandTotal += total;
        
        const totalElement = document.querySelector(`.total-questions[data-root="${root}"]`);
        if (totalElement) {
            totalElement.textContent = total;
        }
    });
    
    document.getElementById('grand-total').textContent = grandTotal;
}

// Presets
function applyPreset(preset) {
    const presets = {
        balanced: {
            jawhar: [2, 2, 1],
            zihn: [2, 2, 1],
            waslat: [1, 1, 1],
            roaya: [1, 1, 0]
        },
        comprehension: {
            jawhar: [3, 2, 1],
            zihn: [2, 1, 0],
            waslat: [1, 1, 0],
            roaya: [0, 0, 0]
        },
        analytical: {
            jawhar: [1, 1, 0],
            zihn: [2, 3, 2],
            waslat: [2, 2, 1],
            roaya: [0, 1, 0]
        },
        creative: {
            jawhar: [1, 0, 0],
            zihn: [1, 1, 0],
            waslat: [1, 2, 1],
            roaya: [2, 2, 2]
        }
    };
    
    const config = presets[preset];
    if (config) {
        Object.keys(config).forEach(root => {
            config[root].forEach((value, index) => {
                const level = index + 1;
                const input = document.getElementById(`${root}-${level}`);
                if (input) {
                    input.value = value;
                }
            });
        });
        updateTotals();
        showNotification(`تم تطبيق القالب`, 'success');
    }
}

// Step 3 form submission (final quiz creation)
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('step-3-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const grandTotal = parseInt(document.getElementById('grand-total').textContent);
        if (grandTotal === 0) {
            showNotification('يجب إضافة سؤال واحد على الأقل', 'error');
            return;
        }
        
        showLoadingModal('جاري إنشاء الاختبار', 'يتم معالجة البيانات وإنشاء الاختبار...');
        
        // Collect all form data
        const step1Data = new FormData(document.getElementById('step-1-form'));
        const step3Data = new FormData(this);
        
        // Combine all data
        const finalData = new FormData();
        
        // Add step 1 data
        for (let [key, value] of step1Data.entries()) {
            finalData.append(key, value);
        }
        
        // Add step 2 data
        finalData.append('educational_text', document.getElementById('educational_text').value);
        finalData.append('text_source', textSource);
        
        // Add step 3 data
        for (let [key, value] of step3Data.entries()) {
            finalData.append(key, value);
        }
        
        try {
            const response = await fetch('{{ route("quizzes.store") }}', {
                method: 'POST',
                body: finalData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                hideLoadingModal();
                showSuccessModal(data.quiz_pin, data.redirect);
            } else {
                hideLoadingModal();
                showNotification(data.message || 'فشل إنشاء الاختبار', 'error');
            }
        } catch (error) {
            hideLoadingModal();
            showNotification('حدث خطأ في الاتصال', 'error');
        }
    });
});

// Loading modal
function showLoadingModal(title, message) {
    document.getElementById('loading-title').textContent = title;
    document.getElementById('loading-message').textContent = message;
    document.getElementById('loading-modal').classList.remove('hidden');
}

function hideLoadingModal() {
    document.getElementById('loading-modal').classList.add('hidden');
}

// Success modal
function showSuccessModal(pin, redirectUrl) {
    document.getElementById('quiz-pin').textContent = pin;
    document.getElementById('view-quiz-btn').href = redirectUrl;
    document.getElementById('success-modal').classList.remove('hidden');
    
    // Auto redirect after 5 seconds
    setTimeout(() => {
        window.location.href = redirectUrl;
    }, 5000);
}

// Copy PIN
function copyPIN() {
    const pin = document.getElementById('quiz-pin').textContent;
    navigator.clipboard.writeText(pin);
    showNotification('تم نسخ رمز الدخول', 'success');
}

// Notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-2xl shadow-2xl z-50 flex items-center gap-3 animate-fade-in ${
        type === 'error' ? 'bg-red-100 text-red-800' :
        type === 'success' ? 'bg-green-100 text-green-800' :
        'bg-blue-100 text-blue-800'
    }`;
    
    const icon = type === 'error' ? '❌' :
                 type === 'success' ? '✅' :
                 'ℹ️';
    
    notification.innerHTML = `
        <span class="text-2xl">${icon}</span>
        <span class="font-medium text-lg">${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endpush