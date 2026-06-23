<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final class GpaUpdated
{
    public function __construct(
        public readonly string $studentId,
        public readonly float $previousGpa,
        public readonly float $newGpa,
        public readonly \DateTimeImmutable $updatedAt,
    ) {}
}
