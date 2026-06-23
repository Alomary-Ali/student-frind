<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use DomainException;

final class UserNotFoundException extends DomainException
{
    public static function withId(string $id): self
    {
        return new self("User with ID \"{$id}\" was not found.");
    }

    public static function withEmail(string $email): self
    {
        return new self("User with email \"{$email}\" was not found.");
    }
}
