<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Enums;

enum SkillLevel: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    case EXPERT = 'expert';

    public function label(): string
    {
        return match ($this) {
            self::BEGINNER => 'مبتدئ',
            self::INTERMEDIATE => 'متوسط',
            self::ADVANCED => 'متقدم',
            self::EXPERT => 'خبير',
        };
    }

    public function weight(): int
    {
        return match ($this) {
            self::BEGINNER => 1,
            self::INTERMEDIATE => 2,
            self::ADVANCED => 3,
            self::EXPERT => 4,
        };
    }
}
