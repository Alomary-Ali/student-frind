<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Exceptions;

use DomainException;

final class InvalidPortfolioSlugException extends DomainException
{
    public static function invalidFormat(string $value): self
    {
        return new self("InvalidPortfolioSlugException: invalid slug format for value '{$value}'. Slug must be 3-100 characters, lowercase alphanumeric with hyphens.");
    }

    public static function notFound(string $slug): self
    {
        return new self("InvalidPortfolioSlugException: no portfolio found with slug '{$slug}'");
    }
}
