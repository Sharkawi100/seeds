<?php

namespace App\Services;

use App\Models\User;
use App\Models\LoginAttempt;
use App\Models\UserLogin;
use App\Mail\NewDeviceLogin;
use App\Mail\AccountLocked;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class LoginSecurityService
{
    const MAX_ATTEMPTS = 5;
    const LOCKOUT_MINUTES = 15;
    const WARNING_AFTER_ATTEMPTS = 3;

    /**
     * Process login attempt
     */
    public function processLoginAttempt(string $email, string $password): array
    {
        // Check if account is locked
        if (LoginAttempt::isLocked($email)) {
            $remainingMinutes = LoginAttempt::lockTimeRemaining($email);
            return [
                'success' => false,
                'locked' => true,
                'message' => "الحساب مغلق. يرجى المحاولة بعد {$remainingMinutes} دقيقة.",
                'remaining_minutes' => $remainingMinutes
            ];
        }

        // Get user
        $user = User::where('email', $email)->first();

        // Check credentials
        if (!$user || !Hash::check($password, $user->password)) {
            return $this->handleFailedAttempt($email);
        }

        // Check if user account is locked by admin
        if ($user->account_locked) {
            return [
                'success' => false,
                'message' => 'تم تعليق حسابك. يرجى التواصل مع الإدارة.'
            ];
        }

        // Successful login
        return $this->handleSuccessfulLogin($user);
    }

    /**
     * Handle failed login attempt
     */
    protected function handleFailedAttempt(string $email): array
    {
        // Record failed attempt
        LoginAttempt::create([
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'successful' => false,
            'attempted_at' => now()
        ]);

        // Count recent failures
        $failedCount = LoginAttempt::recentFailedCount($email, self::LOCKOUT_MINUTES);

        // Lock account if too many failures
        if ($failedCount >= self::MAX_ATTEMPTS) {
            LoginAttempt::lockAccount($email, self::LOCKOUT_MINUTES);

            // Send email notification if user exists
            $user = User::where('email', $email)->first();
            if ($user) {
                Mail::to($user)->queue(new AccountLocked($user, self::LOCKOUT_MINUTES));
            }

            return [
                'success' => false,
                'locked' => true,
                'message' => 'تم قفل الحساب بسبب محاولات متعددة فاشلة. يرجى المحاولة بعد ' . self::LOCKOUT_MINUTES . ' دقيقة.',
                'attempts_left' => 0
            ];
        }

        // Warning message after 3 attempts
        $attemptsLeft = self::MAX_ATTEMPTS - $failedCount;
        if ($failedCount >= self::WARNING_AFTER_ATTEMPTS) {
            return [
                'success' => false,
                'message' => "بيانات الدخول غير صحيحة. لديك {$attemptsLeft} محاولات متبقية.",
                'show_captcha' => true,
                'attempts_left' => $attemptsLeft
            ];
        }

        return [
            'success' => false,
            'message' => 'بيانات الدخول غير صحيحة.',
            'attempts_left' => $attemptsLeft
        ];
    }

    /**
     * Handle successful login
     */
    protected function handleSuccessfulLogin(User $user): array
    {
        // Record successful attempt
        LoginAttempt::create([
            'email' => $user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'successful' => true,
            'attempted_at' => now()
        ]);

        // Create login record
        $loginRecord = UserLogin::createForUser($user);

        // Check if new device
        if ($loginRecord->isNewDevice()) {
            Mail::to($user)->queue(new NewDeviceLogin($user, $loginRecord));
        }

        // Update user's last login info
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'login_count' => $user->login_count + 1
        ]);

        return [
            'success' => true,
            'user' => $user,
            'is_new_device' => $loginRecord->isNewDevice(),
            'force_password_change' => $user->force_password_change
        ];
    }

    /**
     * Get active sessions for user
     */
    public function getActiveSessions(User $user): \Illuminate\Support\Collection
    {
        return UserLogin::where('user_id', $user->id)
            ->whereNull('logged_out_at')
            ->latest('logged_in_at')
            ->get();
    }

    /**
     * Logout from all devices except current
     */
    public function logoutOtherDevices(User $user, ?int $currentLoginId = null): void
    {
        UserLogin::where('user_id', $user->id)
            ->whereNull('logged_out_at')
            ->when($currentLoginId, function ($query, $currentLoginId) {
                $query->where('id', '!=', $currentLoginId);
            })
            ->update(['logged_out_at' => now()]);
    }
}