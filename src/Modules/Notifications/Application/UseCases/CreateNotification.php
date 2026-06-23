<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\UseCases;

use Modules\Notifications\Application\DTOs\NotificationDto;
use Modules\Notifications\Application\Mappers\NotificationMapper;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Domain\Entities\Notification;
use Modules\Notifications\Domain\Enums\NotificationChannel;
use Modules\Notifications\Domain\Enums\NotificationType;
use Modules\Notifications\Domain\ValueObjects\NotificationId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CreateNotification
{
    public function __construct(
        private NotificationRepositoryInterface $notifications,
        private EventDispatcherInterface $events,
        private NotificationMapper $mapper,
    ) {}

    public function execute(
        string $studentId,
        string $type,
        string $title,
        string $message,
        string $channel = 'in_app',
        ?string $link = null,
    ): NotificationDto {
        $notification = Notification::create(
            id: NotificationId::generate(),
            studentId: $studentId,
            type: NotificationType::from($type),
            title: $title,
            message: $message,
            channel: NotificationChannel::from($channel),
            link: $link,
        );

        $this->notifications->save($notification);
        $this->events->dispatch($notification->releaseEvents());

        return $this->mapper->toDto($notification);
    }
}
