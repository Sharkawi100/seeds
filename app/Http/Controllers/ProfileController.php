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
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the user's profile dashboard.
     */
    public function dashboard(Request $request): View
    {
        return view('profile.dashboard', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update user avatar
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        if ($request->user()->avatar) {
            Storage::delete('public/' . $request->user()->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $request->user()->update([
            'avatar' => $path,
        ]);

        return redirect()->route('profile.dashboard')
            ->with('status', 'تم تحديث الصورة الشخصية بنجاح');
    }

    /**
     * Show profile completion guide
     */
    public function completion(Request $request): View
    {
        return view('profile.completion', [
            'user' => $request->user(),
            'incompleteFields' => $request->user()->incomplete_fields,
            'completion' => $request->user()->profile_completion,
        ]);
    }
}
