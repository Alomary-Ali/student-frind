<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentAssistantSuggestion extends Model
{
    protected $table = 'assistant_suggestions';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'conversation_id',
        'message_id',
        'suggestion_type',
        'title',
        'action_url',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
