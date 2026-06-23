<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Enums;

use Modules\Academic\Domain\Enums\AcademicPlanStatus;
use Modules\Academic\Domain\Enums\AcademicStanding;
use Modules\Academic\Domain\Enums\AcademicStatus;
use Modules\Academic\Domain\Enums\AlertSeverity;
use Modules\Academic\Domain\Enums\AlertType;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\Enums\GradeLetter;
use PHPUnit\Framework\TestCase;

final class AcademicEnumsTest extends TestCase
{
    public function test_grade_letter_has_all_cases(): void
    {
        $this->assertSame('A', GradeLetter::A->value);
        $this->assertSame('A-', GradeLetter::AM->value);
        $this->assertSame('B+', GradeLetter::BP->value);
        $this->assertSame('B', GradeLetter::B->value);
        $this->assertSame('B-', GradeLetter::BM->value);
        $this->assertSame('C+', GradeLetter::CP->value);
        $this->assertSame('C', GradeLetter::C->value);
        $this->assertSame('C-', GradeLetter::CM->value);
        $this->assertSame('D+', GradeLetter::DP->value);
        $this->assertSame('D', GradeLetter::D->value);
        $this->assertSame('F', GradeLetter::F->value);
    }

    public function test_grade_letter_points_are_correct(): void
    {
        $this->assertSame(4.0, GradeLetter::A->gradePoints());
        $this->assertSame(3.7, GradeLetter::AM->gradePoints());
        $this->assertSame(3.3, GradeLetter::BP->gradePoints());
        $this->assertSame(3.0, GradeLetter::B->gradePoints());
        $this->assertSame(2.7, GradeLetter::BM->gradePoints());
        $this->assertSame(2.3, GradeLetter::CP->gradePoints());
        $this->assertSame(2.0, GradeLetter::C->gradePoints());
        $this->assertSame(1.7, GradeLetter::CM->gradePoints());
        $this->assertSame(1.3, GradeLetter::DP->gradePoints());
        $this->assertSame(1.0, GradeLetter::D->gradePoints());
        $this->assertSame(0.0, GradeLetter::F->gradePoints());
    }

    public function test_academic_status_has_correct_values(): void
    {
        $this->assertSame('active', AcademicStatus::Active->value);
        $this->assertSame('inactive', AcademicStatus::Inactive->value);
        $this->assertSame('graduated', AcademicStatus::Graduated->value);
        $this->assertSame('withdrawn', AcademicStatus::Withdrawn->value);
        $this->assertSame('suspended', AcademicStatus::Suspended->value);
    }

    public function test_academic_status_can_enroll_returns_true_only_for_active(): void
    {
        $this->assertTrue(AcademicStatus::Active->canEnroll());
        $this->assertFalse(AcademicStatus::Inactive->canEnroll());
        $this->assertFalse(AcademicStatus::Graduated->canEnroll());
        $this->assertFalse(AcademicStatus::Withdrawn->canEnroll());
        $this->assertFalse(AcademicStatus::Suspended->canEnroll());
    }

    public function test_academic_status_labels_are_correct(): void
    {
        $this->assertSame('Active', AcademicStatus::Active->label());
        $this->assertSame('Inactive', AcademicStatus::Inactive->label());
        $this->assertSame('Graduated', AcademicStatus::Graduated->label());
        $this->assertSame('Withdrawn', AcademicStatus::Withdrawn->label());
        $this->assertSame('Suspended', AcademicStatus::Suspended->label());
    }

    public function test_academic_standing_has_correct_values(): void
    {
        $this->assertSame('good_standing', AcademicStanding::GoodStanding->value);
        $this->assertSame('probation', AcademicStanding::Probation->value);
        $this->assertSame('suspension', AcademicStanding::Suspension->value);
        $this->assertSame('dismissed', AcademicStanding::Dismissed->value);
    }

    public function test_academic_standing_labels_are_correct(): void
    {
        $this->assertSame('Good Standing', AcademicStanding::GoodStanding->label());
        $this->assertSame('Probation', AcademicStanding::Probation->label());
        $this->assertSame('Suspension', AcademicStanding::Suspension->label());
        $this->assertSame('Dismissed', AcademicStanding::Dismissed->label());
    }

    public function test_enrollment_status_has_correct_values(): void
    {
        $this->assertSame('enrolled', EnrollmentStatus::Enrolled->value);
        $this->assertSame('dropped', EnrollmentStatus::Dropped->value);
        $this->assertSame('completed', EnrollmentStatus::Completed->value);
        $this->assertSame('in_progress', EnrollmentStatus::InProgress->value);
        $this->assertSame('failed', EnrollmentStatus::Failed->value);
        $this->assertSame('postponed', EnrollmentStatus::Postponed->value);
        $this->assertSame('equivalent', EnrollmentStatus::Equivalent->value);
    }

    public function test_enrollment_status_labels_are_correct(): void
    {
        $this->assertSame('Enrolled', EnrollmentStatus::Enrolled->label());
        $this->assertSame('Dropped', EnrollmentStatus::Dropped->label());
        $this->assertSame('Completed', EnrollmentStatus::Completed->label());
        $this->assertSame('In Progress', EnrollmentStatus::InProgress->label());
        $this->assertSame('Failed', EnrollmentStatus::Failed->label());
        $this->assertSame('Postponed', EnrollmentStatus::Postponed->label());
        $this->assertSame('Equivalent', EnrollmentStatus::Equivalent->label());
    }

    public function test_academic_plan_status_has_correct_values(): void
    {
        $this->assertSame('active', AcademicPlanStatus::Active->value);
        $this->assertSame('completed', AcademicPlanStatus::Completed->value);
        $this->assertSame('suspended', AcademicPlanStatus::Suspended->value);
    }

    public function test_academic_plan_status_labels_are_correct(): void
    {
        $this->assertSame('Active', AcademicPlanStatus::Active->label());
        $this->assertSame('Completed', AcademicPlanStatus::Completed->label());
        $this->assertSame('Suspended', AcademicPlanStatus::Suspended->label());
    }

    public function test_alert_severity_has_correct_values(): void
    {
        $this->assertSame('low', AlertSeverity::Low->value);
        $this->assertSame('medium', AlertSeverity::Medium->value);
        $this->assertSame('high', AlertSeverity::High->value);
        $this->assertSame('critical', AlertSeverity::Critical->value);
    }

    public function test_alert_type_has_correct_values(): void
    {
        $this->assertSame('low_gpa', AlertType::LowGpa->value);
        $this->assertSame('graduation_delay', AlertType::GraduationDelay->value);
        $this->assertSame('repeated_failure', AlertType::RepeatedFailure->value);
        $this->assertSame('credit_deficit', AlertType::CreditDeficit->value);
        $this->assertSame('prerequisite_not_met', AlertType::PrerequisiteNotMet->value);
        $this->assertSame('enrollment_conflict', AlertType::EnrollmentConflict->value);
    }
}
