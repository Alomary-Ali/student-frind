<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Exceptions;

use DomainException;

final class InvalidPortfolioItemIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidPortfolioItemIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidPortfolioItemIdException: invalid format for value {$value}");
    }
}
