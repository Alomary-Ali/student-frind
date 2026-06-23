<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum EventType: string
{
    case TASK = 'task';
    case EXAM = 'exam';
    case ASSIGNMENT = 'assignment';
    case PROJECT = 'project';
    case PERSONAL = 'personal';
    case ACADEMIC = 'academic';

    public function label(): string
    {
        return match ($this) {
            self::TASK => 'مهمة',
            self::EXAM => 'اختبار',
            self::ASSIGNMENT => 'واجب',
            self::PROJECT => 'مشروع',
            self::PERSONAL => 'شخصي',
            self::ACADEMIC => 'أكاديمي',
        };
    }
}
