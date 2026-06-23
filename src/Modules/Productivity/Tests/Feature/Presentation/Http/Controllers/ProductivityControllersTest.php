<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Feature\Presentation\Http\Controllers;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\Entities\Task;
use Modules\Productivity\Domain\Entities\CalendarEvent;
use Modules\Productivity\Domain\Entities\Reminder;
use Modules\Productivity\Domain\Enums\ReminderType;
use Modules\Productivity\Domain\ValueObjects\CalendarEventId;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use Modules\Productivity\Domain\ValueObjects\ReminderId;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use Modules\Productivity\Domain\Contracts\ProductivitySnapshotRepositoryInterface;
use Modules\Productivity\Infrastructure\Persistence\EloquentCalendarEventRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentGoalRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentProductivitySnapshotRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentReminderRepository;
use Modules\Productivity\Infrastructure\Persistence\EloquentTaskRepository;
use Tests\TestCase;

final class ProductivityControllersTest extends TestCase
{
    use RefreshDatabase;

    private string $userId;
    private GoalRepositoryInterface $goalRepository;
    private TaskRepositoryInterface $taskRepository;
    private ReminderRepositoryInterface $reminderRepository;
    private CalendarEventRepositoryInterface $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->userId = $user->id;
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->goalRepository = new EloquentGoalRepository();
        $this->taskRepository = new EloquentTaskRepository();
        $this->reminderRepository = new EloquentReminderRepository();
        $this->eventRepository = new EloquentCalendarEventRepository();

        $this->app->bind(
            ProductivitySnapshotRepositoryInterface::class,
            EloquentProductivitySnapshotRepository::class,
        );
    }

    public function test_can_list_tasks(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: $this->userId,
            title: 'Test Task',
            description: 'Desc',
            dueDate: new DateTimeImmutable('2026-07-01'),
            priority: PriorityLevel::medium(),
        );
        $this->taskRepository->save($task);

        $response = $this->getJson("/api/v1/productivity/users/{$this->userId}/tasks");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_create_task_via_api(): void
    {
        $response = $this->postJson('/api/v1/productivity/tasks', [
            'user_id' => $this->userId,
            'title' => 'New Task',
            'description' => 'Task Description',
            'due_date' => '2026-07-01 23:59:59',
            'priority' => 'medium',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success', 'message', 'data' => ['id', 'title', 'status'],
            ]);
    }

    public function test_can_show_task(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: $this->userId,
            title: 'Show Task',
            description: 'Desc',
            dueDate: new DateTimeImmutable('2026-07-01'),
            priority: PriorityLevel::low(),
        );
        $this->taskRepository->save($task);

        $response = $this->getJson("/api/v1/productivity/tasks/{$task->id()->value()}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['title' => 'Show Task'],
            ]);
    }

    public function test_can_complete_task(): void
    {
        $task = Task::create(
            id: TaskId::generate(),
            userId: $this->userId,
            title: 'Complete Me',
            description: 'Desc',
            dueDate: new DateTimeImmutable('2026-07-01'),
            priority: PriorityLevel::high(),
        );
        $this->taskRepository->save($task);

        $response = $this->postJson("/api/v1/productivity/tasks/{$task->id()->value()}/complete");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task completed successfully',
            ]);
    }

    public function test_task_show_returns_404_for_nonexistent(): void
    {
        $response = $this->getJson('/api/v1/productivity/tasks/00000000-0000-4000-a000-000000000000');

        $response->assertStatus(404);
    }

    public function test_can_create_reminder_via_api(): void
    {
        $response = $this->postJson('/api/v1/productivity/reminders', [
            'user_id' => $this->userId,
            'message' => 'Test Reminder',
            'trigger_at' => '2026-08-01 09:00:00',
            'type' => 'in_app',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success', 'message', 'data' => ['id', 'message', 'status'],
            ]);
    }

    public function test_can_list_reminders(): void
    {
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: $this->userId,
            message: 'List Reminder',
            triggerAt: new DateTimeImmutable('2026-08-01 09:00:00'),
            type: ReminderType::InApp,
        );
        $this->reminderRepository->save($reminder);

        $response = $this->getJson("/api/v1/productivity/users/{$this->userId}/reminders");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_create_calendar_event_via_api(): void
    {
        $response = $this->postJson('/api/v1/productivity/calendar-events', [
            'user_id' => $this->userId,
            'title' => 'Study Session',
            'description' => 'Study for exam',
            'starts_at' => '2026-08-01 10:00:00',
            'ends_at' => '2026-08-01 12:00:00',
            'is_all_day' => false,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success', 'message', 'data' => ['id', 'title', 'startsAt', 'endsAt'],
            ]);
    }

    public function test_can_list_calendar_events(): void
    {
        $event = CalendarEvent::create(
            id: CalendarEventId::generate(),
            userId: $this->userId,
            title: 'List Event',
            description: 'Desc',
            startsAt: new DateTimeImmutable('+1 day'),
            endsAt: new DateTimeImmutable('+1 day +2 hours'),
        );
        $this->eventRepository->save($event);

        $response = $this->getJson("/api/v1/productivity/users/{$this->userId}/calendar-events");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_get_dashboard(): void
    {
        $response = $this->getJson("/api/v1/productivity/users/{$this->userId}/dashboard");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success', 'data' => [
                    'userId', 'activeGoals', 'completedGoals', 'pendingTasks',
                    'inProgressTasks', 'completedTasks', 'overdueTasks',
                ],
            ]);
    }

    public function test_can_generate_snapshot(): void
    {
        $response = $this->postJson("/api/v1/productivity/users/{$this->userId}/snapshots", [
            'snapshot_date' => '2026-06-23',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success', 'message', 'data' => [
                    'id', 'userId', 'totalGoals', 'completedGoals',
                    'totalTasks', 'completedTasks', 'completionRate',
                ],
            ]);
    }

    public function test_create_task_with_missing_title_returns_500(): void
    {
        $response = $this->postJson('/api/v1/productivity/tasks', [
            'user_id' => $this->userId,
        ]);

        $response->assertStatus(500);
    }

    public function test_create_reminder_without_message_returns_500(): void
    {
        $response = $this->postJson('/api/v1/productivity/reminders', [
            'user_id' => $this->userId,
        ]);

        $response->assertStatus(500);
    }
}
