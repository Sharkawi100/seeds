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
    $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
}