<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case PendingVerification = 'pending_verification';

    public function isAllowedToLogin(): bool
    {
        // In a university platform, accounts are created by administrators.
        // Email verification is optional — both Active and PendingVerification
        // users can log in. Only Suspended users are blocked.
        return match ($this) {
            self::Active, self::PendingVerification => true,
            self::Suspended, self::Inactive => false,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Suspended => 'Suspended',
            self::PendingVerification => 'Pending Email Verification',
        };
    }
}
