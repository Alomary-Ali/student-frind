<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class CreateCalendarEventDto
{
    public function __construct(
        public string $userId,
        public string $title,
        public string $description,
        public string $startsAt,
        public string $endsAt,
        public bool $isAllDay,
        public ?string $linkedTaskId,
    ) {}
}
