<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productivity_calendar_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('title');
            $table->text('description');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('is_all_day')->default(false);
            $table->uuid('linked_task_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('linked_task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productivity_calendar_events');
    }
};
