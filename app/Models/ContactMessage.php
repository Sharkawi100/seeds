<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'category_id',
        'subscription_id',
        'subject',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the subscription associated with this message (for cancellation reasons)
     */
    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription::class);
    }

    /**
     * Get the user who sent this message
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'email', 'email');
    }

    /**
     * Check if this is a subscription cancellation message
     */
    public function isCancellationMessage()
    {
        return !is_null($this->subscription_id);
    }
}