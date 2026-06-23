<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Enums;

enum ResumeTemplate: string
{
    case ATS_FRIENDLY = 'ats_friendly';
    case MODERN = 'modern';
    case ACADEMIC = 'academic';
    case PROFESSIONAL = 'professional';

    public function label(): string
    {
        return match ($this) {
            self::ATS_FRIENDLY => 'متوافق مع أنظمة التوظيف (ATS)',
            self::MODERN => 'تصميم عصري',
            self::ACADEMIC => 'أكاديمي',
            self::PROFESSIONAL => 'احترافي تقليدي',
        };
    }
}
