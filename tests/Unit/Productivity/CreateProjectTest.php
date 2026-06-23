<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Illuminate\Support\Facades\Event;
use Modules\Productivity\Application\DTOs\CreateProjectDto;
use Modules\Productivity\Application\UseCases\CreateProject;
use Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface;
use Modules\Productivity\Domain\Entities\Project;
use Modules\Productivity\Domain\Events\ProjectCreated;
use Modules\Shared\Domain\ValueObjects\UserId;
use Tests\TestCase;

final class CreateProjectTest extends TestCase
{
    public function test_can_create_project(): void
    {
        $repository = $this->createMock(ProjectRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Project::class));

        $useCase = new CreateProject($repository);

        $dto = new CreateProjectDto(
            userId: UserId::generate()->value(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: (new \DateTimeImmutable)->format('Y-m-d H:i:s'),
            dueDate: (new \DateTimeImmutable('+60 days'))->format('Y-m-d H:i:s'),
        );

        $result = $useCase->execute($dto);

        $this->assertInstanceOf(\Modules\Productivity\Application\DTOs\ProjectDto::class, $result);
        $this->assertEquals('مشروع تطوير تطبيق الويب', $result->title);
    }

    public function test_dispatches_project_created_event(): void
    {
        $repository = $this->createMock(ProjectRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Project::class));

        $useCase = new CreateProject($repository);

        $dto = new CreateProjectDto(
            userId: UserId::generate()->value(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: (new \DateTimeImmutable)->format('Y-m-d H:i:s'),
            dueDate: (new \DateTimeImmutable('+60 days'))->format('Y-m-d H:i:s'),
        );

        Event::fake([ProjectCreated::class]);

        $useCase->execute($dto);

        Event::assertDispatched(ProjectCreated::class);
    }
}
