<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\Enums\GoalStatus;
use Modules\Productivity\Domain\Events\GoalCompleted;
use Modules\Productivity\Domain\Events\GoalCreated;
use Modules\Productivity\Domain\Exceptions\GoalAlreadyCompletedException;
use Modules\Productivity\Domain\Exceptions\InvalidGoalProgressException;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\GoalProgress;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use PHPUnit\Framework\TestCase;

final class GoalTest extends TestCase
{
    public function test_goal_can_be_created_with_valid_data(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-123',
            title: 'Complete semester with 3.5 GPA',
            description: 'Maintain high academic performance',
            targetDate: new DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::high(),
        );

        $this->assertSame('Complete semester with 3.5 GPA', $goal->title());
        $this->assertSame('user-123', $goal->userId());
        $this->assertSame(GoalStatus::Active, $goal->status());
        $this->assertSame(0.0, $goal->progress()->value());
        $this->assertFalse($goal->isOverdue());

        $events = $goal->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(GoalCreated::class, $events[0]);
    }

    public function test_goal_progress_can_be_updated(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-123',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );

        $goal->updateProgress(GoalProgress::of(50.0));

        $this->assertSame(50.0, $goal->progress()->value());
    }

    public function test_goal_completes_when_progress_reaches_100(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-123',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );

        $goal->updateProgress(GoalProgress::of(100.0));

        $this->assertTrue($goal->status()->isCompleted());
        $this->assertTrue($goal->progress()->isComplete());

        $events = $goal->releaseEvents();
        $this->assertCount(2, $events);
        $this->assertContainsOnlyInstancesOf(GoalCompleted::class, array_filter($events, fn ($e) => $e instanceof GoalCompleted));
    }

    public function test_goal_cannot_be_modified_after_completion(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-123',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );

        $goal->complete();

        $this->expectException(GoalAlreadyCompletedException::class);
        $goal->updateProgress(GoalProgress::of(50.0));
    }

    public function test_goal_progress_must_be_between_0_and_100(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-123',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );

        $this->expectException(InvalidGoalProgressException::class);
        $goal->updateProgress(GoalProgress::of(150.0));
    }

    public function test_goal_is_overdue_when_target_date_passed(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-123',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new DateTimeImmutable('2020-01-01'),
            priority: PriorityLevel::medium(),
        );

        $this->assertTrue($goal->isOverdue());
    }

    public function test_goal_can_be_archived(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-123',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );

        $goal->archive();

        $this->assertTrue($goal->status()->isArchived());
    }

    public function test_goal_priority_can_be_updated(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: 'user-123',
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );

        $goal->updatePriority(PriorityLevel::urgent());

        $this->assertTrue($goal->priority()->isUrgent());
    }
}
