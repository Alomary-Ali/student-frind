<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_semesters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->uuid('institution_id')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_semesters');
    }
};
