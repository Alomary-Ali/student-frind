<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum RequestPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'منخفضة',
            self::MEDIUM => 'متوسطة',
            self::HIGH => 'عالية',
            self::URGENT => 'عاجلة',
        };
    }
}
