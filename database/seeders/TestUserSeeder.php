<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Test seeder disabled for security
        // Use factories for testing instead
        $this->command->warn('⚠️  TestUserSeeder is disabled. Use factories for testing.');
    }
}
