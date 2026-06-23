<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class InvalidCurriculumIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidCurriculumIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidCurriculumIdException: invalid format for value {$value}");
    }
}
