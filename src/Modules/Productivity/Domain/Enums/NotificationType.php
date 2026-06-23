<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum NotificationType: string
{
    case TASK_DUE = 'task_due';
    case TASK_OVERDUE = 'task_overdue';
    case GOAL_DEADLINE = 'goal_deadline';
    case EXAM_REMINDER = 'exam_reminder';
    case ASSIGNMENT_DUE = 'assignment_due';
    case LOW_PRODUCTIVITY = 'low_productivity';
    case PROJECT_DEADLINE = 'project_deadline';
    case SYSTEM = 'system';

    public function label(): string
    {
        return match ($this) {
            self::TASK_DUE => 'موعد مهمة',
            self::TASK_OVERDUE => 'مهمة متأخرة',
            self::GOAL_DEADLINE => 'موعد هدف',
            self::EXAM_REMINDER => 'تذكير اختبار',
            self::ASSIGNMENT_DUE => 'موعد واجب',
            self::LOW_PRODUCTIVITY => 'انخفاض الإنتاجية',
            self::PROJECT_DEADLINE => 'موعد مشروع',
            self::SYSTEM => 'نظام',
        };
    }

    public function isUrgent(): bool
    {
        return match ($this) {
            self::TASK_OVERDUE, self::EXAM_REMINDER, self::ASSIGNMENT_DUE => true,
            default => false,
        };
    }
}
