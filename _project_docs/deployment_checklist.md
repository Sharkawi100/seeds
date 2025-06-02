# Deployment Checklist - جُذور (Juzoor)

Last Updated: December 2024

## Pre-Deployment Checklist

### 1. Environment Configuration

-   [ ] Copy `.env.example` to `.env.production`
-   [ ] Update production database credentials
-   [ ] Set `APP_ENV=production`
-   [ ] Set `APP_DEBUG=false`
-   [ ] Generate new `APP_KEY` for production
-   [ ] Update `APP_URL` to production domain
-   [ ] Set `SESSION_SECURE_COOKIE=true` (if using HTTPS)

### 2. OAuth Configuration

-   [ ] Update Google OAuth redirect URL to production domain
-   [ ] Update Facebook OAuth redirect URL
-   [ ] Add production domain to OAuth app settings
-   [ ] Update `.env` with production OAuth credentials

### 3. Database Preparation

-   [ ] Export local database structure (schema only)
-   [ ] Review migrations for production compatibility
-   [ ] Prepare seed data if needed

### 4. Security Checks

-   [ ] Remove all debug/test code
-   [ ] Ensure no sensitive data in repositories
-   [ ] Check file permissions (storage, bootstrap/cache)
-   [ ] Review CORS settings if using API

### 5. Performance Optimization

-   [ ] Run `composer install --no-dev`
-   [ ] Run `npm run build` for production assets
-   [ ] Enable Laravel caching:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
