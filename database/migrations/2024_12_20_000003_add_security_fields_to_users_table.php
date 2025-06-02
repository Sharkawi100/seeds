<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type', 20)->default('student')->after('password'); // student, teacher, admin
            $table->string('school_name')->nullable()->after('user_type');
            $table->integer('grade_level')->nullable()->after('school_name');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->integer('login_count')->default(0)->after('last_login_ip');
            $table->boolean('account_locked')->default(false)->after('login_count');
            $table->timestamp('locked_until')->nullable()->after('account_locked');
            $table->json('trusted_devices')->nullable()->after('locked_until');
            $table->json('security_questions')->nullable()->after('trusted_devices');
            $table->boolean('force_password_change')->default(false)->after('security_questions');
            $table->timestamp('password_changed_at')->nullable()->after('force_password_change');
            $table->json('password_history')->nullable()->after('password_changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'school_name',
                'grade_level',
                'last_login_at',
                'last_login_ip',
                'login_count',
                'account_locked',
                'locked_until',
                'trusted_devices',
                'security_questions',
                'force_password_change',
                'password_changed_at',
                'password_history'
            ]);
        });
    }
};