<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('career_profile_id');
            $table->string('title');
            $table->text('description');
            $table->string('project_url')->nullable();
            $table->string('github_url')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->json('technologies')->nullable();
            $table->timestamps();

            $table->foreign('career_profile_id')->references('id')->on('career_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};
