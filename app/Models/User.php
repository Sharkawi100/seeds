<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'google_id',
        'facebook_id',
        'avatar',
        'auth_provider',
        'user_type',
        'school_name',
        'grade_level',
    ];
    /**
     * Calculate profile completion percentage
     */
    public function getProfileCompletionAttribute(): int
    {
        $fields = [
            'name' => 20,
            'email' => 20,
            'avatar' => 15,
            'user_type' => 10,
        ];

        if ($this->user_type === 'teacher') {
            $fields['school_name'] = 20;
            $fields['bio'] = 15;
        } else {
            $fields['grade_level'] = 20;
            $fields['favorite_subject'] = 15;
        }

        $completed = 0;
        foreach ($fields as $field => $weight) {
            if (!empty($this->$field)) {
                $completed += $weight;
            }
        }

        return min($completed, 100);
    }

    /**
     * Get incomplete profile fields
     */
    public function getIncompleteFieldsAttribute(): array
    {
        $incomplete = [];

        if (empty($this->avatar))
            $incomplete[] = 'صورة الملف الشخصي';

        if ($this->user_type === 'teacher') {
            if (empty($this->school_name))
                $incomplete[] = 'اسم المدرسة';
            if (empty($this->bio))
                $incomplete[] = 'نبذة شخصية';
        } else {
            if (empty($this->grade_level))
                $incomplete[] = 'المرحلة الدراسية';
            if (empty($this->favorite_subject))
                $incomplete[] = 'المادة المفضلة';
        }

        return $incomplete;
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}