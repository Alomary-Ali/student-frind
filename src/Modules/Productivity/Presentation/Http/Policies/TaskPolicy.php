<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Policies;

use Illuminate\Auth\Access\Response;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentTask;

final class TaskPolicy
{
    public function view(string $userId, string $taskId): Response
    {
        $task = EloquentTask::find($taskId);

        if ($task === null) {
            return Response::deny('Task not found');
        }

        return $task->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this task');
    }

    public function create(string $userId): Response
    {
        return Response::allow();
    }

    public function update(string $userId, string $taskId): Response
    {
        $task = EloquentTask::find($taskId);

        if ($task === null) {
            return Response::deny('Task not found');
        }

        return $task->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this task');
    }

    public function delete(string $userId, string $taskId): Response
    {
        $task = EloquentTask::find($taskId);

        if ($task === null) {
            return Response::deny('Task not found');
        }

        return $task->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this task');
    }
}
