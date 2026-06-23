<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Enums;

enum EnrollmentStatus: string
{
    case Enrolled   = 'enrolled';
    case Dropped    = 'dropped';
    case Completed  = 'completed';
    case InProgress = 'in_progress';
    case Failed     = 'failed';
    case Postponed  = 'postponed';
    case Equivalent = 'equivalent';

    public function label(): string
    {
        return match ($this) {
            self::Enrolled   => 'Enrolled',
            self::Dropped    => 'Dropped',
            self::Completed  => 'Completed',
            self::InProgress => 'In Progress',
            self::Failed     => 'Failed',
            self::Postponed  => 'Postponed',
            self::Equivalent => 'Equivalent',
        };
    }

    public function isActive(): bool
    {
        return $this === self::Enrolled || $this === self::InProgress;
    }

    public function isCompleted(): bool
    {
        return $this === self::Completed || $this === self::Equivalent;
    }

    public function isFailed(): bool
    {
        return $this === self::Failed;
    }
}
