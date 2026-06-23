<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class ReminderDto
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $message,
        public string $triggerAt,
        public string $type,
        public ?string $linkedTaskId,
        public string $status,
        public string $createdAt,
        public ?string $triggeredAt,
        public bool $isDue,
    ) {}
}
