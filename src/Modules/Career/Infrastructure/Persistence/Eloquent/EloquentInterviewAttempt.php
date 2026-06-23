<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentInterviewAttempt extends Model
{
    protected $table = 'interview_attempts';

    protected $fillable = [
        'id',
        'interview_id',
        'student_id',
        'answers',
        'score',
        'feedback',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
