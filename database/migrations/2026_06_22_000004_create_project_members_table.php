<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->uuid('student_id');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('productivity_projects')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('academic_students')->onDelete('cascade');
            $table->unique(['project_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_members');
    }
};
