<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Postponed = 'postponed';
    case Cancelled = 'cancelled';

    public function isCompleted(): bool
    {
        return $this === self::Completed;
    }

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    public function isInProgress(): bool
    {
        return $this === self::InProgress;
    }

    public function isPostponed(): bool
    {
        return $this === self::Postponed;
    }

    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }
}
