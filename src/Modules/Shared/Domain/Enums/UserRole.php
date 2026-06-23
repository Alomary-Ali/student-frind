<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Enums;

enum UserRole: string
{
    case Student        = 'student';
    case Advisor        = 'advisor';
    case CareerCounselor = 'career_counselor';
    case Admin          = 'admin';
    case Mentor         = 'mentor';
    case Employer       = 'employer';

    public function label(): string
    {
        return match($this) {
            self::Student         => 'Student',
            self::Advisor         => 'Academic Advisor',
            self::CareerCounselor => 'Career Counselor',
            self::Admin           => 'Administrator',
            self::Mentor          => 'Mentor',
            self::Employer        => 'Employer',
        };
    }

    public function isAcademicStaff(): bool
    {
        return in_array($this, [self::Advisor, self::CareerCounselor, self::Admin], true);
    }

    public function isExternal(): bool
    {
        return in_array($this, [self::Mentor, self::Employer], true);
    }
}
