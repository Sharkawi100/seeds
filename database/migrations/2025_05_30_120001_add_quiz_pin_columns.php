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
// Check and add columns only if they don't exist
Schema::table('quizzes', function (Blueprint $table) {
if (!Schema::hasColumn('quizzes', 'pin')) {
$table->string('pin', 6)->nullable()->unique()->after('grade_level');
}

if (!Schema::hasColumn('quizzes', 'is_active')) {
$table->boolean('is_active')->default(true)->after('pin');
}

if (!Schema::hasColumn('quizzes', 'is_demo')) {
$table->boolean('is_demo')->default(false)->after('is_active');
}

if (!Schema::hasColumn('quizzes', 'is_public')) {
$table->boolean('is_public')->default(false)->after('is_demo');
}

if (!Schema::hasColumn('quizzes', 'expires_at')) {
$table->timestamp('expires_at')->nullable()->after('is_public');
}
});

// Add indexes if they don't exist
Schema::table('quizzes', function (Blueprint $table) {
$sm = Schema::getConnection()->getDoctrineSchemaManager();
$indexesFound = $sm->listTableIndexes('quizzes');

if (!array_key_exists('quizzes_pin_index', $indexesFound)) {
$table->index('pin');
}

if (!array_key_exists('quizzes_is_active_index', $indexesFound)) {
$table->index('is_active');
}

if (!array_key_exists('quizzes_is_demo_index', $indexesFound)) {
$table->index('is_demo');
}
});
}

/**
* Reverse the migrations.
*/
public function down(): void
{
Schema::table('quizzes', function (Blueprint $table) {
// Drop indexes first
$sm = Schema::getConnection()->getDoctrineSchemaManager();
$indexesFound = $sm->listTableIndexes('quizzes');

if (array_key_exists('quizzes_is_demo_index', $indexesFound)) {
$table->dropIndex(['is_demo']);
}

if (array_key_exists('quizzes_is_active_index', $indexesFound)) {
$table->dropIndex(['is_active']);
}

if (array_key_exists('quizzes_pin_index', $indexesFound)) {
$table->dropIndex(['pin']);
}

// Drop columns
$columns = [];

if (Schema::hasColumn('quizzes', 'expires_at')) {
$columns[] = 'expires_at';
}

if (Schema::hasColumn('quizzes', 'is_public')) {
$columns[] = 'is_public';
}

if (Schema::hasColumn('quizzes', 'is_demo')) {
$columns[] = 'is_demo';
}

if (Schema::hasColumn('quizzes', 'is_active')) {
$columns[] = 'is_active';
}

if (Schema::hasColumn('quizzes', 'pin')) {
$columns[] = 'pin';
}

if (!empty($columns)) {
$table->dropColumn($columns);
}
});
}
};