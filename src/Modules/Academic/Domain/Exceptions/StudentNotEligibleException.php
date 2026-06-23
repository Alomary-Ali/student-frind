<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class StudentNotEligibleException extends DomainException
{
    public static function cannotEnroll(string $studentId, string $reason): self
    {
        return new self("Student {$studentId} is not eligible to enroll: {$reason}");
    }
}
