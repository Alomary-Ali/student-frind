<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('enrollment_id');
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->string('grade_letter');
            $table->decimal('grade_points', 3, 1);
            $table->timestamp('recorded_at');
            $table->uuid('recorded_by_user_id');
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('academic_enrollments')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('academic_students')->cascadeOnDelete();
            $table->unique('enrollment_id');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};
