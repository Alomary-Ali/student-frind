<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Domain\Enums;

use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use PHPUnit\Framework\TestCase;

final class SkillsEnumsTest extends TestCase
{
    public function test_skill_category_cases_have_labels(): void
    {
        $this->assertSame('programming', SkillCategory::PROGRAMMING->value);
        $this->assertSame('البرمجة والتطوير', SkillCategory::PROGRAMMING->label());

        $this->assertSame('networking', SkillCategory::NETWORKING->value);
        $this->assertSame('الشبكات والأنظمة', SkillCategory::NETWORKING->label());

        $this->assertSame('design', SkillCategory::DESIGN->value);
        $this->assertSame('التصميم وواجهات المستخدم', SkillCategory::DESIGN->label());

        $this->assertSame('ai', SkillCategory::AI->value);
        $this->assertSame('الذكاء الاصطناعي وهندسة البيانات', SkillCategory::AI->label());

        $this->assertSame('data_analysis', SkillCategory::DATA_ANALYSIS->value);
        $this->assertSame('تحليل البيانات والإحصاء', SkillCategory::DATA_ANALYSIS->label());

        $this->assertSame('leadership', SkillCategory::LEADERSHIP->value);
        $this->assertSame('القيادة والإدارة', SkillCategory::LEADERSHIP->label());

        $this->assertSame('communication', SkillCategory::COMMUNICATION->value);
        $this->assertSame('التواصل والعرض', SkillCategory::COMMUNICATION->label());

        $this->assertSame('teamwork', SkillCategory::TEAMWORK->value);
        $this->assertSame('العمل الجماعي', SkillCategory::TEAMWORK->label());

        $this->assertSame('problem_solving', SkillCategory::PROBLEM_SOLVING->value);
        $this->assertSame('حل المشكلات والتفكير الإبداعي', SkillCategory::PROBLEM_SOLVING->label());

        $this->assertSame('time_management', SkillCategory::TIME_MANAGEMENT->value);
        $this->assertSame('إدارة الوقت والتخطيط', SkillCategory::TIME_MANAGEMENT->label());
    }

    public function test_skill_level_cases_have_labels_and_weights(): void
    {
        $this->assertSame('beginner', SkillLevel::BEGINNER->value);
        $this->assertSame('مبتدئ', SkillLevel::BEGINNER->label());
        $this->assertSame(1, SkillLevel::BEGINNER->weight());

        $this->assertSame('intermediate', SkillLevel::INTERMEDIATE->value);
        $this->assertSame('متوسط', SkillLevel::INTERMEDIATE->label());
        $this->assertSame(2, SkillLevel::INTERMEDIATE->weight());

        $this->assertSame('advanced', SkillLevel::ADVANCED->value);
        $this->assertSame('متقدم', SkillLevel::ADVANCED->label());
        $this->assertSame(3, SkillLevel::ADVANCED->weight());

        $this->assertSame('expert', SkillLevel::EXPERT->value);
        $this->assertSame('خبير', SkillLevel::EXPERT->label());
        $this->assertSame(4, SkillLevel::EXPERT->weight());
    }

    public function test_achievement_type_cases_have_labels(): void
    {
        $this->assertSame('academic', AchievementType::ACADEMIC->value);
        $this->assertSame('أكاديمي', AchievementType::ACADEMIC->label());

        $this->assertSame('productivity', AchievementType::PRODUCTIVITY->value);
        $this->assertSame('إنتاجية', AchievementType::PRODUCTIVITY->label());

        $this->assertSame('career', AchievementType::CAREER->value);
        $this->assertSame('تطوير مهني', AchievementType::CAREER->label());

        $this->assertSame('community', AchievementType::COMMUNITY->value);
        $this->assertSame('مشاركة مجتمعية', AchievementType::COMMUNITY->label());
    }
}
