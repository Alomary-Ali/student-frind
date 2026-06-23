<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Enums;

enum GoalStatus: string
{
    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case POSTPONED = 'postponed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::NOT_STARTED => 'لم يبدأ بعد',
            self::IN_PROGRESS => 'قيد التنفيذ',
            self::COMPLETED => 'مكتمل',
            self::POSTPONED => 'مؤجل',
            self::CANCELLED => 'ملغي',
        };
    }
}
