<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;

final class EloquentGraduationPath extends Model
{
    protected $table = 'academic_graduation_paths';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'student_id', 'curriculum_id', 'credits_earned',
        'credits_required', 'completion_percentage', 'is_on_track',
        'estimated_graduation_date',
    ];

    protected $casts = [
        'credits_earned' => 'integer',
        'credits_required' => 'integer',
        'completion_percentage' => 'float',
        'is_on_track' => 'boolean',
        'estimated_graduation_date' => 'date',
    ];
}
