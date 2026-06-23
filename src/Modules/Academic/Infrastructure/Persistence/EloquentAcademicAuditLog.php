<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;

final class EloquentAcademicAuditLog extends Model
{
    protected $table = 'academic_audit_logs';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'actor_user_id', 'action', 'entity_type',
        'entity_id', 'old_values', 'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}
