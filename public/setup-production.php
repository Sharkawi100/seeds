<?php
// IMPORTANT: DELETE THIS FILE AFTER RUNNING!
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Production Setup for جُذور</h2>";

// Run migrations
echo "<h3>Running Migrations...</h3>";
echo "<pre>";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();
echo "</pre>";

// Create storage link
echo "<h3>Creating Storage Link...</h3>";
echo "<pre>";
Artisan::call('storage:link');
echo Artisan::output();
echo "</pre>";

// Clear and cache
echo "<h3>Optimizing for Production...</h3>";
echo "<pre>";
Artisan::call('config:cache');
echo Artisan::output();
Artisan::call('route:cache');
echo Artisan::output();
Artisan::call('view:cache');
echo Artisan::output();
echo "</pre>";

// Check database connection
echo "<h3>Database Connection Test...</h3>";
try {
    $tables = DB::select('SHOW TABLES');
    echo "<span style='color:green'>✓ Database connected successfully!</span><br>";
    echo "Tables found: " . count($tables) . "<br>";
} catch (Exception $e) {
    echo "<span style='color:red'>✗ Database connection failed: " . $e->getMessage() . "</span><br>";
}

// Check social login columns
echo "<h3>Checking Social Login Setup...</h3>";
$hasGoogleId = Schema::hasColumn('users', 'google_id');
echo "Google ID column: " . ($hasGoogleId ? "✓ Exists" : "✗ Missing") . "<br>";

echo "<hr>";
echo "<h2 style='color:red'>⚠️ IMPORTANT: DELETE THIS FILE NOW!</h2>";
echo "<p>Access it at: https://www.iseraj.com/roots/setup-production.php</p>";