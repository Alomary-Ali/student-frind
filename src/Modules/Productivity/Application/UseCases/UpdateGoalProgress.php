<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\GoalDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Exceptions\GoalNotFoundException;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\GoalProgress;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class UpdateGoalProgress
{
    public function __construct(
        private GoalRepositoryInterface $goals,
        private EventDispatcherInterface $events,
        private ProductivityMapper $mapper,
    ) {}

    public function execute(string $goalId, float $progress): GoalDto
    {
        $id = GoalId::fromString($goalId);
        $goal = $this->goals->findById($id)
            ?? throw GoalNotFoundException::forId($goalId);

        $goal->updateProgress(GoalProgress::of($progress));

        $this->goals->save($goal);
        $this->events->dispatch($goal->releaseEvents());

        return $this->mapper->toGoalDto($goal);
    }
}
