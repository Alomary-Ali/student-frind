<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;

final class EloquentSession extends Model
{
    protected $table = 'sessions';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];
}
