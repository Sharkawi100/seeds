@echo off
setlocal enabledelayedexpansion

echo ========================================
echo      COMPLETE SYNC WORKFLOW
echo ========================================
echo.
echo This will:
echo 1. Sync from LIVE to LOCAL
echo 2. Commit to Git
echo 3. Push to GitHub
echo.

set /p confirm="Continue? (yes/no): "
if /i "!confirm!" neq "yes" (
    echo Cancelled.
    pause
    exit /b 0
)

:: Step 1: Sync from live
echo.
echo STEP 1: Syncing from live...
echo ========================================
call sync-from-live.bat

:: Step 2: Check git changes
echo.
echo STEP 2: Checking Git status...
echo ========================================
git status --short

:: Step 3: Commit and push
echo.
echo STEP 3: Pushing to GitHub...
echo ========================================
set /p commit_msg="Enter commit message: "
if "!commit_msg!"=="" (
    set commit_msg=Sync from live - %date% %time%
)

git add -A
git commit -m "!commit_msg!"
git push origin main

echo.
echo ========================================
echo    Workflow Complete!
echo ========================================
pause