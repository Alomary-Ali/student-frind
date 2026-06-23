<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Entities\Task;
use Modules\Productivity\Domain\Enums\TaskStatus;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentTask;

final class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function findById(TaskId $id): ?Task
    {
        $model = EloquentTask::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByUserId(string $userId): array
    {
        $models = EloquentTask::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return array_map(fn ($model) => $this->toEntity($model), $models->all());
    }

    public function findByGoalId(string $goalId): array
    {
        $models = EloquentTask::where('linked_goal_id', $goalId)
            ->orderBy('created_at', 'desc')
            ->get();

        return array_map(fn ($model) => $this->toEntity($model), $models->all());
    }

    public function findOverdueByUserId(string $userId): array
    {
        $now = now();

        $models = EloquentTask::where('user_id', $userId)
            ->where('due_date', '<', $now)
            ->where('status', '!=', TaskStatus::Completed->value)
            ->orderBy('due_date', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toEntity($model), $models->all());
    }

    public function save(Task $task): void
    {
        $model = EloquentTask::find($task->id()->value());

        if ($model === null) {
            $model = new EloquentTask();
            $model->id = $task->id()->value();
        }

        $model->user_id = $task->userId();
        $model->title = $task->title();
        $model->description = $task->description();
        $model->due_date = $task->dueDate();
        $model->priority = $task->priority()->value();
        $model->status = $task->status()->value;
        $model->linked_goal_id = $task->linkedGoalId()?->value();
        $model->completed_at = $task->completedAt();

        $model->save();
    }

    public function delete(TaskId $id): void
    {
        EloquentTask::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentTask $model): Task
    {
        $linkedGoalId = null;
        if ($model->linked_goal_id) {
            try {
                $linkedGoalId = GoalId::fromString($model->linked_goal_id);
            } catch (\Modules\Productivity\Domain\Exceptions\InvalidGoalIdException $e) {
                // Handle invalid goal_id format gracefully by setting to null
                $linkedGoalId = null;
            }
        }

        return Task::reconstitute(
            id: TaskId::fromString($model->id),
            userId: $model->user_id,
            title: $model->title,
            description: $model->description,
            dueDate: $model->due_date ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->due_date->format('Y-m-d H:i:s')) : null,
            priority: PriorityLevel::fromString($model->priority),
            status: TaskStatus::from($model->status),
            linkedGoalId: $linkedGoalId,
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at->format('Y-m-d H:i:s')),
            completedAt: $model->completed_at ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->completed_at->format('Y-m-d H:i:s')) : null,
        );
    }
}
