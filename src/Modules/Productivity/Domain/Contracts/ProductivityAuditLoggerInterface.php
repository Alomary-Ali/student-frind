<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

interface ProductivityAuditLoggerInterface
{
    public function log(
        string $actorUserId,
        string $action,
        string $entityType,
        string $entityId,
        ?array $newValues = null,
        ?array $oldValues = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): void;
}
