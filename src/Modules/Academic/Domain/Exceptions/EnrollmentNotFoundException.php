<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class EnrollmentNotFoundException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("EnrollmentNotFoundException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("EnrollmentNotFoundException: invalid format for value {$value}");
    }
}
