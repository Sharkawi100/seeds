<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Result extends Model
{
    protected $fillable = ['quiz_id', 'user_id', 'guest_token', 'guest_name', 'scores', 'total_score', 'expires_at'];

    protected $casts = [
        'scores' => 'array',
        'expires_at' => 'datetime'
    ];

    /**
     * Get final score for a student based on quiz scoring method
     */
    public static function getFinalScore($quizId, $userId)
    {
        $quiz = \App\Models\Quiz::find($quizId);
        if (!$quiz || !$userId)
            return null;

        $results = static::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->orderBy('attempt_number')
            ->get();

        if ($results->isEmpty())
            return null;

        switch ($quiz->scoring_method) {
            case 'latest':
                return $results->last()->total_score;
            case 'average':
                return round($results->avg('total_score'));
            case 'highest':
                return $results->max('total_score');
            case 'first_only':
                return $results->first()->total_score;
            default:
                return $results->last()->total_score;
        }
    }
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
    public function scopeNonDemo($query)
    {
        return $query->whereHas('quiz', function ($q) {
            $q->where('is_demo', false);
        });
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}