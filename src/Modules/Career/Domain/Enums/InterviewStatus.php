<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Enums;

enum InterviewStatus: string
{
    case SCHEDULED = 'scheduled';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => 'مجدولة',
            self::IN_PROGRESS => 'قيد التنفيذ',
            self::COMPLETED => 'مكتملة',
            self::CANCELLED => 'ملغاة',
        };
    }
}
