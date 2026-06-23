<?php

declare(strict_types=1);

namespace Modules\Academic\Application\UseCases;

use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterPlanRepositoryInterface;
use Modules\Academic\Domain\Entities\SemesterPlan;
use Modules\Academic\Domain\Exceptions\CourseNotFoundException;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\SemesterPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;

final readonly class CreateSemesterPlan
{
    public function __construct(
        private SemesterPlanRepositoryInterface $semesterPlanRepository,
        private CourseRepositoryInterface $courseRepository,
    ) {}

    public function execute(string $studentId, string $semesterId, array $courseIds, ?string $notes = null): SemesterPlan
    {
        $studentId = StudentId::fromString($studentId);
        $semesterId = SemesterId::fromString($semesterId);

        // Check if a plan already exists for this student and semester
        $existingPlan = $this->semesterPlanRepository->findByStudentAndSemester($studentId, $semesterId);
        if ($existingPlan !== null) {
            throw new \RuntimeException('A semester plan already exists for this student and semester');
        }

        // Validate all courses exist
        $totalCredits = 0;
        foreach ($courseIds as $courseId) {
            $course = $this->courseRepository->findById(\Modules\Academic\Domain\ValueObjects\CourseId::fromString($courseId));
            if ($course === null) {
                throw new CourseNotFoundException($courseId);
            }
            $totalCredits += $course->creditHours()->value();
        }

        $plan = SemesterPlan::create(
            id: SemesterPlanId::generate(),
            studentId: $studentId,
            semesterId: $semesterId,
            plannedCourses: $courseIds,
            totalCredits: $totalCredits,
            notes: $notes,
        );

        $this->semesterPlanRepository->save($plan);

        return $plan;
    }
}
