<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Services;

use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\Gpa;

final class GpaCalculationService
{
    /**
     * Calculate weighted GPA from grade points and credit hours.
     *
     * @param list<array{grade_points: float, credit_hours: int}> $records
     */
    public function calculateCumulativeGpa(array $records): Gpa
    {
        if ($records === []) {
            return Gpa::zero();
        }

        $totalPoints = 0.0;
        $totalCredits = 0;

        foreach ($records as $record) {
            $credits = $record['credit_hours'];
            $totalPoints += $record['grade_points'] * $credits;
            $totalCredits += $credits;
        }

        if ($totalCredits === 0) {
            return Gpa::zero();
        }

        return Gpa::of($totalPoints / $totalCredits);
    }

    /**
     * @param list<array{grade_points: float, credit_hours: int}> $records
     */
    public function calculateSemesterGpa(array $records): Gpa
    {
        return $this->calculateCumulativeGpa($records);
    }

    public function calculateCreditsEarned(array $completedCourseCredits): Credits
    {
        $total = array_sum($completedCourseCredits);

        return Credits::of($total);
    }
}
