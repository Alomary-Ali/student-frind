<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('type', 50);
            $table->string('title');
            $table->text('message');
            $table->string('channel', 50)->default('in_app');
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->index('student_id');
            $table->index(['student_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
