<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Services;

use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class ProductivityScoreEngine
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private GoalRepositoryInterface $goalRepository,
    ) {}

    public function calculateScore(UserId $userId): int
    {
        $tasks = $this->taskRepository->findByUserId($userId);
        $goals = $this->goalRepository->findByUserId($userId);

        $completedTasks = count(array_filter($tasks, fn ($task) => $task->status()->isCompleted()));
        $totalTasks = count($tasks);

        $goalProgress = array_reduce($goals, fn ($sum, $goal) => $sum + $goal->progressPercentage()->value(), 0);
        $totalGoals = count($goals);

        $taskScore = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 40 : 0;
        $goalScore = $totalGoals > 0 ? ($goalProgress / ($totalGoals * 100)) * 40 : 0;
        $baseScore = 20;

        $totalScore = (int) round($taskScore + $goalScore + $baseScore);

        return min(100, max(0, $totalScore));
    }

    public function getScoreLevel(int $score): string
    {
        return match (true) {
            $score >= 90 => 'ممتاز',
            $score >= 75 => 'جيد جداً',
            $score >= 60 => 'جيد',
            $score >= 40 => 'متوسط',
            default => 'يحتاج تحسين',
        };
    }
}
