<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use DomainException;

final class EmailAlreadyTakenException extends DomainException
{
    public static function forEmail(string $email): self
    {
        return new self("The email address \"{$email}\" is already registered.");
    }
}
