<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class EloquentGoal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productivity_goals';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'target_date',
        'priority',
        'progress',
        'status',
        'goal_type',
    ];

    protected $casts = [
        'target_date' => 'datetime',
        'progress' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
}
