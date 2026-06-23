<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Enums;

enum OpportunityType: string
{
    case JOB = 'job';
    case INTERNSHIP = 'internship';
    case SCHOLARSHIP = 'scholarship';
    case COURSE = 'course';
    case COMPETITION = 'competition';
    case VOLUNTEERING = 'volunteering';
    case CONFERENCE = 'conference';

    public function label(): string
    {
        return match ($this) {
            self::JOB => 'وظيفة',
            self::INTERNSHIP => 'تدريب',
            self::SCHOLARSHIP => 'منحة دراسية',
            self::COURSE => 'دورة تدريبية',
            self::COMPETITION => 'مسابقة',
            self::VOLUNTEERING => 'تطوع',
            self::CONFERENCE => 'مؤتمر',
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
