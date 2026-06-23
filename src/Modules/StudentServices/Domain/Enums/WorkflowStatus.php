<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum WorkflowStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'نشط',
            self::INACTIVE => 'غير نشط',
            self::ARCHIVED => 'مؤرشف',
        };
    }
}
