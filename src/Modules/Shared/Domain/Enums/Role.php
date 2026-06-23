<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case ADVISOR = 'advisor';
    case STUDENT = 'student';
    case FACULTY = 'faculty';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'مدير النظام',
            self::ADMIN => 'مدير الجامعة',
            self::ADVISOR => 'المرشد الأكاديمي',
            self::STUDENT => 'الطالب',
            self::FACULTY => 'عضو هيئة التدريس',
        };
    }

    public function canManageSystem(): bool
    {
        return $this === self::SUPER_ADMIN;
    }

    public function canManageUniversity(): bool
    {
        return $this === self::SUPER_ADMIN || $this === self::ADMIN;
    }

    public function canAdviseStudents(): bool
    {
        return $this === self::SUPER_ADMIN || $this === self::ADMIN || $this === self::ADVISOR;
    }

    public function canManageCourses(): bool
    {
        return $this === self::SUPER_ADMIN || $this === self::ADMIN || $this === self::FACULTY;
    }
}
