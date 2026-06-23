<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Enums;

enum AcademicPlanStatus: string
{
    case Active    = 'active';
    case Completed = 'completed';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::Active    => 'Active',
            self::Completed => 'Completed',
            self::Suspended => 'Suspended',
        };
    }
}
