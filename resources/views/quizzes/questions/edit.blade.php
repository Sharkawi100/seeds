@extends('layouts.app')

@section('title', 'تعديل السؤال - ' . $quiz->title)

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="relative z-10">
            <!-- Breadcrumb -->
            <nav class="mb-8 animate-fade-in">
                <ol class="flex items-center gap-3 text-sm">
                    <li>
                        <a href="{{ route('quizzes.index') }}" class="text-gray-500 hover:text-purple-600 transition-colors">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li>
                        <a href="{{ route('quizzes.show', $quiz) }}" class="text-gray-500 hover:text-purple-600 transition-colors">
                            {{ $quiz->title }}
                        </a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li>
                        <a href="{{ route('quizzes.questions.index', $quiz) }}" class="text-gray-500 hover:text-purple-600 transition-colors">
                            الأسئلة
                        </a>
                    </li>
                    <li class="text-gray-400">/</li>
                    <li class="text-purple-600 font-medium">تعديل السؤال</li>
                </ol>
            </nav>
            
            <!-- Main Card -->
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 p-8 relative overflow-hidden">
                    <!-- Animated Shapes -->
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full transform translate-x-16 -translate-y-16"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full transform -translate-x-24 translate-y-24"></div>
                    </div>
                    
                    <div class="relative flex items-center gap-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 animate-bounce-slow">
                            <i class="fas fa-edit text-3xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-white mb-2">تعديل السؤال</h2>
                            <p class="text-white/80">{{ $quiz->title }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Form -->
                <form action="{{ route('quizzes.questions.update', [$quiz, $question]) }}" method="POST" class="p-8" id="question-edit-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Question Text -->
                    <div class="mb-8 animate-fade-in animation-delay-300">
                        <label class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-question-circle text-purple-600"></i>
                            نص السؤال
                        </label>
                        <div class="relative">
                            <textarea name="question" 
                                      id="question-editor"
                                      class="w-full"
                                      required>{{ old('question', $question->question) }}</textarea>
                        </div>
                        @error('question')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                        @enderror
                        
                        <!-- Character Counter -->
                        <div class="mt-2 text-sm text-gray-500 flex justify-between">
                            <span>استخدم المحرر لتنسيق السؤال بشكل جذاب</span>
                            <span id="char-counter" class="font-medium">0 حرف</span>
                        </div>
                    </div>
                    
                    <!-- Root Type and Depth Level -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <!-- Root Type -->
                        <div class="animate-fade-in animation-delay-400">
                            <label class="block text-lg font-bold text-gray-700 mb-3">
                                <i class="fas fa-tree text-purple-600 ml-2"></i>
                                نوع الجذر
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                $roots = [
                                    'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯', 'desc' => 'ما هو؟', 'color' => 'from-red-500 to-pink-500'],
                                    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠', 'desc' => 'كيف يعمل؟', 'color' => 'from-cyan-500 to-blue-500'],
                                    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗', 'desc' => 'كيف يرتبط؟', 'color' => 'from-yellow-500 to-orange-500'],
                                    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️', 'desc' => 'كيف نستخدمه؟', 'color' => 'from-purple-500 to-indigo-500']
                                ];
                                @endphp
                                
                                @foreach($roots as $key => $root)
                                <label class="cursor-pointer">
                                    <input type="radio" 
                                           name="root_type" 
                                           value="{{ $key }}" 
                                           {{ old('root_type', $question->root_type) == $key ? 'checked' : '' }}
                                           class="sr-only peer"
                                           required>
                                    <div class="relative p-4 border-2 border-gray-200 rounded-xl peer-checked:border-transparent peer-checked:shadow-lg transition-all duration-200 hover:shadow-md group">
                                        <div class="absolute inset-0 bg-gradient-to-br {{ $root['color'] }} rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></div>
                                        <div class="relative text-center">
                                            <span class="text-3xl block mb-2 group-hover:scale-110 transition-transform">{{ $root['icon'] }}</span>
                                            <h4 class="font-bold text-gray-800 peer-checked:text-white">{{ $root['name'] }}</h4>
                                            <p class="text-xs text-gray-600 peer-checked:text-white/80 mt-1">{{ $root['desc'] }}</p>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('root_type')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Depth Level -->
                        <div class="animate-fade-in animation-delay-500">
                            <label class="block text-lg font-bold text-gray-700 mb-3">
                                <i class="fas fa-layer-group text-purple-600 ml-2"></i>
                                مستوى العمق
                            </label>
                            <div class="space-y-3">
                                @php
                                $levels = [
                                    1 => ['name' => 'سطحي', 'icon' => '🟡', 'desc' => 'أسئلة أساسية ومباشرة', 'color' => 'from-yellow-400 to-yellow-600'],
                                    2 => ['name' => 'متوسط', 'icon' => '🟠', 'desc' => 'أسئلة تحليلية وتطبيقية', 'color' => 'from-orange-400 to-orange-600'],
                                    3 => ['name' => 'عميق', 'icon' => '🟢', 'desc' => 'أسئلة إبداعية ونقدية', 'color' => 'from-green-400 to-green-600']
                                ];
                                @endphp
                                
                                @foreach($levels as $value => $level)
                                <label class="cursor-pointer">
                                    <input type="radio" 
                                           name="depth_level" 
                                           value="{{ $value }}" 
                                           {{ old('depth_level', $question->depth_level) == $value ? 'checked' : '' }}
                                           class="sr-only peer"
                                           required>
                                    <div class="relative p-4 border-2 border-gray-200 rounded-xl peer-checked:border-transparent peer-checked:shadow-lg transition-all duration-200 hover:shadow-md">
                                        <div class="absolute inset-0 bg-gradient-to-r {{ $level['color'] }} rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></div>
                                        <div class="relative flex items-center gap-4">
                                            <span class="text-2xl">{{ $level['icon'] }}</span>
                                            <div>
                                                <h4 class="font-bold text-gray-800 peer-checked:text-white">
                                                    المستوى {{ $value }} - {{ $level['name'] }}
                                                </h4>
                                                <p class="text-xs text-gray-600 peer-checked:text-white/80">{{ $level['desc'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @error('depth_level')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Answer Options -->
                    <div class="mb-8 animate-fade-in animation-delay-600">
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-lg font-bold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-list-ul text-purple-600"></i>
                                خيارات الإجابة
                            </label>
                            <div class="flex gap-2">
                                <button type="button" 
                                        onclick="removeOption()" 
                                        class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-all duration-200 flex items-center gap-2 text-sm font-medium">
                                    <i class="fas fa-minus-circle"></i>
                                    إزالة خيار
                                </button>
                                <button type="button" 
                                        onclick="addOption()" 
                                        class="px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-all duration-200 flex items-center gap-2 text-sm font-medium">
                                    <i class="fas fa-plus-circle"></i>
                                    إضافة خيار
                                </button>
                            </div>
                        </div>
                        
                        <div id="options-container" class="space-y-3">
                            @foreach($question->options as $index => $option)
                            <div class="option-item flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200 group" data-index="{{ $index }}">
                                <input type="radio" 
                                       name="correct_answer_index" 
                                       value="{{ $index }}" 
                                       id="option-{{ $index }}"
                                       class="w-5 h-5 text-green-600 focus:ring-green-500 cursor-pointer"
                                       {{ old('correct_answer_index', $question->correct_answer == $option ? $index : -1) == $index ? 'checked' : '' }}
                                       required>
                                
                                <label for="option-{{ $index }}" class="flex items-center gap-3 flex-1 cursor-pointer">
                                    <span class="flex-shrink-0 w-10 h-10 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center text-sm font-bold text-gray-700 group-hover:border-purple-400 transition-colors">
                                        {{ ['أ', 'ب', 'ج', 'د', 'هـ', 'و'][$index] }}
                                    </span>
                                    
                                    <input type="text" 
                                           name="options[]" 
                                           value="{{ old('options.' . $index, $option) }}" 
                                           class="flex-1 px-4 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-200"
                                           placeholder="اكتب خيار الإجابة..."
                                           required>
                                    
                                    <div class="option-status opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-grip-vertical text-gray-400"></i>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        
                        <p class="mt-3 text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            حدد الإجابة الصحيحة بالنقر على الدائرة بجانب الخيار
                        </p>
                    </div>
                    
                    <!-- AI Suggestions (Optional) -->
                    <div class="mb-8 animate-fade-in animation-delay-700">
                        <button type="button" 
                                onclick="toggleAISuggestions()"
                                class="w-full bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 rounded-xl p-4 flex items-center justify-between transition-all duration-200 group">
                            <span class="flex items-center gap-3 text-lg font-medium text-gray-700">
                                <i class="fas fa-magic text-purple-600 group-hover:rotate-12 transition-transform"></i>
                                اقتراحات الذكاء الاصطناعي
                            </span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="ai-chevron"></i>
                        </button>
                        
                        <div id="ai-suggestions" class="hidden mt-4 p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                            <div class="text-center py-8">
                                <i class="fas fa-robot text-5xl text-purple-600 mb-4 animate-pulse"></i>
                                <p class="text-gray-600">اضغط لتوليد اقتراحات ذكية لتحسين السؤال</p>
                                <button type="button" 
                                        onclick="generateAISuggestions()"
                                        class="mt-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg font-medium hover:shadow-lg transition-all">
                                    توليد اقتراحات
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t-2 border-gray-100 animate-fade-in animation-delay-800">
                        <button type="submit" 
                                id="submit-btn"
                                class="group relative px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-bold text-lg hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
                            <span class="relative z-10 flex items-center justify-center gap-3">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        </button>
                        
                        <button type="button"
                                onclick="previewQuestion()"
                                class="px-8 py-4 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-bold text-lg transition-all duration-200 flex items-center justify-center gap-3">
                            <i class="fas fa-eye"></i>
                            معاينة
                        </button>
                        
                        <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                           class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold text-lg transition-all duration-200 flex items-center justify-center gap-3">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Tips Card -->
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 animate-fade-in animation-delay-900">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-lightbulb text-yellow-500"></i>
                    نصائح لكتابة أسئلة فعّالة
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📝</span>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">وضوح السؤال</h4>
                            <p class="text-sm text-gray-600">اكتب السؤال بلغة واضحة ومباشرة</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🎯</span>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">هدف محدد</h4>
                            <p class="text-sm text-gray-600">ركز على مفهوم واحد في كل سؤال</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">⚖️</span>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">خيارات متوازنة</h4>
                            <p class="text-sm text-gray-600">اجعل جميع الخيارات منطقية ومتقاربة</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🔍</span>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">تجنب الإشارات</h4>
                            <p class="text-sm text-gray-600">لا تضع إشارات واضحة للإجابة الصحيحة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50" onclick="closePreviewModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-pink-600 p-6 rounded-t-3xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-white">معاينة السؤال</h3>
                    <button onclick="closePreviewModal()" class="text-white/80 hover:text-white">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                <div id="preview-content">
                    <!-- Preview content will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animations */
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .animate-bounce-slow {
        animation: bounce-slow 3s ease-in-out infinite;
    }
    
    /* Animation Delays */
    .animation-delay-300 { animation-delay: 300ms; }
    .animation-delay-400 { animation-delay: 400ms; }
    .animation-delay-500 { animation-delay: 500ms; }
    .animation-delay-600 { animation-delay: 600ms; }
    .animation-delay-700 { animation-delay: 700ms; }
    .animation-delay-800 { animation-delay: 800ms; }
    .animation-delay-900 { animation-delay: 900ms; }
    
    /* Custom Radio Buttons */
    input[type="radio"]:checked + div {
        transform: scale(1.02);
    }
    
    /* Sortable Options */
    .sortable-ghost {
        opacity: 0.4;
        background: #f3f4f6;
    }
    
    .sortable-drag {
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
</style>
@endpush

@push('scripts')
<!-- TinyMCE with API Key -->
<script src="https://cdn.tiny.cloud/1/cmtwmtmif3u7ducaiqvogvq1wvc280ugtxjzo2ffaymjmuxg/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Sortable.js for drag-and-drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
// Initialize TinyMCE
tinymce.init({
    selector: '#question-editor',
    language: 'ar',
    directionality: 'rtl',
    height: 300,
    menubar: false,
    plugins: 'lists link charmap preview searchreplace autolink directionality code fullscreen table emoticons',
    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | bullist numlist | link | alignleft aligncenter alignright alignjustify | removeformat | preview code | emoticons',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; font-size: 18px; line-height: 1.6; }',
    branding: false,
    promotion: false,
    entity_encoding: 'raw',
    placeholder: 'اكتب نص السؤال هنا...',
    setup: function(editor) {
        editor.on('init', function() {
            updateCharCounter();
        });
        editor.on('keyup', function() {
            updateCharCounter();
        });
    }
});

// Character Counter
function updateCharCounter() {
    const editor = tinymce.get('question-editor');
    if (editor) {
        const text = editor.getContent({ format: 'text' });
        document.getElementById('char-counter').textContent = text.length + ' حرف';
    }
}

// Options Management
const optionLetters = ['أ', 'ب', 'ج', 'د', 'هـ', 'و'];

function addOption() {
    const container = document.getElementById('options-container');
    const optionCount = container.children.length;
    
    if (optionCount >= 6) {
        showNotification('لا يمكن إضافة أكثر من 6 خيارات', 'warning');
        return;
    }
    
    const newOption = document.createElement('div');
    newOption.className = 'option-item flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200 group opacity-0';
    newOption.setAttribute('data-index', optionCount);
    newOption.innerHTML = `
        <input type="radio" 
               name="correct_answer_index" 
               value="${optionCount}" 
               id="option-${optionCount}"
               class="w-5 h-5 text-green-600 focus:ring-green-500 cursor-pointer"
               required>
        
        <label for="option-${optionCount}" class="flex items-center gap-3 flex-1 cursor-pointer">
            <span class="flex-shrink-0 w-10 h-10 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center text-sm font-bold text-gray-700 group-hover:border-purple-400 transition-colors">
                ${optionLetters[optionCount]}
            </span>
            
            <input type="text" 
                   name="options[]" 
                   class="flex-1 px-4 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-100 transition-all duration-200"
                   placeholder="اكتب خيار الإجابة..."
                   required>
            
            <div class="option-status opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-grip-vertical text-gray-400"></i>
            </div>
        </label>
    `;
    
    container.appendChild(newOption);
    
    // Animate in
    setTimeout(() => {
        newOption.style.opacity = '1';
    }, 10);
    
    // Focus on new input
    newOption.querySelector('input[type="text"]').focus();
    
    // Reinitialize sortable
    initSortable();
    
    showNotification('تم إضافة خيار جديد', 'success');
}

function removeOption() {
    const container = document.getElementById('options-container');
    const optionCount = container.children.length;
    
    if (optionCount <= 2) {
        showNotification('يجب أن يكون هناك خياران على الأقل', 'warning');
        return;
    }
    
    const lastOption = container.lastElementChild;
    lastOption.style.opacity = '0';
    lastOption.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
        lastOption.remove();
        updateOptionIndices();
    }, 300);
    
    showNotification('تم حذف الخيار', 'info');
}

function updateOptionIndices() {
    const options = document.querySelectorAll('.option-item');
    options.forEach((option, index) => {
        option.setAttribute('data-index', index);
        option.querySelector('input[type="radio"]').value = index;
        option.querySelector('input[type="radio"]').id = `option-${index}`;
        option.querySelector('label').setAttribute('for', `option-${index}`);
        option.querySelector('.rounded-full span').textContent = optionLetters[index];
    });
}

// Initialize Sortable for drag-and-drop
function initSortable() {
    const container = document.getElementById('options-container');
    if (container) {
        new Sortable(container, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            handle: '.option-status',
            onEnd: function() {
                updateOptionIndices();
            }
        });
    }
}

// AI Suggestions
function toggleAISuggestions() {
    const suggestions = document.getElementById('ai-suggestions');
    const chevron = document.getElementById('ai-chevron');
    
    suggestions.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

async function generateAISuggestions() {
    const submitBtn = document.querySelector('button[onclick="generateAISuggestions()"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التوليد...';
    
    try {
        const response = await fetch(`{{ route('quizzes.questions.suggestions', [$quiz, $question]) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.suggestions) {
            displaySuggestions(data.suggestions);
            showNotification('تم توليد الاقتراحات بنجاح', 'success');
        } else {
            showNotification(data.message || 'فشل توليد الاقتراحات', 'error');
        }
    } catch (error) {
        showNotification('حدث خطأ في الاتصال', 'error');
        console.error('Suggestions error:', error);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Add function to display suggestions
function displaySuggestions(suggestions) {
    const container = document.getElementById('ai-suggestions');
    const content = container.querySelector('.text-center');
    
    if (suggestions.suggestions && suggestions.suggestions.length > 0) {
        let html = '<div class="space-y-4">';
        
        suggestions.suggestions.forEach((suggestion, index) => {
            html += `
                <div class="bg-white rounded-lg p-4 border border-purple-200">
                    <h4 class="font-bold text-purple-600 mb-2">${suggestion.type}</h4>
                    <p class="text-gray-600 text-sm mb-2">${suggestion.reason}</p>
                    <div class="bg-gray-50 p-3 rounded">
                        <strong>الحالي:</strong> ${suggestion.current}<br>
                        <strong>المقترح:</strong> <span class="text-green-600">${suggestion.suggested}</span>
                    </div>
                </div>
            `;
        });
        
        if (suggestions.improved_question) {
            html += `
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <h4 class="font-bold text-green-600 mb-3">السؤال المحسن</h4>
                    <div class="space-y-2">
                        <p><strong>السؤال:</strong> ${suggestions.improved_question.question}</p>
                        <p><strong>الخيارات:</strong></p>
                        <ul class="list-disc list-inside ml-4">
                            ${suggestions.improved_question.options.map(opt => `<li>${opt}</li>`).join('')}
                        </ul>
                        <p><strong>الإجابة الصحيحة:</strong> <span class="text-green-600">${suggestions.improved_question.correct_answer}</span></p>
                    </div>
                    <button onclick="applySuggestions(${JSON.stringify(suggestions.improved_question).replace(/"/g, '&quot;')})" 
                            class="mt-3 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        تطبيق التحسينات
                    </button>
                </div>
            `;
        }
        
        html += '</div>';
        content.innerHTML = html;
    } else {
        content.innerHTML = '<p class="text-gray-500">لا توجد اقتراحات متاحة</p>';
    }
}

// Add function to apply suggestions
function applySuggestions(improvedQuestion) {
    if (confirm('هل تريد تطبيق التحسينات المقترحة؟')) {
        // Update question text
        if (tinymce.get('question-editor')) {
            tinymce.get('question-editor').setContent(improvedQuestion.question);
        }
        
        // Update options
        const optionInputs = document.querySelectorAll('input[name="options[]"]');
        improvedQuestion.options.forEach((option, index) => {
            if (optionInputs[index]) {
                optionInputs[index].value = option;
            }
        });
        
        // Update correct answer
        const correctAnswerRadios = document.querySelectorAll('input[name="correct_answer_index"]');
        const correctIndex = improvedQuestion.options.indexOf(improvedQuestion.correct_answer);
        if (correctIndex !== -1 && correctAnswerRadios[correctIndex]) {
            correctAnswerRadios[correctIndex].checked = true;
        }
        
        showNotification('تم تطبيق التحسينات بنجاح', 'success');
    }
}

// Preview Function
function previewQuestion() {
    const modal = document.getElementById('preview-modal');
    const content = document.getElementById('preview-content');
    
    // Get form data
    const questionText = tinymce.get('question-editor').getContent();
    const rootType = document.querySelector('input[name="root_type"]:checked')?.value;
    const depthLevel = document.querySelector('input[name="depth_level"]:checked')?.value;
    const options = Array.from(document.querySelectorAll('input[name="options[]"]')).map(input => input.value);
    const correctIndex = document.querySelector('input[name="correct_answer_index"]:checked')?.value;
    
    // Build preview HTML
    let previewHTML = `
        <div class="mb-6">
            <div class="flex gap-2 mb-4">
                <span class="px-3 py-1 rounded-full text-sm font-bold text-white" style="background: ${
                    rootType === 'jawhar' ? 'linear-gradient(135deg, #ff6b6b, #ff8e8e)' :
                    rootType === 'zihn' ? 'linear-gradient(135deg, #4ecdc4, #6ee7de)' :
                    rootType === 'waslat' ? 'linear-gradient(135deg, #f7b731, #faca5f)' :
                    'linear-gradient(135deg, #5f27cd, #7c3aed)'
                }">
                    ${rootType === 'jawhar' ? '🎯 جَوهر' :
                      rootType === 'zihn' ? '🧠 ذِهن' :
                      rootType === 'waslat' ? '🔗 وَصلات' :
                      '👁️ رُؤية'}
                </span>
                <span class="px-3 py-1 rounded-full text-sm font-bold ${
                    depthLevel == 1 ? 'bg-yellow-100 text-yellow-800' :
                    depthLevel == 2 ? 'bg-orange-100 text-orange-800' :
                    'bg-green-100 text-green-800'
                }">
                    مستوى ${depthLevel}
                </span>
            </div>
            <div class="text-lg font-medium text-gray-800 mb-6">${questionText}</div>
            <div class="space-y-3">
    `;
    
    options.forEach((option, index) => {
        if (option) {
            const isCorrect = index == correctIndex;
            previewHTML += `
                <div class="flex items-center gap-3 p-3 rounded-lg ${
                    isCorrect ? 'bg-green-50 border-2 border-green-300' : 'bg-gray-50 border-2 border-gray-200'
                }">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold ${
                        isCorrect ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700'
                    }">
                        ${optionLetters[index]}
                    </span>
                    <span class="${isCorrect ? 'font-bold text-green-800' : 'text-gray-700'}">
                        ${option}
                    </span>
                    ${isCorrect ? '<i class="fas fa-check-circle text-green-500 mr-auto"></i>' : ''}
                </div>
            `;
        }
    });
    
    previewHTML += '</div></div>';
    
    content.innerHTML = previewHTML;
    
    // Show modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').style.transform = 'scale(1)';
        modal.querySelector('.bg-white').style.opacity = '1';
    }, 10);
}

function closePreviewModal() {
    const modal = document.getElementById('preview-modal');
    modal.querySelector('.bg-white').style.transform = 'scale(0.95)';
    modal.querySelector('.bg-white').style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Form Validation
document.getElementById('question-edit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate at least one option has text
    const options = Array.from(document.querySelectorAll('input[name="options[]"]'));
    const validOptions = options.filter(opt => opt.value.trim() !== '');
    
    if (validOptions.length < 2) {
        showNotification('يجب إدخال خيارين على الأقل', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <span class="flex items-center justify-center gap-3">
            <i class="fas fa-spinner fa-spin"></i>
            جاري الحفظ...
        </span>
    `;
    
    // Submit form
    setTimeout(() => {
        this.submit();
    }, 500);
});

// Notification System
function showNotification(message, type = 'success') {
    const colors = {
        success: 'from-green-500 to-emerald-500',
        error: 'from-red-500 to-pink-500',
        warning: 'from-yellow-500 to-orange-500',
        info: 'from-blue-500 to-cyan-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 transform translate-x-full transition-transform duration-300 z-50`;
    notification.innerHTML = `
        <i class="fas ${icons[type]} text-2xl"></i>
        <p class="font-medium">${message}</p>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Keyboard Shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('question-edit-form').requestSubmit();
    }
    
    // Ctrl/Cmd + P to preview
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        previewQuestion();
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        const previewModal = document.getElementById('preview-modal');
        if (!previewModal.classList.contains('hidden')) {
            closePreviewModal();
        }
    }
});

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    initSortable();
    
    // Add smooth transitions to form elements
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('scale-[1.02]');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('scale-[1.02]');
        });
    });
});
document.querySelector('form').addEventListener('submit', function(e) {
    tinymce.triggerSave();
});
</script>
@endpush