<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum TaskPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function weight(): int
    {
        return match ($this) {
            self::LOW => 1,
            self::MEDIUM => 2,
            self::HIGH => 3,
            self::URGENT => 4,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'منخفض',
            self::MEDIUM => 'متوسط',
            self::HIGH => 'عالي',
            self::URGENT => 'عاجل',
        };
    }
}
