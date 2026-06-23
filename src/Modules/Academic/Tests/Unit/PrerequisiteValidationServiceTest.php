<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Exceptions\PrerequisiteNotMetException;
use Modules\Academic\Domain\Services\PrerequisiteValidationService;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class PrerequisiteValidationServiceTest extends TestCase
{
    private PrerequisiteValidationService $service;

    protected function setUp(): void
    {
        $this->service = new PrerequisiteValidationService;
    }

    public function test_validates_when_no_prerequisites(): void
    {
        $prerequisites = [];
        $completedEnrollments = [];

        $this->expectNotToPerformAssertions();
        $this->service->validatePrerequisites($prerequisites, $completedEnrollments);
    }

    public function test_validates_when_prerequisite_is_optional(): void
    {
        $prerequisites = [
            [
                'prerequisite_course_id' => '660e8400-e29b-41d4-a716-446655440030',
                'is_required' => false,
                'minimum_grade' => 2.0,
            ],
        ];
        $completedEnrollments = [];

        $this->expectNotToPerformAssertions();
        $this->service->validatePrerequisites($prerequisites, $completedEnrollments);
    }

    public function test_throws_exception_when_required_prerequisite_not_met(): void
    {
        $prerequisites = [
            [
                'prerequisite_course_id' => '660e8400-e29b-41d4-a716-446655440030',
                'is_required' => true,
                'minimum_grade' => 2.0,
            ],
        ];
        $completedEnrollments = [];

        $this->expectException(PrerequisiteNotMetException::class);
        $this->expectExceptionMessage('Prerequisite course 660e8400-e29b-41d4-a716-446655440030 not met');
        $this->service->validatePrerequisites($prerequisites, $completedEnrollments);
    }

    public function test_validates_when_required_prerequisite_is_completed(): void
    {
        $prerequisites = [
            [
                'prerequisite_course_id' => '660e8400-e29b-41d4-a716-446655440030',
                'is_required' => true,
                'minimum_grade' => 2.0,
            ],
        ];

        $enrollment = Enrollment::reconstitute(
            id: EnrollmentId::fromString('660e8400-e29b-41d4-a716-446655440050'),
            studentId: StudentId::fromString('550e8400-e29b-41d4-a716-446655440099'),
            courseId: CourseId::fromString('660e8400-e29b-41d4-a716-446655440030'),
            semesterId: SemesterId::fromString('660e8400-e29b-41d4-a716-446655440041'),
            status: \Modules\Academic\Domain\Enums\EnrollmentStatus::Completed,
            enrolledAt: new \DateTimeImmutable,
        );

        $completedEnrollments = [$enrollment];

        $this->expectNotToPerformAssertions();
        $this->service->validatePrerequisites($prerequisites, $completedEnrollments);
    }

    public function test_validates_multiple_prerequisites(): void
    {
        $prerequisites = [
            [
                'prerequisite_course_id' => '660e8400-e29b-41d4-a716-446655440030',
                'is_required' => true,
                'minimum_grade' => 2.0,
            ],
            [
                'prerequisite_course_id' => '660e8400-e29b-41d4-a716-446655440031',
                'is_required' => true,
                'minimum_grade' => 2.5,
            ],
        ];

        $enrollment1 = Enrollment::reconstitute(
            id: EnrollmentId::fromString('660e8400-e29b-41d4-a716-446655440050'),
            studentId: StudentId::fromString('550e8400-e29b-41d4-a716-446655440099'),
            courseId: CourseId::fromString('660e8400-e29b-41d4-a716-446655440030'),
            semesterId: SemesterId::fromString('660e8400-e29b-41d4-a716-446655440041'),
            status: \Modules\Academic\Domain\Enums\EnrollmentStatus::Completed,
            enrolledAt: new \DateTimeImmutable,
        );

        $enrollment2 = Enrollment::reconstitute(
            id: EnrollmentId::fromString('660e8400-e29b-41d4-a716-446655440051'),
            studentId: StudentId::fromString('550e8400-e29b-41d4-a716-446655440099'),
            courseId: CourseId::fromString('660e8400-e29b-41d4-a716-446655440031'),
            semesterId: SemesterId::fromString('660e8400-e29b-41d4-a716-446655440041'),
            status: \Modules\Academic\Domain\Enums\EnrollmentStatus::Completed,
            enrolledAt: new \DateTimeImmutable,
        );

        $completedEnrollments = [$enrollment1, $enrollment2];

        $this->expectNotToPerformAssertions();
        $this->service->validatePrerequisites($prerequisites, $completedEnrollments);
    }
}
