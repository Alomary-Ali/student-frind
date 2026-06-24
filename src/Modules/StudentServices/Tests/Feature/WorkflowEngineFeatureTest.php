<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Modules\StudentServices\Infrastructure\Persistence\EloquentServiceCategory;
use Modules\StudentServices\Infrastructure\Persistence\EloquentServiceWorkflow;
use Tests\TestCase;

final class WorkflowEngineFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_define_workflow(): void
    {
        $admin = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'admin@test.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password_hash' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'academic_id' => null,
        ]);
        Sanctum::actingAs($admin);

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->postJson('/api/v1/student-services/workflows', [
            'service_category_id' => $category->id,
            'name' => 'سير عمل إثبات قيد',
            'steps' => [
                [
                    'name' => 'تقديم الطلب',
                    'type' => 'form',
                    'order' => 1,
                    'assignee_role' => 'student',
                ],
                [
                    'name' => 'مراجعة',
                    'type' => 'approval',
                    'order' => 2,
                    'assignee_role' => 'admin',
                ],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'service_category_id',
                    'name',
                    'status',
                ],
            ]);

        $this->assertDatabaseHas('service_workflows', [
            'service_category_id' => $category->id,
            'name' => 'سير عمل إثبات قيد',
            'status' => 'active',
        ]);
    }

    public function test_student_can_get_workflow_status(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);
        Sanctum::actingAs($user);

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $workflow = EloquentServiceWorkflow::create([
            'id' => 'wf-001',
            'service_category_id' => $category->id,
            'name' => 'سير عمل',
            'status' => 'active',
        ]);

        $response = $this->getJson("/api/v1/student-services/workflows/{$workflow->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'service_category_id',
                    'name',
                    'status',
                ],
            ]);
    }

    public function test_unauthenticated_user_cannot_define_workflow(): void
    {
        $response = $this->postJson('/api/v1/student-services/workflows', [
            'service_category_id' => 'cat-001',
            'name' => 'سير عمل',
        ]);

        $response->assertStatus(401);
    }
}
