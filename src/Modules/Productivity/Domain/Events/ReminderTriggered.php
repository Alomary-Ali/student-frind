<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

final readonly class ReminderTriggered
{
    public function __construct(
        public string $reminderId,
        public string $userId,
        public string $message,
        public string $type,
        public \DateTimeImmutable $triggeredAt,
    ) {}
}
