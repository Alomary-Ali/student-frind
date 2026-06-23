<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

final class EloquentUser extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'users';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'academic_id',
        'email',
        'first_name',
        'last_name',
        'password_hash',
        'role',
        'status',
        'email_verified_at',
        'failed_login_attempts',
        'locked_until',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'locked_until' => 'datetime',
        'role' => 'string',
    ];

    /**
     * Get the password for the user.
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    /**
     * Get the password for the user (compatibility).
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }
}
