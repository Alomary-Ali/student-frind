<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentServiceCategory;
use Tests\TestCase;

final class ServiceRequestFeatureTest extends TestCase
{
    use RefreshDatabase;
    use WithAuthenticatedStudent;

    public function test_student_can_create_service_request(): void
    {
        $user = $this->createAndAuthenticateStudent();

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'شهادة إثبات قيد دراسي',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->postJson('/api/v1/student-services/requests', [
            'category_id' => $category->id,
            'priority' => 'medium',
            'notes' => 'أحتاج الشهادة للعمل',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'ref_number',
                    'category_id',
                    'student_id',
                    'status',
                    'priority',
                ],
            ]);

        $this->assertDatabaseHas('student_service_requests', [
            'category_id' => $category->id,
            'status' => 'new',
            'priority' => 'medium',
        ]);
    }

    public function test_create_request_fails_with_invalid_priority(): void
    {
        $this->createAndAuthenticateStudent();

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->postJson('/api/v1/student-services/requests', [
            'category_id' => $category->id,
            'priority' => 'invalid',
        ]);

        $response->assertStatus(422);
    }

    public function test_student_can_list_their_requests(): void
    {
        $this->createAndAuthenticateStudent();

        $response = $this->getJson('/api/v1/student-services/requests');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [],
            ]);
    }

    public function test_unauthenticated_user_cannot_create_request(): void
    {
        $response = $this->postJson('/api/v1/student-services/requests', [
            'category_id' => 'cat-001',
            'priority' => 'medium',
        ]);

        $response->assertStatus(401);
    }
}
