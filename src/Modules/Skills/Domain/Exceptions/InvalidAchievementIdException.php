<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Exceptions;

use DomainException;

final class InvalidAchievementIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidAchievementIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidAchievementIdException: invalid format for value {$value}");
    }
}
