@extends('layouts.app')

@section('title', 'تعديل الاختبار - ' . $quiz->title)

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Animated Background -->
        <div class="fixed inset-0 z-0">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
        
        <div class="relative z-10">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-5xl font-black gradient-text mb-4 animate-fade-in">
                    تعديل الاختبار
                </h1>
                <p class="text-xl text-gray-600 animate-fade-in animation-delay-200">
                    قم بتحديث معلومات الاختبار الأساسية
                </p>
            </div>
            
            <!-- Main Form Card -->
            <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                                <i class="fas fa-edit text-3xl text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">معلومات الاختبار</h2>
                                <p class="text-white/80 mt-1">قم بتعديل التفاصيل الأساسية للاختبار</p>
                            </div>
                        </div>
                        
                        <!-- Stats -->
                        <div class="hidden md:flex gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-white">{{ $quiz->questions->count() }}</div>
                                <div class="text-sm text-white/80">سؤال</div>
                            </div>
                            <div class="w-px bg-white/30"></div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-white">{{ $quiz->results->count() }}</div>
                                <div class="text-sm text-white/80">محاولة</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Body -->
                <form action="{{ route('quizzes.update', $quiz) }}" method="POST" class="p-8" id="quiz-edit-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Title Field -->
                    <div class="mb-8 animate-fade-in animation-delay-300">
                        <label class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-heading text-purple-600"></i>
                            عنوان الاختبار
                        </label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title', $quiz->title) }}" 
                               class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 @error('title') border-red-500 @enderror" 
                               placeholder="أدخل عنوان الاختبار..."
                               required>
                        @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div class="mb-8 animate-fade-in animation-delay-400">
                        <label class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-align-right text-purple-600"></i>
                            وصف الاختبار
                            <span class="text-sm font-normal text-gray-500">(اختياري)</span>
                        </label>
                        <div class="relative">
                            <textarea name="description" 
                                      id="quiz-description" 
                                      class="tinymce-editor">{{ old('description', $quiz->description) }}</textarea>
                        </div>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    
                    <!-- Subject and Grade Grid -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <!-- Subject Field -->
                        <div class="animate-fade-in animation-delay-500">
                            <label class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-book text-purple-600"></i>
                                المادة الدراسية
                            </label>
                            <div class="relative">
                                <select name="subject" 
                                        class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 appearance-none cursor-pointer @error('subject') border-red-500 @enderror" 
                                        required>
                                    <option value="arabic" {{ $quiz->subject == 'arabic' ? 'selected' : '' }}>
                                        🌍 اللغة العربية
                                    </option>
                                    <option value="english" {{ $quiz->subject == 'english' ? 'selected' : '' }}>
                                        🌎 اللغة الإنجليزية
                                    </option>
                                    <option value="hebrew" {{ $quiz->subject == 'hebrew' ? 'selected' : '' }}>
                                        🌏 اللغة العبرية
                                    </option>
                                </select>
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('subject')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Grade Level Field -->
                        <div class="animate-fade-in animation-delay-600">
                            <label class="block text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-purple-600"></i>
                                الصف الدراسي
                            </label>
                            <div class="relative">
                                <select name="grade_level" 
                                        class="w-full px-5 py-4 text-lg border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 appearance-none cursor-pointer @error('grade_level') border-red-500 @enderror" 
                                        required>
                                    <optgroup label="🏫 المرحلة الابتدائية">
                                        @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ $quiz->grade_level == $i ? 'selected' : '' }}>
                                            الصف {{ ['الأول', 'الثاني', 'الثالث', 'الرابع', 'الخامس', 'السادس'][$i-1] }}
                                        </option>
                                        @endfor
                                    </optgroup>
                                    <optgroup label="🎓 المرحلة الإعدادية">
                                        @for($i = 7; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ $quiz->grade_level == $i ? 'selected' : '' }}>
                                            الصف {{ ['السابع', 'الثامن', 'التاسع'][$i-7] }}
                                        </option>
                                        @endfor
                                    </optgroup>
                                </select>
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('grade_level')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Advanced Settings (Collapsible) -->
                    <div class="mb-8 animate-fade-in animation-delay-700">
                        <button type="button" 
                                onclick="toggleAdvancedSettings()" 
                                class="w-full bg-gray-50 hover:bg-gray-100 rounded-xl p-4 flex items-center justify-between transition-all duration-200 group">
                            <span class="flex items-center gap-3 text-lg font-medium text-gray-700">
                                <i class="fas fa-cog text-purple-600 group-hover:rotate-180 transition-transform duration-500"></i>
                                إعدادات متقدمة
                            </span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="advanced-chevron"></i>
                        </button>
                        
                        <div id="advanced-settings" class="hidden mt-4 p-6 bg-gray-50 rounded-xl">
                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Time Limit -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-clock ml-1"></i>
                                        المدة الزمنية (بالدقائق)
                                    </label>
                                    <input type="number" 
                                           name="time_limit" 
                                           value="{{ old('time_limit', $quiz->time_limit ?? '') }}" 
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-100"
                                           placeholder="غير محدد"
                                           min="1">
                                </div>
                                
                                <!-- Passing Score -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-percentage ml-1"></i>
                                        درجة النجاح
                                    </label>
                                    <input type="number" 
                                           name="passing_score" 
                                           value="{{ old('passing_score', $quiz->passing_score ?? 60) }}" 
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-100"
                                           placeholder="60"
                                           min="0"
                                           max="100">
                                </div>
                                
                                <!-- Show Results -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" 
                                               name="show_results" 
                                               value="1"
                                               {{ old('show_results', $quiz->show_results ?? true) ? 'checked' : '' }}
                                               class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-sm font-medium text-gray-700">
                                            عرض النتائج للطلاب بعد إنهاء الاختبار
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t-2 border-gray-100 animate-fade-in animation-delay-800">
                        <button type="submit" 
                                class="group relative px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold text-lg hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
                            <span class="relative z-10 flex items-center justify-center gap-3">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-700 to-pink-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        </button>
                        
                        <a href="{{ route('quizzes.index') }}" 
                           class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold text-lg transition-all duration-200 flex items-center justify-center gap-3">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        
                        <!-- Delete Button (Danger Zone) -->
                        <button type="button"
                                onclick="confirmDelete()"
                                class="px-8 py-4 bg-red-100 hover:bg-red-600 text-red-600 hover:text-white rounded-xl font-bold text-lg transition-all duration-200 flex items-center justify-center gap-3 sm:mr-auto">
                            <i class="fas fa-trash"></i>
                            حذف الاختبار
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Quick Stats Cards -->
            <div class="grid md:grid-cols-3 gap-6 mt-8">
                <!-- Questions Card -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 animate-fade-in animation-delay-900">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 rounded-xl p-3">
                            <i class="fas fa-question-circle text-2xl text-purple-600"></i>
                        </div>
                        <span class="text-3xl font-bold text-gray-800">{{ $quiz->questions->count() }}</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">الأسئلة</h3>
                    <a href="{{ route('quizzes.questions.index', $quiz) }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center gap-1">
                        إدارة الأسئلة
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                
                <!-- Results Card -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 animate-fade-in animation-delay-1000">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 rounded-xl p-3">
                            <i class="fas fa-chart-line text-2xl text-green-600"></i>
                        </div>
                        <span class="text-3xl font-bold text-gray-800">{{ $quiz->results->count() }}</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">النتائج</h3>
                    <p class="text-sm text-gray-500">
                        متوسط النجاح: {{ $quiz->results->avg('total_score') ? round($quiz->results->avg('total_score')) . '%' : 'لا يوجد' }}
                    </p>
                </div>
                
                <!-- Last Activity Card -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 animate-fade-in animation-delay-1100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 rounded-xl p-3">
                            <i class="fas fa-clock text-2xl text-blue-600"></i>
                        </div>
                        <span class="text-sm text-gray-500">{{ $quiz->updated_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">آخر تحديث</h3>
                    <p class="text-sm text-gray-500">
                        {{ $quiz->updated_at->format('Y/m/d - h:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50" onclick="closeDeleteModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform scale-95 opacity-0 transition-all duration-300" onclick="event.stopPropagation()">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">تأكيد الحذف</h3>
                <p class="text-gray-600">
                    هل أنت متأكد من حذف هذا الاختبار؟
                    <br>
                    <strong class="text-red-600">{{ $quiz->title }}</strong>
                    <br>
                    <span class="text-sm">سيتم حذف جميع الأسئلة والنتائج المرتبطة نهائياً</span>
                </p>
            </div>
            
            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                
                <button type="button" 
                        onclick="closeDeleteModal()" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold transition-all">
                    إلغاء
                </button>
                
                <button type="submit" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold transition-all">
                    نعم، احذف الاختبار
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animations */
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
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .animation-delay-200 { animation-delay: 200ms; }
    .animation-delay-300 { animation-delay: 300ms; }
    .animation-delay-400 { animation-delay: 400ms; }
    .animation-delay-500 { animation-delay: 500ms; }
    .animation-delay-600 { animation-delay: 600ms; }
    .animation-delay-700 { animation-delay: 700ms; }
    .animation-delay-800 { animation-delay: 800ms; }
    .animation-delay-900 { animation-delay: 900ms; }
    .animation-delay-1000 { animation-delay: 1000ms; }
    .animation-delay-1100 { animation-delay: 1100ms; }
    
    /* Custom select styling */
    select {
        background-image: none;
    }
    
    /* Form validation states */
    input:valid:not(:placeholder-shown),
    select:valid {
        border-color: #10b981;
    }
    
    input:invalid:not(:placeholder-shown),
    select:invalid {
        border-color: #ef4444;
    }
    
    /* Loading state for submit button */
    .btn-loading {
        position: relative;
        color: transparent;
    }
    
    .btn-loading::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spinner 0.8s linear infinite;
    }
    
    @keyframes spinner {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@push('scripts')
<!-- TinyMCE with API Key -->
<script src="https://cdn.tiny.cloud/1/cmtwmtmif3u7ducaiqvogvq1wvc280ugtxjzo2ffaymjmuxg/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
// Initialize TinyMCE
tinymce.init({
    selector: '.tinymce-editor',
    language: 'ar',
    directionality: 'rtl',
    height: 350,
    menubar: false,
    plugins: 'lists link charmap preview searchreplace autolink directionality code fullscreen table emoticons image media',
    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | bullist numlist | link image media | alignleft aligncenter alignright alignjustify | outdent indent | removeformat | preview fullscreen | emoticons',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; font-size: 16px; line-height: 1.6; }',
    branding: false,
    promotion: false,
    entity_encoding: 'raw',
    toolbar_mode: 'sliding',
    image_advtab: true,
    link_default_target: '_blank',
    placeholder: 'اكتب وصفاً تفصيلياً للاختبار...',
    setup: function(editor) {
        editor.on('change', function() {
            // Auto-save draft
            saveDraft();
        });
    }
});

// Toggle Advanced Settings
function toggleAdvancedSettings() {
    const settings = document.getElementById('advanced-settings');
    const chevron = document.getElementById('advanced-chevron');
    
    settings.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

// Delete Confirmation Modal
function confirmDelete() {
    const modal = document.getElementById('delete-modal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').style.transform = 'scale(1)';
        modal.querySelector('.bg-white').style.opacity = '1';
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    modal.querySelector('.bg-white').style.transform = 'scale(0.95)';
    modal.querySelector('.bg-white').style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Auto-save Draft
let draftTimer;
function saveDraft() {
    clearTimeout(draftTimer);
    draftTimer = setTimeout(() => {
        // Show saving indicator
        showNotification('جاري حفظ المسودة...', 'info');
        
        // In a real application, you would save to server here
        localStorage.setItem('quiz_draft_{{ $quiz->id }}', JSON.stringify({
            title: document.querySelector('[name="title"]').value,
            description: tinymce.get('quiz-description').getContent(),
            subject: document.querySelector('[name="subject"]').value,
            grade_level: document.querySelector('[name="grade_level"]').value,
            timestamp: new Date().toISOString()
        }));
        
        setTimeout(() => {
            showNotification('تم حفظ المسودة', 'success');
        }, 500);
    }, 2000);
}

// Load Draft on Page Load
document.addEventListener('DOMContentLoaded', function() {
    const draft = localStorage.getItem('quiz_draft_{{ $quiz->id }}');
    if (draft) {
        const data = JSON.parse(draft);
        const timeDiff = new Date() - new Date(data.timestamp);
        const minutesAgo = Math.floor(timeDiff / 60000);
        
        if (minutesAgo < 60) { // Show draft if less than 1 hour old
            showNotification(`يوجد مسودة محفوظة منذ ${minutesAgo} دقيقة`, 'info');
        }
    }
    
    // Add form validation
    const form = document.getElementById('quiz-edit-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state on submit button
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
        
        // Clear draft
        localStorage.removeItem('quiz_draft_{{ $quiz->id }}');
        
        // Submit form
        setTimeout(() => {
            form.submit();
        }, 500);
    });
    
    // Add input animations
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('scale-105');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('scale-105');
        });
    });
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
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Animate out and remove
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
        document.getElementById('quiz-edit-form').requestSubmit();
    }
    
    // Escape to cancel
    if (e.key === 'Escape') {
        if (!document.getElementById('delete-modal').classList.contains('hidden')) {
            closeDeleteModal();
        } else {
            if (confirm('هل تريد إلغاء التعديلات والعودة؟')) {
                window.location.href = '{{ route("quizzes.index") }}';
            }
        }
    }
});
</script>
@endpush