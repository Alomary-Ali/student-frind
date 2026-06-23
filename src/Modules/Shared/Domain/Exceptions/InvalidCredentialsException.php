<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use RuntimeException;

final class InvalidCredentialsException extends RuntimeException
{
    public static function create(): self
    {
        return new self('Invalid email or password.');
    }
}
