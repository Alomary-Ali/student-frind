<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EloquentAcademicAlert extends Model
{
    use HasUuids;

    protected $table = 'academic_advisory_alerts';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'student_id',
        'alert_type',
        'severity',
        'message',
        'metadata',
        'is_resolved',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(EloquentStudent::class, 'student_id');
    }
}
