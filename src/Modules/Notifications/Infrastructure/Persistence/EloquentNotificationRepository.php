<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Domain\Entities\Notification;
use Modules\Notifications\Domain\Enums\NotificationChannel;
use Modules\Notifications\Domain\Enums\NotificationType;
use Modules\Notifications\Domain\ValueObjects\NotificationId;
use Modules\Notifications\Infrastructure\Persistence\Eloquent\EloquentNotification;

final class EloquentNotificationRepository implements NotificationRepositoryInterface
{
    public function findById(NotificationId $id): ?Notification
    {
        $model = EloquentNotification::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(string $studentId, int $limit = 20): array
    {
        $models = EloquentNotification::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $models->map(fn ($model) => $this->toEntity($model))->toArray();
    }

    public function findUnreadByStudentId(string $studentId): array
    {
        $models = EloquentNotification::where('student_id', $studentId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return $models->map(fn ($model) => $this->toEntity($model))->toArray();
    }

    public function save(Notification $notification): void
    {
        $model = EloquentNotification::find($notification->id()->value());

        if ($model === null) {
            $model = new EloquentNotification;
            $model->id = $notification->id()->value();
        }

        $model->student_id = $notification->studentId();
        $model->type = $notification->type()->value;
        $model->title = $notification->title();
        $model->message = $notification->message();
        $model->channel = $notification->channel()->value;
        $model->link = $notification->link();
        $model->is_read = $notification->isRead();
        $model->save();
    }

    public function delete(NotificationId $id): void
    {
        EloquentNotification::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentNotification $model): Notification
    {
        return Notification::reconstitute(
            id: NotificationId::fromString($model->id),
            studentId: $model->student_id,
            type: NotificationType::from($model->type),
            title: $model->title,
            message: $model->message,
            channel: NotificationChannel::from($model->channel),
            link: $model->link,
            isRead: (bool) $model->is_read,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
