<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\Enums\GoalStatus;
use Modules\Productivity\Domain\Enums\GoalType;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\GoalProgress;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentGoal;

final class EloquentGoalRepository implements GoalRepositoryInterface
{
    public function findById(GoalId $id): ?Goal
    {
        $model = EloquentGoal::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByUserId(string $userId): array
    {
        $models = EloquentGoal::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $goals = [];
        foreach ($models->all() as $model) {
            try {
                $goals[] = $this->toEntity($model);
            } catch (\Modules\Productivity\Domain\Exceptions\InvalidGoalIdException) {
                continue;
            }
        }

        return $goals;
    }

    public function findActiveByUserId(string $userId): array
    {
        $models = EloquentGoal::where('user_id', $userId)
            ->where('status', GoalStatus::Active->value)
            ->orderBy('created_at', 'desc')
            ->get();

        $goals = [];
        foreach ($models->all() as $model) {
            try {
                $goals[] = $this->toEntity($model);
            } catch (\Modules\Productivity\Domain\Exceptions\InvalidGoalIdException $e) {
                // Skip goals with invalid ID format
                continue;
            }
        }

        return $goals;
    }

    public function save(Goal $goal): void
    {
        $model = EloquentGoal::find($goal->id()->value());

        if ($model === null) {
            $model = new EloquentGoal();
            $model->id = $goal->id()->value();
        }

        $model->user_id = $goal->userId();
        $model->title = $goal->title();
        $model->description = $goal->description();
        $model->target_date = $goal->targetDate();
        $model->priority = $goal->priority()->value();
        $model->progress = $goal->progress()->value();
        $model->status = $goal->status()->value;
        $model->goal_type = $goal->goalType()->value;

        $model->save();
    }

    public function delete(GoalId $id): void
    {
        EloquentGoal::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentGoal $model): Goal
    {
        return Goal::reconstitute(
            id: GoalId::fromString($model->id),
            userId: $model->user_id,
            title: $model->title,
            description: $model->description,
            targetDate: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->target_date->format('Y-m-d H:i:s')),
            priority: PriorityLevel::fromString($model->priority),
            progress: GoalProgress::of($model->progress),
            status: GoalStatus::from($model->status),
            goalType: GoalType::from($model->goal_type ?? 'semester'),
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at->format('Y-m-d H:i:s')),
        );
    }
}
