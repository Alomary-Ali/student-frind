<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('academic_students', function (Blueprint $table) {
            $table->float('semester_gpa')->nullable()->after('cumulative_gpa');
            $table->uuid('current_semester_id')->nullable()->after('semester_gpa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_students', function (Blueprint $table) {
            $table->dropColumn(['semester_gpa', 'current_semester_id']);
        });
    }
};
