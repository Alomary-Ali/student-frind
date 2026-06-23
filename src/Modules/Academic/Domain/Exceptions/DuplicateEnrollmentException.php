<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class DuplicateEnrollmentException extends DomainException
{
    public static function forStudentAndCourse(string $studentId, string $courseId, string $semesterId): self
    {
        return new self("Student {$studentId} is already enrolled in course {$courseId} for semester {$semesterId}");
    }
}
