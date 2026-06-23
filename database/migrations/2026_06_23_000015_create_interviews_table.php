<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('type', 50);
            $table->string('status', 50)->default('scheduled');
            $table->dateTime('scheduled_at');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
