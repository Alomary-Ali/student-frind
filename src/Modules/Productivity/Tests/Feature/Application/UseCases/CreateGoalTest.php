<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Feature\Application\UseCases;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Productivity\Application\DTOs\CreateGoalDto;
use Modules\Productivity\Application\UseCases\CreateGoal;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Infrastructure\Persistence\EloquentGoalRepository;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Tests\TestCase;

final class CreateGoalTest extends TestCase
{
    use RefreshDatabase;

    private CreateGoal $useCase;
    private GoalRepositoryInterface $goalRepository;
    private string $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->userId = $user->id;

        $this->goalRepository = new EloquentGoalRepository();
        $this->useCase = new CreateGoal(
            goals: $this->goalRepository,
            events: $this->app->make(EventDispatcherInterface::class),
            mapper: $this->app->make(\Modules\Productivity\Application\Mappers\ProductivityMapper::class),
        );
    }

    public function test_goal_can_be_created(): void
    {
        $dto = new CreateGoalDto(
            userId: $this->userId,
            title: 'Complete semester with 3.5 GPA',
            description: 'Maintain high academic performance',
            targetDate: '2026-12-31',
            priority: 'high',
        );

        $result = $this->useCase->execute($dto);

        $this->assertSame('Complete semester with 3.5 GPA', $result->title);
        $this->assertSame($this->userId, $result->userId);
        $this->assertSame(0.0, $result->progress);
        $this->assertSame('active', $result->status);
    }

    public function test_goal_is_persisted(): void
    {
        $dto = new CreateGoalDto(
            userId: $this->userId,
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: '2026-12-31',
            priority: 'medium',
        );

        $result = $this->useCase->execute($dto);

        $goal = $this->goalRepository->findById(GoalId::fromString($result->id));

        $this->assertNotNull($goal);
        $this->assertSame('Test Goal', $goal->title());
    }
}
