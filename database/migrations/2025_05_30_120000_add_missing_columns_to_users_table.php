<?php
// File: database/migrations/2025_05_30_120000_add_missing_columns_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if is_admin column exists before adding
        if (!Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('password');
            });
        }

        // Check if is_school column exists before adding
        if (!Schema::hasColumn('users', 'is_school')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_school')->default(false)->after('is_admin');
            });
        }

        // Check if school_name column exists before adding
        if (!Schema::hasColumn('users', 'school_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('school_name')->nullable()->after('is_school');
            });
        }

        // Check if last_login_at column exists before adding
        if (!Schema::hasColumn('users', 'last_login_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('users', 'last_login_at')) {
                $columns[] = 'last_login_at';
            }

            if (Schema::hasColumn('users', 'school_name')) {
                $columns[] = 'school_name';
            }

            if (Schema::hasColumn('users', 'is_school')) {
                $columns[] = 'is_school';
            }

            if (Schema::hasColumn('users', 'is_admin')) {
                $columns[] = 'is_admin';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};