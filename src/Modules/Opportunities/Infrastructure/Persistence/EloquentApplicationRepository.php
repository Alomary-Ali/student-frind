<?php

declare(strict_types=1);

namespace Modules\Opportunities\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Opportunities\Domain\Contracts\ApplicationRepositoryInterface;
use Modules\Opportunities\Domain\Entities\OpportunityApplication;
use Modules\Opportunities\Domain\Enums\ApplicationStatus;
use Modules\Opportunities\Domain\ValueObjects\ApplicationId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentOpportunityApplication;

final class EloquentApplicationRepository implements ApplicationRepositoryInterface
{
    public function findById(ApplicationId $id): ?OpportunityApplication
    {
        $model = EloquentOpportunityApplication::find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function findByStudentId(string $studentId): array
    {
        $models = EloquentOpportunityApplication::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $models->map(fn (EloquentOpportunityApplication $model) => $this->toEntity($model))->toArray();
    }

    public function findByOpportunityAndStudent(OpportunityId $opportunityId, string $studentId): ?OpportunityApplication
    {
        $model = EloquentOpportunityApplication::where('opportunity_id', $opportunityId->value())
            ->where('student_id', $studentId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function save(OpportunityApplication $application): void
    {
        $model = EloquentOpportunityApplication::find($application->id()->value());

        if ($model === null) {
            $model = new EloquentOpportunityApplication;
            $model->id = $application->id()->value();
        }

        $model->opportunity_id = $application->opportunityId()->value();
        $model->student_id = $application->studentId();
        $model->application_status = $application->status()->value;
        $model->applied_at = $application->appliedAt()?->format('Y-m-d H:i:s');
        $model->notes = $application->notes();
        $model->save();
    }

    public function delete(ApplicationId $id): void
    {
        EloquentOpportunityApplication::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentOpportunityApplication $model): OpportunityApplication
    {
        return OpportunityApplication::reconstitute(
            id: ApplicationId::of($model->id),
            opportunityId: OpportunityId::of($model->opportunity_id),
            studentId: $model->student_id,
            status: ApplicationStatus::from($model->application_status),
            appliedAt: $model->applied_at ? new DateTimeImmutable($model->applied_at->format('Y-m-d H:i:s')) : null,
            notes: $model->notes,
        );
    }
}
