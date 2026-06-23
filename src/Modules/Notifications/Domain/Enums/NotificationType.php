<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Enums;

enum NotificationType: string
{
    case INFO = 'info';
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case ERROR = 'error';

    public function label(): string
    {
        return match ($this) {
            self::INFO => 'معلومات',
            self::SUCCESS => 'نجاح',
            self::WARNING => 'تحذير',
            self::ERROR => 'خطأ',
        };
    }
}
