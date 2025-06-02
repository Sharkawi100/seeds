@echo off
setlocal enabledelayedexpansion

echo ========================================
echo           PUSH TO GITHUB
echo ========================================
echo.

:: Check if git is installed
git --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Git is not installed or not in PATH
    pause
    exit /b 1
)

:: Check git status
echo Checking git status...
git status --porcelain > temp_status.txt
set /p status=<temp_status.txt
del temp_status.txt

if "!status!"=="" (
    echo No changes to commit!
    pause
    exit /b 0
)

:: Show changes
echo.
echo Changes to be committed:
echo ----------------------------------------
git status --short
echo ----------------------------------------
echo.

:: Get commit message
set /p commit_msg="Enter commit message (or press Enter for auto): "
if "!commit_msg!"=="" (
    set commit_msg=Update from %date% %time%
)

:: Commit and push
echo.
echo Committing changes...
git add -A
git commit -m "!commit_msg!"

echo.
echo Pushing to GitHub...
git push origin main

if errorlevel 1 (
    echo.
    echo ERROR: Push failed! Check your internet connection and GitHub credentials.
    pause
    exit /b 1
)

echo.
echo ========================================
echo     Successfully pushed to GitHub!
echo ========================================
pause