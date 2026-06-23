<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_goals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('career_profile_id');
            $table->string('title');
            $table->date('target_date');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'postponed', 'cancelled'])->default('not_started');
            $table->integer('progress')->default(0);
            $table->timestamps();

            $table->foreign('career_profile_id')->references('id')->on('career_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_goals');
    }
};
