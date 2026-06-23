<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class EloquentProductivitySnapshot extends Model
{
    use HasFactory;

    protected $table = 'productivity_snapshots';

    protected $fillable = [
        'id',
        'user_id',
        'total_goals',
        'completed_goals',
        'total_tasks',
        'completed_tasks',
        'overdue_tasks',
        'completion_rate',
        'snapshot_date',
    ];

    protected $casts = [
        'completion_rate' => 'float',
        'snapshot_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
}
