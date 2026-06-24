<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\Gateways\NotificationGatewayInterface;

final readonly class CreateServiceNotification
{
    public function __construct(
        private NotificationGatewayInterface $notifications,
    ) {}

    public function execute(string $studentId, string $type, string $title, string $message, ?string $link = null): array
    {
        $this->notifications->send($studentId, $type, $title, $message, $link);

        return [
            'student_id' => $studentId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'sent' => true,
        ];
    }
}
