<?php
// IMPORTANT: DELETE THIS FILE AFTER CHECKING!
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Production Status Check</h2>";

// Environment check
echo "<h3>Environment:</h3>";
echo "APP_ENV: " . env('APP_ENV') . "<br>";
echo "APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "<br>";
echo "APP_URL: " . env('APP_URL') . "<br>";

// Database check
echo "<h3>Database:</h3>";
try {
    $userCount = \App\Models\User::count();
    $quizCount = \App\Models\Quiz::count();
    echo "✓ Database connected<br>";
    echo "Users: $userCount<br>";
    echo "Quizzes: $quizCount<br>";
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "<br>";
}

// OAuth check
echo "<h3>OAuth Configuration:</h3>";
echo "Google Client ID: " . (env('GOOGLE_CLIENT_ID') ? '✓ Set' : '✗ Not set') . "<br>";
echo "Google Redirect: " . env('GOOGLE_REDIRECT_URL') . "<br>";

// Storage check
echo "<h3>Storage:</h3>";
$publicStorage = public_path('storage');
echo "Storage link: " . (is_link($publicStorage) ? '✓ Exists' : '✗ Missing') . "<br>";

// Permissions check
echo "<h3>Permissions:</h3>";
$storagePath = storage_path('logs');
echo "Storage writable: " . (is_writable($storagePath) ? '✓ Yes' : '✗ No') . "<br>";

echo "<hr>";
echo "<h2 style='color:red'>⚠️ DELETE THIS FILE AFTER CHECKING!</h2>";