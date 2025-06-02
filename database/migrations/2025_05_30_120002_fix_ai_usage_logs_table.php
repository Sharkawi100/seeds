use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
/**
* Run the migrations.
*/
public function up(): void
{
// Drop the incomplete table if it exists
if (Schema::hasTable('ai_usage_logs')) {
// Check if all required columns exist
$hasAllColumns = Schema::hasColumn('ai_usage_logs', 'type') &&
Schema::hasColumn('ai_usage_logs', 'model') &&
Schema::hasColumn('ai_usage_logs', 'count') &&
Schema::hasColumn('ai_usage_logs', 'user_id');

if (!$hasAllColumns) {
// Drop and recreate with correct structure
Schema::dropIfExists('ai_usage_logs');

Schema::create('ai_usage_logs', function (Blueprint $table) {
$table->id();
$table->string('type', 50);
$table->string('model', 100)->nullable();
$table->integer('count')->default(1);
$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
$table->json('metadata')->nullable();
$table->timestamps();

// Indexes
$table->index('type');
$table->index('created_at');
$table->index(['type', 'created_at']);
$table->index('user_id');
});
}
} else {
// Create the table fresh
Schema::create('ai_usage_logs', function (Blueprint $table) {
$table->id();
$table->string('type', 50);
$table->string('model', 100)->nullable();
$table->integer('count')->default(1);
$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
$table->json('metadata')->nullable();
$table->timestamps();

// Indexes
$table->index('type');
$table->index('created_at');
$table->index(['type', 'created_at']);
$table->index('user_id');
});
}
}

/**
* Reverse the migrations.
*/
public function down(): void
{
Schema::dropIfExists('ai_usage_logs');
}
};