<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'successful',
        'attempted_at',
        'locked_until'
    ];

    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    /**
     * Check if account is currently locked
     */
    public static function isLocked(string $email): bool
    {
        $lastAttempt = self::where('email', $email)
            ->whereNotNull('locked_until')
            ->latest('attempted_at')
            ->first();

        if (!$lastAttempt) {
            return false;
        }

        return $lastAttempt->locked_until->isFuture();
    }

    /**
     * Get remaining lockout time in minutes
     */
    public static function lockTimeRemaining(string $email): int
    {
        $lastAttempt = self::where('email', $email)
            ->whereNotNull('locked_until')
            ->latest('attempted_at')
            ->first();

        if (!$lastAttempt || $lastAttempt->locked_until->isPast()) {
            return 0;
        }

        return $lastAttempt->locked_until->diffInMinutes(now());
    }

    /**
     * Count recent failed attempts
     */
    public static function recentFailedCount(string $email, int $minutes = 15): int
    {
        return self::where('email', $email)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Lock account after too many failed attempts
     */
    public static function lockAccount(string $email, int $minutes = 15): void
    {
        self::create([
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'successful' => false,
            'attempted_at' => now(),
            'locked_until' => now()->addMinutes($minutes)
        ]);
    }

    /**
     * Clear old attempts (cleanup)
     */
    public static function clearOldAttempts(int $days = 30): int
    {
        return self::where('attempted_at', '<', now()->subDays($days))->delete();
    }
}