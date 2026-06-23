<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentStudent extends Model
{
    use HasUuids;

    protected $table = 'academic_students';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'student_number',
        'academic_status',
        'academic_standing',
        'cumulative_gpa',
        'semester_gpa',
        'current_semester_id',
        'institution_id',
        'university_id',
        'college_id',
        'department_id',
        'major_id',
        'level',
    ];

    protected $casts = [
        'cumulative_gpa' => 'float',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(EloquentEnrollment::class, 'student_id');
    }
}
