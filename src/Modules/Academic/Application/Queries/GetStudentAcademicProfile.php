<?php

declare(strict_types=1);

namespace Modules\Academic\Application\Queries;

use Modules\Academic\Domain\Contracts\AcademicPlanReaderInterface;

final readonly class GetStudentAcademicProfile
{
    public function __construct(
        private AcademicPlanReaderInterface $reader,
    ) {}

    public function execute(string $studentId): ?array
    {
        $profile = $this->reader->getStudentProfile($studentId);

        if ($profile === null) {
            return null;
        }

        return [
            'id' => $profile->studentId,
            'user_id' => $profile->userId,
            'student_number' => $profile->studentNumber,
            'academic_status' => $profile->academicStatus,
            'academic_standing' => $profile->academicStanding,
            'cumulative_gpa' => $profile->cumulativeGpa,
            'institution_id' => $profile->institutionId,
            'created_at' => $profile->createdAt,
        ];
    }
}
