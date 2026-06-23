<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class InvalidPriorityLevelException extends DomainException
{
    public static function invalidValue(string $value): self
    {
        return new self("Invalid priority level: {$value}. Must be one of: low, medium, high, urgent");
    }
}
