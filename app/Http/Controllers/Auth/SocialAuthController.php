<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoginSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;



class SocialAuthController extends Controller
{
    protected LoginSecurityService $securityService;

    public function __construct(LoginSecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Redirect to provider
     */
    public function redirect(string $provider)
    {
        $this->validateProvider($provider);

        // Store intended URL
        session(['url.intended' => url()->previous()]);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle provider callback
     */
    public function callback(string $provider)
    {
        $this->validateProvider($provider);

        try {
            // Debug point 1
            Log::info('Social callback started for: ' . $provider);

            $socialUser = Socialite::driver($provider)->user();

            // Debug point 2
            Log::info('Social user retrieved', [
                'id' => $socialUser->getId(),
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName()
            ]);

            // Find or create user
            $user = $this->findOrCreateUser($socialUser, $provider);

            // Debug point 3
            Log::info('User found/created', ['user_id' => $user->id]);

            // Update last login info (ONLY ONCE)
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
                'login_count' => $user->login_count + 1
            ]);

            // Login user
            Auth::login($user, true);

            // Debug point 4
            Log::info('User logged in', ['auth_check' => Auth::check()]);

            // Flash success message
            session()->flash('success', 'تم تسجيل الدخول بنجاح عبر ' . $this->getProviderName($provider));

            // Redirect based on user type
            $route = match ($user->user_type) {
                'admin' => 'admin.dashboard',
                'teacher' => 'dashboard',
                default => 'dashboard'
            };

            return redirect()->intended(route($route));

        } catch (\Exception $e) {
            Log::error('Social login failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('login')
                ->with('error', 'فشل تسجيل الدخول عبر ' . $this->getProviderName($provider) . '. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Find or create user from social data
     */
    protected function findOrCreateUser($socialUser, string $provider): User
    {
        // First, try to find by social ID
        $user = User::where($provider . '_id', $socialUser->getId())->first();

        if ($user) {
            return $user;
        }

        // Try to find by email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Link social account to existing user
            $user->update([
                $provider . '_id' => $socialUser->getId(),
                'avatar' => $user->avatar ?: $socialUser->getAvatar(),
            ]);

            return $user;
        }

        // Create new user
        return User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            $provider . '_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
            'auth_provider' => $provider,
            'password' => Hash::make(Str::random(24)), // Random password for social users
            'email_verified_at' => now(), // Auto-verify social users
            'user_type' => 'student', // Default to student
        ]);
    }

    /**
     * Validate provider
     */
    protected function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['google'])) {
            abort(404);
        }
    }

    /**
     * Get provider display name in Arabic
     */
    protected function getProviderName(string $provider): string
    {
        return match ($provider) {
            'google' => 'جوجل',

            default => $provider
        };
    }
}