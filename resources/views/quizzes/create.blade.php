@extends('layouts.app')

@section('title', 'إنشاء اختبار جديد - جُذور')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold text-primary mb-2">إنشاء اختبار جديد</h1>
                    <p class="text-muted mb-0">اتبع الخطوات لإنشاء اختبار تفاعلي بنموذج الجُذور الأربعة</p>
                </div>
                <div>
                    <a href="{{ route('quizzes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للاختبارات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <!-- Progress Line -->
                        <div class="progress-line position-absolute w-75" style="height: 3px; background: #e9ecef; top: 50%; left: 12.5%; transform: translateY(-50%); z-index: 1;"></div>
                        <div class="progress-fill position-absolute" style="height: 3px; background: #6366f1; top: 50%; left: 12.5%; transform: translateY(-50%); z-index: 2; width: 0%; transition: width 0.3s ease;" id="progress-fill"></div>
                        
                        <!-- Step 1 -->
                        <div class="text-center position-relative" style="z-index: 3;">
                            <div class="step-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;" id="step-circle-1">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <small class="fw-bold text-primary" id="step-title-1">المعلومات الأساسية</small>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="text-center position-relative" style="z-index: 3;">
                            <div class="step-circle bg-light text-muted rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;" id="step-circle-2">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <small class="text-muted" id="step-title-2">طريقة الإنشاء</small>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="text-center position-relative" style="z-index: 3;">
                            <div class="step-circle bg-light text-muted rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;" id="step-circle-3">
                                <i class="fas fa-edit"></i>
                            </div>
                            <small class="text-muted" id="step-title-3">المحتوى والأسئلة</small>
                        </div>
                        
                        <!-- Step 4 -->
                        <div class="text-center position-relative" style="z-index: 3;">
                            <div class="step-circle bg-light text-muted rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;" id="step-circle-4">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <small class="text-muted" id="step-title-4">الإعدادات النهائية</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Step 1: Basic Information -->
            <div id="step-1" class="wizard-step">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>المعلومات الأساسية</h5>
                    </div>
                    <form id="step-1-form">
                        @csrf
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="title" class="form-label fw-semibold">عنوان الاختبار <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required
                                           placeholder="مثال: اختبار فهم المقروء - الصف الخامس">
                                    <div class="invalid-feedback" id="title-error"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="subject_id" class="form-label fw-semibold">المادة الدراسية <span class="text-danger">*</span></label>
                                    <select class="form-select" id="subject_id" name="subject_id" required>
                                        <option value="">اختر المادة</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="subject_id-error"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="grade_level" class="form-label fw-semibold">المستوى الدراسي <span class="text-danger">*</span></label>
                                    <select class="form-select" id="grade_level" name="grade_level" required>
                                        <option value="">اختر المستوى</option>
                                        @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">الصف {{ $i === 1 ? 'الأول' : ($i === 2 ? 'الثاني' : ($i === 3 ? 'الثالث' : ($i === 4 ? 'الرابع' : ($i === 5 ? 'الخامس' : ($i === 6 ? 'السادس' : ($i === 7 ? 'السابع' : ($i === 8 ? 'الثامن' : 'التاسع'))))))) }}</option>
                                        @endfor
                                    </select>
                                    <div class="invalid-feedback" id="grade_level-error"></div>
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">وصف الاختبار</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                              placeholder="وصف مختصر عن محتوى وأهداف الاختبار..."></textarea>
                                    <div class="form-text">وصف اختياري يساعد الطلاب على فهم محتوى الاختبار</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                التالي <i class="fas fa-arrow-left ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Step 2: Creation Method -->
            <div id="step-2" class="wizard-step d-none">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>طريقة إنشاء الاختبار</h5>
                    </div>
                    <form id="step-2-form">
                        @csrf
                        <input type="hidden" id="quiz_id" name="quiz_id">
                        <div class="card-body p-4">
                            <p class="text-muted mb-4">اختر الطريقة المفضلة لإنشاء أسئلة الاختبار:</p>
                            
                            <!-- AI Method -->
                            <div class="method-option mb-3" data-method="ai">
                                <div class="card border-2 h-100" style="cursor: pointer;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-magic"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-primary mb-2">إنشاء بالذكاء الاصطناعي</h6>
                                                <p class="text-muted mb-2">اسمح للذكاء الاصطناعي بإنشاء أسئلة متنوعة وفقاً لنموذج الجُذور الأربعة</p>
                                                <ul class="list-unstyled mb-0 small text-muted">
                                                    <li><i class="fas fa-check text-success me-2"></i>أسئلة متوازنة للجذور الأربعة</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>نصوص تعليمية مُولدة تلقائياً</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>توفير الوقت والجهد</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Method -->
                            <div class="method-option mb-3" data-method="manual">
                                <div class="card border-2 h-100" style="cursor: pointer;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-edit"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-success mb-2">إنشاء يدوي</h6>
                                                <p class="text-muted mb-2">أنشئ الأسئلة بنفسك للحصول على تحكم كامل في المحتوى</p>
                                                <ul class="list-unstyled mb-0 small text-muted">
                                                    <li><i class="fas fa-check text-success me-2"></i>تحكم كامل في صياغة الأسئلة</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>إمكانية إضافة نصوص خاصة</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>تخصيص مستوى الصعوبة</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hybrid Method -->
                            <div class="method-option mb-3" data-method="hybrid">
                                <div class="card border-2 h-100" style="cursor: pointer;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-layer-group"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-warning mb-2">الطريقة المختلطة</h6>
                                                <p class="text-muted mb-2">ابدأ بالذكاء الاصطناعي ثم عدّل الأسئلة حسب احتياجاتك</p>
                                                <ul class="list-unstyled mb-0 small text-muted">
                                                    <li><i class="fas fa-check text-success me-2"></i>نقطة انطلاق سريعة</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>مرونة في التعديل</li>
                                                    <li><i class="fas fa-check text-success me-2"></i>أفضل ما في الطريقتين</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="creation_method" id="creation_method">
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="goToStep(1)">
                                <i class="fas fa-arrow-right me-2"></i>السابق
                            </button>
                            <button type="submit" class="btn btn-primary">
                                التالي <i class="fas fa-arrow-left ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Step 3: Content Creation -->
            <div id="step-3" class="wizard-step d-none">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>إنشاء المحتوى والأسئلة</h5>
                    </div>
                    <div class="card-body p-4">
                        <div id="ai-content-section">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">تفاصيل المحتوى</h6>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="topic" class="form-label fw-semibold">موضوع الاختبار <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="topic" name="topic"
                                           placeholder="مثال: قصة الأسد والفأر">
                                </div>

                                <div class="col-md-6">
                                    <label for="passage_topic" class="form-label fw-semibold">موضوع النص <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="passage_topic" name="passage_topic"
                                           placeholder="مثال: قيم التعاون والصداقة">
                                </div>

                                <div class="col-md-6">
                                    <label for="text_type" class="form-label fw-semibold">نوع النص</label>
                                    <select class="form-select" id="text_type" name="text_type">
                                        <option value="story">قصة</option>
                                        <option value="article">مقال</option>
                                        <option value="dialogue">حوار</option>
                                        <option value="informational">نص إعلامي</option>
                                        <option value="description">وصف</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex gap-3">
                                        <button type="button" id="generate-text-btn" class="btn btn-success">
                                            <i class="fas fa-magic me-2"></i>إنشاء النص التعليمي
                                        </button>
                                        <button type="button" id="generate-questions-btn" class="btn btn-primary d-none">
                                            <i class="fas fa-question-circle me-2"></i>إنشاء الأسئلة
                                        </button>
                                    </div>
                                </div>

                                <!-- Generated Content Display -->
                                <div id="generated-content" class="col-12 d-none">
                                    <div class="alert alert-success">
                                        <h6 class="fw-bold mb-2">تم إنشاء المحتوى بنجاح!</h6>
                                        <p class="mb-0">يمكنك الآن الانتقال للخطوة التالية لتخصيص إعدادات الاختبار.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Content Section -->
                        <div id="manual-content-section" class="d-none">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                سيتم توجيهك إلى صفحة إدارة الأسئلة لإضافة الأسئلة يدوياً.
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" onclick="goToStep(2)">
                            <i class="fas fa-arrow-right me-2"></i>السابق
                        </button>
                        <button type="button" class="btn btn-primary" onclick="goToStep(4)">
                            الإعدادات النهائية <i class="fas fa-arrow-left ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 4: Final Settings -->
            <div id="step-4" class="wizard-step d-none">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>الإعدادات النهائية</h5>
                    </div>
                    <form id="step-4-form">
                        @csrf
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">إعدادات الاختبار</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="time_limit" class="form-label fw-semibold">مدة الاختبار (بالدقائق)</label>
                                    <input type="number" class="form-control" id="time_limit" name="time_limit"
                                           min="5" max="180" placeholder="60">
                                    <div class="form-text">اتركه فارغاً للاختبار بدون وقت محدد</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="passing_score" class="form-label fw-semibold">درجة النجاح (%)</label>
                                    <input type="number" class="form-control" id="passing_score" name="passing_score"
                                           min="0" max="100" value="60">
                                </div>

                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3 mt-4">خيارات إضافية</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="shuffle_questions" name="shuffle_questions" checked>
                                                <label class="form-check-label fw-semibold" for="shuffle_questions">
                                                    خلط ترتيب الأسئلة
                                                </label>
                                                <div class="form-text">يظهر الأسئلة بترتيب مختلف لكل طالب</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="shuffle_answers" name="shuffle_answers" checked>
                                                <label class="form-check-label fw-semibold" for="shuffle_answers">
                                                    خلط ترتيب الخيارات
                                                </label>
                                                <div class="form-text">يخلط ترتيب خيارات كل سؤال</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="show_results" name="show_results" checked>
                                                <label class="form-check-label fw-semibold" for="show_results">
                                                    إظهار النتائج للطلاب
                                                </label>
                                                <div class="form-text">السماح للطلاب برؤية نتائجهم</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="allow_retake" name="allow_retake">
                                                <label class="form-check-label fw-semibold" for="allow_retake">
                                                    السماح بإعادة المحاولة
                                                </label>
                                                <div class="form-text">السماح للطلاب بحل الاختبار أكثر من مرة</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="goToStep(3)">
                                <i class="fas fa-arrow-right me-2"></i>السابق
                            </button>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i>إنهاء وحفظ الاختبار
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>

