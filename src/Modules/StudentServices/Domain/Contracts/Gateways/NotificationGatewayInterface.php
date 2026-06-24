<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Contracts\Gateways;

interface NotificationGatewayInterface
{
    public function send(string $studentId, string $type, string $title, string $message, ?string $link = null): void;

    public function getUnreadCount(string $studentId): int;
}
