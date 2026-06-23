<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EloquentEnrollment extends Model
{
    use HasUuids;

    protected $table = 'academic_enrollments';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'student_id',
        'course_id',
        'semester_id',
        'status',
        'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(EloquentCourse::class, 'course_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(EloquentSemester::class, 'semester_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(EloquentStudent::class, 'student_id');
    }

    public function academicRecord(): HasMany
    {
        return $this->hasMany(EloquentAcademicRecord::class, 'enrollment_id');
    }
}
