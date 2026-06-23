<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\ValueObjects;

use Modules\Productivity\Domain\Exceptions\InvalidGoalProgressException;
use Modules\Productivity\Domain\ValueObjects\GoalProgress;
use PHPUnit\Framework\TestCase;

final class GoalProgressTest extends TestCase
{
    public function test_goal_progress_can_be_created(): void
    {
        $progress = GoalProgress::of(50.0);

        $this->assertSame(50.0, $progress->value());
    }

    public function test_goal_progress_zero(): void
    {
        $progress = GoalProgress::zero();

        $this->assertSame(0.0, $progress->value());
    }

    public function test_goal_progress_complete(): void
    {
        $progress = GoalProgress::complete();

        $this->assertSame(100.0, $progress->value());
        $this->assertTrue($progress->isComplete());
    }

    public function test_goal_progress_must_be_between_0_and_100(): void
    {
        $this->expectException(InvalidGoalProgressException::class);
        GoalProgress::of(150.0);
    }

    public function test_goal_progress_negative_throws_exception(): void
    {
        $this->expectException(InvalidGoalProgressException::class);
        GoalProgress::of(-10.0);
    }

    public function test_goal_progress_equality(): void
    {
        $progress1 = GoalProgress::of(50.0);
        $progress2 = GoalProgress::of(50.0);
        $progress3 = GoalProgress::of(75.0);

        $this->assertTrue($progress1->equals($progress2));
        $this->assertFalse($progress1->equals($progress3));
    }

    public function test_goal_progress_can_add_amount(): void
    {
        $progress = GoalProgress::of(50.0);
        $newProgress = $progress->add(25.0);

        $this->assertSame(75.0, $newProgress->value());
    }

    public function test_goal_progress_add_caps_at_100(): void
    {
        $progress = GoalProgress::of(80.0);
        $newProgress = $progress->add(50.0);

        $this->assertSame(100.0, $newProgress->value());
    }
}
