<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Policies;

use Illuminate\Auth\Access\Response;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentGoal;

final class GoalPolicy
{
    public function view(string $userId, string $goalId): Response
    {
        $goal = EloquentGoal::find($goalId);

        if ($goal === null) {
            return Response::deny('Goal not found');
        }

        return $goal->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this goal');
    }

    public function create(string $userId): Response
    {
        return Response::allow();
    }

    public function update(string $userId, string $goalId): Response
    {
        $goal = EloquentGoal::find($goalId);

        if ($goal === null) {
            return Response::deny('Goal not found');
        }

        return $goal->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this goal');
    }

    public function delete(string $userId, string $goalId): Response
    {
        $goal = EloquentGoal::find($goalId);

        if ($goal === null) {
            return Response::deny('Goal not found');
        }

        return $goal->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this goal');
    }
}
