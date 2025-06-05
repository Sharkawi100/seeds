<?php

namespace App\Http\Controllers\Auth\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class StudentRegisterController extends Controller
{
    /**
     * Display student registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.student.register');
    }

    /**
     * Handle student registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'grade_level' => ['required', 'integer', 'min:1', 'max:12'],
            'parent_email' => ['nullable', 'email', 'max:255'],
            'student_school_id' => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'student',
            'grade_level' => $request->grade_level,
            'parent_email' => $request->parent_email,
            'student_school_id' => $request->student_school_id,
            'is_approved' => true, // Students auto-approved
            'student_data' => json_encode([
                'registration_date' => now(),
                'registration_ip' => $request->ip(),
            ]),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard'));
    }
}