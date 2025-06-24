@extends('layouts.guest')

@section('title', 'خطط الاشتراك')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Cairo', sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
        min-height: 100vh;
        overflow-x: hidden;
    }
    
    .pricing-hero {
        text-align: center;
        padding: 80px 20px 60px;
        position: relative;
    }
    
    .pricing-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events: none;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        color: white;
        margin-bottom: 1rem;
        text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        letter-spacing: -0.02em;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        color: rgba(255,255,255,0.9);
        max-width: 600px;
        margin: 0 auto 2rem;
        line-height: 1.6;
        font-weight: 400;
    }
    
    .hero-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 50px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .pricing-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 80px;
        position: relative;
        z-index: 2;
    }
    
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }
    
    .pricing-card {
        background: white;
        border-radius: 24px;
        padding: 0;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .pricing-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 40px 80px rgba(0,0,0,0.15);
    }
    
    .pricing-card.featured {
        transform: scale(1.05);
        border: 2px solid #10b981;
        box-shadow: 0 30px 80px rgba(16, 185, 129, 0.2);
    }
    
    .pricing-card.featured:hover {
        transform: scale(1.05) translateY(-8px);
    }
    
    .pricing-card.featured::before {
        content: 'يوصى به';
        position: absolute;
        top: 20px;
        right: -30px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 8px 40px;
        font-size: 0.85rem;
        font-weight: 700;
        transform: rotate(45deg);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        z-index: 3;
    }
    
    .card-header {
        padding: 2.5rem 2rem 1rem;
        text-align: center;
        position: relative;
    }
    
    .plan-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .plan-description {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }
    
    .price-display {
        margin-bottom: 2rem;
    }
    
    .price-amount {
        font-size: 4rem;
        font-weight: 900;
        color: #1f2937;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .price-currency {
        font-size: 2rem;
        font-weight: 700;
        vertical-align: top;
        margin-left: 0.5rem;
    }
    
    .price-period {
        color: #6b7280;
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .features-list {
        padding: 0 2rem 2rem;
    }
    
    .feature-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .feature-icon {
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 12px;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .feature-icon svg {
        width: 12px;
        height: 12px;
        fill: white;
    }
    
    .feature-text {
        color: #374151;
        font-weight: 500;
    }
    
    .card-footer {
        padding: 0 2rem 2.5rem;
    }
    
    .cta-button {
        width: 100%;
        padding: 1rem 2rem;
        border: none;
        border-radius: 16px;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .cta-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .cta-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .cta-secondary {
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        color: #374151;
        border: 2px solid #e5e7eb;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .cta-secondary:hover {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        color: #374151;
        text-decoration: none;
    }
    
    .cta-featured {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 12px 35px rgba(16, 185, 129, 0.3);
    }
    
    .cta-featured:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 45px rgba(16, 185, 129, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .student-notice {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
    }
    
    .contact-section {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 24px;
        padding: 3rem 2rem;
        text-align: center;
        color: white;
        margin-top: 4rem;
    }
    
    .contact-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .contact-description {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .contact-button {
        display: inline-flex;
        align-items: center;
        background: white;
        color: #667eea;
        padding: 1rem 2rem;
        border-radius: 16px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .contact-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        color: #5a67d8;
        text-decoration: none;
    }
    
    .final-cta {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 24px;
        padding: 3rem 2rem;
        text-align: center;
        color: white;
        margin-top: 3rem;
    }
    
    .final-cta-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .final-cta-text {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .final-cta-button {
        display: inline-flex;
        align-items: center;
        background: white;
        color: #667eea;
        padding: 1.2rem 2.5rem;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        box-shadow: 0 12px 35px rgba(0,0,0,0.1);
        margin-left: 1rem;
    }
    
    .final-cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 45px rgba(0,0,0,0.15);
        color: #5a67d8;
        text-decoration: none;
    }
    
    .login-link {
        color: rgba(255,255,255,0.8);
        text-decoration: underline;
        font-weight: 500;
        margin-top: 1rem;
        display: inline-block;
    }
    
    .login-link:hover {
        color: white;
        text-decoration: underline;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .pricing-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .pricing-card.featured {
            transform: none;
        }
        
        .pricing-card.featured:hover {
            transform: translateY(-8px);
        }
        
        .price-amount {
            font-size: 3rem;
        }
        
        .contact-section,
        .final-cta {
            padding: 2rem 1.5rem;
        }
        
        .final-cta-button {
            margin-left: 0;
            margin-top: 0.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .pricing-hero {
            padding: 60px 15px 40px;
        }
        
        .pricing-container {
            padding: 0 15px 60px;
        }
        
        .card-header,
        .features-list,
        .card-footer {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .price-amount {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="pricing-hero">
    <div class="hero-content">
        <h1 class="hero-title">🌱 خطط اشتراك جُذور</h1>
        <p class="hero-subtitle">
            اكتشف قوة الذكاء الاصطناعي في التعليم واختر الخطة التي تناسب احتياجاتك التعليمية
        </p>
        @guest
        <div class="hero-badge">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px;">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg>
            للمعلمين فقط • الطلاب يستخدمون المنصة مجاناً
        </div>
        @endguest
    </div>
</div>

<!-- Pricing Plans -->
<div class="pricing-container">
    @if($plans->count() > 0)
    <div class="pricing-grid">
        @foreach($plans as $index => $plan)
        @php
            $isPaidPlan = $plan->price_monthly > 0;
            $isFirstPaidPlan = $isPaidPlan && $plans->where('price_monthly', '>', 0)->first()->id == $plan->id;
        @endphp
        <div class="pricing-card {{ $isFirstPaidPlan ? 'featured' : '' }}">
            <div class="card-header">
                <h3 class="plan-name">{{ $plan->name }}</h3>
                <p class="plan-description">{{ $isFirstPaidPlan ? 'الخطة الموصى بها للمعلمين' : 'خطة مثالية للمعلمين النشطين' }}</p>
                
                <div class="price-display">
                    <div class="price-amount">
                        <span class="price-currency">$</span>{{ number_format($plan->price_monthly, 0) }}
                    </div>
                    <div class="price-period">شهرياً</div>
                </div>
            </div>
            
            <div class="features-list">
                @if($plan->monthly_quiz_limit)
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <span class="feature-text">
                        {{ $plan->monthly_quiz_limit == 999 ? 'اختبارات غير محدودة' : $plan->monthly_quiz_limit . ' اختبار شهرياً' }}
                    </span>
                </div>
                @endif
                
                @if($plan->monthly_ai_text_limit)
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <span class="feature-text">
                        {{ $plan->monthly_ai_text_limit == 999 ? 'نصوص ذكية غير محدودة' : $plan->monthly_ai_text_limit . ' نص ذكي شهرياً' }}
                    </span>
                </div>
                @endif
                
                @if($plan->monthly_ai_quiz_limit)
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <span class="feature-text">
                        {{ $plan->monthly_ai_quiz_limit == 999 ? 'اختبارات ذكية غير محدودة' : $plan->monthly_ai_quiz_limit . ' اختبار ذكي' }}
                    </span>
                </div>
                @endif
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <span class="feature-text">تحليل تفصيلي للنتائج</span>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <span class="feature-text">دعم فني متميز 24/7</span>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <span class="feature-text">تحديثات وميزات جديدة</span>
                </div>
            </div>
            
            <div class="card-footer">
                @auth
                    @if(Auth::user()->user_type === 'student')
                        <div class="student-notice">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 6px; vertical-align: text-top;">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                            </svg>
                            الطلاب يستخدمون جُذور مجاناً!
                        </div>
                        <a href="{{ route('dashboard') }}" class="cta-button cta-secondary">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px;">
                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                            </svg>
                            انتقل لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="cta-button {{ $isFirstPaidPlan ? 'cta-featured' : 'cta-primary' }}">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px;">
                                <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                            </svg>
                            اشترك من لوحة التحكم
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="cta-button {{ $isFirstPaidPlan ? 'cta-featured' : 'cta-primary' }}">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px;">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        </svg>
                        ابدأ الآن
                    </a>
                @endauth
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align: center; color: white; padding: 4rem 2rem;">
        <div style="background: rgba(255,255,255,0.1); border-radius: 24px; padding: 3rem; max-width: 500px; margin: 0 auto;">
            <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="margin-bottom: 1.5rem; opacity: 0.7;">
                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
            </svg>
            <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">لا توجد خطط متاحة حالياً</h3>
            <p style="opacity: 0.8;">سيتم إضافة خطط الاشتراك قريباً</p>
        </div>
    </div>
    @endif
    
    <!-- Custom Plans Contact -->
    <div class="contact-section">
        <h3 class="contact-title">هل تحتاج خطة مخصصة؟</h3>
        <p class="contact-description">
            إذا كنت تحتاج المزيد من الاختبارات أو ميزات إضافية، تواصل معنا لتصميم خطة تناسب احتياجاتك التعليمية
        </p>
        <a href="mailto:sharkawi@gmail.com" class="contact-button">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px;">
                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
            </svg>
            تواصل معنا
        </a>
    </div>
    
    <!-- Final CTA -->
    <div class="final-cta">
        <h3 class="final-cta-title">ابدأ رحلتك التعليمية اليوم</h3>
        <p class="final-cta-text">
            انضم إلى آلاف المعلمين الذين يستخدمون جُذور لتطوير التعليم وتوفير الوقت
        </p>
        @guest
        <a href="{{ route('register') }}" class="final-cta-button">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px;">
                <path fill-rule="evenodd" d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z"/>
            </svg>
            ابدأ مجاناً الآن
        </a>
        <div style="margin-top: 1rem;">
            <a href="{{ route('login') }}" class="login-link">لديك حساب؟ سجل دخولك هنا</a>
        </div>
        @else
        <a href="{{ route('dashboard') }}" class="final-cta-button">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px;">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            لوحة التحكم
        </a>
        @endauth
    </div>
</div>
@endsection