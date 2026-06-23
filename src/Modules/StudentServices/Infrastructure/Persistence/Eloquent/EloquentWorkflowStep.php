<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentWorkflowStep extends Model
{
    protected $table = 'workflow_steps';

    protected $fillable = [
        'id',
        'workflow_id',
        'name',
        'type',
        'order',
        'config',
        'assignee_role',
        'status',
    ];

    protected $casts = [
        'config' => 'array',
        'order' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(EloquentServiceWorkflow::class, 'workflow_id');
    }
}
