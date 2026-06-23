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
            $table->uuid('university_id')->nullable()->after('institution_id');
            $table->uuid('college_id')->nullable()->after('university_id');
            $table->uuid('department_id')->nullable()->after('college_id');
            $table->uuid('major_id')->nullable()->after('department_id');
            $table->string('level')->default('1')->after('academic_standing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_students', function (Blueprint $table) {
            $table->dropColumn(['university_id', 'college_id', 'department_id', 'major_id', 'level']);
        });
    }
};
