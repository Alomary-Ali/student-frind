<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Application\DTOs\RecordGradeDto;
use Modules\Academic\Application\UseCases\RecordAcademicGrade;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\AcademicRecordRepositoryInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\EnrollmentRepositoryInterface;
use Modules\Academic\Domain\Contracts\GraduationPathRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Contracts\TransactionManagerInterface;
use Modules\Academic\Domain\Entities\AcademicRecord;
use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\Exceptions\EnrollmentNotFoundException;
use Modules\Academic\Domain\Services\GpaCalculationService;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

final class RecordAcademicGradeTest extends TestCase
{
    private StudentRepositoryInterface $studentRepository;
    private EnrollmentRepositoryInterface $enrollmentRepository;
    private CourseRepositoryInterface $courseRepository;
    private AcademicRecordRepositoryInterface $academicRecordRepository;
    private GraduationPathRepositoryInterface $graduationPathRepository;
    private TransactionManagerInterface $transactionManager;
    private EventDispatcherInterface $eventDispatcher;
    private AcademicAuditLoggerInterface $auditLogger;
    private GpaCalculationService $gpaService;
    private RecordAcademicGrade $useCase;

    private string $enrollmentId;
    private Enrollment $enrollment;

    protected function setUp(): void
    {
        $this->studentRepository = $this->createMock(StudentRepositoryInterface::class);
        $this->enrollmentRepository = $this->createMock(EnrollmentRepositoryInterface::class);
        $this->courseRepository = $this->createMock(CourseRepositoryInterface::class);
        $this->academicRecordRepository = $this->createMock(AcademicRecordRepositoryInterface::class);
        $this->graduationPathRepository = $this->createMock(GraduationPathRepositoryInterface::class);
        $this->transactionManager = $this->createMock(TransactionManagerInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->auditLogger = $this->createMock(AcademicAuditLoggerInterface::class);
        $this->gpaService = new GpaCalculationService();

        $this->useCase = new RecordAcademicGrade(
            students: $this->studentRepository,
            enrollments: $this->enrollmentRepository,
            courses: $this->courseRepository,
            records: $this->academicRecordRepository,
            graduationPaths: $this->graduationPathRepository,
            transactions: $this->transactionManager,
            events: $this->eventDispatcher,
            audit: $this->auditLogger,
            gpaService: $this->gpaService,
        );

        $this->enrollmentId = '660e8400-e29b-41d4-a716-446655440050';

        $this->enrollment = Enrollment::reconstitute(
            id: EnrollmentId::fromString($this->enrollmentId),
            studentId: StudentId::fromString('550e8400-e29b-41d4-a716-446655440099'),
            courseId: CourseId::fromString('660e8400-e29b-41d4-a716-446655440030'),
            semesterId: SemesterId::fromString('660e8400-e29b-41d4-a716-446655440041'),
            status: EnrollmentStatus::Enrolled,
            enrolledAt: new \DateTimeImmutable(),
        );

        $this->transactionManager->method('runInTransaction')
            ->willReturnCallback(fn (callable $callback) => $callback());
    }

    public function test_records_grade_successfully(): void
    {
        $this->enrollmentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->enrollment);

        $student = \Modules\Academic\Domain\Entities\Student::create(
            id: StudentId::fromString('550e8400-e29b-41d4-a716-446655440099'),
            userId: '550e8400-e29b-41d4-a716-446655440001',
            studentNumber: '2023001',
        );

        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($student);

        $this->academicRecordRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(AcademicRecord::class));

        $this->academicRecordRepository->expects($this->once())
            ->method('findGradedRecordsByStudentId')
            ->willReturn([]);

        $this->graduationPathRepository->expects($this->once())
            ->method('findByStudentId')
            ->willReturn(null);

        $dto = new RecordGradeDto(
            enrollmentId: $this->enrollmentId,
            gradeLetter: 'A',
            recordedByUserId: '550e8400-e29b-41d4-a716-446655440001',
        );

        $result = $this->useCase->execute($dto);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('record_id', $result);
        $this->assertArrayHasKey('grade', $result);
        $this->assertEquals('A', $result['grade']);
    }

    public function test_throws_exception_when_enrollment_not_found(): void
    {
        $this->enrollmentRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(EnrollmentNotFoundException::class);

        $dto = new RecordGradeDto(
            enrollmentId: $this->enrollmentId,
            gradeLetter: 'A',
            recordedByUserId: '550e8400-e29b-41d4-a716-446655440001',
        );

        $this->useCase->execute($dto);
    }
}
