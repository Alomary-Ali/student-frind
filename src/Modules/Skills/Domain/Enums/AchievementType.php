<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Enums;

enum AchievementType: string
{
    case ACADEMIC = 'academic';
    case PRODUCTIVITY = 'productivity';
    case CAREER = 'career';
    case COMMUNITY = 'community';

    public function label(): string
    {
        return match ($this) {
            self::ACADEMIC => 'أكاديمي',
            self::PRODUCTIVITY => 'إنتاجية',
            self::CAREER => 'تطوير مهني',
            self::COMMUNITY => 'مشاركة مجتمعية',
        };
    }
}
