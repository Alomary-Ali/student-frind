<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentExam;

final class ExamPolicy
{
    use HandlesAuthorization;

    public function view($user, EloquentExam $exam): bool
    {
        return $user->id === $exam->user_id;
    }

    public function create($user): bool
    {
        return true;
    }

    public function update($user, EloquentExam $exam): bool
    {
        return $user->id === $exam->user_id;
    }

    public function delete($user, EloquentExam $exam): bool
    {
        return $user->id === $exam->user_id;
    }
}
