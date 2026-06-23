<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->onDelete('cascade');
            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_profiles');
    }
};
