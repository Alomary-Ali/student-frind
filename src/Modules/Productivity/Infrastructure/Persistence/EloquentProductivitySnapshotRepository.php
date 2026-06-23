<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Productivity\Domain\Contracts\ProductivitySnapshotRepositoryInterface;
use Modules\Productivity\Domain\Entities\ProductivitySnapshot;
use Modules\Productivity\Domain\ValueObjects\ProductivitySnapshotId;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentProductivitySnapshot;

final class EloquentProductivitySnapshotRepository implements ProductivitySnapshotRepositoryInterface
{
    public function findById(ProductivitySnapshotId $id): ?ProductivitySnapshot
    {
        $model = EloquentProductivitySnapshot::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByUserId(string $userId): array
    {
        $models = EloquentProductivitySnapshot::where('user_id', $userId)
            ->orderBy('snapshot_date', 'desc')
            ->get();

        return array_map(fn ($model) => $this->toEntity($model), $models->all());
    }

    public function findLatestByUserId(string $userId): ?ProductivitySnapshot
    {
        $model = EloquentProductivitySnapshot::where('user_id', $userId)
            ->orderBy('snapshot_date', 'desc')
            ->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(ProductivitySnapshot $snapshot): void
    {
        $model = EloquentProductivitySnapshot::find($snapshot->id()->value());

        if ($model === null) {
            $model = new EloquentProductivitySnapshot;
            $model->id = $snapshot->id()->value();
        }

        $model->user_id = $snapshot->userId();
        $model->total_goals = $snapshot->totalGoals();
        $model->completed_goals = $snapshot->completedGoals();
        $model->total_tasks = $snapshot->totalTasks();
        $model->completed_tasks = $snapshot->completedTasks();
        $model->overdue_tasks = $snapshot->overdueTasks();
        $model->completion_rate = $snapshot->completionRate();
        $model->snapshot_date = $snapshot->snapshotDate();

        $model->save();
    }

    public function delete(ProductivitySnapshotId $id): void
    {
        EloquentProductivitySnapshot::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentProductivitySnapshot $model): ProductivitySnapshot
    {
        return ProductivitySnapshot::reconstitute(
            id: ProductivitySnapshotId::fromString($model->id),
            userId: $model->user_id,
            totalGoals: $model->total_goals,
            completedGoals: $model->completed_goals,
            totalTasks: $model->total_tasks,
            completedTasks: $model->completed_tasks,
            overdueTasks: $model->overdue_tasks,
            completionRate: $model->completion_rate,
            snapshotDate: DateTimeImmutable::createFromFormat('Y-m-d', $model->snapshot_date->format('Y-m-d')),
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at->format('Y-m-d H:i:s')),
        );
    }
}
