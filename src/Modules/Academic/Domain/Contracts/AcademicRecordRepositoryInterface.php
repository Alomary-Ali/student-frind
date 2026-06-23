<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\AcademicRecord;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\StudentId;

interface AcademicRecordRepositoryInterface
{
    public function save(AcademicRecord $record): void;

    public function findByEnrollmentId(EnrollmentId $enrollmentId): ?AcademicRecord;

    /**
     * @return list<array{grade_points: float, credit_hours: int, semester_id: string}>
     */
    public function findGradedRecordsByStudentId(StudentId $studentId): array;
}
