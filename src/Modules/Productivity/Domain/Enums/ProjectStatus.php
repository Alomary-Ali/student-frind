<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum ProjectStatus: string
{
    case PLANNING = 'planning';
    case IN_PROGRESS = 'in_progress';
    case ON_HOLD = 'on_hold';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PLANNING => 'تخطيط',
            self::IN_PROGRESS => 'قيد التنفيذ',
            self::ON_HOLD => 'معلق',
            self::COMPLETED => 'مكتمل',
            self::CANCELLED => 'ملغي',
        };
    }

    public function isActive(): bool
    {
        return match ($this) {
            self::PLANNING, self::IN_PROGRESS => true,
            default => false,
        };
    }
}
