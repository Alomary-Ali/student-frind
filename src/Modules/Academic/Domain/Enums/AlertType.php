<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Enums;

enum AlertType: string
{
    case LowGpa = 'low_gpa';
    case GraduationDelay = 'graduation_delay';
    case RepeatedFailure = 'repeated_failure';
    case CreditDeficit = 'credit_deficit';
    case PrerequisiteNotMet = 'prerequisite_not_met';
    case EnrollmentConflict = 'enrollment_conflict';
}
