<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentCareerPath extends Model
{
    protected $table = 'career_paths';

    protected $fillable = [
        'id',
        'title',
        'description',
        'target_role',
        'required_skills',
        'average_salary',
        'growth_rate',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function stages(): HasMany
    {
        return $this->hasMany(EloquentCareerPathStage::class, 'career_path_id');
    }
}
