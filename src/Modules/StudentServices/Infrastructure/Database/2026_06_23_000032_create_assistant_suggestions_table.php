<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistant_suggestions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('conversation_id');
            $table->uuid('message_id');
            $table->string('suggestion_type', 50);
            $table->string('title');
            $table->string('action_url')->nullable();
            $table->timestamp('created_at');

            $table->index('conversation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistant_suggestions');
    }
};
