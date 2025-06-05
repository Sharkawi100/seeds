<?php

namespace App\Http\Controllers\Auth\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class TeacherLoginController extends Controller
{
    /**
     * Display teacher login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.teacher.login');
    }

    /**
     * Handle teacher login request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Verify user is a teacher
        if (Auth::user()->user_type !== 'teacher') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'هذا الحساب مسجل كطالب. استخدم صفحة دخول الطلاب.',
            ]);
        }

        // Check if teacher is approved
        if (!Auth::user()->is_approved) {
            return redirect()->route('teacher.pending-approval');
        }

        // Check if account is active
        if (!Auth::user()->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'حسابك معطل. يرجى التواصل مع الإدارة.',
            ]);
        }

        session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}