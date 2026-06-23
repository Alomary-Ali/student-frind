<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EloquentUniversity extends Model
{
    use HasUuids;

    protected $table = 'universities';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'name_en',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function colleges()
    {
        return $this->hasMany(EloquentCollege::class, 'university_id');
    }
}
