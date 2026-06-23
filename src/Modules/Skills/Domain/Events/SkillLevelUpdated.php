<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Events;

use DateTimeImmutable;

final readonly class SkillLevelUpdated
{
    public function __construct(
        public string $skillId,
        public string $profileId,
        public string $name,
        public string $oldLevel,
        public string $newLevel,
        public DateTimeImmutable $occurredAt,
    ) {}
}
