<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicAlert;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class GetStudentAlertsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_student_can_get_alerts(): void
    {
        $user = $this->createUser('student@test.com', 'student');
        $student = $this->createStudent($user);
        Sanctum::actingAs($user);

        // Create an alert
        EloquentAcademicAlert::create([
            'id' => 'alert-uuid-123',
            'student_id' => $student->id,
            'alert_type' => 'low_gpa',
            'severity' => 'high',
            'message' => 'GPA is below 2.0',
            'metadata' => null,
            'is_resolved' => false,
            'resolved_at' => null,
            'resolved_by' => null,
        ]);

        $response = $this->getJson('/academic/alerts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'alerts' => [
                        '*' => [
                            'id',
                            'student_id',
                            'alert_type',
                            'severity',
                            'message',
                            'is_resolved',
                            'created_at',
                        ],
                    ],
                ],
            ]);
    }

    public function test_unauthenticated_user_cannot_get_alerts(): void
    {
        $response = $this->getJson('/academic/alerts');

        $response->assertStatus(401);
    }

    public function test_returns_empty_array_when_no_alerts(): void
    {
        $user = $this->createUser('student@test.com', 'student');
        $this->createStudent($user);
        Sanctum::actingAs($user);

        $response = $this->getJson('/academic/alerts');

        $response->assertStatus(200)
            ->assertJson(['data' => ['alerts' => []]]);
    }

    private function createUser(string $email, string $role): EloquentUser
    {
        return EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440099',
            'email' => $email,
            'first_name' => 'Test',
            'last_name' => 'User',
            'password_hash' => Hash::make('password'),
            'role' => $role,
            'status' => 'active',
        ]);
    }

    private function createStudent(EloquentUser $user): EloquentStudent
    {
        return EloquentStudent::create([
            'id' => '660e8400-e29b-41d4-a716-446655440020',
            'user_id' => $user->id,
            'student_number' => 'STU-001',
            'academic_status' => 'active',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 3.5,
        ]);
    }
}
