<?php
declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class ConversationStarted
{
    public function __construct(
        public string $conversationId,
        public string $studentId,
        public DateTimeImmutable $occurredAt,
    ) {}
}
