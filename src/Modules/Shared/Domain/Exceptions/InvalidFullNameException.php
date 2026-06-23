<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use DomainException;

final class InvalidFullNameException extends DomainException
{
    public static function invalidFirstName(string $name): self
    {
        return new self("First name \"{$name}\" must be between 2 and 50 characters.");
    }

    public static function invalidLastName(string $name): self
    {
        return new self("Last name \"{$name}\" must be between 2 and 50 characters.");
    }
}
