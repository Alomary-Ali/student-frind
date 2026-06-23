<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;

final class EloquentAcademicPlan extends Model
{
    protected $table = 'academic_plans';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'student_id', 'curriculum_id', 'status',
        'assigned_at', 'institution_id',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];
}
