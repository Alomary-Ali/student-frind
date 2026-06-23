<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;

final class EloquentSemesterPlan extends Model
{
    protected $table = 'academic_semester_plans';

    protected $fillable = [
        'id',
        'student_id',
        'semester_id',
        'planned_courses',
        'total_credits',
        'status',
        'notes',
        'submitted_at',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'planned_courses' => 'array',
        'total_credits' => 'integer',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public $incrementing = false;

    protected $keyType = 'string';
}
