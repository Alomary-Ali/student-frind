<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentInterview extends Model
{
    protected $table = 'interviews';

    protected $fillable = [
        'id',
        'student_id',
        'type',
        'status',
        'scheduled_at',
        'score',
        'feedback',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function questions(): HasMany
    {
        return $this->hasMany(EloquentInterviewQuestion::class, 'interview_id');
    }
}
