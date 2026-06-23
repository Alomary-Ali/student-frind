<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use DomainException;

final class InvalidUserIdException extends DomainException
{
    public static function invalidFormat(string $value): self
    {
        return new self("The value \"{$value}\" is not a valid UserId (UUID v4 expected).");
    }
}
