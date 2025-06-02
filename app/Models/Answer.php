<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $fillable = ['question_id', 'result_id', 'selected_answer', 'is_correct'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}