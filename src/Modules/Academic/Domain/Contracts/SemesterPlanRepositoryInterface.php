<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\SemesterPlan;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\SemesterPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;

interface SemesterPlanRepositoryInterface
{
    public function findById(SemesterPlanId $id): ?SemesterPlan;

    public function findByStudentAndSemester(StudentId $studentId, SemesterId $semesterId): ?SemesterPlan;

    public function save(SemesterPlan $plan): void;

    /** @return list<SemesterPlan> */
    public function findByStudent(StudentId $studentId): array;
}
