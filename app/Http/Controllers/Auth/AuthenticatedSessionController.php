<?php

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
        // Validate basic input
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'login_role' => ['nullable', 'in:student,teacher']
        ]);

        // Process login attempt with security service
        $result = $this->securityService->processLoginAttempt(
            $validated['email'],
            $validated['password']
        );

        // Handle locked account
        if (isset($result['locked']) && $result['locked']) {
            throw ValidationException::withMessages([
                'email' => $result['message'],
            ])->status(423); // 423 Locked status code
        }

        // Handle failed login
        if (!$result['success']) {
            // Add CAPTCHA requirement to session if needed
            if (isset($result['show_captcha'])) {
                session(['require_captcha' => true]);
            }

            throw ValidationException::withMessages([
                'email' => $result['message'],
            ]);
        }

        // Login successful
        $user = $result['user'];
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // Store login record ID in session
        if (isset($result['login_record'])) {
            session(['login_record_id' => $result['login_record']->id]);
        }

        // Check if password change is required
        if ($result['force_password_change']) {
            return redirect()->route('profile.edit')
                ->with('warning', 'يجب عليك تغيير كلمة المرور الخاصة بك.');
        }

        // Redirect based on user type
        $route = match ($user->user_type) {
            'admin' => 'admin.dashboard',
            'teacher' => 'dashboard',
            default => 'dashboard'
        };

        // Show new device notification
        if ($result['is_new_device']) {
            session()->flash('info', 'تم تسجيل الدخول من جهاز جديد. تم إرسال إشعار إلى بريدك الإلكتروني.');
        }

        return redirect()->intended(route($route, absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Update logout time in user_logins table
        if ($loginRecordId = session('login_record_id')) {
            \App\Models\UserLogin::where('id', $loginRecordId)
                ->update(['logged_out_at' => now()]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}