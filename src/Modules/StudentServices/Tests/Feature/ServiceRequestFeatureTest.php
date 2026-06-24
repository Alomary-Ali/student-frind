<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Modules\StudentServices\Infrastructure\Persistence\EloquentServiceCategory;
use Tests\TestCase;

final class ServiceRequestFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_create_service_request(): void
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
            'student_id' => $user->id,
            'status' => 'new',
            'priority' => 'medium',
        ]);
    }

    public function test_create_request_fails_with_invalid_priority(): void
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

        $response = $this->postJson('/api/v1/student-services/requests', [
            'category_id' => $category->id,
            'priority' => 'invalid',
        ]);

        $response->assertStatus(422);
    }

    public function test_student_can_list_their_requests(): void
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
