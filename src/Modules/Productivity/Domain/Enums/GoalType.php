<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum GoalType: string
{
    case Daily = 'daily';
    case Semester = 'semester';
    case LongTerm = 'long_term';

    public function label(): string
    {
        return match ($this) {
            self::Daily => 'يومي',
            self::Semester => 'فصلي',
            self::LongTerm => 'طويل المدى',
        };
    }
}
