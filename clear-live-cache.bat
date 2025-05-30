@echo off
echo ========================================
echo Creating cache clear script on server
echo ========================================

:: Create a PHP file to clear cache
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
"C:\Program Files (x86)\WinSCP\WinSCP.com" ^
  /log="cache.log" /ini=nul ^
  /command ^
    "open ftp://jqfujdmy:Eron910138@premium287.web-hosting.com/" ^
    "put temp_clear_cache.php /home/jqfujdmy/public_html/roots/clear_cache_temp.php" ^
    "exit"

del temp_clear_cache.php

echo ========================================
echo Visit: https://iseraj.com/roots/clear_cache_temp.php
echo Then delete it from server!
echo ========================================
pause