<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentServiceRequest extends Model
{
    protected $table = 'student_service_requests';

    protected $fillable = [
        'id',
        'ref_number',
        'category_id',
        'student_id',
        'status',
        'priority',
        'notes',
        'admin_notes',
        'workflow_id',
        'current_step_id',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function category(): BelongsTo
    {
        return $this->belongsTo(EloquentServiceCategory::class, 'category_id');
    }
}
