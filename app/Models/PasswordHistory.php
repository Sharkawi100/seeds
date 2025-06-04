<?php
// File: app/Models/PasswordHistory.php
// Optional: Create this model if you want to track password history

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'password',
    ];

    /**
     * Get the user that owns the password history
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}