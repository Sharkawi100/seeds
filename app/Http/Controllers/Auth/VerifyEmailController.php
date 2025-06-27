<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return $this->getSuccessRedirect($user, true);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->getSuccessRedirect($user, false);
    }

    /**
     * Determine the appropriate redirect after email verification
     */
    private function getSuccessRedirect($user, $alreadyVerified = false): RedirectResponse
    {
        // Check if user profile is complete
        $profileComplete = $this->isProfileComplete($user);

        if (!$profileComplete) {
            // Redirect to profile completion with welcome message
            return redirect()->route('profile.edit')
                ->with('verification_success', true)
                ->with(
                    'message',
                    $alreadyVerified ?
                    'مرحباً بك! حسابك مفعل بالفعل. يرجى إكمال بيانات ملفك الشخصي.' :
                    'مبروك! تم تفعيل حسابك بنجاح. الآن قم بإكمال بيانات ملفك الشخصي للاستفادة الكاملة من المنصة.'
                );
        }

        // Profile is complete, redirect to dashboard
        return redirect()->route('dashboard')
            ->with('verification_success', true)
            ->with(
                'message',
                $alreadyVerified ?
                'مرحباً بك في منصة جُذور!' :
                'مبروك! تم تفعيل حسابك بنجاح. مرحباً بك في منصة جُذور!'
            );
    }

    /**
     * Check if user profile is complete
     */
    private function isProfileComplete($user): bool
    {
        // For teachers, check required fields
        if ($user->user_type === 'teacher') {
            return !empty($user->school_name) &&
                !empty($user->grade_level) &&
                !empty($user->subjects_taught);
        }

        // For students, check basic info
        if ($user->user_type === 'student') {
            return !empty($user->grade_level);
        }

        // For users without type, profile is incomplete
        return !empty($user->user_type);
    }
}