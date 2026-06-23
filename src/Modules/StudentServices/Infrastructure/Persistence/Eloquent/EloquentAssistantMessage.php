<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentAssistantMessage extends Model
{
    protected $table = 'assistant_messages';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'conversation_id',
        'role',
        'content',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
