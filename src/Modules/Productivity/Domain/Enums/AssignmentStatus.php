<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum AssignmentStatus: string
{
    case ASSIGNED = 'assigned';
    case IN_PROGRESS = 'in_progress';
    case SUBMITTED = 'submitted';
    case GRADED = 'graded';
    case LATE = 'late';

    public function label(): string
    {
        return match ($this) {
            self::ASSIGNED => 'مسند',
            self::IN_PROGRESS => 'قيد التنفيذ',
            self::SUBMITTED => 'تم التسليم',
            self::GRADED => 'تم التقييم',
            self::LATE => 'متأخر',
        };
    }

    public function isCompleted(): bool
    {
        return match ($this) {
            self::SUBMITTED, self::GRADED => true,
            default => false,
        };
    }

    public function isOverdue(): bool
    {
        return $this === self::LATE;
    }
}
