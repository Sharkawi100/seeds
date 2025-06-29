<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAiReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'report_data',
        'student_count',
        'tokens_used',
        'generation_status'
    ];

    protected $casts = [
        'report_data' => 'array'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted(): bool
    {
        return $this->generation_status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->generation_status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->generation_status === 'failed';
    }
}