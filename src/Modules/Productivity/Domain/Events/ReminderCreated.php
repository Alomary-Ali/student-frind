<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

final readonly class ReminderCreated
{
    public function __construct(
        public string $reminderId,
        public string $userId,
        public string $message,
        public string $triggerAt,
        public string $type,
        public ?string $linkedTaskId,
        public \DateTimeImmutable $occurredAt,
    ) {}
}
