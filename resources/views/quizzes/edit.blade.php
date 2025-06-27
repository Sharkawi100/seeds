@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center gap-3 text-sm">
                <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-purple-600 transition-colors">الرئيسية</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('quizzes.index') }}" class="text-gray-500 hover:text-purple-600 transition-colors">اختباراتي</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-purple-600 font-medium">تعديل الاختبار</li>
            </ol>
        </nav>

        <!-- Main Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-6 relative">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3">
                            <i class="fas fa-edit text-2xl text-white" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">تعديل الاختبار</h1>
                            <p class="text-white/80 mt-1">{{ $quiz->title }}</p>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="hidden md:flex gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">{{ $quiz->questions->count() }}</div>
                            <div class="text-xs text-white/80">سؤال</div>
                        </div>
                        <div class="w-px bg-white/30"></div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">{{ $quiz->results->count() }}</div>
                            <div class="text-xs text-white/80">محاولة</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Management Section -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-cogs text-purple-600" aria-hidden="true"></i>
                    إدارة الاختبار
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- PIN Management -->
                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">رمز الدخول</span>
                            <i class="fas fa-key text-blue-500" aria-hidden="true"></i>
                        </div>
                        <div class="text-xl font-bold text-gray-900 mb-3" dir="ltr">{{ $quiz->pin }}</div>
                        <button type="button" 
                                class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                onclick="copyToClipboard('{{ $quiz->pin }}')"
                                aria-label="نسخ رمز الدخول">
                            <i class="fas fa-copy mr-1" aria-hidden="true"></i>
                            نسخ الرمز
                        </button>
                    </div>

                    <!-- Status Toggle -->
                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">حالة الاختبار</span>
                            <i class="fas {{ $quiz->is_active ? 'fa-play text-green-500' : 'fa-pause text-red-500' }}" aria-hidden="true"></i>
                        </div>
                        <div class="text-sm font-bold mb-3 {{ $quiz->is_active ? 'text-green-700' : 'text-red-700' }}">
                            {{ $quiz->is_active ? 'نشط' : 'متوقف' }}
                        </div>
                        @if(!$quiz->has_submissions)
                        <form action="{{ route('quizzes.toggle-status', $quiz) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $quiz->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }}"
                                    aria-label="{{ $quiz->is_active ? 'إيقاف الاختبار' : 'تفعيل الاختبار' }}">
                                <i class="fas {{ $quiz->is_active ? 'fa-pause' : 'fa-play' }} mr-1" aria-hidden="true"></i>
                                {{ $quiz->is_active ? 'إيقاف' : 'تفعيل' }}
                            </button>
                        </form>
                        @else
                        <div class="w-full bg-gray-100 text-gray-500 px-3 py-2 rounded-lg text-sm text-center">
                            <i class="fas fa-lock mr-1" aria-hidden="true"></i>
                            مؤمن
                        </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">إجراءات سريعة</span>
                            <i class="fas fa-bolt text-yellow-500" aria-hidden="true"></i>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('quizzes.questions.index', $quiz) }}" 
                               class="w-full bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center"
                               aria-label="إدارة أسئلة الاختبار">
                                <i class="fas fa-question-circle mr-1" aria-hidden="true"></i>
                                الأسئلة
                            </a>
                            <a href="{{ route('results.quiz', $quiz) }}" 
                               class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center"
                               aria-label="عرض نتائج الاختبار">
                                <i class="fas fa-chart-bar mr-1" aria-hidden="true"></i>
                                النتائج
                            </a>
                        </div>
                    </div>

                    <!-- Actions Zone -->
                    <div class="bg-white rounded-xl p-4 border {{ $quiz->has_submissions ? 'border-blue-200' : 'border-red-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium {{ $quiz->has_submissions ? 'text-blue-600' : 'text-red-600' }}">
                                {{ $quiz->has_submissions ? 'إجراءات متاحة' : 'منطقة الخطر' }}
                            </span>
                            <i class="fas {{ $quiz->has_submissions ? 'fa-tools text-blue-500' : 'fa-exclamation-triangle text-red-500' }}" aria-hidden="true"></i>
                        </div>
                        <div class="space-y-2">
                            @if($quiz->has_submissions)
                            <!-- For quizzes with submissions - emphasize duplication -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-info-circle text-blue-600" aria-hidden="true"></i>
                                    <span class="text-sm font-medium text-blue-800">نصيحة</span>
                                </div>
                                <p class="text-xs text-blue-700">هذا الاختبار محمي من التعديل لوجود محاولات. انسخه لإنشاء نسخة قابلة للتعديل.</p>
                            </div>
                            <form action="{{ route('quizzes.duplicate', $quiz) }}" method="POST" class="inline w-full">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-3 rounded-lg text-sm font-bold transition-all transform hover:scale-105 shadow-md hover:shadow-lg"
                                        aria-label="إنشاء نسخة قابلة للتعديل">
                                    <i class="fas fa-copy mr-2" aria-hidden="true"></i>
                                    إنشاء نسخة قابلة للتعديل
                                </button>
                            </form>
                            @else
                            <!-- For editable quizzes - standard actions -->
                            <form action="{{ route('quizzes.duplicate', $quiz) }}" method="POST" class="inline w-full">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors mb-2"
                                        aria-label="نسخ الاختبار">
                                    <i class="fas fa-copy mr-1" aria-hidden="true"></i>
                                    نسخ الاختبار
                                </button>
                            </form>
                            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="inline w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا الاختبار؟ هذا الإجراء لا يمكن التراجع عنه.')"
                                        aria-label="حذف الاختبار نهائياً">
                                    <i class="fas fa-trash mr-1" aria-hidden="true"></i>
                                    حذف نهائياً
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            @if(!$quiz->has_submissions)
            <form action="{{ route('quizzes.update', $quiz) }}" method="POST" class="p-6" id="quiz-edit-form" novalidate>
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Left Column -->
                    <div class="space-y-6">
                        
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-heading text-purple-600 mr-2" aria-hidden="true"></i>
                                عنوان الاختبار *
                            </label>
                            <input type="text" 
                                   id="title"
                                   name="title" 
                                   value="{{ old('title', $quiz->title) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   placeholder="أدخل عنوان الاختبار"
                                   required
                                   maxlength="255"
                                   aria-describedby="title-error">
                            @error('title')
                                <p id="title-error" class="mt-2 text-sm text-red-600" role="alert">
                                    <i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i>
                                    {{ $errors->first('title') }}
                                </p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject_id" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-book text-blue-600 mr-2" aria-hidden="true"></i>
                                المادة *
                            </label>
                            <select id="subject_id" 
                                    name="subject_id" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                    required
                                    aria-describedby="subject-error">
                                <option value="">اختر المادة</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $quiz->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p id="subject-error" class="mt-2 text-sm text-red-600" role="alert">
                                    <i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i>
                                    {{ $errors->first('subject_id') }}
                                </p>
                            @enderror
                        </div>

                        <!-- Grade Level -->
                        <div>
                            <label for="grade_level" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap text-green-600 mr-2" aria-hidden="true"></i>
                                الصف الدراسي *
                            </label>
                            <select id="grade_level" 
                                    name="grade_level" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                    required
                                    aria-describedby="grade-error">
                                <option value="">اختر الصف</option>
                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}" {{ old('grade_level', $quiz->grade_level) == $i ? 'selected' : '' }}>
                                        الصف {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('grade_level')
                                <p id="grade-error" class="mt-2 text-sm text-red-600" role="alert">
                                    <i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i>
                                    {{ $errors->first('grade_level') }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-align-left text-gray-600 mr-2" aria-hidden="true"></i>
                                وصف الاختبار
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                      placeholder="وصف مختصر عن محتوى الاختبار..."
                                      maxlength="500"
                                      aria-describedby="description-counter">{{ old('description', $quiz->description) }}</textarea>
                            <div id="description-counter" class="mt-1 text-xs text-gray-500 text-left" dir="ltr">
                                <span id="char-count">{{ strlen($quiz->description ?? '') }}</span>/500
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        
                        <!-- Time Limit -->
                        <div>
                            <label for="time_limit" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-clock text-orange-600 mr-2" aria-hidden="true"></i>
                                الحد الزمني (بالدقائق)
                            </label>
                            <input type="number" 
                                   id="time_limit"
                                   name="time_limit" 
                                   value="{{ old('time_limit', $quiz->time_limit) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   placeholder="0 = بدون حد زمني"
                                   min="0"
                                   max="300"
                                   aria-describedby="time-limit-help">
                            <p id="time-limit-help" class="mt-1 text-xs text-gray-500">
                                اتركه فارغاً أو 0 لعدم تحديد وقت
                            </p>
                        </div>

                        <!-- Passing Score -->
                        <div>
                            <label for="passing_score" class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-target text-red-600 mr-2" aria-hidden="true"></i>
                                درجة النجاح (%)
                            </label>
                            <input type="number" 
                                   id="passing_score"
                                   name="passing_score" 
                                   value="{{ old('passing_score', $quiz->passing_score ?? 60) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   min="0"
                                   max="100"
                                   aria-describedby="passing-score-help">
                            <p id="passing-score-help" class="mt-1 text-xs text-gray-500">
                                النسبة المئوية المطلوبة للنجاح
                            </p>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                            <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-cog text-purple-600" aria-hidden="true"></i>
                                إعدادات متقدمة
                            </h3>
                            
                            <!-- Shuffle Questions -->
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="shuffle_questions" value="0">
                                <input type="checkbox" 
                                       id="shuffle_questions"
                                       name="shuffle_questions" 
                                       value="1"
                                       {{ old('shuffle_questions', $quiz->shuffle_questions ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                <label for="shuffle_questions" class="text-sm font-medium text-gray-700">
                                    ترتيب عشوائي للأسئلة
                                </label>
                            </div>
                            
                            <!-- Shuffle Answers -->
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="shuffle_answers" value="0">
                                <input type="checkbox" 
                                       id="shuffle_answers"
                                       name="shuffle_answers" 
                                       value="1"
                                       {{ old('shuffle_answers', $quiz->shuffle_answers ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                <label for="shuffle_answers" class="text-sm font-medium text-gray-700">
                                    ترتيب عشوائي للخيارات
                                </label>
                            </div>
                            
                            <!-- Show Results -->
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="show_results" value="0">
                                <input type="checkbox" 
                                       id="show_results"
                                       name="show_results" 
                                       value="1"
                                       {{ old('show_results', $quiz->show_results ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                <label for="show_results" class="text-sm font-medium text-gray-700">
                                    عرض النتائج للطلاب بعد انتهاء الاختبار
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8 border-t border-gray-200 mt-8">
                    <button type="submit" 
                            id="save-btn"
                            class="px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2"
                            aria-label="حفظ تغييرات الاختبار">
                        <i class="fas fa-save" aria-hidden="true"></i>
                        <span>حفظ التغييرات</span>
                    </button>
                    
                    <a href="{{ route('quizzes.show', $quiz) }}" 
                       class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2"
                       aria-label="إلغاء التعديل والعودة">
                        <i class="fas fa-times" aria-hidden="true"></i>
                        <span>إلغاء</span>
                    </a>
                </div>
            </form>
            @else
            <!-- Locked State -->
            <div class="p-8 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-lock text-3xl text-red-600" aria-hidden="true"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">الاختبار محمي من التعديل</h3>
                <p class="text-gray-600 mb-6">
                    لا يمكن تعديل هذا الاختبار لأن هناك {{ $quiz->results->count() }} محاولة أو أكثر مسجلة عليه.
                </p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('quizzes.show', $quiz) }}" 
                       class="px-6 py-3 bg-purple-600 text-white rounded-xl font-medium hover:bg-purple-700 transition-colors">
                        عرض تفاصيل الاختبار
                    </a>
                    <a href="{{ route('quizzes.duplicate', $quiz) }}" 
                       class="px-6 py-3 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 transition-colors">
                        إنشاء نسخة قابلة للتعديل
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 flex items-center gap-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
        <span class="text-gray-700 font-medium">جاري حفظ التغييرات...</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Form validation and submission
    const form = document.getElementById('quiz-edit-form');
    const saveBtn = document.getElementById('save-btn');
    const loadingOverlay = document.getElementById('loading-overlay');
    
    if (form) {
        // Character counter for description
        const descriptionTextarea = document.getElementById('description');
        const charCount = document.getElementById('char-count');
        
        if (descriptionTextarea && charCount) {
            descriptionTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }
        
        // Form validation
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            const errorElements = form.querySelectorAll('.text-red-600');
            errorElements.forEach(el => el.remove());
            
            let isValid = true;
            
            // Validate title
            const title = form.querySelector('#title');
            if (!title.value.trim()) {
                showFieldError(title, 'عنوان الاختبار مطلوب');
                isValid = false;
            }
            
            // Validate subject
            const subject = form.querySelector('#subject_id');
            if (!subject.value) {
                showFieldError(subject, 'يجب اختيار المادة');
                isValid = false;
            }
            
            // Validate grade level
            const gradeLevel = form.querySelector('#grade_level');
            if (!gradeLevel.value) {
                showFieldError(gradeLevel, 'يجب اختيار الصف الدراسي');
                isValid = false;
            }
            
            // Validate time limit
            const timeLimit = form.querySelector('#time_limit');
            if (timeLimit.value && (timeLimit.value < 0 || timeLimit.value > 300)) {
                showFieldError(timeLimit, 'الحد الزمني يجب أن يكون بين 0 و 300 دقيقة');
                isValid = false;
            }
            
            // Validate passing score
            const passingScore = form.querySelector('#passing_score');
            if (passingScore.value && (passingScore.value < 0 || passingScore.value > 100)) {
                showFieldError(passingScore, 'درجة النجاح يجب أن تكون بين 0 و 100');
                isValid = false;
            }
            
            if (isValid) {
                // Show loading state
                saveBtn.disabled = true;
                loadingOverlay.classList.remove('hidden');
                
                // Submit form
                form.submit();
            } else {
                // Scroll to first error
                const firstError = form.querySelector('.text-red-600');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    function showFieldError(field, message) {
        const errorElement = document.createElement('p');
        errorElement.className = 'mt-2 text-sm text-red-600';
        errorElement.setAttribute('role', 'alert');
        errorElement.innerHTML = `<i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i>${message}`;
        
        field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        field.parentNode.appendChild(errorElement);
    }
    
    // Auto-save draft functionality
    let saveTimeout;
    const formInputs = form?.querySelectorAll('input, select, textarea') || [];
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                // Auto-save draft logic here if needed
                console.log('Auto-saving draft...');
            }, 2000);
        });
    });
    
    // Accessibility improvements
    const requiredFields = form?.querySelectorAll('[required]') || [];
    requiredFields.forEach(field => {
        field.setAttribute('aria-required', 'true');
    });
    
    // Keyboard navigation improvements
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (form && !saveBtn.disabled) {
                form.dispatchEvent(new Event('submit'));
            }
        }
    });
});

// Notification system
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('opacity-0', 'transform', 'translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('تم نسخ الرمز بنجاح', 'success');
    }).catch(function() {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showNotification('تم نسخ الرمز بنجاح', 'success');
    });
}

// Check for success messages from server
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('success') }}', 'success');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('error') }}', 'error');
    });
@endif
</script>

<style>
/* Subtle animations - not heavy */
.transition-colors {
    transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

.transition-all {
    transition: all 0.2s ease-in-out;
}

/* Focus states for accessibility */
input:focus, select:focus, textarea:focus, button:focus {
    outline: 2px solid #9333ea;
    outline-offset: 2px;
}

/* Loading animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Reduced motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    .transition-all,
    .transition-colors,
    .animate-spin {
        animation: none;
        transition: none;
    }
}

/* RTL specific styles */
[dir="rtl"] input[type="number"] {
    text-align: right;
}

/* Custom checkbox styles for better visibility */
input[type="checkbox"]:checked {
    background-color: #9333ea;
    border-color: #9333ea;
}
</style>
@endsection