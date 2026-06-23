<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidCreditsException extends DomainException
{
    public static function outOfRange(int $value): self
    {
        return new self("Credits must be between 0 and 30, got {$value}");
    }
}
