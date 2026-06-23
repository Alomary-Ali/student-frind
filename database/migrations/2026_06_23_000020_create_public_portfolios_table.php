<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_portfolios', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('student_id')->unique();
            $table->string('slug', 100)->unique();
            $table->string('title', 255);
            $table->text('bio')->nullable();
            $table->string('theme', 50)->default('modern');
            $table->boolean('is_active')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamps();

            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_portfolios');
    }
};
