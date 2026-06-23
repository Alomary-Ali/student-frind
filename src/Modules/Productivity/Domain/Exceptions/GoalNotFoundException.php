<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class GoalNotFoundException extends DomainException
{
    public static function forId(string $id): self
    {
        return new self("Goal not found: {$id}");
    }
}
