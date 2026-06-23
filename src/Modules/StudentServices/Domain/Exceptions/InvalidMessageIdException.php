<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Exceptions;

use DomainException;

final class InvalidMessageIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidMessageIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidMessageIdException: invalid format for value {$value}");
    }
}
