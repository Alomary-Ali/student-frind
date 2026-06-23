<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum ExamType: string
{
    case MIDTERM = 'midterm';
    case FINAL = 'final';
    case QUIZ = 'quiz';
    case PRACTICAL = 'practical';
    case ORAL = 'oral';

    public function label(): string
    {
        return match ($this) {
            self::MIDTERM => 'منتصف الفصل',
            self::FINAL => 'نهائي',
            self::QUIZ => 'اختبار قصير',
            self::PRACTICAL => 'عملي',
            self::ORAL => 'شفوي',
        };
    }

    public function weight(): float
    {
        return match ($this) {
            self::FINAL => 0.4,
            self::MIDTERM => 0.3,
            self::PRACTICAL => 0.2,
            self::QUIZ => 0.05,
            self::ORAL => 0.05,
        };
    }
}
