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
        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // e.g., 'text_generation', 'questions_from_text', 'complete_quiz_generation'
            $table->string('model', 100)->nullable(); // Claude model used
            $table->integer('count')->default(1); // Number of items generated
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->json('metadata')->nullable(); // Additional data if needed
            $table->timestamps();

            // Indexes for performance
            $table->index('type');
            $table->index('created_at');
            $table->index(['type', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');
    }
};