<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasUuids;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'academic_id',
        'first_name',
        'last_name',
        'email',
        'password_hash',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'role' => 'string',
            // NOTE: NO 'hashed' cast on password_hash.
            // Passwords are pre-hashed by the domain layer before storage.
            // Adding 'hashed' here would double-hash and break all logins.
        ];
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // NOTE: getAuthIdentifierName() is intentionally NOT overridden.
    // Laravel must use the primary key 'id' (UUID) as the session identifier.
    // If we return 'academic_id' here, Laravel stores academic_id in session
    // then tries to retrieve by UUID → mismatch → user logged out immediately.
    // The AuthenticateUser use case handles academic_id lookup manually.
}
