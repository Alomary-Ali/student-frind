<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class EloquentCalendarEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productivity_calendar_events';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'starts_at',
        'ends_at',
        'is_all_day',
        'linked_task_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_all_day' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
}
