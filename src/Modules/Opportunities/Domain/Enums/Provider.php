<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Enums;

enum Provider: string
{
    case LINKEDIN = 'linkedin';
    case COURSERA = 'coursera';
    case FUTURE_LEARN = 'future_learn';
    case EDRAK = 'edrak';
    case FOR9A = 'for9a';
    case INTERNSHALA = 'internshala';
    case MANUAL = 'manual';
    case UNIVERSITY = 'university';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::LINKEDIN => 'LinkedIn',
            self::COURSERA => 'Coursera',
            self::FUTURE_LEARN => 'FutureLearn',
            self::EDRAK => 'إدراك',
            self::FOR9A => 'فرصة',
            self::INTERNSHALA => 'Internshala',
            self::MANUAL => 'إدخال يدوي',
            self::UNIVERSITY => 'الجامعة',
            self::OTHER => 'مصدر آخر',
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
