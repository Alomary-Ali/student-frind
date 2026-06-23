<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EloquentMajor extends Model
{
    use HasUuids;

    protected $table = 'majors';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'department_id',
        'name',
        'name_en',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(EloquentDepartment::class, 'department_id');
    }
}
