<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Gateways;

use Modules\Notifications\Application\UseCases\CreateNotification;
use Modules\Notifications\Application\UseCases\GetStudentNotifications;
use Modules\StudentServices\Domain\Contracts\Gateways\NotificationGatewayInterface;

final class NotificationGateway implements NotificationGatewayInterface
{
    public function __construct(
        private CreateNotification $createNotification,
        private GetStudentNotifications $getStudentNotifications,
    ) {}

    public function send(string $studentId, string $type, string $title, string $message, ?string $link = null): void
    {
        $this->createNotification->execute($studentId, $type, $title, $message, 'in_app', $link);
    }

    public function getUnreadCount(string $studentId): int
    {
        return count($this->getStudentNotifications->execute($studentId));
    }
}
