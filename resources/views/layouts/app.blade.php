<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'جُذور Quiz') }} - @yield('title', 'منصة التعلم التفاعلي')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-CX5so9Op.css') }}">
<script src="{{ asset('build/assets/app-Bf4POITK.js') }}" defer></script>
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --warning-gradient: linear-gradient(135deg, #fcb69f 0%, #ffecd2 100%);
            --info-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            
            --animation-duration: 0.3s;
            --animation-timing: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }
        
        /* FontAwesome fix */
        i[class*="fa-"],
        i[class*="fas"],
        i[class*="far"],
        i[class*="fab"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }
        
        /* Glassmorphism Effect */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46a1);
        }
        
        /* Loading Animation */
        .loading-dots {
            display: inline-flex;
            gap: 4px;
        }
        
        .loading-dots span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: loading-bounce 1.4s infinite ease-in-out both;
        }
        
        .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots span:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes loading-bounce {
            0%, 80%, 100% {
                transform: scale(0);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Pulse Animation */
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }
        
        /* Tooltip */
        .tooltip {
            position: relative;
        }
        
        .tooltip::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-5px);
            background: #1a202c;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .tooltip::after {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(5px);
            border: 6px solid transparent;
            border-top-color: #1a202c;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .tooltip:hover::before,
        .tooltip:hover::after {
            opacity: 1;
            transform: translateX(-50%) translateY(-10px);
        }
        
        /* Root Type Colors */
        .root-jawhar { background: linear-gradient(135deg, #ff6b6b, #ff8e8e); }
        .root-zihn { background: linear-gradient(135deg, #4ecdc4, #6ee7de); }
        .root-waslat { background: linear-gradient(135deg, #f7b731, #faca5f); }
        .root-roaya { background: linear-gradient(135deg, #5f27cd, #7c3aed); }
        
        /* Notification Toast */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            z-index: 9999;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        /* Page Transitions */
        .page-transition-enter {
            opacity: 0;
            transform: translateY(20px);
        }
        
        .page-transition-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        
        /* Keyboard Shortcuts Badge */
        kbd {
            background: #f4f4f5;
            border: 1px solid #d4d4d8;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 12px;
            font-family: monospace;
            box-shadow: 0 2px 0 #d4d4d8;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 overflow-x-hidden">
    {{-- Impersonation Warning Banner --}}
    @if(session()->has('impersonator'))
    <div class="bg-yellow-500 text-black px-4 py-2 text-center font-bold relative z-50">
        <i class="fas fa-exclamation-triangle ml-2"></i>
        أنت تتصفح الآن بحساب: {{ Auth::user()->name }} 
        ({{ Auth::user()->is_admin ? 'مدير' : (Auth::user()->user_type === 'teacher' ? 'معلم' : 'طالب') }})
        <form method="POST" action="{{ route('admin.stop-impersonation') }}" class="inline mr-4">
            @csrf
            <button type="submit" class="underline hover:no-underline">
                <i class="fas fa-undo ml-1"></i>
                العودة لحسابك الأصلي
            </button>
        </form>
    </div>
    @endif
        <!-- Background Pattern -->
    <div class="fixed inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 opacity-70"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    
    <div class="relative min-h-screen z-10">
        <!-- Navigation -->
        @include('layouts.navigation')
        
        <!-- Page Content -->
        <main class="page-transition-enter page-transition-enter-active">
            @if(session('success'))
            <div class="toast show bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3" id="success-toast">
                <i class="fas fa-check-circle text-2xl"></i>
                <p>{{ session('success') }}</p>
                <button onclick="closeToast('success-toast')" class="mr-auto">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="toast show bg-red-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3" id="error-toast">
                <i class="fas fa-exclamation-circle text-2xl"></i>
                <p>{{ session('error') }}</p>
                <button onclick="closeToast('error-toast')" class="mr-auto">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif
            
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="mt-20 bg-white/80 backdrop-blur-lg border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-right">
                        <h3 class="text-lg font-bold gradient-text mb-2">منصة جُذور التعليمية</h3>
                        <p class="text-gray-600 text-sm">نموذج تعليمي مبتكر لتنمية جميع جوانب التعلم</p>
                    </div>
                    
                    <div class="flex gap-6">
                        <a href="{{ route('juzoor.model') }}" class="text-gray-600 hover:text-purple-600 transition">
                            <i class="fas fa-book ml-2"></i>عن النموذج
                        </a>
                        <a href="#" class="text-gray-600 hover:text-purple-600 transition">
                            <i class="fas fa-envelope ml-2"></i>تواصل معنا
                        </a>
                        <a href="#" class="text-gray-600 hover:text-purple-600 transition">
                            <i class="fas fa-question-circle ml-2"></i>مساعدة
                        </a>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                    <p>&copy; 2024 جُذور. جميع الحقوق محفوظة. صُنع بـ ❤️ لموقع السراج</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Global Scripts -->
    <script>
        // Toast Notifications
        function closeToast(id) {
            const toast = document.getElementById(id);
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }
        
        // Auto-hide toasts
        setTimeout(() => {
            document.querySelectorAll('.toast').forEach(toast => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            });
        }, 5000);
        
        // Keyboard Shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('global-search')?.focus();
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    modal.classList.remove('show');
                });
            }
        });
        
        // Page Transition
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="/"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!link.hasAttribute('data-no-transition')) {
                        e.preventDefault();
                        document.querySelector('main').classList.remove('page-transition-enter-active');
                        setTimeout(() => {
                            window.location.href = link.href;
                        }, 300);
                    }
                });
            });
        });
        
        // Smooth Scroll for Anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>