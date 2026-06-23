<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->enum('type', ['academic', 'productivity', 'career', 'community']);
            $table->string('title');
            $table->text('description');
            $table->string('badge_url')->nullable();
            $table->timestamp('unlocked_at');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('student_id')->references('id')->on('academic_students')->onDelete('cascade');
            $table->index(['student_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
