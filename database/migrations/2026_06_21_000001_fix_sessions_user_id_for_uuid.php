<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix sessions.user_id column type.
 *
 * The default Laravel sessions table uses bigint(unsigned) for user_id,
 * which is incompatible with UUID-based user primary keys.
 * When Auth::loginUsingId($uuid) writes the session, Laravel's
 * DatabaseSessionHandler tries to store the UUID in the user_id column.
 * MySQL silently truncates it to 0, so the session row is written
 * but with user_id=0. On the NEXT request, the session payload IS read
 * correctly — BUT if Laravel's session handler also queries by user_id,
 * it may not find the session properly.
 *
 * Fix: change user_id to varchar(36) to accommodate UUIDs.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Drop the existing index on user_id first
            $table->dropIndex('sessions_user_id_index');
        });

        Schema::table('sessions', function (Blueprint $table) {
            // Change from bigint to varchar(36) for UUID compatibility
            $table->string('user_id', 36)->nullable()->change();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex('sessions_user_id_index');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->index('user_id');
        });
    }
};
