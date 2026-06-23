<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\ValueObjects\CourseId;

interface CourseRepositoryInterface
{
    public function findById(CourseId $id): ?Course;

    public function findByCode(string $code): ?Course;

    public function save(Course $course): void;

    /** @return list<Course> */
    public function findAllActive(): array;

    public function findAllActivePaginated(int $page, int $perPage): object;

    /**
     * @return list<array{prerequisite_course_id: string, is_required: bool, minimum_grade: float}>
     */
    public function findPrerequisites(CourseId $courseId): array;
}
