<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\Mappers;

use Modules\Notifications\Application\DTOs\NotificationDto;
use Modules\Notifications\Domain\Entities\Notification;

final class NotificationMapper
{
    public function toDto(Notification $notification): NotificationDto
    {
        return new NotificationDto(
            id: $notification->id()->value(),
            studentId: $notification->studentId(),
            type: $notification->type()->value,
            title: $notification->title(),
            message: $notification->message(),
            channel: $notification->channel()->value,
            link: $notification->link(),
            isRead: $notification->isRead(),
            createdAt: $notification->createdAt()->format('Y-m-d H:i:s'),
        );
    }
}
