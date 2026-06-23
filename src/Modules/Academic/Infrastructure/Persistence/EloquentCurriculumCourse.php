<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentCurriculumCourse extends Model
{
    protected $table = 'academic_curriculum_courses';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'curriculum_id', 'course_id', 'is_required', 'semester_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'semester_order' => 'integer',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(EloquentCurriculum::class, 'curriculum_id');
    }
}
