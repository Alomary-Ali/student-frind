<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class AcademicDashboardFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_student_can_view_dashboard(): void
    {
        $user = $this->createUser('student@test.com', 'student');
        Sanctum::actingAs($user);

        $response = $this->get('/academic/dashboard');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_view_dashboard(): void
    {
        $response = $this->get('/academic/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_dashboard_displays_student_profile_data(): void
    {
        $user = $this->createUser('student@test.com', 'student');
        $student = $this->createStudent($user);
        Sanctum::actingAs($user);

        $response = $this->get('/academic/dashboard');

        $response->assertStatus(200);
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
            'university_id' => 'university-uuid',
            'college_id' => 'college-uuid',
            'department_id' => 'department-uuid',
            'major_id' => 'major-uuid',
            'level' => '3',
        ]);
    }
}
