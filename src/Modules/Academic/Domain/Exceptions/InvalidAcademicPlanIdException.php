<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidAcademicPlanIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidAcademicPlanIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidAcademicPlanIdException: invalid format for value {$value}");
    }
}
