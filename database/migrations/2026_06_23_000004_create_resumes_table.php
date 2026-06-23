<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('career_profile_id');
            $table->enum('template', ['ats_friendly', 'modern', 'academic', 'professional']);
            $table->longText('content');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();

            $table->foreign('career_profile_id')->references('id')->on('career_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
