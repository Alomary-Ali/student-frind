<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\ReadModels\AcademicPlanSummary;
use Modules\Academic\Domain\ReadModels\GraduationProgress;
use Modules\Academic\Domain\ReadModels\StudentAcademicProfile;

/**
 * Public contract exposed to other modules (read-only).
 */
interface AcademicPlanReaderInterface
{
    public function getStudentProfile(string $studentId): ?StudentAcademicProfile;

    public function getActivePlan(string $studentId): ?AcademicPlanSummary;

    public function getGraduationProgress(string $studentId): ?GraduationProgress;
}
