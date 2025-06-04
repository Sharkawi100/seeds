# Deployment Guide - جُذور (Juzoor)

Last Updated: June 2025

## 🚀 Successful Production Deployment

### Hosting Environment

-   **Host**: Namecheap Shared Hosting
-   **Domain**: https://www.iseraj.com/roots/
-   **PHP Version**: 8.3.21
-   **Database**: MySQL
-   **No SSH Access**: Used PHP scripts for deployment tasks

### Directory Structure (Shared Hosting)

/home/username/
├── roots_app/ ← Laravel application (outside public_html)
│ ├── app/
│ ├── bootstrap/
│ ├── config/
│ ├── database/
│ ├── resources/
│ ├── routes/
│ ├── storage/
│ ├── vendor/
│ ├── .env ← Production environment file
│ └── composer.json
│
└── public_html/
└── roots/ ← Only public folder contents
├── index.php ← Modified to point to roots_app
├── .htaccess
├── build/ ← Compiled CSS/JS assets
│ ├── assets/
│ └── manifest.json
└── storage/ ← Symlink to roots_app/storage/app/public

### Key Deployment Steps

1. **Local Preparation**
    ```bash
    composer install --optimize-autoloader --no-dev
    npm run build
    ```

### OAuth Implementation Notes

#### Issue: Missing Tables in Production

During deployment, some security tables were not created due to foreign key constraints.

**Tables Successfully Created:**

-   ✅ `login_attempts` - For security tracking
-   ✅ Social login fields in `users` table

**Tables Skipped (Non-Critical):**

-   ❌ `user_logins` - Device tracking (nice to have)
-   ❌ `password_histories` - Password reuse prevention (future)

**Solution Applied:**
Modified `SocialAuthController` to skip UserLogin model:

```php
// Instead of:
// $loginRecord = \App\Models\UserLogin::createForUser($user, true);

// We use:
$user->update([
    'last_login_at' => now(),
    'last_login_ip' => request()->ip(),
    'login_count' => $user->login_count + 1
]);
```
