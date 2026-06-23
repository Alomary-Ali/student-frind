<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;

interface EnrollmentRepositoryInterface
{
    public function findById(EnrollmentId $id): ?Enrollment;

    public function existsForStudentCourseSemester(
        StudentId $studentId,
        CourseId $courseId,
        SemesterId $semesterId,
    ): bool;

    public function save(Enrollment $enrollment): void;

    /** @return list<Enrollment> */
    public function findByStudentId(StudentId $studentId): array;

    /**
     * @return list<Enrollment>
     */
    public function findCompletedByStudent(StudentId $studentId): array;
}
