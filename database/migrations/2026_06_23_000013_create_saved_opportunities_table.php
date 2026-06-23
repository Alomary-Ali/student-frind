<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_opportunities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('student_id', 36);
            $table->uuid('opportunity_id');
            $table->timestamp('saved_at')->useCurrent();
            $table->timestamps();

            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('cascade');
            $table->unique(['student_id', 'opportunity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_opportunities');
    }
};
