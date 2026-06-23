<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Enums;

enum OpportunityStatus: string
{
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case DRAFT = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'نشط',
            self::CLOSED => 'منتهي',
            self::DRAFT => 'مسودة',
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
