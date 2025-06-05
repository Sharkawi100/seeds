# Deployment Checklist - جُذور (Juzoor)

Last Updated: June 2025

## Pre-Deployment Checklist

### 1. Files to Create

-   [ ] Create `resources/views/admin/reports.blade.php`
-   [ ] Verify all role-specific views exist

### 2. Environment Configuration

-   [ ] Verify `.env` has all required fields
-   [ ] Set `APP_ENV=production`
-   [ ] Set `APP_DEBUG=false`
-   [ ] Update OAuth redirect URLs if needed

### 3. Database Updates

-   [ ] Verify new columns exist:
    -   subjects_taught, experience_years, teacher_data
    -   parent_email, student_school_id, student_data
    -   is_approved
-   [ ] Check indexes are created

### 4. Route Verification

-   [ ] Clear route cache: `php artisan route:clear`
-   [ ] Verify teacher/student routes work
-   [ ] Test role selection at /login and /register

### 5. Files to Upload via WinSCP

```
/app/Http/Controllers/Auth/Teacher/
/app/Http/Controllers/Auth/Student/
/resources/views/auth/role-selection.blade.php
/resources/views/auth/teacher/
/resources/views/auth/student/
/resources/views/admin/reports.blade.php
/routes/web.php
/routes/auth.php
```

### 6. Post-Upload Commands

```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

### 7. Test Checklist

-   [ ] Teacher registration → pending approval flow
-   [ ] Student registration → auto-approval
-   [ ] Admin can see pending teachers
-   [ ] Social login defaults to student
-   [ ] PIN login for students works

## Common Issues

-   If routes not found: Clear route cache
-   If views not found: Clear view cache
-   If database errors: Check column names match exactly
