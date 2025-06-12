@extends('layouts.app')

@section('title', 'دليل الطالب - كيف تستخدم منصة جُذور')

@push('styles')
<style>
    :root {
        --primary-color: #6366f1;
        --primary-light: #a5b4fc;
        --primary-dark: #4338ca;
        --secondary-color: #64748b;
        --accent-color: #0ea5e9;
        --success-color: #059669;
        --warning-color: #d97706;
        --surface: #ffffff;
        --surface-alt: #f8fafc;
        --surface-dark: #f1f5f9;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --border-light: #e2e8f0;
        --border-medium: #cbd5e1;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        --radius-sm: 0.5rem;
        --radius-md: 0.75rem;
        --radius-lg: 1rem;
        --radius-xl: 1.5rem;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Tajawal', 'Inter', system-ui, -apple-system, sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background: var(--surface-alt);
    }

    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        min-height: 70vh;
        display: flex;
        align-items: center;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="80" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="60" r="1" fill="white" opacity="0.1"/><circle cx="60" cy="40" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        color: white;
        margin-bottom: 1.5rem;
        letter-spacing: -0.025em;
    }

    .hero-subtitle {
        font-size: clamp(1.125rem, 2.5vw, 1.5rem);
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        font-weight: 400;
    }

    .section {
        padding: 5rem 1rem;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        text-align: center;
        margin-bottom: 1rem;
        color: var(--text-primary);
        letter-spacing: -0.025em;
    }

    .section-subtitle {
        font-size: 1.25rem;
        text-align: center;
        color: var(--text-secondary);
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .step-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }

    .step-card {
        background: var(--surface);
        border-radius: var(--radius-xl);
        padding: 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .step-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    }

    .step-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }

    .step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-radius: 50%;
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .step-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .step-description {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .step-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: var(--radius-md);
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid var(--border-light);
    }

    .step-image:hover {
        border-color: var(--primary-color);
        transform: scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    .step-tip {
        background: var(--surface-dark);
        padding: 1rem;
        border-radius: var(--radius-md);
        border-left: 4px solid var(--accent-color);
        margin-top: 1rem;
    }

    .step-tip-text {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .roots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin: 3rem 0;
    }

    .root-card {
        background: var(--surface);
        border-radius: var(--radius-xl);
        padding: 2rem;
        text-align: center;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .root-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .root-card.jawhar::before {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
    }

    .root-card.zihn::before {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(8, 145, 178, 0.1));
    }

    .root-card.waslat::before {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
    }

    .root-card.roaya::before {
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(126, 34, 206, 0.1));
    }

    .root-card:hover::before {
        opacity: 1;
    }

    .root-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .root-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .root-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .root-subtitle {
        color: var(--text-muted);
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .root-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .tabs-container {
        background: var(--surface);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        overflow: hidden;
        margin: 3rem 0;
    }

    .tabs-nav {
        display: flex;
        background: var(--surface-dark);
        border-bottom: 1px solid var(--border-light);
        overflow-x: auto;
    }

    .tab-button {
        padding: 1rem 1.5rem;
        border: none;
        background: none;
        color: var(--text-secondary);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
        font-size: 0.875rem;
    }

    .tab-button.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
        background: var(--surface);
    }

    .tab-button:hover:not(.active) {
        color: var(--text-primary);
        background: rgba(99, 102, 241, 0.05);
    }

    .tab-content {
        padding: 2rem;
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .faq-item {
        background: var(--surface);
        border-radius: var(--radius-lg);
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-light);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        box-shadow: var(--shadow-md);
    }

    .faq-question {
        width: 100%;
        padding: 1.5rem;
        background: none;
        border: none;
        text-align: right;
        font-weight: 600;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .faq-question:hover {
        background: var(--surface-alt);
    }

    .faq-answer {
        padding: 0 1.5rem 1.5rem;
        color: var(--text-secondary);
        line-height: 1.6;
        display: none;
    }

    .faq-answer.active {
        display: block;
    }

    .cta-section {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        text-align: center;
        padding: 4rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
    }

    .cta-content {
        position: relative;
        z-index: 2;
        max-width: 600px;
        margin: 0 auto;
    }

    .cta-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .cta-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .cta-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border-radius: var(--radius-lg);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        font-size: 1rem;
    }

    .btn-primary {
        background: white;
        color: var(--primary-color);
        box-shadow: var(--shadow-lg);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* Image click indicator */
    .clickable-image {
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .clickable-image::after {
        content: '🔍';
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.5rem;
        border-radius: 50%;
        font-size: 1rem;
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .clickable-image:hover::after {
        opacity: 1;
    }

    .clickable-image:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-xl);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }

    .animate-on-scroll.animate {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .section {
            padding: 3rem 1rem;
        }

        .step-grid {
            grid-template-columns: 1fr;
        }

        .tabs-nav {
            flex-direction: column;
        }

        .tab-button {
            text-align: center;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }

        /* Tab content responsive */
        #access-tab > div:first-child,
        #taking-tab > div:first-child {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
    }
    }

    @media (max-width: 480px) {
        /* Further mobile optimizations can be added here */
    }

    /* Large screens */
    @media (min-width: 1200px) {
        /* Large screen optimizations can be added here */
    }

    /* Very large screens */
    @media (min-width: 1600px) {
        /* Very large screen optimizations can be added here */
    }

    /* Print Styles */
    @media print {
        .hero-section,
        .cta-section {
            background: white !important;
            color: black !important;
        }

        .step-card,
        .root-card,
        .faq-item {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
    }

    /* High Contrast Mode */
    @media (prefers-contrast: high) {
        :root {
            --border-light: #000;
            --border-medium: #000;
            --text-secondary: #000;
            --text-muted: #333;
        }
    }

    /* Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">دليل الطالب</h1>
        <p class="hero-subtitle">
            تعلم كيف تستخدم منصة جُذور خطوة بخطوة واكتشف كيف تنمو معرفتك في جميع الجوانب
        </p>
        <div class="cta-buttons">
            <a href="#quick-start" class="btn btn-primary">
                <span>🚀</span>
                ابدأ الآن
            </a>
            <a href="#juzoor-model" class="btn btn-secondary">
                <span>🌱</span>
                اكتشف نموذج جُذور
            </a>
        </div>
    </div>
</section>

<!-- Quick Start Section -->
<section id="quick-start" class="section">
    <div class="container">
        <h2 class="section-title animate-on-scroll">ابدأ في 3 خطوات سريعة</h2>
        <p class="section-subtitle animate-on-scroll">
            احصل على أول نتيجة جُذور في أقل من 5 دقائق
        </p>

        <div class="step-grid">
            <!-- Step 1 -->
            <div class="step-card animate-on-scroll">
                <div class="step-number">1</div>
                <h3 class="step-title">أدخل رمز الاختبار</h3>
                <p class="step-description">
                    احصل على الرمز من معلمك وأدخله في الصفحة الرئيسية
                </p>
                <img src="{{ asset('images/help/pin-entry-interface.png') }}" 
                     alt="واجهة إدخال رمز الاختبار" 
                     class="step-image clickable-image" 
                     onclick="openImageInNewTab(this)">
                <div class="step-tip">
                    <p class="step-tip-text">
                        <strong>💡 نصيحة:</strong> الرمز يتكون من 6 أحرف أو أرقام
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step-card animate-on-scroll">
                <div class="step-number">2</div>
                <h3 class="step-title">اقرأ النص وأجب</h3>
                <p class="step-description">
                    اقرأ النص بعناية وأجب على أسئلة الجذور الأربعة
                </p>
                <img src="{{ asset('images/help/quiz-interface.png') }}" 
                     alt="واجهة الاختبار" 
                     class="step-image clickable-image" 
                     onclick="openImageInNewTab(this)">
                <div class="step-tip">
                    <p class="step-tip-text">
                        <strong>💡 نصيحة:</strong> خذ وقتك في قراءة النص أولاً
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step-card animate-on-scroll">
                <div class="step-number">3</div>
                <h3 class="step-title">شاهد نتائجك</h3>
                <p class="step-description">
                    اكتشف قوتك في كل جذر واعرف كيف تحسن أداءك
                </p>
                <img src="{{ asset('images/help/results-screen.png') }}" 
                     alt="شاشة النتائج" 
                     class="step-image clickable-image" 
                     onclick="openImageInNewTab(this)">
                <div class="step-tip">
                    <p class="step-tip-text">
                        <strong>💡 نصيحة:</strong> احفظ رابط النتائج لمراجعتها لاحقاً
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Demo Button -->
        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('quiz.demo') }}" class="btn btn-primary" style="font-size: 1.125rem; padding: 1rem 2rem;">
                <span>🎮</span>
                جرب الآن مع اختبار تجريبي
            </a>
        </div>
    </div>
</section>

<!-- جُذور Model Section -->
<section id="juzoor-model" class="section" style="background: var(--surface-alt);">
    <div class="container">
        <h2 class="section-title animate-on-scroll">ما هو نموذج جُذور؟</h2>
        <p class="section-subtitle animate-on-scroll">
            نموذج تعليمي مبتكر يقيس 4 جوانب مختلفة من فهمك وتفكيرك
        </p>

        <!-- The 4 Roots -->
        <div class="roots-grid">
            <div class="root-card jawhar animate-on-scroll">
                <span class="root-icon">🎯</span>
                <h3 class="root-title">جَوهر</h3>
                <p class="root-subtitle">ما هو الشيء؟</p>
                <p class="root-description">
                    فهم المعلومات الأساسية والتعريفات
                </p>
            </div>

            <div class="root-card zihn animate-on-scroll">
                <span class="root-icon">🧠</span>
                <h3 class="root-title">ذِهن</h3>
                <p class="root-subtitle">كيف يعمل؟</p>
                <p class="root-description">
                    تحليل العمليات والأسباب
                </p>
            </div>

            <div class="root-card waslat animate-on-scroll">
                <span class="root-icon">🔗</span>
                <h3 class="root-title">وَصلات</h3>
                <p class="root-subtitle">كيف يرتبط؟</p>
                <p class="root-description">
                    ربط المعلومات والعلاقات
                </p>
            </div>

            <div class="root-card roaya animate-on-scroll">
                <span class="root-icon">👁️</span>
                <h3 class="root-title">رُؤية</h3>
                <p class="root-subtitle">كيف نستخدمه؟</p>
                <p class="root-description">
                    التطبيق والإبداع
                </p>
            </div>
        </div>

        <!-- Interactive Chart Demo -->
        <div class="step-card animate-on-scroll" style="text-align: center; max-width: 600px; margin: 3rem auto 0;">
            <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">
                هكذا تبدو نتائجك 📊
            </h3>
            <img src="{{ asset('images/help/juzoor-chart-example.png') }}" 
                 alt="مثال على رسم جُذور البياني" 
                 class="step-image clickable-image" 
                 onclick="openImageInNewTab(this)"
                 style="height: 250px; cursor: pointer; border: 2px solid var(--border-light); transition: all 0.3s ease;"
                 onmouseover="this.style.borderColor='var(--primary-color)'; this.style.transform='scale(1.02)'; this.style.boxShadow='var(--shadow-lg)'"
                 onmouseout="this.style.borderColor='var(--border-light)'; this.style.transform='scale(1)'; this.style.boxShadow='none'">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.5rem;">
                <div style="background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(4, 120, 87, 0.1)); padding: 1rem; border-radius: var(--radius-md);">
                    <h4 style="font-weight: 600; color: var(--success-color); margin-bottom: 0.5rem;">📈 نقاط القوة</h4>
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">الجذور التي تتفوق فيها تظهر بألوان زاهية</p>
                </div>
                <div style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.1), rgba(180, 83, 9, 0.1)); padding: 1rem; border-radius: var(--radius-md);">
                    <h4 style="font-weight: 600; color: var(--warning-color); margin-bottom: 0.5rem;">📊 مجالات التحسين</h4>
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">الجذور التي تحتاج تطوير تظهر بألوان أفتح</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Guide Section -->
<section id="detailed-guide" class="section">
    <div class="container">
        <h2 class="section-title animate-on-scroll">الدليل التفصيلي</h2>
        <p class="section-subtitle animate-on-scroll">
            كل ما تحتاج معرفته لتحقيق أفضل النتائج
        </p>

        <div class="tabs-container animate-on-scroll">
            <div class="tabs-nav">
                <button class="tab-button active" onclick="showTab('access')">🚪 الدخول للاختبار</button>
                <button class="tab-button" onclick="showTab('taking')">✍️ أخذ الاختبار</button>
                <button class="tab-button" onclick="showTab('results')">📊 فهم النتائج</button>
                <button class="tab-button" onclick="showTab('tips')">💡 نصائح ذهبية</button>
            </div>

            <!-- Access Tab -->
            <div id="access-tab" class="tab-content active">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center; margin-bottom: 2rem;">
                    <div>
                        <h3 style="margin-bottom: 2rem; color: var(--text-primary); font-size: 1.5rem;">طرق الدخول للاختبار</h3>
                        
                        <div style="display: grid; gap: 1.5rem;">
                            <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(79, 70, 229, 0.1)); padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid rgba(99, 102, 241, 0.2);">
                                <h4 style="color: var(--primary-color); font-weight: 600; margin-bottom: 1rem;">
                                    <span style="font-size: 1.5rem;">📱</span> الدخول بالرمز (الأسرع)
                                </h4>
                                <p style="color: var(--text-secondary); margin-bottom: 1rem;">
                                    احصل على رمز مكون من 6 أحرف أو أرقام من معلمك
                                </p>
                                <div style="background: var(--surface); padding: 1rem; border-radius: var(--radius-md); font-size: 0.875rem; color: var(--text-secondary);">
                                    ✅ لا يحتاج تسجيل<br>
                                    ✅ دخول فوري<br>
                                    ✅ مناسب للضيوف
                                </div>
                            </div>
                            
                            <div style="background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(4, 120, 87, 0.1)); padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid rgba(5, 150, 105, 0.2);">
                                <h4 style="color: var(--success-color); font-weight: 600; margin-bottom: 1rem;">
                                    <span style="font-size: 1.5rem;">👤</span> الدخول بالحساب
                                </h4>
                                <p style="color: var(--text-secondary); margin-bottom: 1rem;">
                                    سجل حساب لحفظ جميع نتائجك ومتابعة تقدمك
                                </p>
                                <div style="background: var(--surface); padding: 1rem; border-radius: var(--radius-md); font-size: 0.875rem; color: var(--text-secondary);">
                                    ✅ حفظ النتائج دائماً<br>
                                    ✅ متابعة التقدم<br>
                                    ✅ إحصائيات شخصية
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="text-align: center;">
                        <img src="{{ asset('images/help/access-methods.png') }}" 
                             alt="طرق الدخول المختلفة" 
                             class="step-image clickable-image" 
                             onclick="openImageInNewTab(this)"
                             style="height: 300px; max-width: 100%;">
                    </div>
                </div>
            </div>

            <!-- Taking Tab -->
            <div id="taking-tab" class="tab-content">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center; margin-bottom: 2rem;">
                    <div>
                        <img src="{{ asset('images/help/quiz-demo-preview.jpg') }}" 
                             alt="معاينة الاختبار التجريبي" 
                             class="step-image clickable-image" 
                             onclick="openImageInNewTab(this)"
                             style="height: 250px; max-width: 100%;">
                    </div>
                    
                    <div>
                        <h3 style="margin-bottom: 2rem; color: var(--text-primary); font-size: 1.5rem;">خطوات أخذ الاختبار</h3>
                        
                        <div style="display: grid; gap: 1rem;">
                            <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--surface-alt); border-radius: var(--radius-md);">
                                <div style="width: 2rem; height: 2rem; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">1</div>
                                <div>
                                    <h4 style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">اقرأ النص بعناية</h4>
                                    <p style="color: var(--text-secondary); margin: 0;">خذ وقتك لفهم النص قبل الانتقال للأسئلة</p>
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--surface-alt); border-radius: var(--radius-md);">
                                <div style="width: 2rem; height: 2rem; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">2</div>
                                <div>
                                    <h4 style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">تذكر الجذور الأربعة</h4>
                                    <p style="color: var(--text-secondary); margin: 0;">كل سؤال يقيس جذر معين، انتبه للألوان</p>
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--surface-alt); border-radius: var(--radius-md);">
                                <div style="width: 2rem; height: 2rem; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; flex-shrink: 0;">3</div>
                                <div>
                                    <h4 style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">راجع إجاباتك</h4>
                                    <p style="color: var(--text-secondary); margin: 0;">تأكد من إجاباتك قبل الإرسال النهائي</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1)); padding: 1.5rem; border-radius: var(--radius-lg); margin-top: 2rem; border: 1px solid rgba(245, 158, 11, 0.2);">
                    <h4 style="font-weight: 600; color: var(--warning-color); margin-bottom: 1rem;">⚡ ميزات مساعدة</h4>
                    <ul style="color: var(--text-secondary); margin: 0; padding-right: 1rem;">
                        <li>يمكنك العودة للنص في أي وقت</li>
                        <li>استخدم الأسهم للتنقل بين الأسئلة</li>
                        <li>النقاط الملونة تدل على تقدمك</li>
                    </ul>
                </div>
            </div>

            <!-- Results Tab -->
            <div id="results-tab" class="tab-content">
                <h3 style="margin-bottom: 2rem; color: var(--text-primary); font-size: 1.5rem; text-align: center;">كيف تفهم نتائجك</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div style="background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(4, 120, 87, 0.1)); border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; border: 1px solid rgba(5, 150, 105, 0.2);">
                        <h4 style="color: var(--success-color); font-weight: 600; margin-bottom: 1rem; font-size: 1.25rem;">🎯 النتيجة الإجمالية</h4>
                        <div style="font-size: 3rem; font-weight: 700; color: var(--success-color); margin: 1rem 0;">85%</div>
                        <p style="color: var(--text-secondary); margin: 0; font-size: 0.875rem;">تخبرك كم سؤال أجبت عليه بشكل صحيح</p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(126, 34, 206, 0.1)); border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; border: 1px solid rgba(147, 51, 234, 0.2);">
                        <h4 style="color: rgb(147, 51, 234); font-weight: 600; margin-bottom: 1rem; font-size: 1.25rem;">🌱 نتائج الجذور</h4>
                        <img src="{{ asset('images/help/juzoor-chart-mini.svg') }}" 
                             alt="رسم جُذور مصغر" 
                             onclick="openImageInNewTab(this)"
                             class="clickable-image"
                             style="width: 100%; height: 80px; object-contain; cursor: pointer; margin: 1rem 0; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='scale(1.05)'"
                             onmouseout="this.style.transform='scale(1)'">
                        <p style="color: var(--text-secondary); margin: 0; font-size: 0.875rem;">تُظهر قوتك في كل نوع من أنواع التفكير</p>
                    </div>
                </div>
                
                <div style="max-width: 600px; margin: 0 auto;">
                    <h4 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem; text-align: center; font-size: 1.25rem;">🎨 فهم الألوان</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem;">
                        <div style="text-align: center; padding: 1rem; background: rgba(239, 68, 68, 0.1); border-radius: var(--radius-md); border: 1px solid rgba(239, 68, 68, 0.2);">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎯</div>
                            <p style="font-weight: 600; color: rgb(239, 68, 68); margin: 0; font-size: 0.875rem;">جَوهر</p>
                        </div>
                        <div style="text-align: center; padding: 1rem; background: rgba(6, 182, 212, 0.1); border-radius: var(--radius-md); border: 1px solid rgba(6, 182, 212, 0.2);">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🧠</div>
                            <p style="font-weight: 600; color: rgb(6, 182, 212); margin: 0; font-size: 0.875rem;">ذِهن</p>
                        </div>
                        <div style="text-align: center; padding: 1rem; background: rgba(245, 158, 11, 0.1); border-radius: var(--radius-md); border: 1px solid rgba(245, 158, 11, 0.2);">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔗</div>
                            <p style="font-weight: 600; color: rgb(245, 158, 11); margin: 0; font-size: 0.875rem;">وَصلات</p>
                        </div>
                        <div style="text-align: center; padding: 1rem; background: rgba(147, 51, 234, 0.1); border-radius: var(--radius-md); border: 1px solid rgba(147, 51, 234, 0.2);">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">👁️</div>
                            <p style="font-weight: 600; color: rgb(147, 51, 234); margin: 0; font-size: 0.875rem;">رُؤية</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Tab -->
            <div id="tips-tab" class="tab-content">
                <h3 style="margin-bottom: 2rem; color: var(--text-primary); font-size: 1.5rem; text-align: center;">نصائح ذهبية للنجاح</h3>
                
                <div style="display: grid; gap: 1.5rem; max-width: 800px; margin: 0 auto;">
                    <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(79, 70, 229, 0.1)); border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid rgba(99, 102, 241, 0.2);">
                        <h4 style="color: var(--primary-color); font-weight: 600; margin-bottom: 1rem;">
                            <span style="font-size: 1.5rem;">🎯</span> قبل البدء
                        </h4>
                        <ul style="color: var(--text-secondary); margin: 0; padding-right: 1rem; line-height: 1.6;">
                            <li>تأكد من اتصال الإنترنت المستقر</li>
                            <li>اختر مكان هادئ ومريح</li>
                            <li>جهز ورقة وقلم للملاحظات</li>
                            <li>تأكد من شحن جهازك إذا كان محمول</li>
                        </ul>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(4, 120, 87, 0.1)); border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid rgba(5, 150, 105, 0.2);">
                        <h4 style="color: var(--success-color); font-weight: 600; margin-bottom: 1rem;">
                            <span style="font-size: 1.5rem;">📚</span> أثناء القراءة
                        </h4>
                        <ul style="color: var(--text-secondary); margin: 0; padding-right: 1rem; line-height: 1.6;">
                            <li>اقرأ النص مرتين: مرة للفهم العام ومرة للتفاصيل</li>
                            <li>ركز على الأفكار الرئيسية والكلمات المفتاحية</li>
                            <li>لا تتعجل، خذ وقتك الكافي</li>
                            <li>يمكنك العودة للنص في أي وقت</li>
                        </ul>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1)); border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid rgba(245, 158, 11, 0.2);">
                        <h4 style="color: var(--warning-color); font-weight: 600; margin-bottom: 1rem;">
                            <span style="font-size: 1.5rem;">✍️</span> عند الإجابة
                        </h4>
                        <ul style="color: var(--text-secondary); margin: 0; padding-right: 1rem; line-height: 1.6;">
                            <li>اقرأ السؤال بعناية وحدد الجذر المطلوب</li>
                            <li>احذر من الخيارات المشابهة</li>
                            <li>إذا لم تكن متأكداً، استخدم الاستبعاد</li>
                            <li>راجع إجاباتك قبل الإرسال</li>
                        </ul>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, rgba(147, 51, 234, 0.1), rgba(126, 34, 206, 0.1)); border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid rgba(147, 51, 234, 0.2);">
                        <h4 style="color: rgb(147, 51, 234); font-weight: 600; margin-bottom: 1rem;">
                            <span style="font-size: 1.5rem;">🚀</span> لتحسين الأداء
                        </h4>
                        <ul style="color: var(--text-secondary); margin: 0; padding-right: 1rem; line-height: 1.6;">
                            <li>تدرب على اختبارات مختلفة</li>
                            <li>راجع نتائجك السابقة وحلل نقاط الضعف</li>
                            <li>اطلب من معلمك شرح الأسئلة الصعبة</li>
                            <li>طور مهارات القراءة والتحليل</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section" style="background: var(--surface-alt);">
    <div class="container">
        <h2 class="section-title animate-on-scroll">أسئلة شائعة</h2>
        <p class="section-subtitle animate-on-scroll">
            إجابات على أكثر الأسئلة تكراراً
        </p>

        <div class="faq-container">
            <div class="faq-item animate-on-scroll">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    <span>ماذا لو نسيت رمز الاختبار؟</span>
                    <span style="transition: transform 0.3s ease;">▼</span>
                </button>
                <div class="faq-answer">
                    <p>اطلب من معلمك الرمز مرة أخرى، أو ابحث عن الرمز في رسائل الواتساب أو البريد الإلكتروني.</p>
                </div>
            </div>

            <div class="faq-item animate-on-scroll">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    <span>هل يمكنني إعادة الاختبار؟</span>
                    <span style="transition: transform 0.3s ease;">▼</span>
                </button>
                <div class="faq-answer">
                    <p>نعم! يمكنك إعادة الاختبار عدة مرات. كل مرة ستحصل على تجربة تعلم جديدة.</p>
                </div>
            </div>

            <div class="faq-item animate-on-scroll">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    <span>كيف أحسن نتائجي في الجذور الضعيفة؟</span>
                    <span style="transition: transform 0.3s ease;">▼</span>
                </button>
                <div class="faq-answer">
                    <p>راجع أسئلة الجذر الضعيف، اطلب شرح من معلمك، وتدرب على أنواع الأسئلة المختلفة لهذا الجذر.</p>
                </div>
            </div>

            <div class="faq-item animate-on-scroll">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    <span>هل النتائج محفوظة دائماً؟</span>
                    <span style="transition: transform 0.3s ease;">▼</span>
                </button>
                <div class="faq-answer">
                    <p>إذا سجلت حساب، نعم. إذا دخلت كضيف، النتائج محفوظة لمدة أسبوع فقط.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="cta-content">
        <h2 class="cta-title">جاهز لتبدأ رحلة التعلم؟</h2>
        <p class="cta-subtitle">
            اختبر معرفتك الآن واكتشف قدراتك في جميع الجذور
        </p>
        
        <div class="cta-buttons">
            <a href="{{ route('quiz.demo') }}" class="btn btn-primary">
                <span>🎮</span>
                جرب الاختبار التجريبي
            </a>
            
            <a href="{{ route('register') }}" class="btn btn-secondary">
                <span>👤</span>
                أنشئ حساب مجاني
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

// FAQ toggle functionality
function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const arrow = button.querySelector('span:last-child');
    
    if (answer.classList.contains('active')) {
        answer.classList.remove('active');
        arrow.style.transform = 'rotate(0deg)';
    } else {
        // Close all other FAQ items
        document.querySelectorAll('.faq-answer').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelectorAll('.faq-question span:last-child').forEach(arrow => {
            arrow.style.transform = 'rotate(0deg)';
        });
        
        // Open clicked item
        answer.classList.add('active');
        arrow.style.transform = 'rotate(180deg)';
    }
}

// Open image in new tab
function openImageInNewTab(img) {
    // Create a new window/tab with the image
    const newWindow = window.open('', '_blank');
    
    // Create a simple HTML page with the image
    const imageHTML = `
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>${img.alt}</title>
            <style>
                body {
                    margin: 0;
                    padding: 20px;
                    background: #f8fafc;
                    font-family: 'Tajawal', Arial, sans-serif;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    min-height: 100vh;
                }
                
                .image-container {
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                    padding: 20px;
                    max-width: 95vw;
                    max-height: 95vh;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                
                img {
                    max-width: 100%;
                    max-height: 80vh;
                    object-fit: contain;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }
                
                h1 {
                    color: #1e293b;
                    text-align: center;
                    margin: 20px 0 10px 0;
                    font-size: 1.5rem;
                    font-weight: 600;
                }
                
                .actions {
                    margin-top: 20px;
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                    justify-content: center;
                }
                
                .btn {
                    padding: 8px 16px;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: 500;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    transition: all 0.3s ease;
                }
                
                .btn-primary {
                    background: #6366f1;
                    color: white;
                }
                
                .btn-primary:hover {
                    background: #4f46e5;
                    transform: translateY(-1px);
                }
                
                .btn-secondary {
                    background: #e2e8f0;
                    color: #475569;
                }
                
                .btn-secondary:hover {
                    background: #cbd5e1;
                }
                
                @media (max-width: 600px) {
                    body { padding: 10px; }
                    .image-container { padding: 15px; }
                    h1 { font-size: 1.25rem; }
                }
            </style>
        </head>
        <body>
            <div class="image-container">
                <img src="${img.src}" alt="${img.alt}" onload="this.style.opacity=1" style="opacity:0; transition: opacity 0.3s ease;">
                <h1>${img.alt}</h1>
                <div class="actions">
                    <a href="${img.src}" download class="btn btn-primary">
                        💾 تحميل الصورة
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary">
                        🖨️ طباعة
                    </button>
                    <button onclick="window.close()" class="btn btn-secondary">
                        ✖️ إغلاق
                    </button>
                </div>
            </div>
        </body>
        </html>
    `;
    
    // Write the HTML to the new window
    newWindow.document.write(imageHTML);
    newWindow.document.close();
    
    // Focus the new window
    newWindow.focus();
}

// Scroll animations
function animateOnScroll() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 100;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('animate');
        }
    });
}

// Smooth scrolling for anchor links
function smoothScroll(target) {
    const element = document.querySelector(target);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Event listeners
window.addEventListener('scroll', animateOnScroll);
window.addEventListener('load', animateOnScroll);

// Handle anchor link clicks
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = this.getAttribute('href');
        smoothScroll(target);
    });
});

// Initialize animations on page load
document.addEventListener('DOMContentLoaded', function() {
    animateOnScroll();
});

// Performance optimization: Debounce scroll events
let scrollTimer = null;
window.addEventListener('scroll', function() {
    if (scrollTimer !== null) {
        clearTimeout(scrollTimer);
    }
    scrollTimer = setTimeout(animateOnScroll, 150);
});
</script>
@endpush