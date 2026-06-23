<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EloquentSemester extends Model
{
    use HasUuids;

    protected $table = 'academic_semesters';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'name_en',
        'code',
        'start_date',
        'end_date',
        'is_active',
        'institution_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
}
