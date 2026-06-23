<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidGraduationPathIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidGraduationPathIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidGraduationPathIdException: invalid format for value {$value}");
    }
}
