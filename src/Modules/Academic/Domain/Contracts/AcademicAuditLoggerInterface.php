<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

interface AcademicAuditLoggerInterface
{
    /**
     * @param array<string, mixed>|null $oldValues
     * @param array<string, mixed>|null $newValues
     */
    public function log(
        string $actorUserId,
        string $action,
        string $entityType,
        string $entityId,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): void;
}
