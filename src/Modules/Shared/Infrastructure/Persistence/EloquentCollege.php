<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentCollege extends Model
{
    use HasUuids;

    protected $table = 'colleges';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'university_id',
        'name',
        'name_en',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(EloquentUniversity::class, 'university_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(EloquentDepartment::class, 'college_id');
    }
}
