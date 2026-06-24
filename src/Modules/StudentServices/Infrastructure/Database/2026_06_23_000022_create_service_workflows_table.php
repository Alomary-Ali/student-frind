<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_workflows', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('service_category_id');
            $table->string('name');
            $table->string('status', 50)->default('active');
            $table->json('config')->nullable();
            $table->timestamps();

            $table->index('service_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_workflows');
    }
};
