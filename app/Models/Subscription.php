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
        'trial_ends_at'
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
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

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->current_period_end->isFuture();
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