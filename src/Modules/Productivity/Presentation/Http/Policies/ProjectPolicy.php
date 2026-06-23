<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentProject;

final class ProjectPolicy
{
    use HandlesAuthorization;

    public function view($user, EloquentProject $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, EloquentProject $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function delete($user, EloquentProject $project): bool
    {
        return $user->id === $project->user_id;
    }
}
