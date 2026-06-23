<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class CreateStudentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_student_academic_profile(): void
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

        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440001',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => '12345678',
        ]);

        $response = $this->postJson('/api/v1/academic/students', [
            'user_id' => $user->id,
            'student_number' => 'STU-2026-001',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'student_number' => 'STU-2026-001',
                    'academic_status' => 'active',
                ],
            ]);

        $this->assertDatabaseHas('academic_students', [
            'user_id' => $user->id,
            'student_number' => 'STU-2026-001',
        ]);
    }

    public function test_create_student_fails_with_duplicate_student_number(): void
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

        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440001',
            'email' => 'student2@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => '12345678',
        ]);

        $this->postJson('/api/v1/academic/students', [
            'user_id' => $user->id,
            'student_number' => 'STU-DUP',
        ]);

        $user2 = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440002',
            'email' => 'student3@test.com',
            'first_name' => 'Test',
            'last_name' => 'Two',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => '87654321',
        ]);

        $response = $this->postJson('/api/v1/academic/students', [
            'user_id' => $user2->id,
            'student_number' => 'STU-DUP',
        ]);

        $response->assertStatus(422);
    }
}
