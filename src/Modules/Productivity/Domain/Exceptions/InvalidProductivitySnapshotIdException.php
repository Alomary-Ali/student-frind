<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class InvalidProductivitySnapshotIdException extends DomainException
{
    public static function invalidFormat(string $value): self
    {
        return new self("Invalid Productivity Snapshot ID format: {$value}");
    }
}
