<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Entities;

use DateTimeImmutable;
use InvalidArgumentException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\ValueObjects\LearningPathId;

final class LearningPath
{
    private function __construct(
        private readonly LearningPathId $id,
        private readonly StudentId $studentId,
        private string $title,
        private string $targetRole,
        private array $steps,
        private int $progress,
        private ?DateTimeImmutable $estimatedCompletionDate,
    ) {}

    public static function create(
        LearningPathId $id,
        StudentId $studentId,
        string $title,
        string $targetRole,
        array $steps = [],
        ?DateTimeImmutable $estimatedCompletionDate = null,
    ): self {
        return new self(
            $id,
            $studentId,
            $title,
            $targetRole,
            $steps,
            0,
            $estimatedCompletionDate
        );
    }

    public static function reconstitute(
        LearningPathId $id,
        StudentId $studentId,
        string $title,
        string $targetRole,
        array $steps,
        int $progress,
        ?DateTimeImmutable $estimatedCompletionDate,
    ): self {
        return new self(
            $id,
            $studentId,
            $title,
            $targetRole,
            $steps,
            $progress,
            $estimatedCompletionDate
        );
    }

    public function id(): LearningPathId
    {
        return $this->id;
    }

    public function studentId(): StudentId
    {
        return $this->studentId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function targetRole(): string
    {
        return $this->targetRole;
    }

    public function steps(): array
    {
        return $this->steps;
    }

    public function progress(): int
    {
        return $this->progress;
    }

    public function estimatedCompletionDate(): ?DateTimeImmutable
    {
        return $this->estimatedCompletionDate;
    }

    public function updateProgress(int $progress): void
    {
        if ($progress < 0 || $progress > 100) {
            throw new InvalidArgumentException('Progress must be between 0 and 100');
        }

        $this->progress = $progress;
    }

    public function updateSteps(array $steps): void
    {
        $this->steps = $steps;
        $this->recalculateProgress();
    }

    public function completeStep(string $stepId): void
    {
        foreach ($this->steps as &$step) {
            if (isset($step['id']) && $step['id'] === $stepId) {
                $step['completed'] = true;
                $step['completed_at'] = (new DateTimeImmutable())->format('Y-m-d H:i:s');
                break;
            }
        }
        $this->recalculateProgress();
    }

    private function recalculateProgress(): void
    {
        if (count($this->steps) === 0) {
            $this->progress = 0;
            return;
        }

        $completedCount = 0;
        foreach ($this->steps as $step) {
            if (isset($step['completed']) && $step['completed'] === true) {
                $completedCount++;
            }
        }

        $this->progress = (int) round(($completedCount / count($this->steps)) * 100);
    }
}
