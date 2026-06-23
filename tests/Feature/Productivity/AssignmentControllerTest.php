<?php

declare(strict_types=1);

namespace Tests\Feature\Productivity;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Productivity\Domain\Events\AssignmentCreated;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentAssignment;
use Tests\TestCase;

final class AssignmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_assignment(): void
    {
        Event::fake([AssignmentCreated::class]);
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/productivity/assignments', [
            'user_id' => $user->id,
            'course_id' => 'CS101',
            'title' => 'واجب البرمجة',
            'description' => 'حل مسائل البرمجة الأساسية',
            'due_date' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('productivity_assignments', [
            'title' => 'واجب البرمجة',
            'course_id' => 'CS101',
        ]);
        Event::assertDispatched(AssignmentCreated::class);
    }

    public function test_can_update_assignment_progress(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $assignment = EloquentAssignment::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'course_id' => 'CS101',
            'title' => 'واجب البرمجة',
            'description' => 'حل مسائل البرمجة الأساسية',
            'assigned_at' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'assigned',
        ]);

        $response = $this->post("/productivity/assignments/{$assignment->id}/progress", [
            'status' => 'in_progress',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('productivity_assignments', [
            'id' => $assignment->id,
            'status' => 'in_progress',
        ]);
    }
}
