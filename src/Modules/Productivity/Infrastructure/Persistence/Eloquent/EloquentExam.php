<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class EloquentExam extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'productivity_exams';

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'exam_type',
        'exam_date',
        'location',
        'status',
        'readiness_status',
    ];

    protected $casts = [
        'exam_date' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Shared\Infrastructure\Persistence\EloquentUser::class, 'user_id');
    }
}
