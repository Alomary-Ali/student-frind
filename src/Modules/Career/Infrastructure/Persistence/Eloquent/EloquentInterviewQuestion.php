<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentInterviewQuestion extends Model
{
    protected $table = 'interview_questions';

    protected $fillable = [
        'id',
        'interview_id',
        'question',
        'category',
        'order',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
