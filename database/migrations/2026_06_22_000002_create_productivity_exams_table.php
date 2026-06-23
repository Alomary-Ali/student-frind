<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productivity_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('course_id');
            $table->string('title', 255);
            $table->enum('exam_type', ['midterm', 'final', 'quiz', 'practical', 'oral']);
            $table->timestamp('exam_date');
            $table->string('location', 255);
            $table->string('status')->default('scheduled');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'exam_date']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productivity_exams');
    }
};
