<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'subject_id', // Changed from 'subject' to 'subject_id'
        'grade_level',
        'pin',
        'is_active',
        'is_demo',
        'is_public',
        'shuffle_questions',
        'shuffle_answers',
        'group_by_root',
        'maintain_difficulty_order',
        'time_limit',
        'passing_score',
        'show_results',
        'has_submissions',
        'expires_at',
        'settings',
        'passage_data'
    ];

    /**
     * Boot method for generating PIN
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quiz) {
            if (empty($quiz->pin)) {
                do {
                    $pin = strtoupper(Str::random(6));
                } while (self::where('pin', $pin)->exists());

                $quiz->pin = $pin;
            }
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
        'passage_data' => 'array',
        'is_active' => 'boolean',
        'is_demo' => 'boolean',
        'is_public' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
        'group_by_root' => 'boolean',
        'maintain_difficulty_order' => 'boolean',
        'show_results' => 'boolean',
        'has_submissions' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the quiz.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject that belongs to the quiz.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the results for the quiz.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Get the passage from the first question
     *
     * @return string|null
     */
    public function getPassageAttribute(): ?string
    {
        $firstQuestion = $this->questions->where('passage', '!=', null)->first();
        return $firstQuestion ? $firstQuestion->passage : null;
    }

    /**
     * Get the passage title from the first question
     *
     * @return string|null
     */
    public function getPassageTitleAttribute(): ?string
    {
        $firstQuestion = $this->questions->where('passage_title', '!=', null)->first();
        return $firstQuestion ? $firstQuestion->passage_title : null;
    }

    /**
     * Check if quiz has a passage
     *
     * @return bool
     */
    public function getHasPassageAttribute(): bool
    {
        return !empty($this->passage);
    }

    /**
     * Get questions count grouped by root type
     *
     * @return array
     */
    public function getQuestionsByRootAttribute(): array
    {
        return $this->questions->groupBy('root_type')->map->count()->toArray();
    }

    /**
     * Get questions count grouped by depth level
     *
     * @return array
     */
    public function getQuestionsByDepthAttribute(): array
    {
        return $this->questions->groupBy('depth_level')->map->count()->toArray();
    }

    /**
     * Get the subject name (using the relationship)
     *
     * @return string
     */
    public function getSubjectNameAttribute(): string
    {
        if ($this->subject) {
            return $this->subject->name;
        }

        // Fallback for old data that might still use direct subject field
        return $this->attributes['subject'] ?? 'غير محدد';
    }

    /**
     * Get total questions count
     *
     * @return int
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->questions->count();
    }

    /**
     * Check if quiz is ready (has questions)
     *
     * @return bool
     */
    public function getIsReadyAttribute(): bool
    {
        return $this->total_questions > 0;
    }

    /**
     * Generate a unique PIN for the quiz
     *
     * @return string
     */
    public function generatePin(): string
    {
        do {
            $pin = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (static::where('pin', $pin)->exists());

        $this->pin = $pin;
        $this->save();

        return $pin;
    }

    /**
     * Check if quiz is accessible
     *
     * @return bool
     */
    public function isAccessible(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Scope for active quizzes
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope for demo quizzes
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDemo($query)
    {
        return $query->where('is_demo', true);
    }

    /**
     * Scope for public quizzes
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}