<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Enums;

enum AcademicStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Graduated = 'graduated';
    case Withdrawn = 'withdrawn';
    case Suspended = 'suspended';

    public function canEnroll(): bool
    {
        return $this === self::Active;
    }

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Graduated => 'Graduated',
            self::Withdrawn => 'Withdrawn',
            self::Suspended => 'Suspended',
        };
    }
}
