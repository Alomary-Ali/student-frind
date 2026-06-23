<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\AcademicPlan;
use Modules\Academic\Domain\ValueObjects\AcademicPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;

interface AcademicPlanRepositoryInterface
{
    public function findById(AcademicPlanId $id): ?AcademicPlan;

    public function findActiveByStudentId(StudentId $studentId): ?AcademicPlan;

    public function save(AcademicPlan $plan): void;
}
