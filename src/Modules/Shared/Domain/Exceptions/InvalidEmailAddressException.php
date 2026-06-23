<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use DomainException;

final class InvalidEmailAddressException extends DomainException
{
    public static function invalidFormat(string $email): self
    {
        return new self("The value \"{$email}\" is not a valid email address.");
    }
}
