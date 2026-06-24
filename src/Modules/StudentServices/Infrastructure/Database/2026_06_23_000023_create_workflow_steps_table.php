<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_steps', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id');
            $table->string('name');
            $table->string('type', 50);
            $table->integer('order')->default(0);
            $table->json('config')->nullable();
            $table->string('assignee_role')->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();

            $table->index('workflow_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
