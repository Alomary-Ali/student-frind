<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class EloquentProject extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'productivity_projects';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'due_date',
        'status',
        'progress_percentage',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'progress_percentage' => 'integer',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Shared\Infrastructure\Persistence\EloquentUser::class, 'user_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EloquentProjectMember::class, 'project_id');
    }
}
