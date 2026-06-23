<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_graduation_paths', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('curriculum_id');
            $table->unsignedSmallInteger('credits_earned')->default(0);
            $table->unsignedSmallInteger('credits_required');
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->boolean('is_on_track')->default(true);
            $table->date('estimated_graduation_date')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->cascadeOnDelete();
            $table->foreign('curriculum_id')->references('id')->on('academic_curricula')->restrictOnDelete();
            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_graduation_paths');
    }
};
