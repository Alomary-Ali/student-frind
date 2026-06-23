<?php

declare(strict_types=1);

namespace Modules\Shared\Application\Mappers;

use DateTimeInterface;
use Modules\Shared\Application\DTOs\UserDto;
use Modules\Shared\Domain\Entities\User;

final class UserMapper
{
    public function toDto(User $user): UserDto
    {
        return new UserDto(
            id: $user->id()->value(),
            email: $user->email()->value(),
            firstName: $user->name()->firstName(),
            lastName: $user->name()->lastName(),
            fullName: $user->name()->full(),
            role: $user->role()->value,
            status: $user->status()->value,
            emailVerifiedAt: $user->emailVerifiedAt()?->format(DateTimeInterface::ATOM),
            createdAt: $user->createdAt()->format(DateTimeInterface::ATOM),
        );
    }
}
