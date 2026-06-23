<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit\Application\Mappers;

use Modules\Shared\Application\Mappers\UserMapper;
use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class UserMapperTest extends TestCase
{
    public function test_mapper_converts_entity_to_dto(): void
    {
        $user = User::register(
            id: UserId::generate(),
            academicId: AcademicId::of('12345678'),
            email: EmailAddress::fromString('mapper@test.com'),
            name: FullName::of('Map', 'Per'),
            passwordHash: 'hashed-value',
            role: UserRole::Student,
        );
        $user->verifyEmail();
        $user->releaseEvents();

        $mapper = new UserMapper();
        $dto = $mapper->toDto($user);

        $this->assertSame($user->id()->value(), $dto->id);
        $this->assertSame('mapper@test.com', $dto->email);
        $this->assertSame('Map', $dto->firstName);
        $this->assertSame('Per', $dto->lastName);
        $this->assertSame('Map Per', $dto->fullName);
        $this->assertSame('student', $dto->role);
        $this->assertSame('active', $dto->status);
        $this->assertNotNull($dto->emailVerifiedAt);
        $this->assertNotNull($dto->createdAt);
    }

    public function test_mapper_handles_unverified_user(): void
    {
        $user = User::register(
            id: UserId::generate(),
            academicId: AcademicId::of('87654321'),
            email: EmailAddress::fromString('unverified@test.com'),
            name: FullName::of('Un', 'Verified'),
            passwordHash: 'hash',
            role: UserRole::Advisor,
        );
        $user->releaseEvents();

        $mapper = new UserMapper();
        $dto = $mapper->toDto($user);

        $this->assertSame('advisor', $dto->role);
        $this->assertSame('pending_verification', $dto->status);
        $this->assertNull($dto->emailVerifiedAt);
    }

    public function test_mapper_output_has_correct_date_format(): void
    {
        $user = User::register(
            id: UserId::generate(),
            academicId: AcademicId::of('12345678'),
            email: EmailAddress::fromString('dates@test.com'),
            name: FullName::of('Date', 'Test'),
            passwordHash: 'hash',
            role: UserRole::Admin,
        );
        $user->releaseEvents();

        $mapper = new UserMapper();
        $dto = $mapper->toDto($user);

        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\+\d{2}:\d{2}$/',
            $dto->createdAt,
        );
    }
}
