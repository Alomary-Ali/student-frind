<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productivity_goals', function (Blueprint $table) {
            $table->string('goal_type')->default('semester')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('productivity_goals', function (Blueprint $table) {
            $table->dropColumn('goal_type');
        });
    }
};
