<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productivity_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('title');
            $table->text('description');
            $table->dateTime('due_date')->nullable();
            $table->string('priority');
            $table->string('status')->default('pending');
            $table->uuid('linked_goal_id')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('priority');
            $table->index('linked_goal_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productivity_tasks');
    }
};
