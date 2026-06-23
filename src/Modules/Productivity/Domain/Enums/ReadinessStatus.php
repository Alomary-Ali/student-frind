<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum ReadinessStatus: string
{
    case NotReady = 'not_ready';
    case NeedsReview = 'needs_review';
    case PartiallyReady = 'partially_ready';
    case FullyReady = 'fully_ready';

    public function label(): string
    {
        return match ($this) {
            self::NotReady => 'غير مستعد',
            self::NeedsReview => 'يحتاج مراجعة',
            self::PartiallyReady => 'جاهز جزئياً',
            self::FullyReady => 'جاهز بالكامل',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NotReady => '#EF4444',
            self::NeedsReview => '#F59E0B',
            self::PartiallyReady => '#243B7C',
            self::FullyReady => '#10B981',
        };
    }
}
