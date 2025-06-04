# Troubleshooting Guide

Last Updated: June 2025

## Common Issues & Solutions

### Authentication Issues

#### "Account Locked" Message

**Cause**: Too many failed login attempts
**Solution**:

-   Wait 15 minutes for automatic unlock
-   Admin can unlock via admin panel
-   Check login_attempts table

#### Google Login Not Working

**Cause**: OAuth redirect URL mismatch
**Solution**:

1. Check .env GOOGLE_REDIRECT_URL
2. Update Google Cloud Console
3. Clear config cache

#### Password Reset Not Working

**Cause**: Email configuration
**Solution**:

-   Check MAIL\_\* settings in .env
-   Verify SMTP credentials
-   Check spam folder

### Deployment Issues

#### 500 Server Error

**Causes & Solutions**:

1. **Wrong database credentials**
    - Check .env file
    - Verify DB_PASSWORD is set
2. **Missing vendor folder**

    - Run `composer install`
    - Upload vendor folder

3. **Permission issues**
    - Set storage/ to 755
    - Set bootstrap/cache to 755

#### Missing Styles/CSS

**Cause**: Vite development mode in production
**Solution**:

1. Delete `/public/hot` file
2. Run `npm run build` locally
3. Upload `/public/build/` folder
4. Update blade templates if using @vite

#### "Method Not Allowed" Error

**Cause**: Route caching issues
**Solution**:

```bash
php artisan route:clear
php artisan cache:clear
```

Session Not Persisting
Cause: Session configuration
Solution:

Check SESSION_DOMAIN in .env
Ensure cookies are enabled
Verify session files are writable

Database Issues
Migration Errors
Foreign Key Constraint:

Check table types (InnoDB)
Verify referenced columns exist
Match data types exactly

"Access Denied" Error

Verify database credentials
Check user permissions
Ensure database exists

Asset Issues
JavaScript Not Loading

Check browser console for errors
Verify file paths in blade templates
Clear browser cache

Fonts Not Displaying

Check font file paths
Verify .htaccess allows font files
Add CORS headers if needed

Email Issues
Emails Not Sending

Check MAIL\_\* configuration
Verify SMTP credentials
Check firewall/port blocking
Enable "less secure apps" if using Gmail

Emails Going to Spam

Set proper FROM address
Add SPF/DKIM records
Use transactional email service
