<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class CourseNotFoundException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("CourseNotFoundException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("CourseNotFoundException: invalid format for value {$value}");
    }
}
