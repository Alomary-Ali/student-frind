<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Events;

use DateTimeImmutable;

final readonly class NotificationCreated
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $type,
        public string $title,
        public string $message,
        public string $channel,
        public DateTimeImmutable $createdAt,
    ) {}
}
