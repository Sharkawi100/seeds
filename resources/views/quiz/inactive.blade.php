@extends('layouts.app')

@section('title', 'الاختبار غير مفعل')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        
        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-8 text-center">
                <div class="text-6xl mb-4">⏸️</div>
                <h1 class="text-2xl font-bold text-white mb-2">{{ $message }}</h1>
                <p class="text-orange-100 text-sm">{{ $quiz->title }}</p>
            </div>
            
            <!-- Content -->
            <div class="p-6 text-center space-y-6">
                
                <!-- Description -->
                <div class="space-y-3">
                    <p class="text-gray-600 leading-relaxed">{{ $description }}</p>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 text-blue-700 text-sm">
                            <i class="fas fa-info-circle"></i>
                            <span class="font-medium">معلومة مفيدة</span>
                        </div>
                        <p class="text-blue-600 text-sm mt-1">
                            يمكن للمعلم إعادة تفعيل الاختبار في أي وقت. احتفظ برمز الاختبار للعودة لاحقاً.
                        </p>
                    </div>
                </div>
                
                <!-- Quiz Info -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">رمز الاختبار:</span>
                        <span class="font-mono font-bold text-lg text-gray-700">{{ $quiz->pin }}</span>
                    </div>
                    @if($quiz->subject)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">المادة:</span>
                        <span class="text-gray-700">{{ $quiz->subject_name }}</span>
                    </div>
                    @endif
                    @if($quiz->grade_level)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">الصف:</span>
                        <span class="text-gray-700">الصف {{ $quiz->grade_level }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ $back_url }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        تجربة اختبار آخر
                    </a>
                    
                    <button onclick="copyPin()" 
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-copy"></i>
                        نسخ رمز الاختبار
                    </button>
                </div>
                
            </div>
        </div>
                
    </div>
</div>

<script>
function copyPin() {
    const pin = '{{ $quiz->pin }}';
    navigator.clipboard.writeText(pin).then(() => {
        // Show feedback
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-green-600"></i> تم النسخ!';
        btn.classList.add('bg-green-100', 'text-green-700');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-100', 'text-green-700');
        }, 2000);
    }).catch(() => {
        alert('لم يتم نسخ الرمز. الرمز هو: ' + pin);
    });
}
</script>
@endsection