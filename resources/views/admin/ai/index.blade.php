@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold flex items-center gap-3">
                        <span class="text-4xl">🤖</span>
                        إدارة الذكاء الاصطناعي
                    </h1>
                    <p class="mt-2 text-indigo-100">توليد اختبارات جُذور باستخدام Claude AI</p>
                </div>
                <button onclick="openGenerateModal()" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-bold hover:bg-purple-50 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    توليد اختبار جديد
                </button>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">الاختبارات المولدة بالذكاء الاصطناعي</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_ai_quizzes'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent AI Generated Quizzes -->
        @if($stats['recent_generations']->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="text-3xl">✨</span>
                آخر الاختبارات المولدة
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-right">العنوان</th>
                            <th class="px-4 py-3 text-right">المادة</th>
                            <th class="px-4 py-3 text-right">الصف</th>
                            <th class="px-4 py-3 text-right">عدد الأسئلة</th>
                            <th class="px-4 py-3 text-right">التاريخ</th>
                            <th class="px-4 py-3 text-right">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['recent_generations'] as $quiz)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $quiz->title }}</td>
                            <td class="px-4 py-3">
                                {{ ['arabic' => 'العربية', 'english' => 'الإنجليزية', 'hebrew' => 'العبرية'][$quiz->subject] }}
                            </td>
                            <td class="px-4 py-3">{{ $quiz->grade_level }}</td>
                            <td class="px-4 py-3">{{ $quiz->questions->count() }}</td>
                            <td class="px-4 py-3">{{ $quiz->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-blue-600 hover:text-blue-800">
                                    عرض
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Generate Quiz Modal -->
    <div id="generateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="bg-purple-100 p-3 rounded-full">🪄</span>
                    توليد اختبار جديد بالذكاء الاصطناعي
                </h3>
                
                <form id="generateForm" class="space-y-6">
                    @csrf
                    
                    <!-- Basic Info -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="font-bold text-lg mb-4 text-gray-700">المعلومات الأساسية</h4>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">عنوان الاختبار</label>
                                <input type="text" name="title" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">المادة</label>
                                <select name="subject" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" required>
                                    <option value="arabic">اللغة العربية</option>
                                    <option value="english">اللغة الإنجليزية</option>
                                    <option value="hebrew">اللغة العبرية</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الصف</label>
                                <select name="grade_level" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" required>
                                    @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}">الصف {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">الموضوع</label>
                            <input type="text" name="topic" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="مثال: الأفعال الماضية، الكسور، Present Perfect" required>
                        </div>
                    </div>

                    <!-- Reading Passage -->
                    <div class="bg-blue-50 rounded-xl p-6">
                        <h4 class="font-bold text-lg mb-4 text-gray-700">نص القراءة (اختياري)</h4>
                        <div class="flex items-center mb-4">
                            <input type="checkbox" id="include_passage" name="include_passage" class="ml-2 w-4 h-4 text-purple-600">
                            <label for="include_passage" class="text-gray-700">تضمين نص قراءة في بداية الاختبار</label>
                        </div>
                        <div id="passageOptions" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">موضوع النص (اختياري)</label>
                            <input type="text" name="passage_topic" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="اتركه فارغاً لاستخدام الموضوع الرئيسي">
                        </div>
                    </div>

                    <!-- Root Settings -->
                    <div class="bg-green-50 rounded-xl p-6">
                        <h4 class="font-bold text-lg mb-4 text-gray-700">إعدادات الجذور</h4>
                        <div class="grid md:grid-cols-2 gap-6">
                            @php
                            $roots = [
                                'jawhar' => ['name' => 'جَوهر', 'emoji' => '🎯', 'desc' => 'ما هو؟'],
                                'zihn' => ['name' => 'ذِهن', 'emoji' => '🧠', 'desc' => 'كيف يعمل؟'],
                                'waslat' => ['name' => 'وَصلات', 'emoji' => '🔗', 'desc' => 'كيف يرتبط؟'],
                                'roaya' => ['name' => 'رُؤية', 'emoji' => '👁️', 'desc' => 'كيف نستخدمه؟']
                            ];
                            @endphp
                            
                            @foreach($roots as $key => $root)
                            <div class="bg-white rounded-lg p-4 border">
                                <div class="flex items-center mb-3">
                                    <span class="text-2xl ml-2">{{ $root['emoji'] }}</span>
                                    <div>
                                        <h5 class="font-bold">{{ $root['name'] }}</h5>
                                        <p class="text-sm text-gray-600">{{ $root['desc'] }}</p>
                                    </div>
                                </div>
                                @for($level = 1; $level <= 3; $level++)
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm">
                                        @if($level == 1) 🟡 @elseif($level == 2) 🟠 @else 🟢 @endif
                                        مستوى {{ $level }}
                                    </label>
                                    <input type="number" 
                                           name="roots[{{ $key }}][levels][{{ $level - 1 }}][depth]" 
                                           value="{{ $level }}" 
                                           hidden>
                                    <input type="number" 
                                           name="roots[{{ $key }}][levels][{{ $level - 1 }}][count]" 
                                           min="0" 
                                           max="10" 
                                           value="1"
                                           class="w-16 px-2 py-1 text-center border rounded">
                                </div>
                                @endfor
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            إلغاء
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            توليد الاختبار
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openGenerateModal() {
    document.getElementById('generateModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('generateModal').classList.add('hidden');
}

// Toggle passage options
document.getElementById('include_passage').addEventListener('change', function() {
    document.getElementById('passageOptions').classList.toggle('hidden', !this.checked);
});

// Handle form submission
document.getElementById('generateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    submitButton.disabled = true;
    
    try {
        const formData = new FormData(e.target);
        const response = await fetch('{{ route("admin.ai.generate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('تم توليد الاختبار بنجاح!');
            window.location.href = data.redirect;
        } else {
            alert('خطأ: ' + data.message);
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
    } finally {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    }
});
</script>
@endsection