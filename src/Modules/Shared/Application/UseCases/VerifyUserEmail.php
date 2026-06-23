<?php

declare(strict_types=1);

namespace Modules\Shared\Application\UseCases;

use Modules\Shared\Application\DTOs\UserDto;
use Modules\Shared\Application\Mappers\UserMapper;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Shared\Domain\Contracts\UserRepositoryInterface;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class VerifyUserEmail
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher,
        private UserMapper $userMapper,
    ) {}

    public function execute(string $userIdStr): UserDto
    {
        $userId = new UserId($userIdStr);
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw UserNotFoundException::forUser($userIdStr);
        }

        $user->verifyEmail();

        $this->userRepository->save($user);

        $this->eventDispatcher->dispatch($user->releaseEvents());

        return $this->userMapper->toDto($user);
    }
}
