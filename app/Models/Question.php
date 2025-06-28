<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id',
        'passage',
        'passage_title',
        'question',
        'root_type',
        'depth_level',
        'options',
        'correct_answer',
        'explanation'  // ✅ Added missing field
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'depth_level' => 'integer'
    ];

    /**
     * Juzoor educational roots mapping
     *
     * @var array
     */
    const ROOTS = [
        'jawhar' => [
            'name_ar' => 'جَوهر',
            'name_en' => 'Essence',
            'description_ar' => 'ما هو؟ - التعريفات والفهم الأساسي',
            'description_en' => 'What is it? - Definitions and core understanding',
            'color' => 'emerald'
        ],
        'zihn' => [
            'name_ar' => 'ذِهن',
            'name_en' => 'Mind',
            'description_ar' => 'كيف يعمل؟ - التحليل والتفكير النقدي',
            'description_en' => 'How does it work? - Analysis and critical thinking',
            'color' => 'blue'
        ],
        'waslat' => [
            'name_ar' => 'وَصلات',
            'name_en' => 'Connections',
            'description_ar' => 'كيف يرتبط؟ - العلاقات والتكامل',
            'description_en' => 'How does it connect? - Relationships and integration',
            'color' => 'purple'
        ],
        'roaya' => [
            'name_ar' => 'رُؤية',
            'name_en' => 'Vision',
            'description_ar' => 'كيف نستخدمه؟ - التطبيق والابتكار',
            'description_en' => 'How can we use it? - Application and innovation',
            'color' => 'orange'
        ]
    ];

    /**
     * Depth levels mapping
     *
     * @var array
     */
    const DEPTH_LEVELS = [
        1 => [
            'name_ar' => 'مستوى سطحي',
            'name_en' => 'Surface Level',
            'description_ar' => 'فهم أساسي ومباشر',
            'color' => 'green'
        ],
        2 => [
            'name_ar' => 'مستوى متوسط',
            'name_en' => 'Medium Level',
            'description_ar' => 'فهم متوسط ومعمق',
            'color' => 'yellow'
        ],
        3 => [
            'name_ar' => 'مستوى عميق',
            'name_en' => 'Deep Level',
            'description_ar' => 'فهم عميق ومعقد',
            'color' => 'red'
        ]
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the root type information in Arabic
     *
     * @return string
     */
    public function getRootNameAttribute(): string
    {
        return self::ROOTS[$this->root_type]['name_ar'] ?? $this->root_type;
    }

    /**
     * Get the root type description in Arabic
     *
     * @return string
     */
    public function getRootDescriptionAttribute(): string
    {
        return self::ROOTS[$this->root_type]['description_ar'] ?? '';
    }

    /**
     * Get the root type color
     *
     * @return string
     */
    public function getRootColorAttribute(): string
    {
        return self::ROOTS[$this->root_type]['color'] ?? 'gray';
    }

    /**
     * Get the depth level name in Arabic
     *
     * @return string
     */
    public function getDepthNameAttribute(): string
    {
        return self::DEPTH_LEVELS[$this->depth_level]['name_ar'] ?? "مستوى {$this->depth_level}";
    }

    /**
     * Get the depth level color
     *
     * @return string
     */
    public function getDepthColorAttribute(): string
    {
        return self::DEPTH_LEVELS[$this->depth_level]['color'] ?? 'gray';
    }

    /**
     * Check if question has a passage
     *
     * @return bool
     */
    public function getHasPassageAttribute(): bool
    {
        return !empty($this->passage);
    }

    /**
     * Check if question has an explanation
     *
     * @return bool
     */
    public function getHasExplanationAttribute(): bool
    {
        return !empty($this->explanation);
    }

    /**
     * Get the number of options
     *
     * @return int
     */
    public function getOptionsCountAttribute(): int
    {
        return is_array($this->options) ? count($this->options) : 0;
    }

    /**
     * Get shuffled options for quiz taking
     *
     * @return array
     */
    public function getShuffledOptionsAttribute(): array
    {
        if (!is_array($this->options)) {
            return [];
        }

        $options = collect($this->options)->shuffle();
        return $options->values()->all();
    }

    /**
     * Get the correct answer index in original options
     *
     * @return int|null
     */
    public function getCorrectAnswerIndexAttribute(): ?int
    {
        if (!is_array($this->options)) {
            return null;
        }

        return array_search($this->correct_answer, $this->options);
    }

    /**
     * Validate question data
     *
     * @return bool
     */
    public function isValid(): bool
    {
        // Check required fields
        if (empty($this->question) || empty($this->root_type) || empty($this->depth_level)) {
            return false;
        }

        // Check root type validity
        if (!array_key_exists($this->root_type, self::ROOTS)) {
            return false;
        }

        // Check depth level validity
        if (!array_key_exists($this->depth_level, self::DEPTH_LEVELS)) {
            return false;
        }

        // Check options
        if (!is_array($this->options) || count($this->options) < 2 || count($this->options) > 4) {
            return false;
        }

        // Check correct answer exists in options
        if (!in_array($this->correct_answer, $this->options)) {
            return false;
        }

        return true;
    }

    /**
     * Get question difficulty score (1-10)
     *
     * @return int
     */
    public function getDifficultyScoreAttribute(): int
    {
        $baseScore = match ($this->root_type) {
            'jawhar' => 2,  // Essence - easiest
            'zihn' => 5,    // Mind - medium
            'waslat' => 7,  // Connections - harder
            'roaya' => 8,   // Vision - hardest
            default => 5
        };

        $depthMultiplier = match ($this->depth_level) {
            1 => 1.0,
            2 => 1.5,
            3 => 2.0,
            default => 1.0
        };

        return min(10, max(1, round($baseScore * $depthMultiplier)));
    }

    /**
     * Scope for filtering by root type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $rootType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRoot($query, string $rootType)
    {
        return $query->where('root_type', $rootType);
    }

    /**
     * Scope for filtering by depth level
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $depthLevel
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDepth($query, int $depthLevel)
    {
        return $query->where('depth_level', $depthLevel);
    }

    /**
     * Scope for questions with passages
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPassage($query)
    {
        return $query->whereNotNull('passage');
    }

    /**
     * Scope for questions with explanations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithExplanation($query)
    {
        return $query->whereNotNull('explanation');
    }

    /**
     * Get question statistics for analytics
     *
     * @return array
     */
    public function getStatsAttribute(): array
    {
        $totalAnswers = $this->answers()->count();
        $correctAnswers = $this->answers()->where('is_correct', true)->count();

        return [
            'total_answers' => $totalAnswers,
            'correct_answers' => $correctAnswers,
            'accuracy_rate' => $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 1) : 0,
            'difficulty_score' => $this->difficulty_score
        ];
    }

    /**
     * Format question for API response
     *
     * @return array
     */
    public function toQuizFormat(): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'options' => $this->options,
            'root_type' => $this->root_type,
            'root_name' => $this->root_name,
            'depth_level' => $this->depth_level,
            'depth_name' => $this->depth_name,
            'has_passage' => $this->has_passage,
            'passage' => $this->passage,
            'passage_title' => $this->passage_title,
            'difficulty_score' => $this->difficulty_score
        ];
    }

    /**
     * Format question for results with correct answer
     *
     * @return array
     */
    public function toResultFormat(): array
    {
        return array_merge($this->toQuizFormat(), [
            'correct_answer' => $this->correct_answer,
            'correct_answer_index' => $this->correct_answer_index,
            'explanation' => $this->explanation,
            'has_explanation' => $this->has_explanation,
            'stats' => $this->stats
        ]);
    }

    /**
     * Clone question to another quiz
     *
     * @param Quiz $targetQuiz
     * @return Question
     */
    public function cloneTo(Quiz $targetQuiz): Question
    {
        $clone = $this->replicate();
        $clone->quiz_id = $targetQuiz->id;
        $clone->save();

        return $clone;
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Validate before saving
        static::saving(function ($question) {
            // Ensure options is array
            if (!is_array($question->options)) {
                $question->options = [];
            }

            // Clean empty options
            $question->options = array_filter($question->options, function ($option) {
                return !empty(trim($option));
            });

            // Ensure correct answer exists in options
            if (!empty($question->correct_answer) && !in_array($question->correct_answer, $question->options)) {
                throw new \Exception('الإجابة الصحيحة يجب أن تكون من ضمن الخيارات المتاحة.');
            }
        });
    }
}