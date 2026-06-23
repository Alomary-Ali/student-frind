<?php

declare(strict_types=1);

namespace Modules\Academic\Application\UseCases;

use Modules\Academic\Application\DTOs\CreateStudentDto;
use Modules\Academic\Application\DTOs\StudentDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Exceptions\StudentAlreadyExistsException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Shared\Domain\Contracts\UserRepositoryInterface;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class CreateStudent
{
    public function __construct(
        private StudentRepositoryInterface $students,
        private UserRepositoryInterface $users,
        private EventDispatcherInterface $events,
        private AcademicAuditLoggerInterface $audit,
        private AcademicMapper $mapper,
    ) {}

    public function execute(CreateStudentDto $dto): StudentDto
    {
        $userId = UserId::fromString($dto->userId);
        $user = $this->users->findById($userId)
            ?? throw UserNotFoundException::withId($dto->userId);

        if ($this->students->existsByUserId($dto->userId)) {
            throw StudentAlreadyExistsException::forUserId($dto->userId);
        }

        $student = Student::create(
            id: StudentId::generate(),
            userId: $dto->userId,
            studentNumber: $dto->studentNumber,
            institutionId: $dto->institutionId,
            universityId: $dto->universityId,
            collegeId: $dto->collegeId,
            departmentId: $dto->departmentId,
            majorId: $dto->majorId,
            level: $dto->level,
        );

        if ($dto->semesterGpa !== null || $dto->currentSemesterId !== null) {
            // Update semester GPA and current semester if provided
            // This would require adding update methods to Student entity
        }

        $this->students->save($student);
        $this->events->dispatch($student->releaseEvents());

        $this->audit->log(
            actorUserId: $dto->userId,
            action: 'student.created',
            entityType: 'academic_student',
            entityId: $student->id()->value(),
            newValues: ['student_number' => $dto->studentNumber],
        );

        return $this->mapper->toStudentDto($student);
    }
}
