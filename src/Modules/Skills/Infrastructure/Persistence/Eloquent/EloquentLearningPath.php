<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class EloquentLearningPath extends Model
{
    use HasFactory;

    protected $table = 'learning_paths';

    protected $fillable = [
        'id',
        'student_id',
        'title',
        'target_role',
        'steps',
        'progress',
        'estimated_completion_date',
    ];

    protected $casts = [
        'steps' => 'array',
        'progress' => 'integer',
        'estimated_completion_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
}
