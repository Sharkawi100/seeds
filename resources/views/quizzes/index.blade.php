@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">لوحة الاختبارات</h1>
                    <p class="text-gray-600 mt-1">إدارة جميع اختباراتك في مكان واحد</p>
                </div>
                <a href="{{ route('quizzes.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transform hover:scale-105 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إنشاء اختبار جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-r-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الاختبارات</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $quizzes->count() }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 border-r-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">إجمالي الأسئلة</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $quizzes->sum(function($q) { return $q->questions->count(); }) }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-r-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">مواد دراسية</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $quizzes->pluck('subject')->unique()->count() }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-r-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">مستويات دراسية</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $quizzes->pluck('grade_level')->unique()->count() }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" placeholder="🔍 البحث عن اختبار..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">جميع المواد</option>
                    <option value="arabic">اللغة العربية</option>
                    <option value="english">اللغة الإنجليزية</option>
                    <option value="hebrew">اللغة العبرية</option>
                </select>
                <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">جميع الصفوف</option>
                    @for($i = 1; $i <= 9; $i++)
                    <option value="{{ $i }}">الصف {{ $i }}</option>
                    @endfor
                </select>
                <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Quizzes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($quizzes as $quiz)
                @php
                    $rootCounts = $quiz->questions->groupBy('root_type')->map->count();
                    $totalQuestions = $quiz->questions->count();
                    $hasPassage = $quiz->questions->where('passage', '!=', null)->first();
                @endphp
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-white mb-1">{{ $quiz->title }}</h3>
                                <div class="flex gap-3 text-sm text-white/80">
                                    <span>{{ ['arabic' => '🌍 العربية', 'english' => '🌎 الإنجليزية', 'hebrew' => '🌏 العبرية'][$quiz->subject] }}</span>
                                    <span>📚 الصف {{ $quiz->grade_level }}</span>
                                </div>
                            </div>
                            <div class="bg-white/20 px-3 py-1 rounded-full text-sm text-white">
                                {{ $totalQuestions }} سؤال
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4">
                        <!-- Root Distribution -->
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            <div class="text-center p-2 bg-red-50 rounded-lg">
                                <div class="text-lg font-bold text-red-600">{{ $rootCounts['jawhar'] ?? 0 }}</div>
                                <div class="text-xs text-gray-600">جَوْهَر</div>
                            </div>
                            <div class="text-center p-2 bg-cyan-50 rounded-lg">
                                <div class="text-lg font-bold text-cyan-600">{{ $rootCounts['zihn'] ?? 0 }}</div>
                                <div class="text-xs text-gray-600">ذِهْن</div>
                            </div>
                            <div class="text-center p-2 bg-yellow-50 rounded-lg">
                                <div class="text-lg font-bold text-yellow-600">{{ $rootCounts['waslat'] ?? 0 }}</div>
                                <div class="text-xs text-gray-600">وَصَلات</div>
                            </div>
                            <div class="text-center p-2 bg-purple-50 rounded-lg">
                                <div class="text-lg font-bold text-purple-600">{{ $rootCounts['roaya'] ?? 0 }}</div>
                                <div class="text-xs text-gray-600">رُؤْيَة</div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="flex gap-2 mb-4">
                            @if($hasPassage)
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">📄 يحتوي على نص</span>
                            @endif
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs">📅 {{ $quiz->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Progress Visual -->
                        <div class="mb-4">
                            <x-juzoor-chart :scores="[
                                'jawhar' => $totalQuestions > 0 ? ($rootCounts['jawhar'] ?? 0) / $totalQuestions * 100 : 0,
                                'zihn' => $totalQuestions > 0 ? ($rootCounts['zihn'] ?? 0) / $totalQuestions * 100 : 0,
                                'waslat' => $totalQuestions > 0 ? ($rootCounts['waslat'] ?? 0) / $totalQuestions * 100 : 0,
                                'roaya' => $totalQuestions > 0 ? ($rootCounts['roaya'] ?? 0) / $totalQuestions * 100 : 0
                            ]" size="small" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('quiz.take', $quiz) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg text-center transition flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                تشغيل
                            </a>
                            <a href="{{ route('quizzes.questions.index', $quiz) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-center transition flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                تعديل
                            </a>
                        </div>
                        
                        <div class="flex justify-between items-center mt-3 pt-3 border-t">
                            <a href="{{ route('quizzes.show', $quiz) }}" class="text-gray-600 hover:text-gray-800 text-sm">عرض التفاصيل</a>
                            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('حذف هذا الاختبار نهائياً؟')">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">لا توجد اختبارات</h3>
                        <p class="text-gray-500 mb-6">ابدأ بإنشاء أول اختبار باستخدام نموذج جُذور</p>
                        <a href="{{ route('quizzes.create') }}" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg font-bold hover:from-blue-700 hover:to-purple-700 transition">
                            إنشاء اختبار جديد
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection