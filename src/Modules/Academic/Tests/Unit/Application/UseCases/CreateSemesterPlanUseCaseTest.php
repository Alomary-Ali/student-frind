<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Application\UseCases;

use DateTimeImmutable;
use Modules\Academic\Application\UseCases\CreateSemesterPlan;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterPlanRepositoryInterface;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\Entities\SemesterPlan;
use Modules\Academic\Domain\Exceptions\CourseNotFoundException;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\SemesterPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class CreateSemesterPlanUseCaseTest extends TestCase
{
    private SemesterPlanRepositoryInterface $semesterPlanRepository;
    private CourseRepositoryInterface $courseRepository;
    private CreateSemesterPlan $useCase;

    private string $studentId;
    private string $semesterId;

    protected function setUp(): void
    {
        $this->semesterPlanRepository = $this->createMock(SemesterPlanRepositoryInterface::class);
        $this->courseRepository = $this->createMock(CourseRepositoryInterface::class);

        $this->useCase = new CreateSemesterPlan(
            semesterPlanRepository: $this->semesterPlanRepository,
            courseRepository: $this->courseRepository,
        );

        $this->studentId = '550e8400-e29b-41d4-a716-446655440099';
        $this->semesterId = '660e8400-e29b-41d4-a716-446655440030';
    }

    public function test_creates_semester_plan_successfully(): void
    {
        $courseIds = [
            '770e8400-e29b-41d4-a716-446655440001',
            '770e8400-e29b-41d4-a716-446655440002',
        ];

        $this->semesterPlanRepository->expects($this->once())
            ->method('findByStudentAndSemester')
            ->willReturn(null);

        $course1 = Course::reconstitute(
            id: CourseId::fromString($courseIds[0]),
            code: 'CS101',
            title: 'Intro to CS',
            description: 'Basic CS',
            creditHours: Credits::of(3),
            isActive: true,
            institutionId: null,
            createdAt: new DateTimeImmutable(),
        );

        $course2 = Course::reconstitute(
            id: CourseId::fromString($courseIds[1]),
            code: 'MATH201',
            title: 'Calculus II',
            description: 'Advanced math',
            creditHours: Credits::of(4),
            isActive: true,
            institutionId: null,
            createdAt: new DateTimeImmutable(),
        );

        $this->courseRepository->expects($this->exactly(2))
            ->method('findById')
            ->willReturn($course1, $course2);

        $this->semesterPlanRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(SemesterPlan::class));

        $result = $this->useCase->execute(
            studentId: $this->studentId,
            semesterId: $this->semesterId,
            courseIds: $courseIds,
            notes: 'First semester plan',
        );

        $this->assertInstanceOf(SemesterPlan::class, $result);
        $this->assertSame(7, $result->totalCredits());
        $this->assertSame('draft', $result->status());
        $this->assertSame('First semester plan', $result->notes());
    }

    public function test_throws_exception_when_plan_already_exists(): void
    {
        $courseIds = ['770e8400-e29b-41d4-a716-446655440001'];

        $existingPlan = SemesterPlan::create(
            id: SemesterPlanId::fromString('880e8400-e29b-41d4-a716-446655440003'),
            studentId: StudentId::fromString($this->studentId),
            semesterId: SemesterId::fromString($this->semesterId),
            plannedCourses: $courseIds,
            totalCredits: 3,
        );

        $this->semesterPlanRepository->expects($this->once())
            ->method('findByStudentAndSemester')
            ->willReturn($existingPlan);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('A semester plan already exists for this student and semester');

        $this->useCase->execute(
            studentId: $this->studentId,
            semesterId: $this->semesterId,
            courseIds: $courseIds,
        );
    }

    public function test_throws_exception_when_course_not_found(): void
    {
        $courseIds = ['770e8400-e29b-41d4-a716-446655440001'];

        $this->semesterPlanRepository->expects($this->once())
            ->method('findByStudentAndSemester')
            ->willReturn(null);

        $this->courseRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(CourseNotFoundException::class);

        $this->useCase->execute(
            studentId: $this->studentId,
            semesterId: $this->semesterId,
            courseIds: $courseIds,
        );
    }

    public function test_creates_plan_without_notes(): void
    {
        $courseIds = ['770e8400-e29b-41d4-a716-446655440001'];

        $this->semesterPlanRepository->expects($this->once())
            ->method('findByStudentAndSemester')
            ->willReturn(null);

        $course = Course::reconstitute(
            id: CourseId::fromString($courseIds[0]),
            code: 'CS101',
            title: 'Intro to CS',
            description: 'Basic CS',
            creditHours: Credits::of(3),
            isActive: true,
            institutionId: null,
            createdAt: new DateTimeImmutable(),
        );

        $this->courseRepository->expects($this->once())
            ->method('findById')
            ->willReturn($course);

        $this->semesterPlanRepository->expects($this->once())
            ->method('save');

        $result = $this->useCase->execute(
            studentId: $this->studentId,
            semesterId: $this->semesterId,
            courseIds: $courseIds,
        );

        $this->assertInstanceOf(SemesterPlan::class, $result);
        $this->assertNull($result->notes());
    }
}
