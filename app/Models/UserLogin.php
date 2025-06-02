<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Agent\Agent;

class UserLogin extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'location',
        'latitude',
        'longitude',
        'is_trusted',
        'logged_in_at',
        'logged_out_at'
    ];

    protected $casts = [
        'is_trusted' => 'boolean',
        'logged_in_at' => 'datetime',
        'logged_out_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Get the user that owns the login record
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create login record with device info
     */
    public static function createForUser(User $user, bool $trusted = false): self
    {
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());

        return self::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'location' => self::getLocationFromIp(request()->ip()),
            'is_trusted' => $trusted,
            'logged_in_at' => now()
        ]);
    }

    /**
     * Get location from IP (you'll need a service like ipapi.co)
     */
    protected static function getLocationFromIp(string $ip): ?string
    {
        // For development, return mock data
        if (app()->environment('local')) {
            return 'Tel Aviv, Israel';
        }

        try {
            // You can use a free service like ipapi.co
            $response = file_get_contents("https://ipapi.co/{$ip}/json/");
            $data = json_decode($response, true);

            if (isset($data['city']) && isset($data['country_name'])) {
                return "{$data['city']}, {$data['country_name']}";
            }
        } catch (\Exception $e) {
            // Log error but don't break login flow
            \Log::error('Failed to get location from IP: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Check if this is a new device
     */
    public function isNewDevice(): bool
    {
        $previousLogin = self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->where('device_type', $this->device_type)
                    ->where('browser', $this->browser)
                    ->where('platform', $this->platform);
            })
            ->exists();

        return !$previousLogin;
    }
}