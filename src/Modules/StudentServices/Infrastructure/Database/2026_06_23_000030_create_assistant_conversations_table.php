<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistant_conversations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('title')->nullable();
            $table->string('status', 50)->default('active');
            $table->json('context_data')->nullable();
            $table->dateTime('last_activity_at');
            $table->timestamps();

            $table->index('student_id');
            $table->index('status');
            $table->index('last_activity_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistant_conversations');
    }
};
