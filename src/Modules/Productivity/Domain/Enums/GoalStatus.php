<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum GoalStatus: string
{
    case Active = 'active';
    case Completed = 'completed';
    case Archived = 'archived';

    public function isCompleted(): bool
    {
        return $this === self::Completed;
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }

    public function isArchived(): bool
    {
        return $this === self::Archived;
    }
}
