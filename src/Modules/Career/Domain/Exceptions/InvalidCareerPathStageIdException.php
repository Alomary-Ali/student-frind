<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Exceptions;

use DomainException;

final class InvalidCareerPathStageIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidCareerPathStageIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidCareerPathStageIdException: invalid format for value {$value}");
    }
}
