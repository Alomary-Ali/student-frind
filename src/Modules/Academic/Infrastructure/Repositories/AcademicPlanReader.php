<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use Modules\Academic\Domain\Contracts\AcademicPlanReaderInterface;
use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\CurriculumRepositoryInterface;
use Modules\Academic\Domain\Contracts\GraduationPathRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\ReadModels\AcademicPlanSummary;
use Modules\Academic\Domain\ReadModels\GraduationProgress;
use Modules\Academic\Domain\ReadModels\StudentAcademicProfile;
use Modules\Academic\Domain\ValueObjects\StudentId;

final class AcademicPlanReader implements AcademicPlanReaderInterface
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly AcademicPlanRepositoryInterface $plans,
        private readonly CurriculumRepositoryInterface $curricula,
        private readonly GraduationPathRepositoryInterface $graduationPaths,
    ) {}

    public function getStudentProfile(string $studentId): ?StudentAcademicProfile
    {
        $student = $this->students->findById(StudentId::fromString($studentId));

        if ($student === null) {
            return null;
        }

        return new StudentAcademicProfile(
            studentId: $student->id()->value(),
            userId: $student->userId(),
            studentNumber: $student->studentNumber(),
            academicStatus: $student->academicStatus()->value,
            academicStanding: $student->academicStanding()->value,
            cumulativeGpa: $student->cumulativeGpa()->value(),
            institutionId: $student->institutionId(),
            createdAt: $student->createdAt()->format('c'),
        );
    }

    public function getActivePlan(string $studentId): ?AcademicPlanSummary
    {
        $plan = $this->plans->findActiveByStudentId(StudentId::fromString($studentId));

        if ($plan === null) {
            return null;
        }

        $curriculum = $this->curricula->findById($plan->curriculumId());

        return new AcademicPlanSummary(
            academicPlanId: $plan->id()->value(),
            studentId: $plan->studentId()->value(),
            curriculumId: $plan->curriculumId()->value(),
            curriculumName: $curriculum?->name() ?? '',
            status: $plan->status()->value,
            assignedAt: $plan->assignedAt()->format('c'),
        );
    }

    public function getGraduationProgress(string $studentId): ?GraduationProgress
    {
        $student = $this->students->findById(StudentId::fromString($studentId));
        $path = $this->graduationPaths->findByStudentId(StudentId::fromString($studentId));

        if ($student === null || $path === null) {
            return null;
        }

        return new GraduationProgress(
            studentId: $studentId,
            creditsEarned: $path->creditsEarned()->value(),
            creditsRequired: $path->creditsRequired()->value(),
            completionPercentage: $path->completionPercentage(),
            isOnTrack: $path->isOnTrack(),
            cumulativeGpa: $student->cumulativeGpa()->value(),
            estimatedGraduationDate: $path->estimatedGraduationDate()?->format('Y-m-d'),
        );
    }
}
