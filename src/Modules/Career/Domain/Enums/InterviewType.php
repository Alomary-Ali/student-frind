<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Enums;

enum InterviewType: string
{
    case MOCK = 'mock';
    case TECHNICAL = 'technical';
    case BEHAVIORAL = 'behavioral';
    case GENERAL = 'general';

    public function label(): string
    {
        return match ($this) {
            self::MOCK => 'مقابلة تجريبية',
            self::TECHNICAL => 'تقنية',
            self::BEHAVIORAL => 'سلوكية',
            self::GENERAL => 'عامة',
        };
    }
}
