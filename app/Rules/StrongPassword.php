<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\PasswordHistory;
use Illuminate\Support\Facades\Hash;

class StrongPassword implements ValidationRule
{
    protected ?int $userId;
    protected array $requirements = [];

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->requirements = [];

        // Check length
        if (strlen($value) < 8) {
            $this->requirements[] = 'يجب أن تكون كلمة المرور 8 أحرف على الأقل';
        }

        // Check uppercase
        if (!preg_match('/[A-Z]/', $value)) {
            $this->requirements[] = 'يجب أن تحتوي على حرف كبير واحد على الأقل';
        }

        // Check lowercase
        if (!preg_match('/[a-z]/', $value)) {
            $this->requirements[] = 'يجب أن تحتوي على حرف صغير واحد على الأقل';
        }

        // Check numbers
        if (!preg_match('/[0-9]/', $value)) {
            $this->requirements[] = 'يجب أن تحتوي على رقم واحد على الأقل';
        }

        // Check special characters
        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $value)) {
            $this->requirements[] = 'يجب أن تحتوي على رمز خاص واحد على الأقل (!@#$%^&*()_+-=)';
        }

        // Check common passwords
        if ($this->isCommonPassword($value)) {
            $this->requirements[] = 'كلمة المرور هذه شائعة جداً وغير آمنة';
        }

        // Check password history
        if ($this->userId && $this->isReusedPassword($value)) {
            $this->requirements[] = 'لا يمكن استخدام آخر 5 كلمات مرور سابقة';
        }

        if (!empty($this->requirements)) {
            $fail(implode('. ', $this->requirements));
        }
    }

    /**
     * Check if password is in common passwords list
     */
    protected function isCommonPassword(string $password): bool
    {
        $commonPasswords = [
            'password',
            '12345678',
            'password123',
            'admin123',
            'qwerty123',
            'arabic123',
            'juzoor123',
            '123456789'
        ];

        return in_array(strtolower($password), $commonPasswords);
    }

    /**
     * Check if password was used recently
     */
    protected function isReusedPassword(string $password): bool
    {
        if (!$this->userId) {
            return false;
        }

        $recentPasswords = PasswordHistory::where('user_id', $this->userId)
            ->latest()
            ->take(5)
            ->pluck('password');

        foreach ($recentPasswords as $oldPassword) {
            if (Hash::check($password, $oldPassword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get password strength score (0-4)
     */
    public static function getStrength(string $password): int
    {
        $strength = 0;

        if (strlen($password) >= 8)
            $strength++;
        if (preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password))
            $strength++;
        if (preg_match('/[0-9]/', $password))
            $strength++;
        if (preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password))
            $strength++;

        return $strength;
    }
}