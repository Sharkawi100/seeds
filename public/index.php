<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// CORRECT absolute paths to roots_app
$app_path = '/home/jqfujdmy/roots_app';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $app_path . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $app_path . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $app_path . '/bootstrap/app.php';

$app->handleRequest(Request::capture());