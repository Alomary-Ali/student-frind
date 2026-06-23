<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Policies;

use Illuminate\Auth\Access\Response;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentReminder;

final class ReminderPolicy
{
    public function view(string $userId, string $reminderId): Response
    {
        $reminder = EloquentReminder::find($reminderId);

        if ($reminder === null) {
            return Response::deny('Reminder not found');
        }

        return $reminder->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this reminder');
    }

    public function create(string $userId): Response
    {
        return Response::allow();
    }

    public function delete(string $userId, string $reminderId): Response
    {
        $reminder = EloquentReminder::find($reminderId);

        if ($reminder === null) {
            return Response::deny('Reminder not found');
        }

        return $reminder->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this reminder');
    }
}
