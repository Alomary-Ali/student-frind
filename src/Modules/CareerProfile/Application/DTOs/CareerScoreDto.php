<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class CareerScoreDto
{
    /**
     * @param  array<string,int>  $breakdown
     */
    public function __construct(
        public int $score,
        public array $breakdown,
    ) {}
}
