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
        'phone',
        'birth_date',
        'google_id',
        'facebook_id',
        'avatar',
        'bio',
        'auth_provider',
        'user_type',
        'is_admin',
        'is_school',
        'school_name',
        'grade_level',
        'favorite_subject',
        'last_login_at',
        'achievements',
        'preferences',
        'privacy_settings',
        'password_changed_at',
        'last_login_ip',
        'login_count',
    ];

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
            'is_school' => 'boolean',
            'birth_date' => 'date',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            // Cast LONGTEXT fields as arrays
            'achievements' => 'array',
            'preferences' => 'array',
            'privacy_settings' => 'array',
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

    public function userLogins()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }
}