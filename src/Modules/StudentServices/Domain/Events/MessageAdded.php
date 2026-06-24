<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class MessageAdded
{
    public function __construct(
        public string $messageId,
        public string $conversationId,
        public string $role,
        public string $content,
        public DateTimeImmutable $occurredAt,
    ) {}
}
