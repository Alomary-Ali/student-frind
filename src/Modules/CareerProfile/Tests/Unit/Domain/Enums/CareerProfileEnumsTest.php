<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Domain\Enums;

use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use PHPUnit\Framework\TestCase;

final class CareerProfileEnumsTest extends TestCase
{
    public function test_goal_status_cases_have_correct_values(): void
    {
        $this->assertSame('not_started', GoalStatus::NOT_STARTED->value);
        $this->assertSame('in_progress', GoalStatus::IN_PROGRESS->value);
        $this->assertSame('completed', GoalStatus::COMPLETED->value);
        $this->assertSame('postponed', GoalStatus::POSTPONED->value);
        $this->assertSame('cancelled', GoalStatus::CANCELLED->value);
    }

    public function test_goal_status_labels_are_arabic(): void
    {
        $this->assertSame('لم يبدأ بعد', GoalStatus::NOT_STARTED->label());
        $this->assertSame('قيد التنفيذ', GoalStatus::IN_PROGRESS->label());
        $this->assertSame('مكتمل', GoalStatus::COMPLETED->label());
        $this->assertSame('مؤجل', GoalStatus::POSTPONED->label());
        $this->assertSame('ملغي', GoalStatus::CANCELLED->label());
    }

    public function test_resume_template_cases_have_correct_values(): void
    {
        $this->assertSame('ats_friendly', ResumeTemplate::ATS_FRIENDLY->value);
        $this->assertSame('modern', ResumeTemplate::MODERN->value);
        $this->assertSame('academic', ResumeTemplate::ACADEMIC->value);
        $this->assertSame('professional', ResumeTemplate::PROFESSIONAL->value);
    }

    public function test_resume_template_labels_are_arabic(): void
    {
        $this->assertSame('متوافق مع أنظمة التوظيف (ATS)', ResumeTemplate::ATS_FRIENDLY->label());
        $this->assertSame('تصميم عصري', ResumeTemplate::MODERN->label());
        $this->assertSame('أكاديمي', ResumeTemplate::ACADEMIC->label());
        $this->assertSame('احترافي تقليدي', ResumeTemplate::PROFESSIONAL->label());
    }

    public function test_goal_status_from_string(): void
    {
        $this->assertSame(GoalStatus::NOT_STARTED, GoalStatus::from('not_started'));
        $this->assertSame(GoalStatus::IN_PROGRESS, GoalStatus::from('in_progress'));
        $this->assertSame(GoalStatus::COMPLETED, GoalStatus::from('completed'));
        $this->assertSame(GoalStatus::POSTPONED, GoalStatus::from('postponed'));
        $this->assertSame(GoalStatus::CANCELLED, GoalStatus::from('cancelled'));
    }

    public function test_resume_template_from_string(): void
    {
        $this->assertSame(ResumeTemplate::ATS_FRIENDLY, ResumeTemplate::from('ats_friendly'));
        $this->assertSame(ResumeTemplate::MODERN, ResumeTemplate::from('modern'));
        $this->assertSame(ResumeTemplate::ACADEMIC, ResumeTemplate::from('academic'));
        $this->assertSame(ResumeTemplate::PROFESSIONAL, ResumeTemplate::from('professional'));
    }
}
