<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Feature\Presentation\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Productivity\Infrastructure\Persistence\EloquentGoalRepository;
use Tests\TestCase;

final class GoalControllerTest extends TestCase
{
    use RefreshDatabase;

    private GoalRepositoryInterface $goalRepository;
    private string $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->userId = $user->id;
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->goalRepository = new EloquentGoalRepository();
    }

    public function test_can_create_goal_via_api(): void
    {
        $response = $this->postJson('/api/v1/productivity/goals', [
            'user_id' => $this->userId,
            'title' => 'Complete semester with 3.5 GPA',
            'description' => 'Maintain high academic performance',
            'target_date' => '2026-12-31',
            'priority' => 'high',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'userId',
                    'title',
                    'description',
                    'targetDate',
                    'priority',
                    'progress',
                    'status',
                ],
            ]);
    }

    public function test_can_get_goal_by_id(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: $this->userId,
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new \DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );

        $this->goalRepository->save($goal);

        $response = $this->getJson("/api/v1/productivity/goals/{$goal->id()->value()}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Test Goal',
                ],
            ]);
    }

    public function test_can_get_user_goals(): void
    {
        $goal = Goal::create(
            id: GoalId::generate(),
            userId: $this->userId,
            title: 'Test Goal',
            description: 'Test Description',
            targetDate: new \DateTimeImmutable('2026-12-31'),
            priority: PriorityLevel::medium(),
        );

        $this->goalRepository->save($goal);

        $response = $this->getJson("/api/v1/productivity/users/{$this->userId}/goals");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_validation_fails_for_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/productivity/goals', [
            'user_id' => 'invalid-uuid',
            'title' => '',
            'description' => '',
            'target_date' => 'invalid-date',
            'priority' => 'invalid',
        ]);

        $response->assertStatus(422);
    }
}
