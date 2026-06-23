<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum ConversationStatus: string
{
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'نشط',
            self::CLOSED => 'مغلق',
            self::ARCHIVED => 'مؤرشف',
        };
    }
}
