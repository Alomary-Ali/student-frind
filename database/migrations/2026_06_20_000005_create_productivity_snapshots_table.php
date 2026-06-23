<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productivity_snapshots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->integer('total_goals')->default(0);
            $table->integer('completed_goals')->default(0);
            $table->integer('total_tasks')->default(0);
            $table->integer('completed_tasks')->default(0);
            $table->integer('overdue_tasks')->default(0);
            $table->decimal('completion_rate', 5, 2)->default(0.00);
            $table->date('snapshot_date');
            $table->timestamps();

            $table->index('user_id');
            $table->index('snapshot_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productivity_snapshots');
    }
};
