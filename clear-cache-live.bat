@echo off
echo ========================================
echo    CLEAR CACHE ON LIVE SERVER
echo ========================================
echo.
echo Creating cache clear script...

:: Create PHP script
echo ^<?php > temp_clear_cache.php
echo define('LARAVEL_START', microtime(true)); >> temp_clear_cache.php
echo $appPath = dirname(__DIR__, 2) . '/roots_app'; >> temp_clear_cache.php
echo chdir($appPath); >> temp_clear_cache.php
echo require $appPath . '/vendor/autoload.php'; >> temp_clear_cache.php
echo $app = require_once $appPath . '/bootstrap/app.php'; >> temp_clear_cache.php
echo $app-^>instance('path.base', $appPath); >> temp_clear_cache.php
echo $kernel = $app-^>make(Illuminate\Contracts\Console\Kernel::class); >> temp_clear_cache.php
echo $kernel-^>bootstrap(); >> temp_clear_cache.php
echo use Illuminate\Support\Facades\Artisan; >> temp_clear_cache.php
echo Artisan::call('config:clear'); >> temp_clear_cache.php
echo Artisan::call('cache:clear'); >> temp_clear_cache.php
echo Artisan::call('view:clear'); >> temp_clear_cache.php
echo Artisan::call('route:clear'); >> temp_clear_cache.php
echo echo "All caches cleared!"; >> temp_clear_cache.php
echo ?^> >> temp_clear_cache.php

:: Upload and execute
echo Uploading script...
"C:\Program Files (x86)\WinSCP\WinSCP.com" ^
  /command ^
    "open ftp://roots_sync%%40iseraj.com:Eron_910138@ftp.iseraj.com/" ^
    "put temp_clear_cache.php public_html/roots/clear_cache_temp.php" ^
    "exit"

del temp_clear_cache.php

echo.
echo Script uploaded!
echo.
echo Visit: https://iseraj.com/roots/clear_cache_temp.php
echo.
echo After running, DELETE it immediately!
echo.
pause