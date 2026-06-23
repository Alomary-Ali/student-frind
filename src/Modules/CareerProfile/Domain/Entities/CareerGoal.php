<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Entities;

use DateTimeImmutable;
use InvalidArgumentException;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;

final class CareerGoal
{
    private function __construct(
        private readonly CareerGoalId $id,
        private readonly CareerProfileId $careerProfileId,
        private string $title,
        private DateTimeImmutable $targetDate,
        private GoalStatus $status,
        private int $progress,
    ) {}

    public static function create(
        CareerGoalId $id,
        CareerProfileId $careerProfileId,
        string $title,
        DateTimeImmutable $targetDate,
    ): self {
        return new self(
            $id,
            $careerProfileId,
            $title,
            $targetDate,
            GoalStatus::NOT_STARTED,
            0,
        );
    }

    public static function reconstitute(
        CareerGoalId $id,
        CareerProfileId $careerProfileId,
        string $title,
        DateTimeImmutable $targetDate,
        GoalStatus $status,
        int $progress,
    ): self {
        return new self(
            $id,
            $careerProfileId,
            $title,
            $targetDate,
            $status,
            $progress,
        );
    }

    public function id(): CareerGoalId
    {
        return $this->id;
    }

    public function careerProfileId(): CareerProfileId
    {
        return $this->careerProfileId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function targetDate(): DateTimeImmutable
    {
        return $this->targetDate;
    }

    public function status(): GoalStatus
    {
        return $this->status;
    }

    public function progress(): int
    {
        return $this->progress;
    }

    public function updateProgress(int $progress): void
    {
        if ($progress < 0 || $progress > 100) {
            throw new InvalidArgumentException('Progress must be between 0 and 100');
        }

        $this->progress = $progress;

        if ($progress === 100) {
            $this->status = GoalStatus::COMPLETED;
        } elseif ($progress > 0 && $this->status === GoalStatus::NOT_STARTED) {
            $this->status = GoalStatus::IN_PROGRESS;
        }
    }

    public function changeStatus(GoalStatus $status): void
    {
        $this->status = $status;
        if ($status === GoalStatus::COMPLETED) {
            $this->progress = 100;
        }
    }

    public function editDetails(string $title, DateTimeImmutable $targetDate): void
    {
        $this->title = $title;
        $this->targetDate = $targetDate;
    }
}
