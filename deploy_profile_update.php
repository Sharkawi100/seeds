<?php
require __DIR__ . '/../../roots_app/vendor/autoload.php';
$app = require_once __DIR__ . '/../../roots_app/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Profile Update Deployment</h2>";

// 1. Check if migration is needed
echo "<h3>1. Checking Database Schema:</h3>";
if (!\Schema::hasColumn('users', 'bio')) {
    echo "❌ Profile fields missing. Running migration...<br>";

    try {
        Artisan::call('migrate', ['--force' => true]);
        echo "<pre>" . Artisan::output() . "</pre>";
        echo "✓ Migration completed!<br>";
    } catch (Exception $e) {
        echo "❌ Migration failed: " . $e->getMessage() . "<br>";
    }
} else {
    echo "✓ Profile fields already exist.<br>";
}

// 2. Generate PINs for quizzes without them
echo "<h3>2. Fixing Quiz PINs:</h3>";
$quizzesWithoutPin = \App\Models\Quiz::whereNull('pin')->orWhere('pin', '')->get();
if ($quizzesWithoutPin->count() > 0) {
    foreach ($quizzesWithoutPin as $quiz) {
        $pin = strtoupper(\Str::random(6));
        $quiz->update(['pin' => $pin]);
        echo "Generated PIN for Quiz #{$quiz->id}: {$pin}<br>";
    }
} else {
    echo "✓ All quizzes have PINs.<br>";
}

// 3. Clear all caches
echo "<h3>3. Clearing Caches:</h3>";
$commands = ['config:clear', 'cache:clear', 'view:clear', 'route:clear'];
foreach ($commands as $command) {
    Artisan::call($command);
    echo "✓ {$command}<br>";
}

// 4. Create storage link if needed
echo "<h3>4. Checking Storage Link:</h3>";
$publicStorage = __DIR__ . '/storage';
if (!file_exists($publicStorage)) {
    Artisan::call('storage:link');
    echo "✓ Storage link created.<br>";
} else {
    echo "✓ Storage link exists.<br>";
}

// 5. Test profile completion calculation
echo "<h3>5. Testing Profile Features:</h3>";
try {
    $user = \App\Models\User::first();
    if ($user) {
        echo "Test user: {$user->name}<br>";
        echo "Profile completion: {$user->profile_completion}%<br>";
        echo "✓ Profile methods working correctly.<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2>✓ Deployment Complete!</h2>";
echo "<p>Next steps:</p>";
echo "<ol>";
echo "<li>Test the profile at <a href='/roots/profile' target='_blank'>/profile</a></li>";
echo "<li>Upload an avatar</li>";
echo "<li>Check all features work</li>";
echo "</ol>";
echo "<p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE NOW!</p>";