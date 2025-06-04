<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class UserLogin extends Model
{
    use HasFactory;

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
        'logged_out_at',
    ];

    protected $casts = [
        'is_trusted' => 'boolean',
        'logged_in_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    /**
     * Get the user that owns the login record
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new login record for user
     */
    public static function createForUser(User $user, $isSocialLogin = false)
    {
        $agent = new Agent();

        return static::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => $agent->deviceType(),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'logged_in_at' => now(),
        ]);
    }

    /**
     * Get device icon based on device type
     */
    public function getDeviceIconAttribute()
    {
        $deviceType = strtolower($this->device_type ?? '');

        return match ($deviceType) {
            'desktop' => 'desktop',
            'tablet' => 'tablet',
            'mobile' => 'mobile',
            default => 'laptop'
        };
    }
}