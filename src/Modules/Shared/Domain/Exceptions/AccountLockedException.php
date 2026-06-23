<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Exceptions;

use Exception;

final class AccountLockedException extends Exception
{
    public static function forUser(string $userId): self
    {
        return new self("Account {$userId} is locked due to too many failed login attempts");
    }

    public static function withMessage(string $message): self
    {
        return new self($message);
    }
}
