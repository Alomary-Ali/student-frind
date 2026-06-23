<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\ReadModels;

final readonly class GraduationProgress
{
    public function __construct(
        public string $studentId,
        public int $creditsEarned,
        public int $creditsRequired,
        public float $completionPercentage,
        public bool $isOnTrack,
        public float $cumulativeGpa,
        public ?string $estimatedGraduationDate,
    ) {}
}
