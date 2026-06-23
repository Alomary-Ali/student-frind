<?php

declare(strict_types=1);

namespace Modules\Academic\Application\Queries;

use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\CurriculumRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Domain\ValueObjects\CourseId;

final readonly class GetCurriculumCourses
{
    public function __construct(
        private StudentRepositoryInterface $students,
        private AcademicPlanRepositoryInterface $plans,
        private CurriculumRepositoryInterface $curricula,
        private CourseRepositoryInterface $courses,
    ) {}

    public function execute(string $userId): array
    {
        $student = $this->students->findByUserId($userId);

        if ($student === null) {
            throw StudentNotFoundException::forId($userId);
        }

        $plan = $this->plans->findActiveByStudentId($student->id());

        if ($plan === null) {
            return [];
        }

        $curriculum = $this->curricula->findById($plan->curriculumId());

        if ($curriculum === null) {
            return [];
        }

        $enrollments = $student->enrollments();
        $result = [];

        foreach ($curriculum->courses() as $curriculumCourse) {
            $course = $this->courses->findById($curriculumCourse->courseId());

            if ($course === null) {
                continue;
            }

            $status = $this->determineStatus($curriculumCourse->courseId(), $enrollments);

            $result[] = [
                'id' => $course->id()->value(),
                'code' => $course->code(),
                'name' => $course->title(),
                'credit_hours' => $course->creditHours()->value(),
                'status' => $status,
                'is_required' => $curriculumCourse->isRequired(),
                'semester_order' => $curriculumCourse->semesterOrder(),
            ];
        }

        return $result;
    }

    private function determineStatus(CourseId $courseId, array $enrollments): string
    {
        foreach ($enrollments as $enrollment) {
            if (! $enrollment->courseId()->equals($courseId)) {
                continue;
            }

            return match ($enrollment->status()) {
                EnrollmentStatus::Completed => 'completed',
                EnrollmentStatus::InProgress => 'in_progress',
                EnrollmentStatus::Enrolled => 'in_progress',
                EnrollmentStatus::Failed => 'failed',
                EnrollmentStatus::Postponed => 'postponed',
                EnrollmentStatus::Equivalent => 'completed',
                EnrollmentStatus::Dropped => 'not_started',
            };
        }

        return 'not_started';
    }
}
