<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class LinkedInOptimizationReportDto
{
    /**
     * @param  array<string>  $recommendations
     */
    public function __construct(
        public int $score,
        public array $recommendations,
    ) {}
}
