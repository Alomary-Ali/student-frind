<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Exceptions;

use DomainException;

final class InvalidSkillIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidSkillIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidSkillIdException: invalid format for value {$value}");
    }
}
