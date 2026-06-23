<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\UseCases;

use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Domain\ValueObjects\NotificationId;

final readonly class MarkNotificationAsRead
{
    public function __construct(
        private NotificationRepositoryInterface $notifications,
    ) {}

    public function execute(string $notificationId, string $studentId): void
    {
        $id = NotificationId::fromString($notificationId);
        $notification = $this->notifications->findById($id);

        if ($notification === null) {
            return;
        }

        $notification->markAsRead();
        $this->notifications->save($notification);
    }
}
