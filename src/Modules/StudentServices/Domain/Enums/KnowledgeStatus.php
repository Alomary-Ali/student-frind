<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum KnowledgeStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'مسودة',
            self::PUBLISHED => 'منشور',
            self::ARCHIVED => 'مؤرشف',
        };
    }
}
