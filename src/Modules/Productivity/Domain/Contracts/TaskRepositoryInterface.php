<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

use Modules\Productivity\Domain\Entities\Task;
use Modules\Productivity\Domain\ValueObjects\TaskId;

interface TaskRepositoryInterface
{
    public function findById(TaskId $id): ?Task;

    public function findByUserId(string $userId): array;

    public function findByGoalId(string $goalId): array;

    public function findOverdueByUserId(string $userId): array;

    public function save(Task $task): void;

    public function delete(TaskId $id): void;
}
