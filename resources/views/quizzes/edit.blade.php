@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-3xl font-bold text-center mb-8">تعديل الاختبار</h1>
            
            <form action="{{ route('quizzes.update', $quiz) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">عنوان الاختبار</label>
                    <input type="text" name="title" value="{{ $quiz->title }}" class="w-full px-4 py-3 border-2 rounded-lg focus:border-blue-500" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">وصف الاختبار</label>
                    <textarea name="description" class="tinymce-editor">{{ $quiz->description ?? '' }}</textarea>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">المادة الدراسية</label>
                        <select name="subject" class="w-full px-4 py-3 border-2 rounded-lg focus:border-blue-500" required>
                            <option value="arabic" {{ $quiz->subject == 'arabic' ? 'selected' : '' }}>اللغة العربية</option>
                            <option value="english" {{ $quiz->subject == 'english' ? 'selected' : '' }}>اللغة الإنجليزية</option>
                            <option value="hebrew" {{ $quiz->subject == 'hebrew' ? 'selected' : '' }}>اللغة العبرية</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">الصف الدراسي</label>
                        <select name="grade_level" class="w-full px-4 py-3 border-2 rounded-lg focus:border-blue-500" required>
                            @for($i = 1; $i <= 9; $i++)
                            <option value="{{ $i }}" {{ $quiz->grade_level == $i ? 'selected' : '' }}>الصف {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-4 justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-bold">
                        💾 حفظ التغييرات
                    </button>
                    <a href="{{ route('quizzes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-bold">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<!-- For question editing with TinyMCE -->
<script>
    // Initialize TinyMCE for question text fields
    tinymce.init({
        selector: '.question-editor',
        plugins: 'lists link image charmap',
        toolbar: 'bold italic | bullist numlist | link',
        menubar: false,
        height: 150,
        language: 'ar',
        directionality: 'rtl'
    });
    </script>