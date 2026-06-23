<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_course_prerequisites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('prerequisite_course_id');
            $table->boolean('is_required')->default(true);
            $table->integer('minimum_grade')->nullable()->comment('Minimum grade points required (0-4.0)');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('academic_courses')->onDelete('cascade');
            $table->foreign('prerequisite_course_id')->references('id')->on('academic_courses')->onDelete('cascade');

            $table->unique(['course_id', 'prerequisite_course_id'], 'course_prereq_unique');
            $table->index('course_id');
            $table->index('prerequisite_course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_course_prerequisites');
    }
};
