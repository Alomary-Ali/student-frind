<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class EloquentRole extends Model
{
    protected $table = 'roles';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'label',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            EloquentPermission::class,
            'role_permissions',
            'role_id',
            'permission_id',
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            EloquentUser::class,
            'user_roles',
            'role_id',
            'user_id',
        );
    }
}
