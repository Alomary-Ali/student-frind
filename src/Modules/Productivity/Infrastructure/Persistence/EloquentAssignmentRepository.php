<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Domain\Entities\Assignment;
use Modules\Productivity\Domain\ValueObjects\AssignmentId;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentAssignment;
use Modules\Shared\Domain\ValueObjects\UserId;

final class EloquentAssignmentRepository implements AssignmentRepositoryInterface
{
    public function save(Assignment $assignment): void
    {
        $model = EloquentAssignment::updateOrCreate(
            ['id' => $assignment->id()->value()],
            $assignment->toArray(),
        );
    }

    public function findById(AssignmentId $id): ?Assignment
    {
        $model = EloquentAssignment::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(UserId $userId): array
    {
        $models = EloquentAssignment::where('user_id', $userId->value())
            ->orderBy('due_date', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toDomain($model), $models->toArray());
    }

    public function findUpcomingByUserId(UserId $userId, int $days = 7): array
    {
        $models = EloquentAssignment::where('user_id', $userId->value())
            ->where('due_date', '>=', now()->toDateTime())
            ->where('due_date', '<=', now()->addDays($days)->toDateTime())
            ->whereNotIn('status', ['submitted', 'graded'])
            ->orderBy('due_date', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toDomain($model), $models->toArray());
    }

    public function findOverdueByUserId(UserId $userId): array
    {
        $models = EloquentAssignment::where('user_id', $userId->value())
            ->where('due_date', '<', now()->toDateTime())
            ->whereNotIn('status', ['submitted', 'graded'])
            ->orderBy('due_date', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toDomain($model), $models->toArray());
    }

    public function delete(AssignmentId $id): void
    {
        EloquentAssignment::destroy($id->value());
    }

    private function toDomain(EloquentAssignment $model): Assignment
    {
        return Assignment::fromArray($model->toArray());
    }
}
