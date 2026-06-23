<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('skill_profile_id');
            $table->string('name');
            $table->string('issuer');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('credential_url')->nullable();
            $table->string('verification_code')->nullable();
            $table->timestamps();

            $table->foreign('skill_profile_id')->references('id')->on('skill_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
