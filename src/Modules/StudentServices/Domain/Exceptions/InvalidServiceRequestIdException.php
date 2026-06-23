<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Exceptions;

use DomainException;

final class InvalidServiceRequestIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidServiceRequestIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidServiceRequestIdException: invalid format for value {$value}");
    }
}
