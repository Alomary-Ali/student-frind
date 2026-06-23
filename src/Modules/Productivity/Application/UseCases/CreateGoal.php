<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\CreateGoalDto;
use Modules\Productivity\Application\DTOs\GoalDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CreateGoal
{
    public function __construct(
        private GoalRepositoryInterface $goals,
        private EventDispatcherInterface $events,
        private ProductivityMapper $mapper,
    ) {}

    public function execute(CreateGoalDto $dto): GoalDto
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: $dto->userId,
            title: $dto->title,
            description: $dto->description,
            targetDate: new \DateTimeImmutable($dto->targetDate),
            priority: PriorityLevel::fromString($dto->priority),
        );

        $this->goals->save($goal);
        $this->events->dispatch($goal->releaseEvents());

        return $this->mapper->toGoalDto($goal);
    }
}
