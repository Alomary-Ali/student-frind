<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use RuntimeException;

final class UserEmailNotVerifiedException extends RuntimeException
{
    public static function forUser(string $userId): self
    {
        return new self("User email not verified for user ID: {$userId}");
    }
}
