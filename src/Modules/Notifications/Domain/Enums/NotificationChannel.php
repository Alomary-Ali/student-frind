<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Enums;

enum NotificationChannel: string
{
    case IN_APP = 'in_app';
    case EMAIL = 'email';
    case SMS = 'sms';

    public function label(): string
    {
        return match ($this) {
            self::IN_APP => 'داخل التطبيق',
            self::EMAIL => 'بريد إلكتروني',
            self::SMS => 'رسالة نصية',
        };
    }
}
