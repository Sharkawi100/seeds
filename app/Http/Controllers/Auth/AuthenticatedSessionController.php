<?php
// File: app/Http/Controllers/Auth/AuthenticatedSessionController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\LoginSecurityService;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    protected LoginSecurityService $securityService;

    public function __construct(LoginSecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Check if user is active
        if (!Auth::user()->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'حسابك معطل. يرجى التواصل مع الإدارة.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Update logout time if UserLogin model exists
        if (class_exists(\App\Models\UserLogin::class) && $loginRecordId = session('login_record_id')) {
            \App\Models\UserLogin::where('id', $loginRecordId)
                ->update(['logged_out_at' => now()]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}