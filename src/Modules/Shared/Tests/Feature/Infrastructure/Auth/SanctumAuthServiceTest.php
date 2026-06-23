<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Feature\Infrastructure\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Domain\Contracts\AuthServiceInterface;
use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Infrastructure\Auth\SanctumAuthService;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class SanctumAuthServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_token_creates_token_for_user(): void
    {
        $eloquentUser = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440020',
            'email' => 'tokenuser@test.com',
            'first_name' => 'Token',
            'last_name' => 'User',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => '12345678',
        ]);

        $domainUser = User::reconstitute(
            id: UserId::fromString($eloquentUser->id),
            academicId: AcademicId::of('12345678'),
            email: EmailAddress::fromString($eloquentUser->email),
            name: FullName::of($eloquentUser->first_name, $eloquentUser->last_name),
            passwordHash: $eloquentUser->password_hash,
            role: UserRole::from($eloquentUser->role),
            status: \Modules\Shared\Domain\Enums\UserStatus::from($eloquentUser->status),
            emailVerifiedAt: null,
            createdAt: new \DateTimeImmutable($eloquentUser->created_at->toIso8601String()),
        );

        $service = new SanctumAuthService;
        $token = $service->generateToken($domainUser);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
        $this->assertStringContainsString('|', $token);
    }

    public function test_generate_token_returns_different_tokens_on_each_call(): void
    {
        $eloquentUser = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440021',
            'email' => 'multitoken@test.com',
            'first_name' => 'Multi',
            'last_name' => 'Token',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => '87654321',
        ]);

        $domainUser = User::reconstitute(
            id: UserId::fromString($eloquentUser->id),
            academicId: AcademicId::of('87654321'),
            email: EmailAddress::fromString($eloquentUser->email),
            name: FullName::of($eloquentUser->first_name, $eloquentUser->last_name),
            passwordHash: $eloquentUser->password_hash,
            role: UserRole::from($eloquentUser->role),
            status: \Modules\Shared\Domain\Enums\UserStatus::from($eloquentUser->status),
            emailVerifiedAt: null,
            createdAt: new \DateTimeImmutable($eloquentUser->created_at->toIso8601String()),
        );

        $service = new SanctumAuthService;
        $token1 = $service->generateToken($domainUser);
        $token2 = $service->generateToken($domainUser);

        $this->assertNotSame($token1, $token2);
    }

    public function test_service_implements_auth_service_interface(): void
    {
        $service = new SanctumAuthService;
        $this->assertInstanceOf(AuthServiceInterface::class, $service);
    }
}
