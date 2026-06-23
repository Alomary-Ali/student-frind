<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_attempts', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('interview_id');
            $table->uuid('student_id');
            $table->json('answers');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            $table->index('student_id');
            $table->index('interview_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_attempts');
    }
};
