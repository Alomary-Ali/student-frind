<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class StudentAlreadyExistsException extends DomainException
{
    public static function forUserId(string $userId): self
    {
        return new self("Academic profile already exists for user {$userId}");
    }
}
