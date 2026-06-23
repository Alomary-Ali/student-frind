<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Exceptions;

use DomainException;

final class InvalidOpportunityIdException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("InvalidOpportunityIdException: resource not found with id {$id}");
    }

    public static function invalidFormat(string $value): self
    {
        return new self("InvalidOpportunityIdException: invalid format for value {$value}");
    }
}
