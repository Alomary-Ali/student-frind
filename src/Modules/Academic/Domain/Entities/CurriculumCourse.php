<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use Modules\Academic\Domain\ValueObjects\CourseId;

final class CurriculumCourse
{
    public function __construct(
        private readonly CourseId $courseId,
        private readonly bool $isRequired,
        private readonly int $semesterOrder,
    ) {}

    public function courseId(): CourseId
    {
        return $this->courseId;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function semesterOrder(): int
    {
        return $this->semesterOrder;
    }
}
