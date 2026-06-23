<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Entities\AcademicPlan;
use Modules\Academic\Domain\Enums\AcademicPlanStatus;
use Modules\Academic\Domain\Events\AcademicPlanAssigned;
use Modules\Academic\Domain\ValueObjects\AcademicPlanId;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class AcademicPlanEntityTest extends TestCase
{
    public function test_plan_can_be_assigned(): void
    {
        $plan = AcademicPlan::assign(
            AcademicPlanId::generate(),
            StudentId::generate(),
            CurriculumId::generate(),
        );

        $this->assertSame(AcademicPlanStatus::Active, $plan->status());
        $this->assertTrue($plan->isActive());
        $this->assertInstanceOf(DateTimeImmutable::class, $plan->assignedAt());

        $events = $plan->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(AcademicPlanAssigned::class, $events[0]);
    }

    public function test_plan_can_be_reconstituted(): void
    {
        $plan = AcademicPlan::reconstitute(
            AcademicPlanId::generate(),
            StudentId::generate(),
            CurriculumId::generate(),
            AcademicPlanStatus::Active,
            new DateTimeImmutable('2026-01-01'),
            'inst-1',
        );

        $this->assertSame(AcademicPlanStatus::Active, $plan->status());
        $this->assertTrue($plan->isActive());
        $this->assertCount(0, $plan->releaseEvents());
    }

    public function test_plan_can_be_completed(): void
    {
        $plan = AcademicPlan::assign(
            AcademicPlanId::generate(),
            StudentId::generate(),
            CurriculumId::generate(),
        );
        $plan->releaseEvents();

        $plan->complete();

        $this->assertSame(AcademicPlanStatus::Completed, $plan->status());
        $this->assertFalse($plan->isActive());
    }

    public function test_plan_getters_return_correct_values(): void
    {
        $id = AcademicPlanId::generate();
        $studentId = StudentId::generate();
        $curriculumId = CurriculumId::generate();
        $assignedAt = new DateTimeImmutable('2026-01-15');

        $plan = AcademicPlan::reconstitute(
            $id,
            $studentId,
            $curriculumId,
            AcademicPlanStatus::Active,
            $assignedAt,
            'inst-1',
        );

        $this->assertTrue($id->equals($plan->id()));
        $this->assertTrue($studentId->equals($plan->studentId()));
        $this->assertTrue($curriculumId->equals($plan->curriculumId()));
        $this->assertSame(AcademicPlanStatus::Active, $plan->status());
        $this->assertSame($assignedAt, $plan->assignedAt());
        $this->assertSame('inst-1', $plan->institutionId());
    }
}