@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .card-header {
        border-radius: 15px 15px 0 0 !important;
        border-bottom: none;
        padding: 1.5rem;
    }

    .card-body {
        padding: 2rem;
    }

    .card-footer {
        border-radius: 0 0 15px 15px !important;
        border-top: 1px solid rgba(0,0,0,0.125);
        padding: 1.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    .btn {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .step-circle {
        transition: all 0.3s ease;
        border: 3px solid #e9ecef;
    }

    .step-circle.active {
        background-color: #6366f1 !important;
        color: white !important;
        border-color: #6366f1 !important;
    }

    .method-option .card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .method-option .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .method-option.selected .card {
        border-color: #6366f1 !important;
        background-color: rgba(99, 102, 241, 0.05);
    }

    .form-check-input:checked {
        background-color: #6366f1;
        border-color: #6366f1;
    }

    .wizard-step {
        transition: opacity 0.3s ease;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple, clean quiz creator without loading states
    let currentStep = 1;
    let quizId = null;
    let selectedMethod = null;

    // Configuration
    const config = {
        routes: {
            step1: '{{ route("quizzes.create-step-1") }}',
            step2: '{{ url("/quizzes") }}',
            generateText: '{{ url("/quizzes") }}',
            generateQuestions: '{{ url("/quizzes") }}',
            finalize: '{{ url("/quizzes") }}'
        },
        csrfToken: '{{ csrf_token() }}'
    };

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializeWizard();
    });

    function initializeWizard() {
        // Setup form handlers
        document.getElementById('step-1-form').addEventListener('submit', handleStep1);
        document.getElementById('step-2-form').addEventListener('submit', handleStep2);
        document.getElementById('step-4-form').addEventListener('submit', handleStep4);

        // Setup method selection
        document.querySelectorAll('.method-option').forEach(option => {
            option.addEventListener('click', function() {
                selectMethod(this.dataset.method);
            });
        });

        // Setup content generation buttons
        document.getElementById('generate-text-btn').addEventListener('click', generateText);
        document.getElementById('generate-questions-btn').addEventListener('click', generateQuestions);

        updateProgress();
    }

    async function handleStep1(e) {
        e.preventDefault();
        
        // Basic validation
        const title = document.getElementById('title').value.trim();
        const subjectId = document.getElementById('subject_id').value;
        const gradeLevel = document.getElementById('grade_level').value;

        if (!title || !subjectId || !gradeLevel) {
            showMessage('الرجاء ملء جميع الحقول المطلوبة', 'error');
            return;
        }

        try {
            const formData = new FormData(e.target);
            const response = await fetch(config.routes.step1, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                quizId = data.quiz_id;
                document.getElementById('quiz_id').value = quizId;
                showMessage('تم حفظ المعلومات الأساسية بنجاح', 'success');
                goToStep(2);
            } else {
                showMessage('حدث خطأ في حفظ البيانات', 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ في الاتصال بالخادم', 'error');
        }
    }

    async function handleStep2(e) {
        e.preventDefault();

        if (!selectedMethod) {
            showMessage('الرجاء اختيار طريقة إنشاء الاختبار', 'error');
            return;
        }

        if (!quizId) {
            showMessage('خطأ: لم يتم العثور على معرف الاختبار', 'error');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('_token', config.csrfToken);
            formData.append('creation_method', selectedMethod);

            const response = await fetch(`${config.routes.step2}/${quizId}/update-method`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                showMessage('تم حفظ طريقة الإنشاء بنجاح', 'success');
                setupContentSection();
                goToStep(3);
            } else {
                showMessage('حدث خطأ في حفظ طريقة الإنشاء', 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ في الاتصال بالخادم', 'error');
        }
    }

    async function generateText() {
        const topic = document.getElementById('topic').value.trim();
        const passageTopic = document.getElementById('passage_topic').value.trim();

        if (!topic || !passageTopic) {
            showMessage('الرجاء ملء جميع الحقول المطلوبة', 'error');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('_token', config.csrfToken);
            formData.append('topic', topic);
            formData.append('passage_topic', passageTopic);
            formData.append('text_type', document.getElementById('text_type').value);

            const response = await fetch(`${config.routes.generateText}/${quizId}/generate-text`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('generate-questions-btn').classList.remove('d-none');
                showMessage('تم إنشاء النص بنجاح', 'success');
            } else {
                showMessage('فشل في إنشاء النص', 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ في إنشاء النص', 'error');
        }
    }

    async function generateQuestions() {
        try {
            const formData = new FormData();
            formData.append('_token', config.csrfToken);

            const response = await fetch(`${config.routes.generateQuestions}/${quizId}/generate-questions`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('generated-content').classList.remove('d-none');
                showMessage('تم إنشاء الأسئلة بنجاح', 'success');
            } else {
                showMessage('فشل في إنشاء الأسئلة', 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ في إنشاء الأسئلة', 'error');
        }
    }

    async function handleStep4(e) {
        e.preventDefault();

        try {
            const formData = new FormData(e.target);
            const response = await fetch(`${config.routes.finalize}/${quizId}/finalize`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                showMessage('تم إنشاء الاختبار بنجاح!', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect_url || '{{ route("quizzes.index") }}';
                }, 2000);
            } else {
                showMessage('حدث خطأ في إنهاء الاختبار', 'error');
            }
        } catch (error) {
            showMessage('حدث خطأ في الاتصال بالخادم', 'error');
        }
    }

    function selectMethod(method) {
        selectedMethod = method;
        document.getElementById('creation_method').value = method;

        // Update UI
        document.querySelectorAll('.method-option').forEach(option => {
            option.classList.remove('selected');
        });
        document.querySelector(`[data-method="${method}"]`).classList.add('selected');
    }

    function setupContentSection() {
        const aiSection = document.getElementById('ai-content-section');
        const manualSection = document.getElementById('manual-content-section');

        if (selectedMethod === 'manual') {
            aiSection.classList.add('d-none');
            manualSection.classList.remove('d-none');
        } else {
            aiSection.classList.remove('d-none');
            manualSection.classList.add('d-none');
        }
    }

    function goToStep(step) {
        // Hide all steps
        document.querySelectorAll('.wizard-step').forEach(el => {
            el.classList.add('d-none');
        });

        // Show target step
        document.getElementById(`step-${step}`).classList.remove('d-none');
        
        currentStep = step;
        updateProgress();
    }

    function updateProgress() {
        const progressFill = document.getElementById('progress-fill');
        const percentage = ((currentStep - 1) / 3) * 75;
        progressFill.style.width = `${percentage}%`;

        // Update step indicators
        for (let i = 1; i <= 4; i++) {
            const circle = document.getElementById(`step-circle-${i}`);
            const title = document.getElementById(`step-title-${i}`);

            if (i <= currentStep) {
                circle.classList.add('active');
                circle.classList.remove('bg-light', 'text-muted');
                title.classList.remove('text-muted');
                title.classList.add('text-primary', 'fw-bold');
            } else {
                circle.classList.remove('active');
                circle.classList.add('bg-light', 'text-muted');
                title.classList.add('text-muted');
                title.classList.remove('text-primary', 'fw-bold');
            }
        }
    }

    function showMessage(message, type) {
        const container = document.getElementById('message-container');
        const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        container.appendChild(alert);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
</script>
@endpush