<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('department_id');
            $table->string('name');
            $table->string('name_en');
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
