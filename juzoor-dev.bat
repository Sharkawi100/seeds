@echo off
:MENU
cls
echo.
echo  ╔════════════════════════════════════════════════════╗
echo  ║                                                    ║
echo  ║          جُذور DEVELOPMENT CONTROL PANEL           ║
echo  ║                                                    ║
echo  ╚════════════════════════════════════════════════════╝
echo.
echo  Project: Juzoor Quiz Platform
echo  Local:   http://localhost/juzoor-quiz
echo  Live:    https://iseraj.com/roots
echo  GitHub:  https://github.com/Sharkawi100/seeds
echo.
echo  ┌─────────────────────────────────────────────────────┐
echo  │                   MAIN MENU                         │
echo  └─────────────────────────────────────────────────────┘
echo.
echo   [1] 🚀 Start Development (Sync + Start XAMPP)
echo   [2] 📥 Sync from Live Server
echo   [3] 📤 Push to GitHub
echo   [4] 🌐 Deploy to Live Server
echo   [5] 🗄️  Database Management
echo   [6] 🔧 Utilities
echo   [7] 📖 View Workflow Guide
echo   [8] ❌ Exit
echo.
set /p choice="Select option (1-8): "

if "%choice%"=="1" goto START_DEV
if "%choice%"=="2" goto SYNC_LIVE
if "%choice%"=="3" goto PUSH_GIT
if "%choice%"=="4" goto DEPLOY_LIVE
if "%choice%"=="5" goto DATABASE_MENU
if "%choice%"=="6" goto UTILITIES_MENU
if "%choice%"=="7" goto SHOW_GUIDE
if "%choice%"=="8" exit /b 0

echo Invalid choice! Please try again.
pause
goto MENU

:START_DEV
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║             STARTING DEVELOPMENT                   ║
echo  ╚════════════════════════════════════════════════════╝
echo.
echo  [1] Starting XAMPP...
start "" "C:\xampp\xampp-control.exe"
timeout /t 3 >nul

echo  [2] Syncing from live server...
call sync-from-live.bat

echo.
echo  [3] Opening VSCode...
code C:\xampp\htdocs\juzoor-quiz

echo.
echo  [4] Ready! Your local site: http://localhost/juzoor-quiz
echo.
echo  Development environment is ready!
pause
goto MENU

:SYNC_LIVE
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║            SYNC FROM LIVE SERVER                   ║
echo  ╚════════════════════════════════════════════════════╝
call sync-from-live.bat
goto MENU

:PUSH_GIT
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║              PUSH TO GITHUB                        ║
echo  ╚════════════════════════════════════════════════════╝
call push-to-github.bat
goto MENU

:DEPLOY_LIVE
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║            DEPLOY TO LIVE SERVER                   ║
echo  ╚════════════════════════════════════════════════════╝
echo.
echo  ⚠️  DEPLOYMENT CHECKLIST:
echo  ────────────────────────
echo  ✓ Tested all features on localhost?
echo  ✓ Committed changes to GitHub?
echo  ✓ Backed up the database?
echo.
set /p ready="Ready to deploy? (yes/no): "
if /i "%ready%"=="yes" (
    call deploy-to-live.bat
    echo.
    echo Don't forget to clear cache on live!
    pause
)
goto MENU

:DATABASE_MENU
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║             DATABASE MANAGEMENT                    ║
echo  ╚════════════════════════════════════════════════════╝
echo.
echo   [1] 🔧 Setup Local Database
echo   [2] 💾 Backup Databases
echo   [3] 📥 Import Live Database
echo   [4] 🔄 Export Local Database
echo   [5] ← Back to Main Menu
echo.
set /p dbchoice="Select option (1-5): "

if "%dbchoice%"=="1" call setup-local-database.bat
if "%dbchoice%"=="2" call backup-database.bat
if "%dbchoice%"=="3" goto IMPORT_DB
if "%dbchoice%"=="4" goto EXPORT_DB
if "%dbchoice%"=="5" goto MENU

pause
goto DATABASE_MENU

:IMPORT_DB
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║            IMPORT LIVE DATABASE                    ║
echo  ╚════════════════════════════════════════════════════╝
echo.
echo  1. Login to cPanel at https://premium287.web-hosting.com/
echo  2. Go to phpMyAdmin
echo  3. Select database: jqfujdmy_iseraj_roots
echo  4. Click Export → Quick → SQL → Go
echo  5. Save as: backups\db\latest_live_backup.sql
echo  6. Run setup-local-database.bat to import
echo.
pause
goto DATABASE_MENU

:EXPORT_DB
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║            EXPORT LOCAL DATABASE                   ║
echo  ╚════════════════════════════════════════════════════╝
echo.
set filename=backups\db\local_export_%date:~-4,4%-%date:~-10,2%-%date:~-7,2%.sql
"C:\xampp\mysql\bin\mysqldump.exe" -u root juzoor_quiz_local > "%filename%"
echo Database exported to: %filename%
pause
goto DATABASE_MENU

:UTILITIES_MENU
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║                 UTILITIES                          ║
echo  ╚════════════════════════════════════════════════════╝
echo.
echo   [1] 🧹 Clear Live Cache
echo   [2] 🔄 Full Sync Workflow
echo   [3] 🔍 Check Git Status
echo   [4] 📋 Open Project in Explorer
echo   [5] ← Back to Main Menu
echo.
set /p utilchoice="Select option (1-5): "

if "%utilchoice%"=="1" call clear-cache-live.bat
if "%utilchoice%"=="2" call full-sync-workflow.bat
if "%utilchoice%"=="3" (
    cls
    cd C:\xampp\htdocs\juzoor-quiz
    git status
    pause
)
if "%utilchoice%"=="4" start "" "C:\xampp\htdocs\juzoor-quiz"
if "%utilchoice%"=="5" goto MENU

goto UTILITIES_MENU

:SHOW_GUIDE
cls
echo  ╔════════════════════════════════════════════════════╗
echo  ║              DEVELOPMENT WORKFLOW                  ║
echo  ╚════════════════════════════════════════════════════╝
echo.
echo  📅 DAILY WORKFLOW:
echo  ─────────────────
echo  1. Start Development (Option 1) - This syncs and opens everything
echo  2. Work in VSCode, test on localhost
echo  3. Push to GitHub regularly (Option 3)
echo  4. Deploy when ready (Option 4)
echo.
echo  🔧 KEY SHORTCUTS:
echo  ───────────────
echo  • Quick Start: Option 1 (does everything)
echo  • Save Work: Option 3 (GitHub)
echo  • Deploy: Option 4 (Live server)
echo.
echo  📁 LOCATIONS:
echo  ────────────
echo  • Local: C:\xampp\htdocs\juzoor-quiz
echo  • Test: http://localhost/juzoor-quiz
echo  • Live: https://iseraj.com/roots
echo.
echo  ⚠️  REMEMBER:
echo  ───────────
echo  • Always test locally first
echo  • Backup before major changes
echo  • Clear cache after deploying
echo.
pause
goto MENU