<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_documents', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('type', 50);
            $table->string('title');
            $table->text('file_path')->nullable();
            $table->string('status', 50)->default('pending');
            $table->string('verification_code')->nullable()->unique();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('verification_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
