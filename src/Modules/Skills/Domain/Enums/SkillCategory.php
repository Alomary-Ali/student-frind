<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Enums;

enum SkillCategory: string
{
    case PROGRAMMING = 'programming';
    case NETWORKING = 'networking';
    case DESIGN = 'design';
    case AI = 'ai';
    case DATA_ANALYSIS = 'data_analysis';
    case LEADERSHIP = 'leadership';
    case COMMUNICATION = 'communication';
    case TEAMWORK = 'teamwork';
    case PROBLEM_SOLVING = 'problem_solving';
    case TIME_MANAGEMENT = 'time_management';

    public function label(): string
    {
        return match ($this) {
            self::PROGRAMMING => 'البرمجة والتطوير',
            self::NETWORKING => 'الشبكات والأنظمة',
            self::DESIGN => 'التصميم وواجهات المستخدم',
            self::AI => 'الذكاء الاصطناعي وهندسة البيانات',
            self::DATA_ANALYSIS => 'تحليل البيانات والإحصاء',
            self::LEADERSHIP => 'القيادة والإدارة',
            self::COMMUNICATION => 'التواصل والعرض',
            self::TEAMWORK => 'العمل الجماعي',
            self::PROBLEM_SOLVING => 'حل المشكلات والتفكير الإبداعي',
            self::TIME_MANAGEMENT => 'إدارة الوقت والتخطيط',
        };
    }
}
