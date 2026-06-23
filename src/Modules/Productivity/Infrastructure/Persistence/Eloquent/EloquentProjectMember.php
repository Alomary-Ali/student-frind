<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class EloquentProjectMember extends Model
{
    use HasUuids;

    protected $table = 'project_members';

    protected $fillable = [
        'project_id',
        'student_id',
    ];

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EloquentProject::class, 'project_id');
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Academic\Infrastructure\Persistence\EloquentStudent::class, 'student_id');
    }
}
