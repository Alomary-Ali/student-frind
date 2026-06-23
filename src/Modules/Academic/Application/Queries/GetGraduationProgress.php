<?php

declare(strict_types=1);

namespace Modules\Academic\Application\Queries;

use Modules\Academic\Domain\Contracts\AcademicPlanReaderInterface;

final readonly class GetGraduationProgress
{
    public function __construct(
        private AcademicPlanReaderInterface $reader,
    ) {}

    public function execute(string $studentId): ?array
    {
        $progress = $this->reader->getGraduationProgress($studentId);

        if ($progress === null) {
            return null;
        }

        return [
            'student_id' => $progress->studentId,
            'credits_earned' => $progress->creditsEarned,
            'credits_required' => $progress->creditsRequired,
            'completion_percentage' => $progress->completionPercentage,
            'is_on_track' => $progress->isOnTrack,
            'cumulative_gpa' => $progress->cumulativeGpa,
            'estimated_graduation_date' => $progress->estimatedGraduationDate,
        ];
    }
}
