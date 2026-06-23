<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum ServiceCategoryType: string
{
    case ACADEMIC = 'academic';
    case DOCUMENT = 'document';
    case FINANCIAL = 'financial';
    case STUDENT_AFFAIRS = 'student_affairs';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ACADEMIC => 'أكاديمي',
            self::DOCUMENT => 'وثائق',
            self::FINANCIAL => 'مالي',
            self::STUDENT_AFFAIRS => 'شؤون طلابية',
            self::OTHER => 'أخرى',
        };
    }
}
