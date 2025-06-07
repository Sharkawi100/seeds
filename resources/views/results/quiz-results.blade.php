@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">نتائج الاختبار: {{ $quiz->title }}</h1>
                @if($results->count() > 0)
                <button onclick="generateClassReport({{ $quiz->id }})" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <span>🤖</span>
                    <span>تحليل أداء الصف بالذكاء الاصطناعي</span>
                </button>
                @endif
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">النتيجة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($results as $result)
<tr>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">
            @if($result->user)
                {{ $result->user->name }}
            @else
                {{ $result->guest_name ?? 'طالب ضيف' }}
                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full mr-2">ضيف</span>
            @endif
        </div>
        <div class="text-sm text-gray-500">
            @if($result->user)
                {{ $result->user->email }}
            @else
                رمز: {{ substr($result->guest_token ?? '', 0, 8) }}...
            @endif
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-900">{{ $result->created_at->format('Y/m/d H:i') }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-2xl font-bold {{ $result->total_score >= 60 ? 'text-green-600' : 'text-red-600' }}">
            {{ $result->total_score }}%
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-left">
        <a href="{{ route('results.show', $result) }}" 
           class="text-blue-600 hover:text-blue-900 font-medium">
            عرض التفاصيل
        </a>
    </td>
</tr>
@endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4">
                {{ $results->links() }}
            </div>
        </div>
    </div>
</div>
<!-- AI Class Report Section -->
<div id="classReportSection" class="hidden mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
        <h3 class="text-xl font-bold text-white">تحليل الذكاء الاصطناعي لأداء الصف</h3>
    </div>
    <div class="p-6">
        <div id="classReportContent" class="prose max-w-none"></div>
    </div>
</div>
@push('scripts')
<script>
async function generateClassReport(quizId) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    button.disabled = true;
    
    try {
        const response = await fetch(`/roots/admin/ai/quiz/${quizId}/report`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                type: 'class_analysis'
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('classReportContent').innerHTML = data.report.replace(/\n/g, '<br>');
            document.getElementById('classReportSection').classList.remove('hidden');
            document.getElementById('classReportSection').scrollIntoView({ behavior: 'smooth' });
        } else {
            alert(data.message || 'حدث خطأ في توليد التقرير');
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال');
        console.error(error);
    } finally {
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}
</script>
@endpush
@endsection