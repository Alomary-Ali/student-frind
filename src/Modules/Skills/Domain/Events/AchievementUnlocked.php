<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Events;

use DateTimeImmutable;

final readonly class AchievementUnlocked
{
    public function __construct(
        public string $achievementId,
        public string $studentId,
        public string $title,
        public string $type,
        public DateTimeImmutable $occurredAt,
    ) {}
}
