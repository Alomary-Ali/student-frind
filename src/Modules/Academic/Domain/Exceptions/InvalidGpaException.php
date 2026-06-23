<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidGpaException extends DomainException
{
    public static function outOfRange(float $value): self
    {
        return new self("GPA must be between 0.0 and 4.0, got {$value}");
    }
}
