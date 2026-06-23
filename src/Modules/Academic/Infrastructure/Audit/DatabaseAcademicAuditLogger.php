<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Audit;

use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicAuditLog;
use Ramsey\Uuid\Uuid;

final class DatabaseAcademicAuditLogger implements AcademicAuditLoggerInterface
{
    public function log(
        string $actorUserId,
        string $action,
        string $entityType,
        string $entityId,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): void {
        EloquentAcademicAuditLog::create([
            'id' => Uuid::uuid4()->toString(),
            'actor_user_id' => $actorUserId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
