<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Feature\Application\UseCases;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Shared\Application\DTOs\RegisterUserDto;
use Modules\Shared\Application\Mappers\UserMapper;
use Modules\Shared\Application\UseCases\AuthenticateUser;
use Modules\Shared\Application\UseCases\RegisterUser;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Shared\Domain\Contracts\UserRepositoryInterface;
use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Shared\Domain\Exceptions\InvalidCredentialsException;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;
use Tests\TestCase;

final class SharedUseCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_throws_when_email_taken(): void
    {
        $dto = new RegisterUserDto(
            email: 'taken@test.com',
            firstName: 'Jane',
            lastName: 'Doe',
            password: 'secret123',
            role: 'student',
        );

        $repo = $this->createMock(UserRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('existsByEmail')
            ->willReturn(true);

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $mapper = new UserMapper();
        $useCase = new RegisterUser($repo, $dispatcher, $mapper);

        $this->expectException(EmailAlreadyTakenException::class);
        $useCase->execute($dto);
    }

    public function test_authenticate_user_succeeds_with_valid_credentials(): void
    {
        $academicId = AcademicId::of('12345678');
        $password = 'correct-password';
        $user = User::register(
            id: UserId::generate(),
            academicId: $academicId,
            email: EmailAddress::fromString('test@example.com'),
            name: FullName::of('Test', 'User'),
            passwordHash: bcrypt($password),
            role: UserRole::Student,
        );
        $user->verifyEmail();
        $user->releaseEvents();

        $repo = $this->createMock(UserRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findByAcademicId')
            ->with($this->equalTo($academicId))
            ->willReturn($user);
        $repo->expects($this->once())
            ->method('isAccountLocked')
            ->willReturn(false);
        $repo->expects($this->once())
            ->method('getLockedUntil')
            ->willReturn(null);
        $repo->expects($this->once())
            ->method('resetFailedAttempts');

        $useCase = new AuthenticateUser($repo);
        $result = $useCase->execute('12345678', $password);

        $this->assertTrue($result);
    }

    public function test_authenticate_user_throws_when_user_not_found(): void
    {
        $repo = $this->createMock(UserRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findByAcademicId')
            ->willReturn(null);

        $useCase = new AuthenticateUser($repo);

        $this->expectException(InvalidCredentialsException::class);
        $useCase->execute('12345678', 'any-password');
    }

    public function test_authenticate_user_throws_with_wrong_password(): void
    {
        $user = User::register(
            id: UserId::generate(),
            academicId: AcademicId::of('12345678'),
            email: EmailAddress::fromString('test@example.com'),
            name: FullName::of('Test', 'User'),
            passwordHash: bcrypt('correct-password'),
            role: UserRole::Student,
        );

        $repo = $this->createMock(UserRepositoryInterface::class);
        $repo->expects($this->once())
            ->method('findByAcademicId')
            ->willReturn($user);
        $repo->expects($this->once())
            ->method('isAccountLocked')
            ->willReturn(false);
        $repo->expects($this->once())
            ->method('getLockedUntil')
            ->willReturn(null);
        $repo->expects($this->once())
            ->method('incrementFailedAttempts')
            ->willReturn(1);

        $useCase = new AuthenticateUser($repo);

        $this->expectException(InvalidCredentialsException::class);
        $useCase->execute('12345678', 'wrong-password');
    }
}
