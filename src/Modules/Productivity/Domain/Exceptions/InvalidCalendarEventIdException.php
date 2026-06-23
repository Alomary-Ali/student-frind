<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class InvalidCalendarEventIdException extends DomainException
{
    public static function invalidFormat(string $value): self
    {
        return new self("Invalid Calendar Event ID format: {$value}");
    }
}
