<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Enums;

enum DocumentType: string
{
    case CERTIFICATE = 'certificate';
    case TRANSCRIPT = 'transcript';
    case STATEMENT = 'statement';
    case OFFICIAL_LETTER = 'official_letter';
    case ID_CARD = 'id_card';

    public function label(): string
    {
        return match ($this) {
            self::CERTIFICATE => 'شهادة',
            self::TRANSCRIPT => 'كشف درجات',
            self::STATEMENT => 'إفادة',
            self::OFFICIAL_LETTER => 'خطاب رسمي',
            self::ID_CARD => 'بطاقة جامعية',
        };
    }
}
