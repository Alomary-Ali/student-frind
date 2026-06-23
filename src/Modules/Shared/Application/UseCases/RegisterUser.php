<?php

declare(strict_types=1);

namespace Modules\Shared\Application\UseCases;

use Illuminate\Support\Facades\Hash;
use Modules\Shared\Application\DTOs\RegisterUserDto;
use Modules\Shared\Application\DTOs\UserDto;
use Modules\Shared\Application\Mappers\UserMapper;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Shared\Domain\Contracts\UserRepositoryInterface;
use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class RegisterUser
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher,
        private UserMapper $userMapper,
    ) {}

    public function execute(RegisterUserDto $dto): UserDto
    {
        $email = EmailAddress::fromString($dto->email);

        if ($this->userRepository->existsByEmail($email)) {
            throw EmailAlreadyTakenException::forEmail($dto->email);
        }

        $userId = UserId::generate();
        $name = FullName::of($dto->firstName, $dto->lastName);
        $passwordHash = Hash::make($dto->password);
        $role = UserRole::from($dto->role);

        $user = User::register(
            id: $userId,
            email: $email,
            name: $name,
            passwordHash: $passwordHash,
            role: $role,
        );

        $this->userRepository->save($user);

        $this->eventDispatcher->dispatch($user->releaseEvents());

        return $this->userMapper->toDto($user);
    }
}
