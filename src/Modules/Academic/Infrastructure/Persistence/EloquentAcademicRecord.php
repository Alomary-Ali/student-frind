<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EloquentAcademicRecord extends Model
{
    use HasUuids;

    protected $table = 'academic_records';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'enrollment_id',
        'student_id',
        'course_id',
        'grade_letter',
        'grade_points',
        'recorded_at',
        'recorded_by_user_id',
    ];

    protected $casts = [
        'grade_points' => 'float',
        'recorded_at' => 'datetime',
    ];
}
