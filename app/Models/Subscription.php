<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'lemon_squeezy_subscription_id',
        'lemon_squeezy_customer_id',
        'status',
        'plan_name',
        'plan_id',
        'current_period_start',
        'current_period_end',
        'cancelled_at',
        'trial_ends_at'
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'cancelled_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Check if subscription is active (not expired, even if cancelled)
     * Cancelled subscriptions remain active until period end
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->current_period_end->isFuture();
    }

    /**
     * Check if subscription is cancelled but still active until period end
     */
    public function isCancelled(): bool
    {
        return !is_null($this->cancelled_at);
    }

    /**
     * Check if subscription is cancelled and period has ended
     */
    public function isExpiredAfterCancellation(): bool
    {
        return $this->isCancelled() && $this->current_period_end->isPast();
    }

    /**
     * Get days remaining in current period
     */
    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->current_period_end, false));
    }

    /**
     * Boot method to add model event listeners
     */
    protected static function booted()
    {
        // Auto-sync when subscription is created
        static::created(function ($subscription) {
            $subscription->user->syncSubscriptionData();
        });

        // Auto-sync when subscription is updated
        static::updated(function ($subscription) {
            $subscription->user->syncSubscriptionData();
        });

        // Auto-sync when subscription is deleted
        static::deleted(function ($subscription) {
            $subscription->user->syncSubscriptionData();
        });
    }
}