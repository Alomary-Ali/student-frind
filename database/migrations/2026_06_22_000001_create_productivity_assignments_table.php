<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productivity_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('course_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->timestamp('assigned_at');
            $table->timestamp('due_date')->nullable();
            $table->enum('status', ['assigned', 'in_progress', 'submitted', 'graded', 'late'])->default('assigned');
            $table->string('grade')->nullable();
            $table->string('submission_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'due_date']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productivity_assignments');
    }
};
