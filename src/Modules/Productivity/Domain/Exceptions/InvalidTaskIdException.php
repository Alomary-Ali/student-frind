<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class InvalidTaskIdException extends DomainException
{
    public static function invalidFormat(string $value): self
    {
        return new self("Invalid Task ID format: {$value}");
    }
}
