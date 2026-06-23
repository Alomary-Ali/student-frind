<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\ProjectDto;
use Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface;
use Modules\Productivity\Domain\ValueObjects\ProjectId;

final readonly class UpdateProjectProgress
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    public function execute(string $projectId, int $progressPercentage): ProjectDto
    {
        $project = $this->projectRepository->findById(
            ProjectId::fromString($projectId)
        );

        if ($project === null) {
            throw new \RuntimeException('Project not found');
        }

        $project->updateProgress($progressPercentage);

        $this->projectRepository->save($project);

        return $this->toDto($project);
    }

    private function toDto(\Modules\Productivity\Domain\Entities\Project $project): ProjectDto
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
