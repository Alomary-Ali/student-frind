<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_service_requests', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('ref_number')->unique();
            $table->uuid('category_id');
            $table->uuid('student_id');
            $table->string('status', 50)->default('new');
            $table->string('priority', 50)->default('medium');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->uuid('workflow_id')->nullable();
            $table->uuid('current_step_id')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('ref_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_service_requests');
    }
};
