<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('curriculum_id');
            $table->string('status');
            $table->timestamp('assigned_at');
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->cascadeOnDelete();
            $table->foreign('curriculum_id')->references('id')->on('academic_curricula')->restrictOnDelete();
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_plans');
    }
};
