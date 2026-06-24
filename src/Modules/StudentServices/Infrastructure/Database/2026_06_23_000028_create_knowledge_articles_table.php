<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_articles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('category_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->json('tags')->nullable();
            $table->string('status', 50)->default('draft');
            $table->integer('view_count')->default(0);
            $table->timestamps();

            $table->index('category_id');
            $table->index('status');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_articles');
    }
};
