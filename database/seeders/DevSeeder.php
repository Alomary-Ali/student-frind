<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DevSeeder — creates test accounts for local development.
 *
 * IMPORTANT: NEVER run this in production.
 * Run with: php artisan db:seed --class=DevSeeder
 */
class DevSeeder extends Seeder
{
    public function run(): void
    {
        // Development seeder disabled for security
        // Use DatabaseSeeder with proper test factories instead
        $this->command->warn('⚠️  DevSeeder is disabled. Use test factories in DatabaseSeeder instead.');
    }
}
