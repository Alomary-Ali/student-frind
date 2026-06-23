<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Application\UseCases\CreateSemesterPlan;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterPlanRepositoryInterface;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\Entities\SemesterPlan;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\SemesterPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class CreateSemesterPlanTest extends TestCase
{
    private SemesterPlanRepositoryInterface $semesterPlanRepository;
    private CourseRepositoryInterface $courseRepository;
    private CreateSemesterPlan $useCase;

    protected function setUp(): void
    {
        $this->semesterPlanRepository = $this->createMock(SemesterPlanRepositoryInterface::class);
        $this->courseRepository = $this->createMock(CourseRepositoryInterface::class);
        $this->useCase = new CreateSemesterPlan(
            $this->semesterPlanRepository,
            $this->courseRepository,
        );
    }

    public function test_creates_semester_plan_successfully(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';
        $semesterId = '660e8400-e29b-41d4-a716-446655440041';
        $courseIds = ['660e8400-e29b-41d4-a716-446655440030', '660e8400-e29b-41d4-a716-446655440031'];
        $notes = 'Test notes';

        $this->semesterPlanRepository->expects($this->once())
            ->method('findByStudentAndSemester')
            ->willReturn(null);

        $course1 = Course::reconstitute(
            id: CourseId::fromString('660e8400-e29b-41d4-a716-446655440030'),
            code: 'CS101',
            title: 'Introduction to Computer Science',
            description: 'Basic CS concepts',
            creditHours: Credits::of(3),
            isActive: true,
            institutionId: 'institution-uuid',
            createdAt: new \DateTimeImmutable,
        );

        $course2 = Course::reconstitute(
            id: CourseId::fromString('660e8400-e29b-41d4-a716-446655440031'),
            code: 'CS102',
            title: 'Data Structures',
            description: 'Advanced CS concepts',
            creditHours: Credits::of(3),
            isActive: true,
            institutionId: 'institution-uuid',
            createdAt: new \DateTimeImmutable,
        );

        $this->courseRepository->expects($this->exactly(2))
            ->method('findById')
            ->willReturnOnConsecutiveCalls($course1, $course2);

        $this->semesterPlanRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(SemesterPlan::class));

        $result = $this->useCase->execute($studentId, $semesterId, $courseIds, $notes);

        $this->assertInstanceOf(SemesterPlan::class, $result);
        $this->assertEquals(6, $result->totalCredits());
    }

    public function test_throws_exception_when_plan_already_exists(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';
        $semesterId = '660e8400-e29b-41d4-a716-446655440041';
        $courseIds = ['660e8400-e29b-41d4-a716-446655440030'];

        $existingPlan = SemesterPlan::create(
            id: SemesterPlanId::generate(),
            studentId: StudentId::fromString($studentId),
            semesterId: SemesterId::fromString($semesterId),
            plannedCourses: $courseIds,
            totalCredits: 3,
        );

        $this->semesterPlanRepository->expects($this->once())
            ->method('findByStudentAndSemester')
            ->willReturn($existingPlan);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('A semester plan already exists for this student and semester');

        $this->useCase->execute($studentId, $semesterId, $courseIds);
    }

    public function test_throws_exception_when_course_not_found(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';
        $semesterId = '660e8400-e29b-41d4-a716-446655440041';
        $courseIds = ['660e8400-e29b-41d4-a716-446655440030'];

        $this->semesterPlanRepository->expects($this->once())
            ->method('findByStudentAndSemester')
            ->willReturn(null);

        $this->courseRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(\Modules\Academic\Domain\Exceptions\CourseNotFoundException::class);

        $this->useCase->execute($studentId, $semesterId, $courseIds);
    }
}
