<?php

namespace App\Http\Controllers\Auth\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class StudentLoginController extends Controller
{
    /**
     * Display student login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.student.login');
    }

    /**
     * Handle student email/password login.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Verify user is a student
        if (Auth::user()->user_type !== 'student') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'هذا الحساب مسجل كمعلم. استخدم صفحة دخول المعلمين.',
            ]);
        }

        // Check if account is active
        if (!Auth::user()->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'حسابك معطل. يرجى التواصل مع معلمك.',
            ]);
        }

        session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle student PIN login (school code + student ID).
     */
    public function pinLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'school_code' => ['required', 'string', 'size:6'],
            'student_id' => ['required', 'string', 'max:50'],
        ]);

        // For now, we'll use a simple implementation
        // Later, you can add a schools table if needed
        $user = User::where('user_type', 'student')
            ->where('student_school_id', $request->student_id)
            ->where('school_name', 'LIKE', '%' . $request->school_code . '%')
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'school_code' => 'رمز المدرسة أو رقم الطالب غير صحيح.',
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'school_code' => 'حسابك معطل. يرجى التواصل مع معلمك.',
            ]);
        }

        Auth::login($user);
        session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}