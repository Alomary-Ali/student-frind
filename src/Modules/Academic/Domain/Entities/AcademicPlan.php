<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Enums\AcademicPlanStatus;
use Modules\Academic\Domain\Events\AcademicPlanAssigned;
use Modules\Academic\Domain\ValueObjects\AcademicPlanId;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\StudentId;

final class AcademicPlan
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly AcademicPlanId $id,
        private readonly StudentId $studentId,
        private readonly CurriculumId $curriculumId,
        private AcademicPlanStatus $status,
        private readonly DateTimeImmutable $assignedAt,
        private readonly ?string $institutionId,
    ) {}

    public static function assign(
        AcademicPlanId $id,
        StudentId $studentId,
        CurriculumId $curriculumId,
        ?string $institutionId = null,
    ): self {
        $plan = new self(
            id: $id,
            studentId: $studentId,
            curriculumId: $curriculumId,
            status: AcademicPlanStatus::Active,
            assignedAt: new DateTimeImmutable(),
            institutionId: $institutionId,
        );

        $plan->raise(new AcademicPlanAssigned(
            academicPlanId: $id->value(),
            studentId: $studentId->value(),
            curriculumId: $curriculumId->value(),
            assignedAt: $plan->assignedAt,
        ));

        return $plan;
    }

    public static function reconstitute(
        AcademicPlanId $id,
        StudentId $studentId,
        CurriculumId $curriculumId,
        AcademicPlanStatus $status,
        DateTimeImmutable $assignedAt,
        ?string $institutionId,
    ): self {
        return new self($id, $studentId, $curriculumId, $status, $assignedAt, $institutionId);
    }

    public function complete(): void
    {
        $this->status = AcademicPlanStatus::Completed;
    }

    public function isActive(): bool
    {
        return $this->status === AcademicPlanStatus::Active;
    }

    public function id(): AcademicPlanId { return $this->id; }
    public function studentId(): StudentId { return $this->studentId; }
    public function curriculumId(): CurriculumId { return $this->curriculumId; }
    public function status(): AcademicPlanStatus { return $this->status; }
    public function assignedAt(): DateTimeImmutable { return $this->assignedAt; }
    public function institutionId(): ?string { return $this->institutionId; }

    private function raise(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    /** @return list<object> */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
