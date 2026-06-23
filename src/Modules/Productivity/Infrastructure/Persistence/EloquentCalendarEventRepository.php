<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface;
use Modules\Productivity\Domain\Entities\CalendarEvent;
use Modules\Productivity\Domain\ValueObjects\CalendarEventId;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentCalendarEvent;

final class EloquentCalendarEventRepository implements CalendarEventRepositoryInterface
{
    public function findById(CalendarEventId $id): ?CalendarEvent
    {
        $model = EloquentCalendarEvent::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByUserId(string $userId): array
    {
        $models = EloquentCalendarEvent::where('user_id', $userId)
            ->orderBy('starts_at', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toEntity($model), $models->all());
    }

    public function findByDateRange(string $userId, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $models = EloquentCalendarEvent::where('user_id', $userId)
            ->where('starts_at', '>=', $start->format('Y-m-d H:i:s'))
            ->where('ends_at', '<=', $end->format('Y-m-d H:i:s'))
            ->orderBy('starts_at', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toEntity($model), $models->all());
    }

    public function save(CalendarEvent $event): void
    {
        $model = EloquentCalendarEvent::find($event->id()->value());

        if ($model === null) {
            $model = new EloquentCalendarEvent;
            $model->id = $event->id()->value();
        }

        $model->user_id = $event->userId();
        $model->title = $event->title();
        $model->description = $event->description();
        $model->starts_at = $event->startsAt();
        $model->ends_at = $event->endsAt();
        $model->is_all_day = $event->isAllDay();
        $model->linked_task_id = $event->linkedTaskId()?->value();

        $model->save();
    }

    public function delete(CalendarEventId $id): void
    {
        EloquentCalendarEvent::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentCalendarEvent $model): CalendarEvent
    {
        return CalendarEvent::reconstitute(
            id: CalendarEventId::fromString($model->id),
            userId: $model->user_id,
            title: $model->title,
            description: $model->description,
            startsAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->starts_at->format('Y-m-d H:i:s')),
            endsAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->ends_at->format('Y-m-d H:i:s')),
            isAllDay: $model->is_all_day,
            linkedTaskId: $model->linked_task_id ? TaskId::fromString($model->linked_task_id) : null,
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at->format('Y-m-d H:i:s')),
        );
    }
}
