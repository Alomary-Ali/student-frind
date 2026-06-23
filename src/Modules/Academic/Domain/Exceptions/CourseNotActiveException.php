<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use DomainException;

final class CourseNotActiveException extends DomainException
{
    public static function forCourse(string $courseId): self
    {
        return new self("Course {$courseId} is not active");
    }
}
