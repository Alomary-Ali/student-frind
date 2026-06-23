<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Application\UseCases;

use DateTimeImmutable;
use Modules\Academic\Application\DTOs\AssignAcademicPlanDto;
use Modules\Academic\Application\UseCases\AssignAcademicPlan;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\CurriculumRepositoryInterface;
use Modules\Academic\Domain\Contracts\GraduationPathRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Contracts\TransactionManagerInterface;
use Modules\Academic\Domain\Entities\AcademicPlan;
use Modules\Academic\Domain\Entities\Curriculum;
use Modules\Academic\Domain\Entities\GraduationPath;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Enums\AcademicStanding;
use Modules\Academic\Domain\Enums\AcademicStatus;
use Modules\Academic\Domain\Exceptions\AcademicPlanAlreadyAssignedException;
use Modules\Academic\Domain\Exceptions\CurriculumNotFoundException;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\Gpa;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

final class AssignAcademicPlanUseCaseTest extends TestCase
{
    private StudentRepositoryInterface $studentRepository;
    private CurriculumRepositoryInterface $curriculumRepository;
    private AcademicPlanRepositoryInterface $planRepository;
    private GraduationPathRepositoryInterface $graduationPathRepository;
    private TransactionManagerInterface $transactionManager;
    private EventDispatcherInterface $eventDispatcher;
    private AcademicAuditLoggerInterface $auditLogger;
    private AssignAcademicPlan $useCase;

    private string $studentId;
    private string $curriculumId;
    private Student $student;
    private Curriculum $curriculum;

    protected function setUp(): void
    {
        $this->studentRepository = $this->createMock(StudentRepositoryInterface::class);
        $this->curriculumRepository = $this->createMock(CurriculumRepositoryInterface::class);
        $this->planRepository = $this->createMock(AcademicPlanRepositoryInterface::class);
        $this->graduationPathRepository = $this->createMock(GraduationPathRepositoryInterface::class);
        $this->transactionManager = $this->createMock(TransactionManagerInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->auditLogger = $this->createMock(AcademicAuditLoggerInterface::class);

        $this->useCase = new AssignAcademicPlan(
            students: $this->studentRepository,
            curricula: $this->curriculumRepository,
            plans: $this->planRepository,
            graduationPaths: $this->graduationPathRepository,
            transactions: $this->transactionManager,
            events: $this->eventDispatcher,
            audit: $this->auditLogger,
        );

        $this->studentId = '550e8400-e29b-41d4-a716-446655440099';
        $this->curriculumId = '660e8400-e29b-41d4-a716-446655440030';

        $this->student = Student::reconstitute(
            id: StudentId::fromString($this->studentId),
            userId: 'user-uuid',
            studentNumber: '2024001',
            academicStatus: AcademicStatus::Active,
            academicStanding: AcademicStanding::GoodStanding,
            cumulativeGpa: Gpa::zero(),
            semesterGpa: null,
            currentSemesterId: null,
            institutionId: 'inst-uuid',
            universityId: null,
            collegeId: null,
            departmentId: null,
            majorId: null,
            level: '1',
            createdAt: new DateTimeImmutable,
        );

        $this->curriculum = Curriculum::reconstitute(
            id: CurriculumId::fromString($this->curriculumId),
            name: 'Computer Science',
            code: 'CS',
            description: 'CS program',
            totalCreditsRequired: Credits::of(30),
            institutionId: 'inst-uuid',
            createdAt: new DateTimeImmutable,
        );

        $this->transactionManager->method('runInTransaction')
            ->willReturnCallback(fn (callable $callback) => $callback());
    }

    public function test_assigns_academic_plan_successfully(): void
    {
        $dto = new AssignAcademicPlanDto(
            studentId: $this->studentId,
            curriculumId: $this->curriculumId,
            actorUserId: 'admin-uuid',
            institutionId: 'inst-uuid',
            estimatedGraduationDate: '2029-06-15',
        );

        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->student);

        $this->curriculumRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->curriculum);

        $this->planRepository->expects($this->once())
            ->method('findActiveByStudentId')
            ->willReturn(null);

        $this->planRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(AcademicPlan::class));

        $this->graduationPathRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(GraduationPath::class));

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch');

        $this->auditLogger->expects($this->once())
            ->method('log');

        $result = $this->useCase->execute($dto);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('academic_plan_id', $result);
        $this->assertArrayHasKey('student_id', $result);
        $this->assertArrayHasKey('curriculum_id', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('assigned_at', $result);
    }

    public function test_throws_exception_when_student_not_found(): void
    {
        $dto = new AssignAcademicPlanDto(
            studentId: $this->studentId,
            curriculumId: $this->curriculumId,
            actorUserId: 'admin-uuid',
        );

        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(StudentNotFoundException::class);

        $this->useCase->execute($dto);
    }

    public function test_throws_exception_when_curriculum_not_found(): void
    {
        $dto = new AssignAcademicPlanDto(
            studentId: $this->studentId,
            curriculumId: $this->curriculumId,
            actorUserId: 'admin-uuid',
        );

        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->student);

        $this->curriculumRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(CurriculumNotFoundException::class);

        $this->useCase->execute($dto);
    }

    public function test_throws_exception_when_plan_already_assigned(): void
    {
        $dto = new AssignAcademicPlanDto(
            studentId: $this->studentId,
            curriculumId: $this->curriculumId,
            actorUserId: 'admin-uuid',
        );

        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->student);

        $this->curriculumRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->curriculum);

        $existingPlan = AcademicPlan::assign(
            id: \Modules\Academic\Domain\ValueObjects\AcademicPlanId::generate(),
            studentId: StudentId::fromString($this->studentId),
            curriculumId: CurriculumId::fromString($this->curriculumId),
        );

        $this->planRepository->expects($this->once())
            ->method('findActiveByStudentId')
            ->willReturn($existingPlan);

        $this->expectException(AcademicPlanAlreadyAssignedException::class);

        $this->useCase->execute($dto);
    }
}
