<?php

declare(strict_types=1);

namespace Modules\Opportunities\Tests\Unit\Domain\Enums;

use Modules\Opportunities\Domain\Enums\ApplicationStatus;
use Modules\Opportunities\Domain\Enums\OpportunityStatus;
use Modules\Opportunities\Domain\Enums\OpportunityType;
use Modules\Opportunities\Domain\Enums\Provider;
use PHPUnit\Framework\TestCase;

final class OpportunityEnumsTest extends TestCase
{
    public function test_opportunity_type_cases_have_correct_values(): void
    {
        $this->assertSame('job', OpportunityType::JOB->value);
        $this->assertSame('internship', OpportunityType::INTERNSHIP->value);
        $this->assertSame('scholarship', OpportunityType::SCHOLARSHIP->value);
        $this->assertSame('course', OpportunityType::COURSE->value);
        $this->assertSame('competition', OpportunityType::COMPETITION->value);
        $this->assertSame('volunteering', OpportunityType::VOLUNTEERING->value);
        $this->assertSame('conference', OpportunityType::CONFERENCE->value);
    }

    public function test_opportunity_type_labels_are_arabic(): void
    {
        $this->assertSame('وظيفة', OpportunityType::JOB->label());
        $this->assertSame('تدريب', OpportunityType::INTERNSHIP->label());
        $this->assertSame('منحة دراسية', OpportunityType::SCHOLARSHIP->label());
        $this->assertSame('دورة تدريبية', OpportunityType::COURSE->label());
        $this->assertSame('مسابقة', OpportunityType::COMPETITION->label());
        $this->assertSame('تطوع', OpportunityType::VOLUNTEERING->label());
        $this->assertSame('مؤتمر', OpportunityType::CONFERENCE->label());
    }

    public function test_application_status_labels(): void
    {
        $this->assertSame('محفوظ', ApplicationStatus::SAVED->label());
        $this->assertSame('تم التقديم', ApplicationStatus::APPLIED->label());
        $this->assertSame('قيد المراجعة', ApplicationStatus::IN_REVIEW->label());
        $this->assertSame('مقبول', ApplicationStatus::ACCEPTED->label());
        $this->assertSame('مرفوض', ApplicationStatus::REJECTED->label());
        $this->assertSame('منسحب', ApplicationStatus::WITHDRAWN->label());
    }

    public function test_application_status_is_final(): void
    {
        $this->assertTrue(ApplicationStatus::ACCEPTED->isFinal());
        $this->assertTrue(ApplicationStatus::REJECTED->isFinal());
        $this->assertTrue(ApplicationStatus::WITHDRAWN->isFinal());
        $this->assertFalse(ApplicationStatus::SAVED->isFinal());
        $this->assertFalse(ApplicationStatus::APPLIED->isFinal());
        $this->assertFalse(ApplicationStatus::IN_REVIEW->isFinal());
    }

    public function test_opportunity_status_labels(): void
    {
        $this->assertSame('نشط', OpportunityStatus::ACTIVE->label());
        $this->assertSame('منتهي', OpportunityStatus::CLOSED->label());
        $this->assertSame('مسودة', OpportunityStatus::DRAFT->label());
    }

    public function test_provider_labels(): void
    {
        $this->assertSame('LinkedIn', Provider::LINKEDIN->label());
        $this->assertSame('إدراك', Provider::EDRAK->label());
        $this->assertSame('فرصة', Provider::FOR9A->label());
    }

    public function test_opportunity_type_values_static_method(): void
    {
        $values = OpportunityType::values();
        $this->assertContains('job', $values);
        $this->assertContains('scholarship', $values);
        $this->assertCount(7, $values);
    }

    public function test_application_status_values_static_method(): void
    {
        $values = ApplicationStatus::values();
        $this->assertContains('saved', $values);
        $this->assertContains('applied', $values);
        $this->assertCount(6, $values);
    }
}
