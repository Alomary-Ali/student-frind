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
        Schema::create('academic_advisory_alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('alert_type'); // low_gpa, graduation_delay, repeated_failure, credit_deficit
            $table->string('severity'); // low, medium, high, critical
            $table->text('message');
            $table->json('metadata')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->uuid('resolved_by')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->onDelete('cascade');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');

            $table->index('student_id');
            $table->index('alert_type');
            $table->index('severity');
            $table->index('is_resolved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_advisory_alerts');
    }
};
