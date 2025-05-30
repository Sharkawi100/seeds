@echo off
setlocal enabledelayedexpansion

echo ========================================
echo         DATABASE BACKUP
echo ========================================
echo.

:: Create backups directory if not exists
if not exist "backups" mkdir backups
if not exist "backups\db" mkdir backups\db

:: Set backup filename with timestamp
set backup_date=%date:~-4,4%-%date:~-10,2%-%date:~-7,2%_%time:~0,2%-%time:~3,2%
set backup_date=!backup_date: =0!
set local_backup=backups\db\local_backup_!backup_date!.sql

echo Backing up local database...
"C:\xampp\mysql\bin\mysqldump.exe" -u root juzoor_quiz_local > "!local_backup!"

if errorlevel 1 (
    echo ERROR: Local backup failed!
    echo Make sure XAMPP MySQL is running
    pause
    exit /b 1
)

echo Local backup saved to: !local_backup!
echo.
echo To backup LIVE database:
echo 1. Login to cPanel
echo 2. Go to phpMyAdmin
echo 3. Select database: jqfujdmy_iseraj_roots
echo 4. Export as SQL
echo 5. Save as: backups\db\live_backup_!backup_date!.sql
echo.
pause