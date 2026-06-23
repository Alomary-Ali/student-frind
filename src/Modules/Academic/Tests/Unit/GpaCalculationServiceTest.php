<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Domain\Services\GpaCalculationService;
use PHPUnit\Framework\TestCase;

final class GpaCalculationServiceTest extends TestCase
{
    public function test_calculates_weighted_gpa(): void
    {
        $service = new GpaCalculationService;

        $gpa = $service->calculateCumulativeGpa([
            ['grade_points' => 4.0, 'credit_hours' => 3],
            ['grade_points' => 3.0, 'credit_hours' => 3],
        ]);

        $this->assertSame(3.5, $gpa->value());
    }

    public function test_returns_zero_gpa_for_empty_records(): void
    {
        $service = new GpaCalculationService;

        $this->assertSame(0.0, $service->calculateCumulativeGpa([])->value());
    }
}
