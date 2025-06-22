<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyQuota extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'quiz_count',
        'ai_text_requests',
        'ai_quiz_requests'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getOrCreateCurrent($userId): self
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'year' => now()->year,
            'month' => now()->month,
        ]);
    }

    public function incrementQuizCount(): void
    {
        $this->increment('quiz_count');
    }

    public function incrementAiTextRequests(): void
    {
        $this->increment('ai_text_requests');
    }

    public function incrementAiQuizRequests(): void
    {
        $this->increment('ai_quiz_requests');
    }
}