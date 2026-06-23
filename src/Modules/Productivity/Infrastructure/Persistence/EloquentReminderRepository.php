<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Productivity\Domain\Entities\Reminder;
use Modules\Productivity\Domain\Enums\ReminderStatus;
use Modules\Productivity\Domain\Enums\ReminderType;
use Modules\Productivity\Domain\ValueObjects\ReminderId;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentReminder;

final class EloquentReminderRepository implements ReminderRepositoryInterface
{
    public function findById(ReminderId $id): ?Reminder
    {
        $model = EloquentReminder::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByUserId(string $userId): array
    {
        $models = EloquentReminder::where('user_id', $userId)
            ->orderBy('trigger_at', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toEntity($model), $models->all());
    }

    public function findDueByUserId(string $userId): array
    {
        $now = now();

        $models = EloquentReminder::where('user_id', $userId)
            ->where('trigger_at', '<=', $now)
            ->where('status', ReminderStatus::Pending->value)
            ->orderBy('trigger_at', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toEntity($model), $models->all());
    }

    public function save(Reminder $reminder): void
    {
        $model = EloquentReminder::find($reminder->id()->value());

        if ($model === null) {
            $model = new EloquentReminder();
            $model->id = $reminder->id()->value();
        }

        $model->user_id = $reminder->userId();
        $model->message = $reminder->message();
        $model->trigger_at = $reminder->triggerAt();
        $model->type = $reminder->type()->value;
        $model->linked_task_id = $reminder->linkedTaskId()?->value();
        $model->status = $reminder->status()->value;
        $model->triggered_at = $reminder->triggeredAt();

        $model->save();
    }

    public function delete(ReminderId $id): void
    {
        EloquentReminder::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentReminder $model): Reminder
    {
        return Reminder::reconstitute(
            id: ReminderId::fromString($model->id),
            userId: $model->user_id,
            message: $model->message,
            triggerAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->trigger_at->format('Y-m-d H:i:s')),
            type: ReminderType::from($model->type),
            linkedTaskId: $model->linked_task_id ? TaskId::fromString($model->linked_task_id) : null,
            status: ReminderStatus::from($model->status),
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at->format('Y-m-d H:i:s')),
            triggeredAt: $model->triggered_at ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->triggered_at->format('Y-m-d H:i:s')) : null,
        );
    }
}
