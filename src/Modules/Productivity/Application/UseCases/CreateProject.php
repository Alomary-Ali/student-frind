<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\CreateProjectDto;
use Modules\Productivity\Application\DTOs\ProjectDto;
use Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface;
use Modules\Productivity\Domain\Entities\Project;
use Modules\Productivity\Domain\Events\ProjectCreated;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class CreateProject
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    public function execute(CreateProjectDto $dto): ProjectDto
    {
        $project = Project::create(
            userId: UserId::fromString($dto->userId),
            title: $dto->title,
            description: $dto->description,
            startDate: new \DateTimeImmutable($dto->startDate),
            dueDate: new \DateTimeImmutable($dto->dueDate),
        );

        $this->projectRepository->save($project);

        event(new ProjectCreated(
            projectId: $project->id(),
            userId: $project->userId(),
            title: $project->title(),
            startDate: $project->startDate(),
            dueDate: $project->dueDate(),
        ));

        return $this->toDto($project);
    }

    private function toDto(Project $project): ProjectDto
    {
        return new ProjectDto(
            id: $project->id()->value(),
            userId: $project->userId()->value(),
            title: $project->title(),
            description: $project->description(),
            startDate: $project->startDate()->format('Y-m-d H:i:s'),
            dueDate: $project->dueDate()->format('Y-m-d H:i:s'),
            status: $project->status()->value,
            progressPercentage: $project->progressPercentage(),
            createdAt: $project->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $project->updatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
