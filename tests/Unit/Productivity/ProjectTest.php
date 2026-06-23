<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Modules\Productivity\Domain\Entities\Project;
use Modules\Productivity\Domain\Enums\ProjectStatus;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class ProjectTest extends TestCase
{
    public function test_can_create_project(): void
    {
        $project = Project::create(
            userId: UserId::generate(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: new \DateTimeImmutable,
            dueDate: new \DateTimeImmutable('+60 days'),
        );

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('مشروع تطوير تطبيق الويب', $project->title());
        $this->assertEquals(ProjectStatus::PLANNING, $project->status());
        $this->assertEquals(0, $project->progressPercentage());
    }

    public function test_can_start_project(): void
    {
        $project = Project::create(
            userId: UserId::generate(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: new \DateTimeImmutable,
            dueDate: new \DateTimeImmutable('+60 days'),
        );

        $project->start();

        $this->assertEquals(ProjectStatus::IN_PROGRESS, $project->status());
    }

    public function test_can_update_project_progress(): void
    {
        $project = Project::create(
            userId: UserId::generate(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: new \DateTimeImmutable,
            dueDate: new \DateTimeImmutable('+60 days'),
        );

        $project->updateProgress(50);

        $this->assertEquals(50, $project->progressPercentage());
    }

    public function test_can_complete_project(): void
    {
        $project = Project::create(
            userId: UserId::generate(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: new \DateTimeImmutable,
            dueDate: new \DateTimeImmutable('+60 days'),
        );

        $project->start();
        $project->updateProgress(100);
        $project->complete();

        $this->assertEquals(ProjectStatus::COMPLETED, $project->status());
    }

    public function test_can_cancel_project(): void
    {
        $project = Project::create(
            userId: UserId::generate(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: new \DateTimeImmutable,
            dueDate: new \DateTimeImmutable('+60 days'),
        );

        $project->cancel();

        $this->assertEquals(ProjectStatus::CANCELLED, $project->status());
    }

    public function test_can_convert_to_array(): void
    {
        $project = Project::create(
            userId: UserId::generate(),
            title: 'مشروع تطوير تطبيق الويب',
            description: 'تطوير تطبيق ويب لإدارة المهام',
            startDate: new \DateTimeImmutable,
            dueDate: new \DateTimeImmutable('+60 days'),
        );

        $array = $project->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('status', $array);
    }
}
