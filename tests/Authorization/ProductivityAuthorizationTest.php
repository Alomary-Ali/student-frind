<?php

declare(strict_types=1);

namespace Tests\Authorization\Productivity;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentAssignment;
use Modules\Productivity\Presentation\Http\Policies\AssignmentPolicy;
use Tests\TestCase;

final class ProductivityAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_assignment(): void
    {
        $user = \App\Models\User::factory()->create();
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

        $policy = new AssignmentPolicy();
        $this->assertTrue($policy->view($user, $assignment));
    }

    public function test_user_cannot_view_others_assignment(): void
    {
        $user1 = \App\Models\User::factory()->create();
        $user2 = \App\Models\User::factory()->create();
        $assignment = EloquentAssignment::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user1->id,
            'course_id' => 'CS101',
            'title' => 'واجب البرمجة',
            'description' => 'حل مسائل البرمجة الأساسية',
            'assigned_at' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'assigned',
        ]);

        $policy = new AssignmentPolicy();
        $this->assertFalse($policy->view($user2, $assignment));
    }

    public function test_user_can_update_own_assignment(): void
    {
        $user = \App\Models\User::factory()->create();
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

        $policy = new AssignmentPolicy();
        $this->assertTrue($policy->update($user, $assignment));
    }

    public function test_user_can_delete_own_assignment(): void
    {
        $user = \App\Models\User::factory()->create();
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

        $policy = new AssignmentPolicy();
        $this->assertTrue($policy->delete($user, $assignment));
    }
}
