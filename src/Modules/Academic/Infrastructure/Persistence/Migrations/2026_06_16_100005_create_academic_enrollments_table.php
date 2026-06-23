<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->uuid('semester_id');
            $table->string('status');
            $table->timestamp('enrolled_at');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('academic_courses')->restrictOnDelete();
            $table->foreign('semester_id')->references('id')->on('academic_semesters')->restrictOnDelete();
            $table->unique(['student_id', 'course_id', 'semester_id']);
            $table->index('student_id');
            $table->index('semester_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_enrollments');
    }
};
