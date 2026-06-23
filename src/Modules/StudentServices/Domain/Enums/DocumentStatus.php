<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum DocumentStatus: string
{
    case PENDING = 'pending';
    case GENERATED = 'generated';
    case VERIFIED = 'verified';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'قيد الإنشاء',
            self::GENERATED => 'صادر',
            self::VERIFIED => 'موثق',
            self::EXPIRED => 'منتهي الصلاحية',
        };
    }
}
