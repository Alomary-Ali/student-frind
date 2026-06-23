<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Enums;

enum AcademicStanding: string
{
    case GoodStanding = 'good_standing';
    case Probation = 'probation';
    case Suspension = 'suspension';
    case Dismissed = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::GoodStanding => 'Good Standing',
            self::Probation => 'Probation',
            self::Suspension => 'Suspension',
            self::Dismissed => 'Dismissed',
        };
    }
}
