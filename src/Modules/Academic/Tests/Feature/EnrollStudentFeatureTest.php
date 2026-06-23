<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Academic\Infrastructure\Persistence\EloquentCourse;
use Modules\Academic\Infrastructure\Persistence\EloquentSemester;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class EnrollStudentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_enroll_student_in_course(): void
    {
        $admin = $this->createUser('admin@test.com', 'admin');
        Sanctum::actingAs($admin);

        $student = $this->createStudent();
        $course = EloquentCourse::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'description' => 'Basics',
            'credit_hours' => 3,
            'is_active' => true,
        ]);
        $semester = EloquentSemester::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Fall 2026',
            'name_en' => 'Fall 2026',
            'code' => 'FALL2026',
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-15',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/academic/enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('academic_enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
        ]);
    }

    private function createUser(string $email, string $role): EloquentUser
    {
        return EloquentUser::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'email' => $email,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password_hash' => Hash::make('password'),
            'role' => $role,
            'status' => 'active',
            'academic_id' => null,
        ]);
    }

    private function createStudent(): EloquentStudent
    {
        $userId = (string) \Illuminate\Support\Str::uuid();
        $studentId = (string) \Illuminate\Support\Str::uuid();

        $user = EloquentUser::create([
            'id' => $userId,
            'email' => 'enroll-student-' . $userId . '@test.com',
            'first_name' => 'Enroll',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => $studentId,
        ]);

        return EloquentStudent::create([
            'id' => $studentId,
            'user_id' => $user->id,
            'student_number' => 'STU-ENROLL-' . substr($studentId, 0, 8),
            'academic_status' => 'active',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 0,
        ]);
    }
}
