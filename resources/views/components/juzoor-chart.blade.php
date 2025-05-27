@props(['scores' => null, 'size' => 'medium'])

@php
$defaultScores = $scores ?? ['jawhar' => 0, 'zihn' => 0, 'waslat' => 0, 'roaya' => 0];
$sizeClasses = [
    'small' => 'w-48 h-48',
    'medium' => 'w-64 h-64',
    'large' => 'w-80 h-80'
];
$chartSize = $sizeClasses[$size] ?? $sizeClasses['medium'];
@endphp

<div class="relative {{ $chartSize }} mx-auto">
    <svg viewBox="0 0 400 400" class="w-full h-full">
        <!-- Background circles -->
        <circle cx="200" cy="200" r="150" fill="none" stroke="#e5e7eb" stroke-width="1" />
        <circle cx="200" cy="200" r="100" fill="none" stroke="#e5e7eb" stroke-width="1" />
        <circle cx="200" cy="200" r="50" fill="none" stroke="#e5e7eb" stroke-width="1" />
        
        <!-- Axis lines -->
        <line x1="200" y1="50" x2="200" y2="350" stroke="#e5e7eb" stroke-width="1" />
        <line x1="50" y1="200" x2="350" y2="200" stroke="#e5e7eb" stroke-width="1" />
        <line x1="94" y1="94" x2="306" y2="306" stroke="#e5e7eb" stroke-width="1" />
        <line x1="306" y1="94" x2="94" y2="306" stroke="#e5e7eb" stroke-width="1" />
        
        <!-- Data polygon -->
        @php
            // Calculate positions - extend to full radius (180 instead of 150)
            $jawharY = 200 - ($defaultScores['jawhar'] / 100 * 180);
            $zihnX = 200 + ($defaultScores['zihn'] / 100 * 180);
            $waslatY = 200 + ($defaultScores['waslat'] / 100 * 180);
            $roayaX = 200 - ($defaultScores['roaya'] / 100 * 180);
        @endphp
        
        <polygon points="200,{{ $jawharY }} {{ $zihnX }},200 200,{{ $waslatY }} {{ $roayaX }},200"
                 fill="rgba(59, 130, 246, 0.2)" 
                 stroke="rgb(59, 130, 246)" 
                 stroke-width="3" />
        
        <!-- Data points -->
        <circle cx="200" cy="{{ $jawharY }}" r="8" fill="#ff6b6b" stroke="white" stroke-width="2" />
        <circle cx="{{ $zihnX }}" cy="200" r="8" fill="#4ecdc4" stroke="white" stroke-width="2" />
        <circle cx="200" cy="{{ $waslatY }}" r="8" fill="#f7b731" stroke="white" stroke-width="2" />
        <circle cx="{{ $roayaX }}" cy="200" r="8" fill="#5f27cd" stroke="white" stroke-width="2" />
    </svg>
    
    <!-- Labels -->
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-2 text-center">
        <span class="text-2xl">🎯</span>
        <div class="text-sm font-bold">جَوهر</div>
        <div class="text-xs text-gray-600">{{ $defaultScores['jawhar'] }}%</div>
    </div>
    
    <div class="absolute top-1/2 right-0 transform translate-x-2 -translate-y-1/2 text-center">
        <span class="text-2xl">🧠</span>
        <div class="text-sm font-bold">ذِهن</div>
        <div class="text-xs text-gray-600">{{ $defaultScores['zihn'] }}%</div>
    </div>
    
    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-2 text-center">
        <span class="text-2xl">🔗</span>
        <div class="text-sm font-bold">وَصلات</div>
        <div class="text-xs text-gray-600">{{ $defaultScores['waslat'] }}%</div>
    </div>
    
    <div class="absolute top-1/2 left-0 transform -translate-x-2 -translate-y-1/2 text-center">
        <span class="text-2xl">👁️</span>
        <div class="text-sm font-bold">رُؤية</div>
        <div class="text-xs text-gray-600">{{ $defaultScores['roaya'] }}%</div>
    </div>
</div>