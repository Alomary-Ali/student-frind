<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final class StudentCreated
{
    public function __construct(
        public readonly string $studentId,
        public readonly string $userId,
        public readonly string $studentNumber,
        public readonly \DateTimeImmutable $occurredAt,
    ) {}
}
