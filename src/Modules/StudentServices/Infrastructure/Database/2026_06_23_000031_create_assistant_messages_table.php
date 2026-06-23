<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistant_messages', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('conversation_id');
            $table->string('role', 50);
            $table->longText('content');
            $table->json('metadata')->nullable();
            $table->timestamp('created_at');

            $table->index('conversation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistant_messages');
    }
};
