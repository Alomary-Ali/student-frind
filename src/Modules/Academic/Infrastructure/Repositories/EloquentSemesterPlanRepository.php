<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Academic\Domain\Contracts\SemesterPlanRepositoryInterface;
use Modules\Academic\Domain\Entities\SemesterPlan;
use Modules\Academic\Domain\ValueObjects\SemesterPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Infrastructure\Persistence\EloquentSemesterPlan as EloquentSemesterPlan;

final class EloquentSemesterPlanRepository implements SemesterPlanRepositoryInterface
{
    public function findById(SemesterPlanId $id): ?SemesterPlan
    {
        $model = EloquentSemesterPlan::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByStudentAndSemester(StudentId $studentId, SemesterId $semesterId): ?SemesterPlan
    {
        $model = EloquentSemesterPlan::where('student_id', $studentId->value())
            ->where('semester_id', $semesterId->value())
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function save(SemesterPlan $plan): void
    {
        EloquentSemesterPlan::updateOrCreate(
            ['id' => $plan->id()->value()],
            [
                'student_id' => $plan->studentId()->value(),
                'semester_id' => $plan->semesterId()->value(),
                'planned_courses' => json_encode($plan->plannedCourses()),
                'total_credits' => $plan->totalCredits(),
                'status' => $plan->status(),
                'notes' => $plan->notes(),
                'submitted_at' => $plan->submittedAt()?->format('Y-m-d H:i:s'),
                'approved_by' => $plan->approvedBy(),
                'approved_at' => $plan->approvedAt()?->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function findByStudent(StudentId $studentId): array
    {
        return EloquentSemesterPlan::where('student_id', $studentId->value())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn (EloquentSemesterPlan $m) => $this->toDomain($m))
            ->all();
    }

    private function toDomain(EloquentSemesterPlan $model): SemesterPlan
    {
        return SemesterPlan::reconstitute(
            id: SemesterPlanId::fromString($model->id),
            studentId: StudentId::fromString($model->student_id),
            semesterId: SemesterId::fromString($model->semester_id),
            plannedCourses: json_decode($model->planned_courses, true),
            totalCredits: (int) $model->total_credits,
            status: $model->status,
            notes: $model->notes,
            submittedAt: $model->submitted_at ? new DateTimeImmutable($model->submitted_at->toIso8601String()) : null,
            approvedBy: $model->approved_by,
            approvedAt: $model->approved_at ? new DateTimeImmutable($model->approved_at->toIso8601String()) : null,
            createdAt: new DateTimeImmutable($model->created_at->toIso8601String()),
            updatedAt: new DateTimeImmutable($model->updated_at->toIso8601String()),
        );
    }
}
