@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-slate-100 py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-800 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-envelope text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">تواصل معنا</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                للاستفسارات التقنية، طلبات التسجيل، أو الاقتراحات
            </p>
        </div>

        <!-- FAQ Section -->
        <div class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">الأسئلة الشائعة</h2>
                <p class="text-gray-600">ابحث عن إجابة سريعة أولاً</p>
            </div>

            <!-- FAQ Search -->
            <div class="max-w-xl mx-auto mb-8">
                <div class="relative">
                    <input type="text" 
                           id="faqSearch" 
                           placeholder="ابحث في الأسئلة..."
                           class="w-full px-5 py-3 pr-12 border border-gray-300 rounded-xl focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 transition-all duration-200 bg-white">
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- FAQ Grid -->
            <div class="grid md:grid-cols-2 gap-4 max-w-6xl mx-auto" id="faqContainer">
                @php
                $faqs = [
                    [
                        'q' => 'كيف أحصل على موافقة حساب المعلم؟',
                        'a' => 'بعد التسجيل كمعلم، يراجع المدير طلبك خلال 24-48 ساعة. ستحصل على بريد إلكتروني بالموافقة أو طلب وثائق إضافية.',
                        'link' => route('teacher.register'),
                        'linkText' => 'تسجيل معلم جديد'
                    ],
                    [
                        'q' => 'كم عدد الاختبارات في كل خطة؟',
                        'a' => 'المجانية: 5 اختبارات شهرياً بالطريقة اليدوية فقط. المدفوعة (15$): 40 اختبار شهرياً مع إمكانية استخدام الذكاء الاصطناعي لتوليد النصوص والأسئلة.',
                        'link' => route('plans'),
                        'linkText' => 'مقارنة الخطط التفصيلية'
                    ],
                    [
                        'q' => 'كيف يدخل الطلاب الاختبار بدون حسابات؟',
                        'a' => 'عند إنشاء اختبار، ستحصل على رمز PIN مكون من 6 أحرف. شارك هذا الرمز مع الطلاب، وسيدخلونه في الصفحة الرئيسية مع أسمائهم.',
                        'link' => route('for.students'),
                        'linkText' => 'دليل دخول الطلاب'
                    ],
                    [
                        'q' => 'ما معنى الجذور الأربعة في التقييم؟',
                        'a' => 'جَوهر (التعريفات والمفاهيم الأساسية) - ذِهن (التحليل والتفكير النقدي) - وَصلات (الربط بين المفاهيم) - رُؤية (التطبيق والإبداع). كل جذر يُقيّم بشكل منفصل.',
                        'link' => route('juzoor.model'),
                        'linkText' => 'فهم نموذج التقييم'
                    ],
                    [
                        'q' => 'كيف أحلل نتائج الطلاب وأصدرها؟',
                        'a' => 'في صفحة النتائج، ستجد تحليلاً مفصلاً لكل طالب وكل جذر، مع رسوم بيانية ومقارنات. يمكن التصدير بصيغة PDF للطباعة أو CSV للتحليل الإضافي.',
                        'link' => route('results.index'),
                        'linkText' => 'عرض النتائج والتحليل'
                    ],
                    [
                        'q' => 'هل الذكاء الاصطناعي يولد محتوى عربي صحيح؟',
                        'a' => 'نعم، النظام مُدرّب لتوليد نصوص ومواضيع وأسئلة باللغة العربية الفصحى المناسبة للمراحل التعليمية المختلفة، مع مراعاة القواعد والسياق الثقافي.',
                        'link' => route('about'),
                        'linkText' => 'تفاصيل المنصة'
                    ],
                    [
                        'q' => 'ماذا لو نسيت كلمة المرور أو رمز PIN؟',
                        'a' => 'كلمة المرور: استخدم "نسيت كلمة المرور" في صفحة الدخول. رمز PIN للاختبار: ادخل على حسابك واذهب لصفحة الاختبار لرؤية الرمز مرة أخرى.',
                        'link' => route('login'),
                        'linkText' => 'صفحة تسجيل الدخول'
                    ],
                    [
                        'q' => 'هل يمكن تعديل الاختبار بعد نشره للطلاب؟',
                        'a' => 'يمكن تعديل الاختبار قبل بدء الطلاب في الحل. بعد بدء الطلاب، لا يُنصح بالتعديل للحفاظ على عدالة التقييم، لكن يمكن إيقافه مؤقتاً.',
                        'link' => route('quizzes.index'),
                        'linkText' => 'إدارة الاختبارات'
                    ]
                ];
                @endphp
                
                @foreach($faqs as $index => $faq)
                <div class="faq-item bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                    <button class="faq-toggle w-full px-5 py-4 text-right flex items-start justify-between focus:outline-none group" data-target="faq-{{ $index }}">
                        <span class="font-medium text-gray-900 text-sm leading-relaxed">{{ $faq['q'] }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-sm mt-1 transform transition-transform duration-200 faq-icon group-hover:text-gray-600"></i>
                    </button>
                    <div id="faq-{{ $index }}" class="faq-content hidden">
                        <div class="px-5 pb-4 border-t border-gray-100">
                            <p class="text-gray-600 text-sm leading-relaxed mt-3 mb-3">{{ $faq['a'] }}</p>
                            @if(isset($faq['link']))
                            <a href="{{ $faq['link'] }}" class="inline-flex items-center text-emerald-800 hover:text-emerald-900 text-sm font-medium transition-colors">
                                {{ $faq['linkText'] }}
                                <i class="fas fa-arrow-left mr-1 text-xs"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Contact Form -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                
                <!-- Form Header -->
                <div class="bg-amber-800 px-6 py-5">
                    <h2 class="text-xl font-semibold text-white">إرسال استفسار</h2>
                    <p class="text-amber-100 text-sm mt-1">سنتواصل معك خلال 24 ساعة</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                        <i class="fas fa-check-circle text-green-500 ml-3"></i>
                        <div>
                            <p class="font-medium text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('contact.submit') }}" method="POST" class="p-6 space-y-5" id="contactForm">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 transition-all duration-200 text-sm"
                                   placeholder="اسمك الكامل">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 transition-all duration-200 text-sm"
                                   placeholder="your@email.com">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Category Field -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">نوع الاستفسار</label>
                        <select id="category_id" 
                                name="category_id"
                                required
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 transition-all duration-200 text-sm">
                            <option value="">اختر النوع</option>
                            @php
                            $categories = [
                                ['id' => 1, 'name_ar' => 'دعم فني'],
                                ['id' => 2, 'name_ar' => 'تسجيل معلم'],
                                ['id' => 3, 'name_ar' => 'الاشتراكات'],
                                ['id' => 4, 'name_ar' => 'ميزة جديدة'],
                                ['id' => 5, 'name_ar' => 'شراكة'],
                                ['id' => 6, 'name_ar' => 'أخرى']
                            ];
                            @endphp
                            @foreach($categories as $category)
                            <option value="{{ $category['id'] }}" {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
                                {{ $category['name_ar'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject Field -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">الموضوع</label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 transition-all duration-200 text-sm"
                               placeholder="ملخص المشكلة أو الطلب">
                        @error('subject')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message Field -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">التفاصيل</label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="5" 
                                  required
                                  class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 transition-all duration-200 text-sm resize-vertical"
                                  placeholder="وصف مفصل للمشكلة أو الطلب...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">الحد الأدنى 10 أحرف</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full bg-emerald-700 hover:bg-emerald-800 text-black font-medium py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50">
                            <span class="submit-text flex items-center justify-center">
                                <i class="fas fa-paper-plane ml-2"></i>
                                إرسال الاستفسار
                            </span>
                            <span class="loading-text hidden flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 ml-2" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                جاري الإرسال...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Search
    const searchInput = document.getElementById('faqSearch');
    const faqItems = document.querySelectorAll('.faq-item');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-toggle span').textContent.toLowerCase();
            const answer = item.querySelector('.faq-content p').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = searchTerm ? 'none' : 'block';
            }
        });
    });
    
    // FAQ Accordion
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const icon = this.querySelector('.faq-icon');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });

    // Form functionality
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const messageField = document.getElementById('message');
    
    // Character count
    messageField.addEventListener('input', function() {
        const length = this.value.length;
        const parent = this.parentElement;
        let counter = parent.querySelector('.char-counter');
        
        if (!counter) {
            counter = document.createElement('p');
            counter.className = 'char-counter text-xs text-gray-500 mt-1';
            parent.appendChild(counter);
        }
        
        counter.textContent = `${length} حرف`;
        
        if (length < 10) {
            counter.className = 'char-counter text-xs text-red-500 mt-1';
        } else {
            counter.className = 'char-counter text-xs text-gray-500 mt-1';
        }
    });
    
    // Form submission
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.querySelector('.submit-text').classList.add('hidden');
        submitBtn.querySelector('.loading-text').classList.remove('hidden');
    });
    
    // Validation
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = this.value.trim();
            
            if (this.hasAttribute('required') && !value) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else if (this.type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    });
});
</script>

<style>
/* Professional animations */
.faq-item {
    transition: all 0.2s ease;
}

.faq-item:hover {
    transform: translateY(-1px);
}

input:focus, textarea:focus, select:focus {
    transform: translateY(-1px);
}

button:hover {
    transform: scale(1.01);
}

/* Smooth transitions */
* {
    transition: all 0.2s ease;
}
</style>
@endsection