<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Exceptions;

use DomainException;

final class InvalidResumeIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidResumeIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidResumeIdException: invalid format for value {$value}");
    }
}
