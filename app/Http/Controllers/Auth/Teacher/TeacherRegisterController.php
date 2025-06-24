<?php

namespace App\Http\Controllers\Auth\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TeacherRegisterController extends Controller
{
    /**
     * Display teacher registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.teacher.register');
    }

    /**
     * Handle teacher registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'school_name' => ['required', 'string', 'max:255'],
            'subjects_taught' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'teacher',
            'school_name' => $request->school_name,
            'subjects_taught' => $request->subjects_taught,
            'experience_years' => $request->experience_years,
            'is_approved' => true,
            'teacher_data' => json_encode([
                'registration_date' => now(),
                'registration_ip' => $request->ip(),
            ]),
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Redirect to dashboard like students
        return redirect()->route('dashboard');
    }
}