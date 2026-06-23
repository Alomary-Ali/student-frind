<?php

declare(strict_types=1);

namespace Modules\Skills\Application\DTOs;

final readonly class AchievementDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $type,
        public string $typeLabel,
        public string $title,
        public string $description,
        public ?string $badgeUrl,
        public string $unlockedAt,
    ) {
    }
}
