<?php

declare(strict_types=1);

namespace Modules\Opportunities\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Opportunities\Domain\Contracts\SavedOpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Entities\SavedOpportunity;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentSavedOpportunity;

final class EloquentSavedOpportunityRepository implements SavedOpportunityRepositoryInterface
{
    public function findByStudentId(string $studentId): array
    {
        $models = EloquentSavedOpportunity::where('student_id', $studentId)
            ->orderBy('saved_at', 'desc')
            ->get();

        return $models->map(fn (EloquentSavedOpportunity $model) => $this->toEntity($model))->toArray();
    }

    public function findByOpportunityAndStudent(OpportunityId $opportunityId, string $studentId): ?SavedOpportunity
    {
        $model = EloquentSavedOpportunity::where('opportunity_id', $opportunityId->value())
            ->where('student_id', $studentId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function isSaved(string $studentId, OpportunityId $opportunityId): bool
    {
        return EloquentSavedOpportunity::where('student_id', $studentId)
            ->where('opportunity_id', $opportunityId->value())
            ->exists();
    }

    public function save(SavedOpportunity $saved): void
    {
        $model = EloquentSavedOpportunity::where('student_id', $saved->studentId())
            ->where('opportunity_id', $saved->opportunityId()->value())
            ->first();

        if ($model === null) {
            $model = new EloquentSavedOpportunity;
            $model->id = (string) \Ramsey\Uuid\Uuid::uuid4();
        }

        $model->student_id = $saved->studentId();
        $model->opportunity_id = $saved->opportunityId()->value();
        $model->saved_at = $saved->savedAt()->format('Y-m-d H:i:s');
        $model->save();
    }

    public function delete(string $studentId, OpportunityId $opportunityId): void
    {
        EloquentSavedOpportunity::where('student_id', $studentId)
            ->where('opportunity_id', $opportunityId->value())
            ->delete();
    }

    private function toEntity(EloquentSavedOpportunity $model): SavedOpportunity
    {
        return SavedOpportunity::reconstitute(
            studentId: $model->student_id,
            opportunityId: OpportunityId::of($model->opportunity_id),
            savedAt: new DateTimeImmutable($model->saved_at->format('Y-m-d H:i:s')),
        );
    }
}
