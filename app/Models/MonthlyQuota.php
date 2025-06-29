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
    /**
     * Increment AI report requests count
     */
    public function incrementAiReportRequests(): void
    {
        $this->increment('ai_report_requests');
    }

    /**
     * Check if user has remaining quota for AI reports
     */
    public function hasRemainingAiReportQuota(): bool
    {
        $user = User::find($this->user_id);
        $limits = $user->getCurrentQuotaLimits();

        $totalUsed = $this->quiz_count + $this->ai_text_requests + $this->ai_quiz_requests + $this->ai_report_requests;

        return $totalUsed < $limits['monthly_quiz_limit'];
    }

    /**
     * Get remaining quota count
     */
    public function getRemainingQuota(): int
    {
        $user = User::find($this->user_id);
        $limits = $user->getCurrentQuotaLimits();

        $totalUsed = $this->quiz_count + $this->ai_text_requests + $this->ai_quiz_requests + $this->ai_report_requests;

        return max(0, $limits['monthly_quiz_limit'] - $totalUsed);
    }
}