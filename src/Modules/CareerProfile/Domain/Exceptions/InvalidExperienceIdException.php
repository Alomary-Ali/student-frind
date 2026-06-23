<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Exceptions;

use DomainException;

final class InvalidExperienceIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidExperienceIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidExperienceIdException: invalid format for value {$value}");
    }
}
