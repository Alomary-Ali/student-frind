<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_curricula', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description');
            $table->unsignedSmallInteger('total_credits_required');
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->index('institution_id');
        });

        Schema::create('academic_curriculum_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('curriculum_id');
            $table->uuid('course_id');
            $table->boolean('is_required')->default(true);
            $table->unsignedTinyInteger('semester_order')->default(1);
            $table->timestamps();

            $table->foreign('curriculum_id')->references('id')->on('academic_curricula')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('academic_courses')->cascadeOnDelete();
            $table->unique(['curriculum_id', 'course_id']);
            $table->index('curriculum_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_curriculum_courses');
        Schema::dropIfExists('academic_curricula');
    }
};
