<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunity_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('opportunity_id');
            $table->string('student_id', 36);
            $table->string('application_status', 30)->default('saved');
            $table->timestamp('applied_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('cascade');
            $table->unique(['student_id', 'opportunity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunity_applications');
    }
};
