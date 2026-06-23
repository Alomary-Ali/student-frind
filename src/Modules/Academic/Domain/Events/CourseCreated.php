<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Events;

final class CourseCreated
{
    public function __construct(
        public readonly string $courseId,
        public readonly string $code,
        public readonly string $title,
        public readonly int $creditHours,
        public readonly \DateTimeImmutable $occurredAt,
    ) {}
}
