<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class EloquentCourse extends Model
{
    use HasUuids;

    protected $table = 'academic_courses';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'code',
        'title',
        'description',
        'credit_hours',
        'is_active',
        'institution_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_hours' => 'integer',
    ];
}
