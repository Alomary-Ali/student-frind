<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('student_number')->unique();
            $table->string('academic_status');
            $table->string('academic_standing');
            $table->decimal('cumulative_gpa', 3, 2)->default(0);
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique('user_id');
            $table->index('institution_id');
            $table->index('academic_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_students');
    }
};
