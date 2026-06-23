<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('skill_profile_id');
            $table->string('name');
            $table->enum('category', [
                'programming', 'networking', 'design', 'ai', 'data_analysis',
                'leadership', 'communication', 'teamwork', 'problem_solving', 'time_management'
            ]);
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            $table->integer('years_of_experience')->default(0);
            $table->date('last_used')->nullable();
            $table->timestamps();

            $table->foreign('skill_profile_id')->references('id')->on('skill_profiles')->onDelete('cascade');
            $table->unique(['skill_profile_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
