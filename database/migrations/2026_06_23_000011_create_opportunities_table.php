<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('provider', 100);
            $table->string('type', 50);
            $table->string('location', 255)->nullable();
            $table->string('country', 100)->nullable();
            $table->dateTime('deadline')->nullable();
            $table->string('apply_url', 500)->nullable();
            $table->string('status', 20)->default('active');
            $table->json('metadata')->nullable();
            $table->string('source_url', 500)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
