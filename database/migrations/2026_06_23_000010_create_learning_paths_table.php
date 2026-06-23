<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('title');
            $table->string('target_role');
            $table->json('steps');
            $table->integer('progress')->default(0);
            $table->date('estimated_completion_date')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('academic_students')->onDelete('cascade');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_paths');
    }
};
