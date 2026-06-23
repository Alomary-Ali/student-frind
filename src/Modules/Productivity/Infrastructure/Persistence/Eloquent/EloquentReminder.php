<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class EloquentReminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productivity_reminders';

    protected $fillable = [
        'id',
        'user_id',
        'message',
        'trigger_at',
        'type',
        'linked_task_id',
        'status',
        'triggered_at',
    ];

    protected $casts = [
        'trigger_at' => 'datetime',
        'triggered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
}
