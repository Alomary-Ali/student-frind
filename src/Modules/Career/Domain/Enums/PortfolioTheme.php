<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Enums;

enum PortfolioTheme: string
{
    case MODERN = 'modern';
    case MINIMAL = 'minimal';
    case CREATIVE = 'creative';
    case PROFESSIONAL = 'professional';

    public function label(): string
    {
        return match ($this) {
            self::MODERN => 'حديث',
            self::MINIMAL => 'بسيط',
            self::CREATIVE => 'إبداعي',
            self::PROFESSIONAL => 'احترافي',
        };
    }
}
