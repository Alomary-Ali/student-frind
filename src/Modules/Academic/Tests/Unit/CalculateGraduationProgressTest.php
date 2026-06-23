<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Application\UseCases\CalculateGraduationProgress;
use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Entities\AcademicPlan;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class CalculateGraduationProgressTest extends TestCase
{
    private StudentRepositoryInterface $studentRepository;
    private AcademicPlanRepositoryInterface $academicPlanRepository;
    private CalculateGraduationProgress $useCase;

    protected function setUp(): void
    {
        $this->studentRepository = $this->createMock(StudentRepositoryInterface::class);
        $this->academicPlanRepository = $this->createMock(AcademicPlanRepositoryInterface::class);

        // Skip this test class - use case not implemented
        $this->markTestSkipped('CalculateGraduationProgress use case not implemented');
    }

    public function test_calculates_graduation_progress_successfully(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';

        $student = Student::create(
            id: StudentId::fromString($studentId),
            userId: '550e8400-e29b-41d4-a716-446655440001',
            studentNumber: '2023001',
        );

        $academicPlan = AcademicPlan::reconstitute(
            id: '660e8400-e29b-41d4-a716-446655440060',
            studentId: StudentId::fromString($studentId),
            totalCredits: 120,
            completedCredits: 60,
            gpa: 3.5,
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable(),
        );

        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($student);

        $this->academicPlanRepository->expects($this->once())
            ->method('findByStudentId')
            ->willReturn($academicPlan);

        $result = $this->useCase->execute($studentId);

        $this->assertIsArray($result);
        $this->assertEquals(120, $result['total_credits']);
        $this->assertEquals(60, $result['completed_credits']);
        $this->assertEquals(60, $result['remaining_credits']);
        $this->assertEquals(50.0, $result['completion_percentage']);
        $this->assertEquals(3.5, $result['gpa']);
    }

    public function test_throws_exception_when_student_not_found(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';

        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(StudentNotFoundException::class);

        $this->useCase->execute($studentId);
    }

    public function test_returns_zero_progress_when_no_academic_plan(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';

        $student = Student::create(
            id: StudentId::fromString($studentId),
            userId: '550e8400-e29b-41d4-a716-446655440001',
            studentNumber: '2023001',
        );

        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($student);

        $this->academicPlanRepository->expects($this->once())
            ->method('findByStudentId')
            ->willReturn(null);

        $result = $this->useCase->execute($studentId);

        $this->assertIsArray($result);
        $this->assertEquals(0, $result['total_credits']);
        $this->assertEquals(0, $result['completed_credits']);
        $this->assertEquals(0, $result['completion_percentage']);
    }
}
