<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum ServiceStatus: string
{
    case NEW = 'new';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'جديد',
            self::UNDER_REVIEW => 'قيد المراجعة',
            self::APPROVED => 'معتمد',
            self::REJECTED => 'مرفوض',
            self::COMPLETED => 'مكتمل',
            self::CANCELLED => 'ملغي',
        };
    }
}
