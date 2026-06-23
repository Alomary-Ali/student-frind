<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Modules\Productivity\Application\UseCases\UpdateProjectProgress;
use Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface;
use Modules\Productivity\Domain\Entities\Project;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class UpdateProjectProgressTest extends TestCase
{
    public function test_can_update_project_progress(): void
    {
        $project = Project::create(
            userId: UserId::generate(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: new \DateTimeImmutable,
            dueDate: new \DateTimeImmutable('+60 days'),
        );

        $repository = $this->createMock(ProjectRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findById')
            ->willReturn($project);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Project::class));

        $useCase = new UpdateProjectProgress($repository);

        $result = $useCase->execute(
            $project->id()->value(),
            50,
        );

        $this->assertInstanceOf(\Modules\Productivity\Application\DTOs\ProjectDto::class, $result);
        $this->assertEquals(50, $result->progressPercentage);
    }
}
