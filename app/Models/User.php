<?php
// File: app/Models/User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'is_admin',
        'is_active',
        'is_approved',
        'is_school',
        'school_name',
        'grade_level',
        'subjects_taught',
        'experience_years',
        'teacher_data',
        'parent_email',
        'student_school_id',
        'student_data',
        'google_id',
        'avatar',
        'auth_provider',
        'last_login_at',
        'last_login_ip',
        'login_count',
        'deactivated_at',
        'deactivation_reason',
        'deactivated_by',
        // Add subscription fields
        'subscription_active',
        'subscription_expires_at',
        'subscription_plan',
        'subscription_status',
        'lemon_squeezy_customer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'is_school' => 'boolean',
            'last_login_at' => 'datetime',
            'deactivated_at' => 'datetime',
            'login_count' => 'integer',
            'grade_level' => 'integer',
            'subscription_expires_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Get the user who deactivated this account
     */
    public function deactivatedBy()
    {
        return $this->belongsTo(User::class, 'deactivated_by');
    }

    /**
     * Deactivate the user account
     */
    public function deactivate($reason = null, $deactivatedBy = null)
    {
        $this->update([
            'is_active' => false,
            'deactivated_at' => now(),
            'deactivation_reason' => $reason,
            'deactivated_by' => $deactivatedBy,
        ]);

        // Logout user sessions
        DB::table('sessions')->where('user_id', $this->id)->delete();
    }

    /**
     * Activate the user account
     */
    public function activate()
    {
        $this->update([
            'is_active' => true,
            'deactivated_at' => null,
            'deactivation_reason' => null,
            'deactivated_by' => null,
        ]);
    }

    /**
     * Get display role name
     */
    public function getRoleDisplayAttribute()
    {
        if ($this->is_admin) {
            return 'مدير';
        }

        return match ($this->user_type) {
            'teacher' => 'معلم',
            'student' => 'طالب',
            default => 'مستخدم',
        };
    }

    /**
     * Check if user can be impersonated
     */
    public function canBeImpersonated()
    {
        return $this->is_active
            && Auth::check()
            && $this->id !== Auth::id()
            && !$this->is_admin; // Prevent impersonating other admins
    }

    /**
     * Scope to only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to only inactive users
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function canManageQuizzes()
    {
        return $this->is_admin || $this->user_type === 'teacher';
    }

    public function isTeacherOrAdmin()
    {
        return $this->is_admin || $this->user_type === 'teacher';
    }

    public function scopeTeachers($query)
    {
        return $query->where('user_type', 'teacher');
    }

    public function scopeStudents($query)
    {
        return $query->where('user_type', 'student');
    }
    // Add to app/Models/User.php

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    public function monthlyQuota()
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        return $this->hasOne(MonthlyQuota::class)
            ->where('year', $currentYear)
            ->where('month', $currentMonth);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription_active &&
            ($this->subscription_expires_at === null ||
                ($this->subscription_expires_at && $this->subscription_expires_at->isFuture()));
    }

    public function canUseAI(): bool
    {
        return $this->hasActiveSubscription();
    }

    public function getCurrentQuotaLimits(): array
    {
        if (!$this->hasActiveSubscription()) {
            return [
                'monthly_quiz_limit' => 5, // Free users get 5 quizzes
                'monthly_ai_text_limit' => 0,
                'monthly_ai_quiz_limit' => 0
            ];
        }

        $plan = $this->subscription?->subscriptionPlan;
        return [
            'monthly_quiz_limit' => $plan?->monthly_quiz_limit ?? 40,
            'monthly_ai_text_limit' => $plan?->monthly_ai_text_limit ?? 100,
            'monthly_ai_quiz_limit' => $plan?->monthly_ai_quiz_limit ?? 100
        ];
    }

    public function hasReachedQuizLimit(): bool
    {
        $quota = $this->monthlyQuota;
        $limits = $this->getCurrentQuotaLimits();

        return $quota && $quota->quiz_count >= $limits['monthly_quiz_limit'];
    }
    /**
     * Sync subscription data between users and subscriptions tables
     */
    public function syncSubscriptionData()
    {
        $subscription = $this->subscription()->where('status', 'active')->first();

        if ($subscription) {
            $this->update([
                'subscription_active' => true,
                'subscription_expires_at' => $subscription->current_period_end,
                'subscription_plan' => $subscription->plan_name,
                'subscription_status' => $subscription->status,
            ]);
        } else {
            $this->update([
                'subscription_active' => false,
                'subscription_expires_at' => null,
                'subscription_plan' => null,
                'subscription_status' => null,
            ]);
        }

        return $this;
    }
}