<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\AssignmentDto;
use Modules\Productivity\Application\DTOs\CreateAssignmentDto;
use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Domain\Entities\Assignment;
use Modules\Productivity\Domain\Events\AssignmentCreated;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class CreateAssignment
{
    public function __construct(
        private AssignmentRepositoryInterface $assignmentRepository,
    ) {}

    public function execute(CreateAssignmentDto $dto): AssignmentDto
    {
        $assignment = Assignment::create(
            userId: UserId::fromString($dto->userId),
            courseId: $dto->courseId,
            title: $dto->title,
            description: $dto->description,
            dueDate: new \DateTimeImmutable($dto->dueDate),
        );

        $this->assignmentRepository->save($assignment);

        event(new AssignmentCreated(
            assignmentId: $assignment->id(),
            userId: $assignment->userId(),
            courseId: $assignment->courseId(),
            title: $assignment->title(),
            dueDate: $assignment->dueDate(),
        ));

        return $this->toDto($assignment);
    }

    private function toDto(Assignment $assignment): AssignmentDto
    {
        return new AssignmentDto(
            id: $assignment->id()->value(),
            userId: $assignment->userId()->value(),
            courseId: $assignment->courseId(),
            title: $assignment->title(),
            description: $assignment->description(),
            assignedAt: $assignment->assignedAt()->format('Y-m-d H:i:s'),
            dueDate: $assignment->dueDate()->format('Y-m-d H:i:s'),
            status: $assignment->status()->value,
            grade: $assignment->grade(),
            submissionUrl: $assignment->submissionUrl(),
            createdAt: $assignment->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $assignment->updatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
