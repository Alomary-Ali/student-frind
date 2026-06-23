<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add foreign key constraints to all productivity tables.
 * Separated into its own migration so it runs after users table is created.
 */
return new class extends Migration
{
    public function up(): void
    {
        // productivity_goals
        Schema::table('productivity_goals', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // productivity_tasks
        Schema::table('productivity_tasks', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // productivity_reminders
        Schema::table('productivity_reminders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // productivity_calendar_events
        Schema::table('productivity_calendar_events', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // productivity_snapshots
        Schema::table('productivity_snapshots', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // productivity_audit_logs
        Schema::table('productivity_audit_logs', function (Blueprint $table) {
            $table->foreign('actor_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('productivity_goals', fn ($t) => $t->dropForeign(['user_id']));
        Schema::table('productivity_tasks', fn ($t) => $t->dropForeign(['user_id']));
        Schema::table('productivity_reminders', fn ($t) => $t->dropForeign(['user_id']));
        Schema::table('productivity_calendar_events', fn ($t) => $t->dropForeign(['user_id']));
        Schema::table('productivity_snapshots', fn ($t) => $t->dropForeign(['user_id']));
        Schema::table('productivity_audit_logs', fn ($t) => $t->dropForeign(['actor_user_id']));
    }
};
