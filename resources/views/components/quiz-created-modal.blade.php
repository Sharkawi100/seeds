@if(session('quiz_created'))
<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" id="pinModal">
    <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center">
        <div class="text-6xl mb-4">🎉</div>
        <h2 class="text-2xl font-bold mb-4">تم إنشاء الاختبار بنجاح!</h2>
        
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl p-6 mb-6">
            <p class="text-sm mb-2">رمز الدخول للطلاب</p>
            <p class="text-4xl font-bold tracking-wider">{{ session('quiz_pin') }}</p>
        </div>
        
        <div class="space-y-3">
            <button onclick="copyPIN('{{ session('quiz_pin') }}')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg w-full font-bold transition">
                <i class="fas fa-copy"></i> نسخ رمز الدخول
            </button>
            <a href="{{ route('quizzes.show', session('quiz_id')) }}" 
               class="block bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg w-full font-bold transition">
                عرض الاختبار
            </a>
        </div>
    </div>
</div>
<script>
    // Auto-close modal on outside click
    document.getElementById('pinModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
</script>
@endif