<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentCurriculum extends Model
{
    protected $table = 'academic_curricula';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'name', 'code', 'description',
        'total_credits_required', 'institution_id',
    ];

    protected $casts = [
        'total_credits_required' => 'integer',
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(EloquentCurriculumCourse::class, 'curriculum_id');
    }
}
