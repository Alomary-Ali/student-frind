<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_questions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('interview_id');
            $table->text('question');
            $table->string('category', 100)->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            $table->index('interview_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_questions');
    }
};
