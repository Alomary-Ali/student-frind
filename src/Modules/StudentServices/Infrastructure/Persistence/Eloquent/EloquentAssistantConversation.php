<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentAssistantConversation extends Model
{
    protected $table = 'assistant_conversations';

    protected $fillable = [
        'id',
        'student_id',
        'title',
        'status',
        'context_data',
        'last_activity_at',
    ];

    protected $casts = [
        'context_data' => 'array',
        'last_activity_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function messages(): HasMany
    {
        return $this->hasMany(EloquentAssistantMessage::class, 'conversation_id');
    }
}
