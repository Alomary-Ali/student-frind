<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class GoalAlreadyCompletedException extends DomainException
{
    public static function forGoal(string $goalId): self
    {
        return new self("Goal {$goalId} is already completed and cannot be modified");
    }
}
