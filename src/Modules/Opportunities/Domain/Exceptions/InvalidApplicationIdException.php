<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Exceptions;

use DomainException;

final class InvalidApplicationIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidApplicationIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidApplicationIdException: invalid format for value {$value}");
    }
}
