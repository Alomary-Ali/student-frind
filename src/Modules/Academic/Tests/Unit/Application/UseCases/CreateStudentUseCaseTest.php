<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Application\UseCases;

use DateTimeImmutable;
use Modules\Academic\Application\DTOs\CreateStudentDto;
use Modules\Academic\Application\DTOs\StudentDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Application\UseCases\CreateStudent;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Exceptions\StudentAlreadyExistsException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Shared\Domain\Contracts\UserRepositoryInterface;
use Modules\Shared\Domain\Entities\User;
use Modules\Shared\Domain\Enums\UserRole;
use Modules\Shared\Domain\Enums\UserStatus;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;
use Modules\Shared\Domain\ValueObjects\AcademicId;
use Modules\Shared\Domain\ValueObjects\EmailAddress;
use Modules\Shared\Domain\ValueObjects\FullName;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class CreateStudentUseCaseTest extends TestCase
{
    private StudentRepositoryInterface $studentRepository;
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;
    private AcademicAuditLoggerInterface $auditLogger;
    private AcademicMapper $mapper;
    private CreateStudent $useCase;

    protected function setUp(): void
    {
        $this->studentRepository = $this->createMock(StudentRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->auditLogger = $this->createMock(AcademicAuditLoggerInterface::class);
        $this->mapper = new AcademicMapper();

        $this->useCase = new CreateStudent(
            students: $this->studentRepository,
            users: $this->userRepository,
            events: $this->eventDispatcher,
            audit: $this->auditLogger,
            mapper: $this->mapper,
        );
    }

    public function test_creates_student_successfully(): void
    {
        $userId = '550e8400-e29b-41d4-a716-446655440099';
        $dto = new CreateStudentDto(
            userId: $userId,
            studentNumber: '2024001',
            institutionId: 'inst-uuid',
            level: '1',
        );

        $user = User::reconstitute(
            id: UserId::fromString($userId),
            academicId: AcademicId::of('12345678'),
            email: EmailAddress::fromString('test@example.com'),
            name: FullName::of('John', 'Doe'),
            passwordHash: 'hash',
            role: UserRole::Student,
            status: UserStatus::Active,
            emailVerifiedAt: new DateTimeImmutable(),
            createdAt: new DateTimeImmutable(),
        );

        $this->userRepository->expects($this->once())
            ->method('findById')
            ->with($this->isInstanceOf(UserId::class))
            ->willReturn($user);

        $this->studentRepository->expects($this->once())
            ->method('existsByUserId')
            ->with($userId)
            ->willReturn(false);

        $this->studentRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Student::class));

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isType('array'));

        $this->auditLogger->expects($this->once())
            ->method('log')
            ->with(
                $userId,
                'student.created',
                'academic_student',
                $this->isType('string'),
                null,
                $this->isType('array'),
            );

        $result = $this->useCase->execute($dto);

        $this->assertInstanceOf(StudentDto::class, $result);
        $this->assertSame($userId, $result->userId);
        $this->assertSame('2024001', $result->studentNumber);
        $this->assertSame('active', $result->academicStatus);
        $this->assertSame('good_standing', $result->academicStanding);
    }

    public function test_throws_exception_when_user_not_found(): void
    {
        $userId = '550e8400-e29b-41d4-a716-446655440099';
        $dto = new CreateStudentDto(
            userId: $userId,
            studentNumber: '2024001',
        );

        $this->userRepository->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $this->useCase->execute($dto);
    }

    public function test_throws_exception_when_student_already_exists(): void
    {
        $userId = '550e8400-e29b-41d4-a716-446655440099';
        $dto = new CreateStudentDto(
            userId: $userId,
            studentNumber: '2024001',
        );

        $user = User::reconstitute(
            id: UserId::fromString($userId),
            academicId: AcademicId::of('12345678'),
            email: EmailAddress::fromString('test@example.com'),
            name: FullName::of('John', 'Doe'),
            passwordHash: 'hash',
            role: UserRole::Student,
            status: UserStatus::Active,
            emailVerifiedAt: new DateTimeImmutable(),
            createdAt: new DateTimeImmutable(),
        );

        $this->userRepository->expects($this->once())
            ->method('findById')
            ->willReturn($user);

        $this->studentRepository->expects($this->once())
            ->method('existsByUserId')
            ->with($userId)
            ->willReturn(true);

        $this->expectException(StudentAlreadyExistsException::class);

        $this->useCase->execute($dto);
    }
}
