<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentServiceWorkflow extends Model
{
    protected $table = 'service_workflows';

    protected $fillable = [
        'id',
        'service_category_id',
        'name',
        'status',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function steps(): HasMany
    {
        return $this->hasMany(EloquentWorkflowStep::class, 'workflow_id');
    }
}
