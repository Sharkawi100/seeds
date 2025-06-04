## 3. Create `_project_docs/known_issues.md`

```markdown
# Known Issues & Solutions
Last Updated: June 2025

## Resolved Issues ✅

### 1. Vite Assets Not Loading in Production
**Issue**: CSS/JS not loading, development URLs being used
**Solution**: 
- Deleted `hot` file
- Updated blade templates to use production asset URLs
- Added `ASSET_URL` to production .env

### 2. OAuth Redirect Loop
**Issue**: After Google login, redirected back to login page
**Cause**: Missing `user_logins` table causing error
**Solution**: 
- Modified controller to skip device tracking
- Simplified login tracking to basic fields only

### 3. Database Connection Error
**Issue**: Using local database credentials in production
**Solution**: 
- Created proper production .env file
- Updated database credentials

## Current Limitations ⚠️

### 1. Limited Device Tracking
**Status**: Basic tracking only (IP, last login time)
**Missing**: Detailed device info, browser, platform
**Impact**: Low - not critical for current functionality
**Future**: Create `user_logins` table when needed

### 2. No Password History
**Status**: Users can reuse passwords
**Missing**: `password_histories` table
**Impact**: Low - password strength rules still enforced
**Future**: Add table and implement checking

### 3. IP Location Service Rate Limited
**Issue**: ipapi.co returns 429 errors (too many requests)
**Impact**: Location field shows as null
**Solution**: Not critical - removed from requirements

## Quick Fixes Applied

### Simplified OAuth Implementation
```php
// Original (requires user_logins table):
$loginRecord = UserLogin::createForUser($user, true);

// Simplified (works without table):
$user->update([
    'last_login_at' => now(),
    'last_login_ip' => request()->ip(),
    'login_count' => $user->login_count + 1
]);

`user_id` int unsigned NOT NULL  -- Not bigint

