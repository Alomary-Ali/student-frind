<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Modules\Productivity\Domain\Contracts\ProductivityAuditLoggerInterface;
use Ramsey\Uuid\Uuid;

final class EloquentProductivityAuditLogger implements ProductivityAuditLoggerInterface
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
    ): void {
        DB::table('productivity_audit_logs')->insert([
            'id' => Uuid::uuid4()->toString(),
            'actor_user_id' => $actorUserId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
