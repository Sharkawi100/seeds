<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <meta name="description" content="جُذور - نموذج تعليمي مبتكر يُحول التعلم إلى رحلة نمو شخصية، حيث ينمو كل طالب بطريقته الخاصة عبر أربعة جذور للمعرفة">
    <meta name="keywords" content="جذور, تعليم, تقييم, نموذج تعليمي, تعلم, اختبارات">

    <title>{{ config('app.name', 'جُذور') }} - @yield('title', 'نموذج تعليمي مبتكر')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
   <!-- Scripts -->
{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
<link rel="stylesheet" href="{{ asset('build/assets/app-Dj5BByVQ.css') }}">
<script src="{{ asset('build/assets/app-Bv74iOPe.js') }}" defer></script>
<style>
        * {
            font-family: 'Tajawal', sans-serif !important;
        }
    </style>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
       <style>
        * {
            font-family: 'Tajawal', sans-serif !important;
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen">
        @yield('content')
        {{ $slot ?? '' }}
    </div>

    @stack('scripts')
</body>
</html>