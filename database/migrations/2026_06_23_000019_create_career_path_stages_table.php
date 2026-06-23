<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_path_stages', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('career_path_id');
            $table->string('title', 255);
            $table->integer('order')->default(0);
            $table->json('required_skills')->nullable();
            $table->integer('duration_months')->default(0);
            $table->string('salary_range', 100)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('career_path_id')->references('id')->on('career_paths')->onDelete('cascade');
            $table->index('career_path_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_path_stages');
    }
};
