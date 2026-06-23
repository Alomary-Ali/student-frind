<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Enums;

enum ReminderType: string
{
    case Email = 'email';
    case Push = 'push';
    case InApp = 'in_app';

    public function isEmail(): bool
    {
        return $this === self::Email;
    }

    public function isPush(): bool
    {
        return $this === self::Push;
    }

    public function isInApp(): bool
    {
        return $this === self::InApp;
    }
}
