<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class ServiceStatsDto
{
    public function __construct(
        public int $totalRequests,
        public array $byStatus,
        public array $byCategory,
        public float $averageProcessingTime,
    ) {}
}
