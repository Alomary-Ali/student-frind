<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Exceptions;

use DomainException;

final class InvalidInterviewIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidInterviewIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidInterviewIdException: invalid format for value {$value}");
    }
}
