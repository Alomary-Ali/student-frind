<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class EloquentAssignment extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'productivity_assignments';

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'assigned_at',
        'due_date',
        'status',
        'grade',
        'submission_url',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Shared\Infrastructure\Persistence\EloquentUser::class, 'user_id');
    }
}
