<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Enums;

enum ApplicationStatus: string
{
    case SAVED = 'saved';
    case APPLIED = 'applied';
    case IN_REVIEW = 'in_review';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case WITHDRAWN = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::SAVED => 'محفوظ',
            self::APPLIED => 'تم التقديم',
            self::IN_REVIEW => 'قيد المراجعة',
            self::ACCEPTED => 'مقبول',
            self::REJECTED => 'مرفوض',
            self::WITHDRAWN => 'منسحب',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::ACCEPTED, self::REJECTED, self::WITHDRAWN], true);
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
