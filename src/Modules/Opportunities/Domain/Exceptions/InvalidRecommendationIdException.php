<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Exceptions;

use DomainException;

final class InvalidRecommendationIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidRecommendationIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidRecommendationIdException: invalid format for value {$value}");
    }
}
