<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidEnrollmentIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidEnrollmentIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidEnrollmentIdException: invalid format for value {$value}");
    }
}
