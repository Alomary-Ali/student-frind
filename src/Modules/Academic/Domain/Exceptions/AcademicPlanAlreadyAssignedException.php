<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class AcademicPlanAlreadyAssignedException extends DomainException
{
    public static function forStudent(string $studentId): self
    {
        return new self("Student {$studentId} already has an active academic plan");
    }
}
