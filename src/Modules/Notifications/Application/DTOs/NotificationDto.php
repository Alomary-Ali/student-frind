<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\DTOs;

final readonly class NotificationDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $type,
        public string $title,
        public string $message,
        public string $channel,
        public ?string $link = null,
        public bool $isRead = false,
        public string $createdAt = '',
    ) {}
}
