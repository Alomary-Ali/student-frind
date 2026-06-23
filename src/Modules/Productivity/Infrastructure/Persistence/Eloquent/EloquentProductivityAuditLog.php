<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentProductivityAuditLog extends Model
{
    protected $table = 'productivity_audit_logs';

    protected $fillable = [
        'id',
        'actor_user_id',
        'action',
        'entity_type',
        'entity_id',
        'new_values',
        'old_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'new_values' => 'array',
        'old_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
}
