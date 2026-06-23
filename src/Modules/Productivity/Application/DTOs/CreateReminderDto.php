<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class CreateReminderDto
{
    public function __construct(
        public string $userId,
        public string $message,
        public string $triggerAt,
        public string $type,
        public ?string $linkedTaskId,
    ) {}
}
