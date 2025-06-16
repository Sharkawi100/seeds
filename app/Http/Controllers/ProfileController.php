<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the main dashboard based on user role.
     */
    public function dashboard(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        // Redirect admins to admin dashboard
        if ($user && $user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Show role-based dashboard for teachers and students
        return view('dashboard', [
            'user' => $user,
        ]);
    }

    /**
     * Display the user's profile dashboard.
     */
    public function profileDashboard(Request $request): RedirectResponse
    {
        // Redirect to profile edit page
        return redirect()->route('profile.edit');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['nullable', 'image', 'max:2048'], // 2MB max
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Show profile completion status.
     */
    public function completion(Request $request): View
    {
        $user = Auth::user();

        $completionPercentage = $this->calculateProfileCompletion($user);

        return view('profile.completion', [
            'user' => $user,
            'completion_percentage' => $completionPercentage,
        ]);
    }

    /**
     * Show active sessions.
     */
    public function sessions(Request $request): View
    {
        return view('profile.sessions', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Logout other devices.
     */
    public function logoutOtherDevices(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($request->password);

        return Redirect::route('profile.sessions')->with('status', 'other-devices-logged-out');
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language' => ['nullable', 'string', 'in:ar,en,he'],
            'notifications_enabled' => ['boolean'],
            'email_notifications' => ['boolean'],
            'theme' => ['nullable', 'string', 'in:light,dark,auto'],
        ]);

        $user = Auth::user();

        $preferences = $user->preferences ?? [];
        $preferences = array_merge($preferences, $validated);

        $user->preferences = $preferences;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'preferences-updated');
    }

    /**
     * Update privacy settings.
     */
    public function updatePrivacy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_visibility' => ['required', 'string', 'in:public,private,teachers_only'],
            'show_email' => ['boolean'],
            'show_last_active' => ['boolean'],
            'allow_messages' => ['boolean'],
        ]);

        $user = Auth::user();

        $privacy = $user->privacy_settings ?? [];
        $privacy = array_merge($privacy, $validated);

        $user->privacy_settings = $privacy;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'privacy-updated');
    }

    /**
     * Calculate profile completion percentage.
     */
    private function calculateProfileCompletion($user): int
    {
        $fields = [
            'name' => !empty($user->name),
            'email' => !empty($user->email),
            'avatar' => !empty($user->avatar),
            'bio' => !empty($user->bio),
            'school' => !empty($user->school),
            'grade_level' => !empty($user->grade_level),
        ];

        $completed = array_sum($fields);
        $total = count($fields);

        return round(($completed / $total) * 100);
    }
}