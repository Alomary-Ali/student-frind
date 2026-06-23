<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\AssignmentDto;
use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Domain\ValueObjects\AssignmentId;

final readonly class UpdateAssignmentProgress
{
    public function __construct(
        private AssignmentRepositoryInterface $assignmentRepository,
    ) {}

    public function execute(string $assignmentId, string $status, ?string $submissionUrl = null): AssignmentDto
    {
        $assignment = $this->assignmentRepository->findById(
            AssignmentId::fromString($assignmentId),
        );

        if ($assignment === null) {
            throw new \RuntimeException('Assignment not found');
        }

        match ($status) {
            'in_progress' => $assignment->markAsInProgress(),
            'submitted' => $assignment->markAsSubmitted($submissionUrl ?? ''),
            'late' => $assignment->markAsLate(),
            default => throw new \InvalidArgumentException('Invalid status'),
        };

        $this->assignmentRepository->save($assignment);

        return $this->toDto($assignment);
    }

    private function toDto(\Modules\Productivity\Domain\Entities\Assignment $assignment): AssignmentDto
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
