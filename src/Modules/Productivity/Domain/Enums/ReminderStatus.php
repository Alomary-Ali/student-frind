<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum ReminderStatus: string
{
    case Pending = 'pending';
    case Triggered = 'triggered';
    case Dismissed = 'dismissed';

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    public function isTriggered(): bool
    {
        return $this === self::Triggered;
    }

    public function isDismissed(): bool
    {
        return $this === self::Dismissed;
    }
}
