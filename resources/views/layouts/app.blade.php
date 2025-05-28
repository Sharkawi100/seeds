<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'جُذور Quiz') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            font-family: 'Tajawal', sans-serif !important;
        }
        .root-jawhar { background: #ff6b6b; }
        .root-zihn { background: #4ecdc4; }
        .root-waslat { background: #f7b731; }
        .root-roaya { background: #5f27cd; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @include('layouts.navigation')
        
        <main>
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '.tinymce-editor',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    language: 'ar',
    directionality: 'rtl',
    height: 300,
    menubar: false,
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; direction: rtl; text-align: right; }',
    entity_encoding: 'raw',
    forced_root_block: 'div',
    branding: false
});
</script>
</body>
</html>