@extends('layouts.app')

@section('title', $title ?? 'خطأ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 flex items-center justify-center py-8">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-red-100 to-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-exclamation-triangle text-3xl text-orange-600"></i>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $title ?? 'خطأ' }}</h1>
            <h2 class="text-lg text-orange-600 font-semibold mb-4">{{ $message ?? 'حدث خطأ غير متوقع' }}</h2>
            <p class="text-gray-600 mb-8">{{ $description ?? 'يرجى المحاولة مرة أخرى.' }}</p>
            
            <a href="{{ $back_url ?? route('home') }}" 
               class="bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 inline-block">
                <i class="fas fa-home mr-2"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
</div>
@endsection