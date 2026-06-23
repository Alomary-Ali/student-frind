<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidAlertIdException extends DomainException
{
    public static function invalidFormat(string $value): self
    {
        return new self("Invalid Alert ID format: {$value}");
    }
}
