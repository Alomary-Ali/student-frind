<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\ValueObjects;

use InvalidArgumentException;
use Modules\Academic\Domain\Enums\GradeLetter;
use Modules\Academic\Domain\Exceptions\InvalidAcademicPlanIdException;
use Modules\Academic\Domain\Exceptions\InvalidAcademicRecordIdException;
use Modules\Academic\Domain\Exceptions\InvalidAlertIdException;
use Modules\Academic\Domain\Exceptions\InvalidCourseIdException;
use Modules\Academic\Domain\Exceptions\InvalidCreditsException;
use Modules\Academic\Domain\Exceptions\InvalidCurriculumIdException;
use Modules\Academic\Domain\Exceptions\InvalidEnrollmentIdException;
use Modules\Academic\Domain\Exceptions\InvalidGraduationPathIdException;
use Modules\Academic\Domain\Exceptions\InvalidSemesterIdException;
use Modules\Academic\Domain\Exceptions\InvalidStudentIdException;
use Modules\Academic\Domain\ValueObjects\AcademicPlanId;
use Modules\Academic\Domain\ValueObjects\AcademicRecordId;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Grade;
use Modules\Academic\Domain\ValueObjects\GraduationPathId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\SemesterPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class AcademicValueObjectsTest extends TestCase
{
    private const VALID_UUID = '123e4567-e89b-12d3-a456-426614174000';
    private const INVALID_UUID = 'not-a-uuid';

    public function test_student_id_generates_valid_instance(): void
    {
        $id = StudentId::generate();
        $this->assertNotEmpty($id->value());
    }

    public function test_student_id_from_string_accepts_valid_uuid(): void
    {
        $id = StudentId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_student_id_from_string_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidStudentIdException::class);
        StudentId::fromString(self::INVALID_UUID);
    }

    public function test_student_id_equals_compares_instances(): void
    {
        $a = StudentId::fromString(self::VALID_UUID);
        $b = StudentId::fromString(self::VALID_UUID);
        $c = StudentId::generate();
        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_student_id_to_string_returns_uuid(): void
    {
        $id = StudentId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, (string) $id);
    }

    public function test_student_id_of_creates_instance(): void
    {
        $id = StudentId::of(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_course_id_generates_valid_instance(): void
    {
        $id = CourseId::generate();
        $this->assertNotEmpty($id->value());
    }

    public function test_course_id_from_string_accepts_valid_uuid(): void
    {
        $id = CourseId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_course_id_from_string_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidCourseIdException::class);
        CourseId::fromString(self::INVALID_UUID);
    }

    public function test_course_id_equals_compares_instances(): void
    {
        $a = CourseId::fromString(self::VALID_UUID);
        $b = CourseId::fromString(self::VALID_UUID);
        $c = CourseId::generate();
        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_semester_id_accepts_valid_uuid(): void
    {
        $id = SemesterId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_semester_id_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidSemesterIdException::class);
        SemesterId::fromString(self::INVALID_UUID);
    }

    public function test_semester_id_equals_compares_instances(): void
    {
        $a = SemesterId::fromString(self::VALID_UUID);
        $b = SemesterId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_enrollment_id_accepts_valid_uuid(): void
    {
        $id = EnrollmentId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_enrollment_id_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidEnrollmentIdException::class);
        EnrollmentId::fromString(self::INVALID_UUID);
    }

    public function test_enrollment_id_equals_compares_instances(): void
    {
        $a = EnrollmentId::fromString(self::VALID_UUID);
        $b = EnrollmentId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_academic_record_id_accepts_valid_uuid(): void
    {
        $id = AcademicRecordId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_academic_record_id_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidAcademicRecordIdException::class);
        AcademicRecordId::fromString(self::INVALID_UUID);
    }

    public function test_academic_record_id_equals_compares_instances(): void
    {
        $a = AcademicRecordId::fromString(self::VALID_UUID);
        $b = AcademicRecordId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_academic_plan_id_accepts_valid_uuid(): void
    {
        $id = AcademicPlanId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_academic_plan_id_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidAcademicPlanIdException::class);
        AcademicPlanId::fromString(self::INVALID_UUID);
    }

    public function test_academic_plan_id_equals_compares_instances(): void
    {
        $a = AcademicPlanId::fromString(self::VALID_UUID);
        $b = AcademicPlanId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_graduation_path_id_accepts_valid_uuid(): void
    {
        $id = GraduationPathId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_graduation_path_id_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidGraduationPathIdException::class);
        GraduationPathId::fromString(self::INVALID_UUID);
    }

    public function test_graduation_path_id_equals_compares_instances(): void
    {
        $a = GraduationPathId::fromString(self::VALID_UUID);
        $b = GraduationPathId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_semester_plan_id_accepts_valid_string(): void
    {
        $id = SemesterPlanId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_semester_plan_id_rejects_empty_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SemesterPlanId::fromString('');
    }

    public function test_semester_plan_id_generates_valid_instance(): void
    {
        $id = SemesterPlanId::generate();
        $this->assertNotEmpty($id->value());
    }

    public function test_semester_plan_id_equals_compares_instances(): void
    {
        $a = SemesterPlanId::fromString(self::VALID_UUID);
        $b = SemesterPlanId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_curriculum_id_accepts_valid_uuid(): void
    {
        $id = CurriculumId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_curriculum_id_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidCurriculumIdException::class);
        CurriculumId::fromString(self::INVALID_UUID);
    }

    public function test_curriculum_id_equals_compares_instances(): void
    {
        $a = CurriculumId::fromString(self::VALID_UUID);
        $b = CurriculumId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_alert_id_accepts_valid_uuid(): void
    {
        $id = AlertId::fromString(self::VALID_UUID);
        $this->assertSame(self::VALID_UUID, $id->value());
    }

    public function test_alert_id_rejects_invalid_uuid(): void
    {
        $this->expectException(InvalidAlertIdException::class);
        AlertId::fromString(self::INVALID_UUID);
    }

    public function test_alert_id_equals_compares_instances(): void
    {
        $a = AlertId::fromString(self::VALID_UUID);
        $b = AlertId::fromString(self::VALID_UUID);
        $this->assertTrue($a->equals($b));
    }

    public function test_credits_accepts_valid_value(): void
    {
        $credits = Credits::of(3);
        $this->assertSame(3, $credits->value());
    }

    public function test_credits_accepts_zero(): void
    {
        $credits = Credits::of(0);
        $this->assertSame(0, $credits->value());
    }

    public function test_credits_rejects_negative(): void
    {
        $this->expectException(InvalidCreditsException::class);
        Credits::of(-1);
    }

    public function test_credits_rejects_excessive(): void
    {
        $this->expectException(InvalidCreditsException::class);
        Credits::of(31);
    }

    public function test_grade_from_letter_creates_instance(): void
    {
        $grade = Grade::fromLetter(GradeLetter::A);
        $this->assertSame('A', $grade->letterValue());
        $this->assertSame(4.0, $grade->gradePoints());
    }

    public function test_grade_is_passing_returns_true_for_passing_grades(): void
    {
        $this->assertTrue(Grade::fromLetter(GradeLetter::A)->isPassing());
        $this->assertTrue(Grade::fromLetter(GradeLetter::B)->isPassing());
        $this->assertTrue(Grade::fromLetter(GradeLetter::C)->isPassing());
        $this->assertFalse(Grade::fromLetter(GradeLetter::D)->isPassing());
    }

    public function test_grade_is_passing_returns_false_for_failing_grades(): void
    {
        $this->assertFalse(Grade::fromLetter(GradeLetter::F)->isPassing());
    }

    public function test_grade_from_letter_handles_minus_plus(): void
    {
        $this->assertSame(3.7, Grade::fromLetter(GradeLetter::AM)->gradePoints());
        $this->assertSame(3.3, Grade::fromLetter(GradeLetter::BP)->gradePoints());
        $this->assertSame(1.7, Grade::fromLetter(GradeLetter::CM)->gradePoints());
        $this->assertSame(1.3, Grade::fromLetter(GradeLetter::DP)->gradePoints());
    }
}
