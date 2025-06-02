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
Schema::table('quizzes', function (Blueprint $table) {
$table->string('pin', 6)->nullable()->unique()->after('grade_level');
$table->boolean('is_active')->default(true)->after('pin');
$table->boolean('is_demo')->default(false)->after('is_active');
$table->boolean('is_public')->default(false)->after('is_demo');
$table->timestamp('expires_at')->nullable()->after('is_public');

// Add indexes for performance
$table->index('pin');
$table->index('is_active');
$table->index('is_demo');
});
}

/**
* Reverse the migrations.
*/
public function down(): void
{
Schema::table('quizzes', function (Blueprint $table) {
$table->dropIndex(['pin']);
$table->dropIndex(['is_active']);
$table->dropIndex(['is_demo']);

$table->dropColumn(['pin', 'is_active', 'is_demo', 'is_public', 'expires_at']);
});
}
};