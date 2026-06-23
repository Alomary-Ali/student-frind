<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Services;

use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Exceptions\PrerequisiteNotMetException;
use Modules\Academic\Domain\ValueObjects\Grade;

final class PrerequisiteValidationService
{
    /**
     * Validate that a student has met all prerequisites for a course.
     *
     * @param  array<string, mixed>  $prerequisites  Array of prerequisite data
     * @param  array<Enrollment>  $completedEnrollments  Student's completed enrollments
     *
     * @throws PrerequisiteNotMetException
     */
    public function validatePrerequisites(
        array $prerequisites,
        array $completedEnrollments,
    ): void {
        foreach ($prerequisites as $prerequisite) {
            $prerequisiteCourseId = $prerequisite['prerequisite_course_id'];
            $isRequired = $prerequisite['is_required'] ?? true;
            $minimumGrade = $prerequisite['minimum_grade'] ?? 2.0;

            if (! $isRequired) {
                continue;
            }

            $hasCompleted = $this->hasCompletedCourse(
                $prerequisiteCourseId,
                $completedEnrollments,
                $minimumGrade,
            );

            if (! $hasCompleted) {
                throw new PrerequisiteNotMetException(
                    $prerequisiteCourseId,
                    $minimumGrade,
                );
            }
        }
    }

    /**
     * Check if student has completed a specific course with minimum grade.
     *
     * @param  array<Enrollment>  $completedEnrollments
     */
    private function hasCompletedCourse(
        string $courseId,
        array $completedEnrollments,
        float $minimumGrade,
    ): bool {
        foreach ($completedEnrollments as $enrollment) {
            if ($enrollment->courseId()->value() === $courseId) {
                if ($enrollment->isCompleted()) {
                    // For now, completion is sufficient
                    // TODO: Add grade checking when AcademicRecord is integrated
                    return true;
                }
            }
        }

        return false;
    }
}
