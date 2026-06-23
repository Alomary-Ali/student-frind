<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum MessageRole: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
    case SYSTEM = 'system';

    public function label(): string
    {
        return match ($this) {
            self::USER => 'طالب',
            self::ASSISTANT => 'مساعد',
            self::SYSTEM => 'نظام',
        };
    }
}
