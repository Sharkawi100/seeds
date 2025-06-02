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
        Schema::table('quizzes', function (Blueprint $table) {
            // Add settings column if it doesn't exist
            if (!Schema::hasColumn('quizzes', 'settings')) {
                $table->json('settings')->nullable()->after('grade_level');
            }

            // Add passage_data column if it doesn't exist
            if (!Schema::hasColumn('quizzes', 'passage_data')) {
                $table->json('passage_data')->nullable()->after('settings');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['settings', 'passage_data']);
        });
    }

};
