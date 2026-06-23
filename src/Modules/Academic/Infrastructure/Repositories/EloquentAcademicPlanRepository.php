<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Entities\AcademicPlan;
use Modules\Academic\Domain\Enums\AcademicPlanStatus;
use Modules\Academic\Domain\ValueObjects\AcademicPlanId;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicPlan;

final class EloquentAcademicPlanRepository implements AcademicPlanRepositoryInterface
{
    public function findById(AcademicPlanId $id): ?AcademicPlan
    {
        $model = EloquentAcademicPlan::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findActiveByStudentId(StudentId $studentId): ?AcademicPlan
    {
        $model = EloquentAcademicPlan::where('student_id', $studentId->value())
            ->where('status', AcademicPlanStatus::Active->value)
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function save(AcademicPlan $plan): void
    {
        EloquentAcademicPlan::updateOrCreate(
            ['id' => $plan->id()->value()],
            [
                'student_id' => $plan->studentId()->value(),
                'curriculum_id' => $plan->curriculumId()->value(),
                'status' => $plan->status()->value,
                'assigned_at' => $plan->assignedAt(),
                'institution_id' => $plan->institutionId(),
            ],
        );
    }

    private function toDomain(EloquentAcademicPlan $model): AcademicPlan
    {
        return AcademicPlan::reconstitute(
            id: AcademicPlanId::fromString($model->id),
            studentId: StudentId::fromString($model->student_id),
            curriculumId: CurriculumId::fromString($model->curriculum_id),
            status: AcademicPlanStatus::from($model->status),
            assignedAt: new DateTimeImmutable($model->assigned_at->toIso8601String()),
            institutionId: $model->institution_id,
        );
    }
}
