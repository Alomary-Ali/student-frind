<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use InvalidArgumentException;
use Modules\CareerProfile\Domain\Entities\CareerGoal;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use PHPUnit\Framework\TestCase;

final class CareerGoalEntityTest extends TestCase
{
    private CareerGoalId $goalId;
    private CareerProfileId $profileId;

    protected function setUp(): void
    {
        $this->goalId = CareerGoalId::generate();
        $this->profileId = CareerProfileId::generate();
    }

    public function test_can_create_goal(): void
    {
        $goal = CareerGoal::create(
            $this->goalId,
            $this->profileId,
            'تعلم Laravel',
            new DateTimeImmutable('2026-12-31'),
        );

        $this->assertSame($this->goalId, $goal->id());
        $this->assertSame($this->profileId, $goal->careerProfileId());
        $this->assertSame('تعلم Laravel', $goal->title());
        $this->assertSame(GoalStatus::NOT_STARTED, $goal->status());
        $this->assertSame(0, $goal->progress());
    }

    public function test_can_update_progress(): void
    {
        $goal = CareerGoal::create($this->goalId, $this->profileId, 'هدف', new DateTimeImmutable('2026-12-31'));

        $goal->updateProgress(50);
        $this->assertSame(50, $goal->progress());
        $this->assertSame(GoalStatus::IN_PROGRESS, $goal->status());
    }

    public function test_update_progress_to_100_marks_completed(): void
    {
        $goal = CareerGoal::create($this->goalId, $this->profileId, 'هدف', new DateTimeImmutable('2026-12-31'));

        $goal->updateProgress(100);
        $this->assertSame(100, $goal->progress());
        $this->assertSame(GoalStatus::COMPLETED, $goal->status());
    }

    public function test_update_progress_throws_exception_for_invalid_value(): void
    {
        $goal = CareerGoal::create($this->goalId, $this->profileId, 'هدف', new DateTimeImmutable('2026-12-31'));

        $this->expectException(InvalidArgumentException::class);
        $goal->updateProgress(150);
    }

    public function test_update_progress_throws_exception_for_negative_value(): void
    {
        $goal = CareerGoal::create($this->goalId, $this->profileId, 'هدف', new DateTimeImmutable('2026-12-31'));

        $this->expectException(InvalidArgumentException::class);
        $goal->updateProgress(-1);
    }

    public function test_can_change_status(): void
    {
        $goal = CareerGoal::create($this->goalId, $this->profileId, 'هدف', new DateTimeImmutable('2026-12-31'));

        $goal->changeStatus(GoalStatus::IN_PROGRESS);
        $this->assertSame(GoalStatus::IN_PROGRESS, $goal->status());
    }

    public function test_change_status_to_completed_sets_progress_to_100(): void
    {
        $goal = CareerGoal::create($this->goalId, $this->profileId, 'هدف', new DateTimeImmutable('2026-12-31'));

        $goal->changeStatus(GoalStatus::COMPLETED);
        $this->assertSame(GoalStatus::COMPLETED, $goal->status());
        $this->assertSame(100, $goal->progress());
    }

    public function test_can_edit_details(): void
    {
        $goal = CareerGoal::create($this->goalId, $this->profileId, 'هدف قديم', new DateTimeImmutable('2026-12-31'));

        $newDate = new DateTimeImmutable('2027-06-30');
        $goal->editDetails('هدف جديد', $newDate);

        $this->assertSame('هدف جديد', $goal->title());
        $this->assertSame($newDate, $goal->targetDate());
    }

    public function test_can_reconstitute_goal(): void
    {
        $goal = CareerGoal::reconstitute(
            $this->goalId,
            $this->profileId,
            'هدف معاد',
            new DateTimeImmutable('2026-12-31'),
            GoalStatus::IN_PROGRESS,
            75,
        );

        $this->assertSame('هدف معاد', $goal->title());
        $this->assertSame(GoalStatus::IN_PROGRESS, $goal->status());
        $this->assertSame(75, $goal->progress());
    }
}
