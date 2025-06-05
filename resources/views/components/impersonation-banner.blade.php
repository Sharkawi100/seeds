{{-- File: resources/views/components/impersonation-banner.blade.php --}}
@if(session()->has('impersonator'))
    <div class="bg-yellow-500 text-black py-2 px-4 shadow-lg relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-user-secret ml-3 text-2xl"></i>
                <span class="font-medium">
                    أنت تتصفح كـ <strong>{{ auth()->user()->name }}</strong>
                    @if(session()->has('impersonator_name'))
                        (المدير: {{ session('impersonator_name') }})
                    @endif
                </span>
            </div>
            <a href="{{ route('admin.stop-impersonation') }}" 
               class="bg-white text-yellow-600 px-4 py-1 rounded-md text-sm font-bold hover:bg-yellow-50 transition">
                <i class="fas fa-sign-out-alt ml-2"></i>
                العودة لحسابي
            </a>
        </div>
    </div>
@endif