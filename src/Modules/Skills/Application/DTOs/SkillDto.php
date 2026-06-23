<?php

declare(strict_types=1);

namespace Modules\Skills\Application\DTOs;

final readonly class SkillDto
{
    public function __construct(
        public string $id,
        public string $skillProfileId,
        public string $name,
        public string $category,
        public string $categoryLabel,
        public string $level,
        public string $levelLabel,
        public int $levelWeight,
        public int $yearsOfExperience,
        public string $lastUsed,
    ) {}
}
