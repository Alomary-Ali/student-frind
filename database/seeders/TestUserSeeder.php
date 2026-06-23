<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\Enums\UserStatus;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Infrastructure\Repositories\EloquentUserRepository;

final class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Test seeder disabled for security
        // Use factories for testing instead
        $this->command->warn('⚠️  TestUserSeeder is disabled. Use factories for testing.');
    }
}
