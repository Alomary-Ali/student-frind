<?php

declare(strict_types=1);

namespace Modules\Career\Application\DTOs;

final readonly class ComprehensiveDashboardDto
{
    public function __construct(
        public ?array $profile = null,
        public ?array $skillProfile = null,
        public array $opportunities = [],
        public array $interviews = [],
        public array $careerPaths = [],
        public float $readinessScore = 0.0,
        public array $readinessBreakdown = [],
        public array $recentActivity = [],
    ) {}
}
