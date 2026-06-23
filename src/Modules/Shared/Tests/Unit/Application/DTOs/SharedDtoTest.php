<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Unit\Application\DTOs;

use Modules\Shared\Application\DTOs\AuthTokenDto;
use Modules\Shared\Application\DTOs\RegisterUserDto;
use Modules\Shared\Application\DTOs\UserDto;
use PHPUnit\Framework\TestCase;

final class SharedDtoTest extends TestCase
{
    public function test_register_user_dto_holds_all_values(): void
    {
        $dto = new RegisterUserDto(
            email: 'test@example.com',
            firstName: 'Alice',
            lastName: 'Smith',
            password: 'secure-password',
            role: 'student',
        );

        $this->assertSame('test@example.com', $dto->email);
        $this->assertSame('Alice', $dto->firstName);
        $this->assertSame('Smith', $dto->lastName);
        $this->assertSame('secure-password', $dto->password);
        $this->assertSame('student', $dto->role);
    }

    public function test_user_dto_holds_all_values(): void
    {
        $dto = new UserDto(
            id: 'user-123',
            email: 'user@example.com',
            firstName: 'Bob',
            lastName: 'Jones',
            fullName: 'Bob Jones',
            role: 'advisor',
            status: 'active',
            emailVerifiedAt: '2026-06-01T12:00:00+00:00',
            createdAt: '2026-05-01T10:00:00+00:00',
        );

        $this->assertSame('user-123', $dto->id);
        $this->assertSame('user@example.com', $dto->email);
        $this->assertSame('Bob', $dto->firstName);
        $this->assertSame('Jones', $dto->lastName);
        $this->assertSame('Bob Jones', $dto->fullName);
        $this->assertSame('advisor', $dto->role);
        $this->assertSame('active', $dto->status);
        $this->assertSame('2026-06-01T12:00:00+00:00', $dto->emailVerifiedAt);
        $this->assertSame('2026-05-01T10:00:00+00:00', $dto->createdAt);
    }

    public function test_user_dto_email_verified_at_can_be_null(): void
    {
        $dto = new UserDto(
            id: 'user-456',
            email: 'pending@example.com',
            firstName: 'Carol',
            lastName: 'White',
            fullName: 'Carol White',
            role: 'student',
            status: 'pending_verification',
            emailVerifiedAt: null,
            createdAt: '2026-06-10T08:00:00+00:00',
        );

        $this->assertNull($dto->emailVerifiedAt);
    }

    public function test_auth_token_dto_holds_all_values(): void
    {
        $userDto = new UserDto(
            id: 'user-789',
            email: 'auth@example.com',
            firstName: 'Dave',
            lastName: 'Brown',
            fullName: 'Dave Brown',
            role: 'admin',
            status: 'active',
            emailVerifiedAt: '2026-06-15T10:00:00+00:00',
            createdAt: '2026-04-01T09:00:00+00:00',
        );

        $dto = new AuthTokenDto(
            user: $userDto,
            token: '1|abc123def456',
            tokenType: 'Bearer',
        );

        $this->assertSame($userDto, $dto->user);
        $this->assertSame('1|abc123def456', $dto->token);
        $this->assertSame('Bearer', $dto->tokenType);
    }

    public function test_auth_token_dto_uses_default_token_type(): void
    {
        $userDto = new UserDto(
            id: 'user-000',
            email: 'default@example.com',
            firstName: 'Eve',
            lastName: 'Green',
            fullName: 'Eve Green',
            role: 'student',
            status: 'active',
            emailVerifiedAt: null,
            createdAt: '2026-03-01T00:00:00+00:00',
        );

        $dto = new AuthTokenDto(
            user: $userDto,
            token: '2|xyz789',
        );

        $this->assertSame('Bearer', $dto->tokenType);
    }

    public function test_all_dtos_are_readonly(): void
    {
        $dto = new RegisterUserDto('a@b.com', 'F', 'L', 'pwd', 'student');

        $reflection = new \ReflectionClass($dto);
        $this->assertTrue($reflection->isReadOnly());
    }
}
