<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'student_id',
        'type',
        'title',
        'message',
        'channel',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
