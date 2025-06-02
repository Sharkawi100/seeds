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
Schema::table('users', function (Blueprint $table) {
$table->boolean('is_school')->default(false)->after('is_admin');
$table->string('school_name')->nullable()->after('is_school');
$table->timestamp('last_login_at')->nullable()->after('remember_token');
});
}

/**
* Reverse the migrations.
*/
public function down(): void
{
Schema::table('users', function (Blueprint $table) {
$table->dropColumn(['is_school', 'school_name', 'last_login_at']);
});
}
};