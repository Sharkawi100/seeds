<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'lemon_squeezy_variant_id',
        'monthly_quiz_limit',
        'monthly_ai_text_limit',
        'monthly_ai_quiz_limit',
        'price_monthly',
        'is_active'
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}