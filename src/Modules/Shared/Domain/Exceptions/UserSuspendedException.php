<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use DomainException;

final class UserSuspendedException extends DomainException
{
    public static function forUser(string $userId): self
    {
        return new self("User \"{$userId}\" account is suspended and cannot perform this action.");
    }
}
