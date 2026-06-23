<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Enums;

enum GradeLetter: string
{
    case A = 'A';
    case AM = 'A-';
    case BP = 'B+';
    case B = 'B';
    case BM = 'B-';
    case CP = 'C+';
    case C = 'C';
    case CM = 'C-';
    case DP = 'D+';
    case D = 'D';
    case F = 'F';

    public function gradePoints(): float
    {
        return match ($this) {
            self::A => 4.0,
            self::AM => 3.7,
            self::BP => 3.3,
            self::B => 3.0,
            self::BM => 2.7,
            self::CP => 2.3,
            self::C => 2.0,
            self::CM => 1.7,
            self::DP => 1.3,
            self::D => 1.0,
            self::F => 0.0,
        };
    }
}
