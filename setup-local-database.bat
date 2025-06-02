@echo off
echo ========================================
echo     SETUP LOCAL DATABASE
echo ========================================
echo.

:: Check if XAMPP MySQL is running
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    echo ERROR: MySQL is not running!
    echo Please start XAMPP and run MySQL
    pause
    exit /b 1
)

:: Create database
echo Creating database juzoor_quiz_local...
"C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS juzoor_quiz_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if errorlevel 1 (
    echo ERROR: Failed to create database
    pause
    exit /b 1
)

echo Database created successfully!
echo.

:: Check for backup file
if exist "backups\db\latest_live_backup.sql" (
    echo Found backup file: backups\db\latest_live_backup.sql
    set /p import="Import this backup? (yes/no): "
    if /i "!import!"=="yes" (
        echo Importing database...
        "C:\xampp\mysql\bin\mysql.exe" -u root juzoor_quiz_local < "backups\db\latest_live_backup.sql"
        echo Import complete!
    )
) else (
    echo.
    echo No backup found. To import live database:
    echo 1. Export from cPanel phpMyAdmin
    echo 2. Save as: backups\db\latest_live_backup.sql
    echo 3. Run this script again
)

echo.
echo Now running migrations to ensure database is up to date...
cd C:\xampp\htdocs\juzoor-quiz
php artisan migrate

echo.
echo ========================================
echo Database setup complete!
echo ========================================
pause