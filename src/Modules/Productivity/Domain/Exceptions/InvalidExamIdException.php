<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use RuntimeException;

final class InvalidExamIdException extends RuntimeException
{
    public static function fromValue(string $value): self
    {
        return new self("Invalid exam ID: {$value}");
    }
}
