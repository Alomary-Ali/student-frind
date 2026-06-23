<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Application\DTOs\EnrollStudentDto;
use Modules\Academic\Application\DTOs\EnrollmentDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Application\UseCases\EnrollStudentInCourse;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\EnrollmentRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Contracts\TransactionManagerInterface;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Entities\Semester;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Exceptions\CourseNotFoundException;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Domain\Services\PrerequisiteValidationService;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

final class EnrollStudentInCourseTest extends TestCase
{
    private StudentRepositoryInterface $studentRepository;
    private CourseRepositoryInterface $courseRepository;
    private SemesterRepositoryInterface $semesterRepository;
    private EnrollmentRepositoryInterface $enrollmentRepository;
    private TransactionManagerInterface $transactionManager;
    private EventDispatcherInterface $eventDispatcher;
    private AcademicAuditLoggerInterface $auditLogger;
    private AcademicMapper $mapper;
    private PrerequisiteValidationService $prerequisiteValidationService;
    private EnrollStudentInCourse $useCase;

    private string $studentId;
    private string $courseId;
    private string $semesterId;
    private Student $student;
    private Course $course;
    private Semester $semester;

    protected function setUp(): void
    {
        $this->studentRepository = $this->createMock(StudentRepositoryInterface::class);
        $this->courseRepository = $this->createMock(CourseRepositoryInterface::class);
        $this->semesterRepository = $this->createMock(SemesterRepositoryInterface::class);
        $this->enrollmentRepository = $this->createMock(EnrollmentRepositoryInterface::class);
        $this->transactionManager = $this->createMock(TransactionManagerInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->auditLogger = $this->createMock(AcademicAuditLoggerInterface::class);
        $this->mapper = new AcademicMapper();
        $this->prerequisiteValidationService = new PrerequisiteValidationService();

        $this->useCase = new EnrollStudentInCourse(
            students: $this->studentRepository,
            courses: $this->courseRepository,
            semesters: $this->semesterRepository,
            enrollments: $this->enrollmentRepository,
            transactions: $this->transactionManager,
            events: $this->eventDispatcher,
            audit: $this->auditLogger,
            mapper: $this->mapper,
            prerequisiteValidator: $this->prerequisiteValidationService,
        );

        $this->studentId = '550e8400-e29b-41d4-a716-446655440099';
        $this->courseId = '660e8400-e29b-41d4-a716-446655440030';
        $this->semesterId = '660e8400-e29b-41d4-a716-446655440041';

        $this->student = Student::create(
            id: StudentId::fromString($this->studentId),
            userId: '550e8400-e29b-41d4-a716-446655440001',
            studentNumber: '2023001',
        );

        $this->course = Course::reconstitute(
            id: CourseId::fromString($this->courseId),
            code: 'CS101',
            title: 'Introduction to Computer Science',
            description: 'Basic CS concepts',
            creditHours: Credits::of(3),
            isActive: true,
            institutionId: 'institution-uuid',
            createdAt: new \DateTimeImmutable(),
        );

        $this->semester = Semester::reconstitute(
            id: SemesterId::fromString($this->semesterId),
            name: 'Fall 2026',
            code: 'FALL2026',
            startDate: new \DateTimeImmutable('2026-09-01'),
            endDate: new \DateTimeImmutable('2026-12-31'),
            isActive: true,
            institutionId: 'institution-uuid',
            createdAt: new \DateTimeImmutable(),
        );

        // تكوين mock لـ transactionManager لتنفيذ الكود داخل callback مباشرة
        $this->transactionManager->method('runInTransaction')
            ->willReturnCallback(fn (callable $callback) => $callback());
    }

    public function test_enrolls_student_successfully(): void
    {
        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->student);

        $this->courseRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->course);

        $this->semesterRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->semester);

        $this->courseRepository->expects($this->once())
            ->method('findPrerequisites')
            ->willReturn([]);

        $this->enrollmentRepository->expects($this->once())
            ->method('findCompletedByStudent')
            ->willReturn([]);

        $this->enrollmentRepository->expects($this->once())
            ->method('existsForStudentCourseSemester')
            ->willReturn(false);

        $this->enrollmentRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Enrollment::class));

        $this->studentRepository->expects($this->once())
            ->method('save');

        $dto = new EnrollStudentDto(
            studentId: $this->studentId,
            courseId: $this->courseId,
            semesterId: $this->semesterId,
            actorUserId: '550e8400-e29b-41d4-a716-446655440001',
        );

        $result = $this->useCase->execute($dto);

        $this->assertInstanceOf(EnrollmentDto::class, $result);
        $this->assertEquals($this->courseId, $result->courseId);
    }

    public function test_throws_exception_when_student_not_found(): void
    {
        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(StudentNotFoundException::class);

        $dto = new EnrollStudentDto(
            studentId: $this->studentId,
            courseId: $this->courseId,
            semesterId: $this->semesterId,
            actorUserId: '550e8400-e29b-41d4-a716-446655440001',
        );

        $this->useCase->execute($dto);
    }

    public function test_throws_exception_when_course_not_found(): void
    {
        $this->studentRepository->expects($this->once())
            ->method('findById')
            ->willReturn($this->student);

        $this->courseRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(CourseNotFoundException::class);

        $dto = new EnrollStudentDto(
            studentId: $this->studentId,
            courseId: $this->courseId,
            semesterId: $this->semesterId,
            actorUserId: '550e8400-e29b-41d4-a716-446655440001',
        );

        $this->useCase->execute($dto);
    }
}
