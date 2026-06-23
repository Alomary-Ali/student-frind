<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentAssignment;

final class AssignmentPolicy
{
    use HandlesAuthorization;

    public function view($user, EloquentAssignment $assignment): bool
    {
        return $user->id === $assignment->user_id;
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, EloquentAssignment $assignment): bool
    {
        return $user->id === $assignment->user_id;
    }

    public function delete($user, EloquentAssignment $assignment): bool
    {
        return $user->id === $assignment->user_id;
    }
}
