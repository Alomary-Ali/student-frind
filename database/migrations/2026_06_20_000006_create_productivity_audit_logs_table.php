<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productivity_audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('actor_user_id');
            $table->string('action');
            $table->string('entity_type');
            $table->uuid('entity_id');
            $table->json('new_values')->nullable();
            $table->json('old_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('actor_user_id');
            $table->index('entity_type');
            $table->index('entity_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productivity_audit_logs');
    }
};
