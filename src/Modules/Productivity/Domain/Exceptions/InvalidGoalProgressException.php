<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class InvalidGoalProgressException extends DomainException
{
    public static function outOfRange(float $value): self
    {
        return new self("Goal progress must be between 0 and 100, got: {$value}");
    }
}
