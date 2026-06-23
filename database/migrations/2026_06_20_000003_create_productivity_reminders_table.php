<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productivity_reminders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('message');
            $table->dateTime('trigger_at');
            $table->string('type');
            $table->uuid('linked_task_id')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('triggered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('trigger_at');
            $table->index('status');
            $table->index('linked_task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productivity_reminders');
    }
};
