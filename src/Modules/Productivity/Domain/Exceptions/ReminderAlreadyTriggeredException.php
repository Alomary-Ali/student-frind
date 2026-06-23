<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Exceptions;

use DomainException;

final class ReminderAlreadyTriggeredException extends DomainException
{
    public static function forReminder(string $reminderId): self
    {
        return new self("Reminder {$reminderId} has already been triggered");
    }
}
