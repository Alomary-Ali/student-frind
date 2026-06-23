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
        Schema::create('academic_semester_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('semester_id');
            $table->json('planned_courses'); // Array of course IDs
            $table->integer('total_credits')->default(0);
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('academic_semesters')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');

            $table->unique(['student_id', 'semester_id']);
            $table->index('student_id');
            $table->index('semester_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_semester_plans');
    }
};
