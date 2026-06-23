<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentCareerPathStage extends Model
{
    protected $table = 'career_path_stages';

    protected $fillable = [
        'id',
        'career_path_id',
        'title',
        'order',
        'required_skills',
        'duration_months',
        'salary_range',
        'description',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
