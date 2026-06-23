<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Policies;

use Illuminate\Auth\Access\Response;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentCalendarEvent;

final class CalendarEventPolicy
{
    public function view(string $userId, string $eventId): Response
    {
        $event = EloquentCalendarEvent::find($eventId);

        if ($event === null) {
            return Response::deny('Event not found');
        }

        return $event->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this event');
    }

    public function create(string $userId): Response
    {
        return Response::allow();
    }

    public function update(string $userId, string $eventId): Response
    {
        $event = EloquentCalendarEvent::find($eventId);

        if ($event === null) {
            return Response::deny('Event not found');
        }

        return $event->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this event');
    }

    public function delete(string $userId, string $eventId): Response
    {
        $event = EloquentCalendarEvent::find($eventId);

        if ($event === null) {
            return Response::deny('Event not found');
        }

        return $event->user_id === $userId
            ? Response::allow()
            : Response::deny('You do not own this event');
    }
}
