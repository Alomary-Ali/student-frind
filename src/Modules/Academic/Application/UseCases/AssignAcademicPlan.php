<?php

declare(strict_types=1);

namespace Modules\Academic\Application\UseCases;

use Modules\Academic\Application\DTOs\AssignAcademicPlanDto;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\CurriculumRepositoryInterface;
use Modules\Academic\Domain\Contracts\GraduationPathRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Contracts\TransactionManagerInterface;
use Modules\Academic\Domain\Entities\AcademicPlan;
use Modules\Academic\Domain\Entities\GraduationPath;
use Modules\Academic\Domain\Exceptions\AcademicPlanAlreadyAssignedException;
use Modules\Academic\Domain\Exceptions\CurriculumNotFoundException;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Domain\ValueObjects\AcademicPlanId;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\GraduationPathId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class AssignAcademicPlan
{
    public function __construct(
        private StudentRepositoryInterface $students,
        private CurriculumRepositoryInterface $curricula,
        private AcademicPlanRepositoryInterface $plans,
        private GraduationPathRepositoryInterface $graduationPaths,
        private TransactionManagerInterface $transactions,
        private EventDispatcherInterface $events,
        private AcademicAuditLoggerInterface $audit,
    ) {}

    public function execute(AssignAcademicPlanDto $dto): array
    {
        return $this->transactions->runInTransaction(function () use ($dto) {
            $studentId = StudentId::fromString($dto->studentId);
            $student = $this->students->findById($studentId)
                ?? throw StudentNotFoundException::forId($dto->studentId);

            $curriculum = $this->curricula->findById(CurriculumId::fromString($dto->curriculumId))
                ?? throw CurriculumNotFoundException::forId($dto->curriculumId);

            if ($this->plans->findActiveByStudentId($studentId) !== null) {
                throw AcademicPlanAlreadyAssignedException::forStudent($dto->studentId);
            }

            $plan = AcademicPlan::assign(
                id: AcademicPlanId::generate(),
                studentId: $studentId,
                curriculumId: $curriculum->id(),
                institutionId: $dto->institutionId,
            );

            $graduationPath = GraduationPath::initialize(
                id: GraduationPathId::generate(),
                studentId: $studentId,
                curriculumId: $curriculum->id(),
                creditsRequired: $curriculum->totalCreditsRequired(),
                estimatedGraduationDate: $dto->estimatedGraduationDate
                    ? new \DateTimeImmutable($dto->estimatedGraduationDate)
                    : null,
            );

            $this->plans->save($plan);
            $this->graduationPaths->save($graduationPath);

            $events = array_merge($plan->releaseEvents(), $student->releaseEvents());
            $this->events->dispatch($events);

            $this->audit->log(
                actorUserId: $dto->actorUserId,
                action: 'academic_plan.assigned',
                entityType: 'academic_plan',
                entityId: $plan->id()->value(),
                newValues: [
                    'student_id' => $dto->studentId,
                    'curriculum_id' => $dto->curriculumId,
                ],
            );

            return [
                'academic_plan_id' => $plan->id()->value(),
                'student_id' => $student->id()->value(),
                'curriculum_id' => $curriculum->id()->value(),
                'status' => $plan->status()->value,
                'assigned_at' => $plan->assignedAt()->format('c'),
            ];
        });
    }
}
