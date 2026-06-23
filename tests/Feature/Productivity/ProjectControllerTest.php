<?php

declare(strict_types=1);

namespace Tests\Feature\Productivity;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Productivity\Domain\Events\ProjectCreated;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentProject;
use Tests\TestCase;

final class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_project(): void
    {
        Event::fake([ProjectCreated::class]);
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/productivity/projects', [
            'user_id' => $user->id,
            'title' => 'مشروع تطوير تطبيق الويب',
            'description' => 'تطوير تطبيق ويب لإدارة المهام',
            'start_date' => now()->toDateTimeString(),
            'due_date' => now()->addDays(60)->toDateTimeString(),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('productivity_projects', [
            'title' => 'مشروع تطوير تطبيق الويب',
        ]);
        Event::assertDispatched(ProjectCreated::class);
    }

    public function test_can_update_project_progress(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = EloquentProject::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'title' => 'مشروع تطوير تطبيق الويب',
            'description' => 'تطوير تطبيق ويب لإدارة المهام',
            'start_date' => now(),
            'due_date' => now()->addDays(60),
            'status' => 'planning',
            'progress_percentage' => 0,
        ]);

        $response = $this->post("/productivity/projects/{$project->id}/progress", [
            'progress_percentage' => 50,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('productivity_projects', [
            'id' => $project->id,
            'progress_percentage' => 50,
        ]);
    }
}
