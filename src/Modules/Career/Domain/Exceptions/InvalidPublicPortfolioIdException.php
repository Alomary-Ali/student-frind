<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Exceptions;

use DomainException;

final class InvalidPublicPortfolioIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidPublicPortfolioIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidPublicPortfolioIdException: invalid format for value {$value}");
    }
}
