<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class EloquentPermission extends Model
{
    protected $table = 'permissions';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            EloquentRole::class,
            'role_permissions',
            'permission_id',
            'role_id',
        );
    }
}
