<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentDepartment extends Model
{
    use HasUuids;

    protected $table = 'departments';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'college_id',
        'name',
        'name_en',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(EloquentCollege::class, 'college_id');
    }

    public function majors(): HasMany
    {
        return $this->hasMany(EloquentMajor::class, 'department_id');
    }
}
